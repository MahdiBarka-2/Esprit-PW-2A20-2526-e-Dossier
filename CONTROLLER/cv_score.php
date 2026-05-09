<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// ── CONFIG ──────────────────────────────────────────────
define('OPENAI_API_KEY', 'YOUR_GROQ_API_KEY_HERE');  
define('MAX_FILE_SIZE',  5 * 1024 * 1024);

// ── DB CONFIG — adapte ces valeurs ──────────────────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'e_dossier');   // ← ton nom de base
define('DB_USER', 'root');       // ← ton user MySQL
define('DB_PASS', '');           // ← ton mot de passe

// ── HELPERS ─────────────────────────────────────────────
function jsonError(string $msg, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}

function getDB(): PDO {
    static $pdo = null;
    if (!$pdo) {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
            DB_USER, DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
    return $pdo;
}

function extractTextFromPDF(string $path): string {
    if (class_exists('\Smalot\PdfParser\Parser')) {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf    = $parser->parseFile($path);
        return $pdf->getText();
    }
    $text = shell_exec("pdftotext " . escapeshellarg($path) . " -");
    return $text ?: '';
}

function extractTextFromDocx(string $path): string {
    $zip = new ZipArchive();
    if ($zip->open($path) !== true) return '';
    $xml  = $zip->getFromName('word/document.xml');
    $zip->close();
    if (!$xml) return '';
    $text = strip_tags(str_replace('</w:p>', "\n", $xml));
    return html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
}

function scoreWithGroq(string $cvText, string $jobDescription): array {
    $prompt = <<<PROMPT
Tu es un expert RH. Analyse ce CV par rapport au poste et retourne UNIQUEMENT un JSON valide, sans markdown, sans explication.

Format JSON attendu :
{
  "score_global": <entier 0-100>,
  "competences": <entier 0-100>,
  "experience": <entier 0-100>,
  "formation": <entier 0-100>,
  "adequation_poste": <entier 0-100>,
  "points_forts": ["...", "...", "..."],
  "points_amelioration": ["...", "..."],
  "recommandation": "Retenir",
  "resume": "<2 phrases max>"
}

--- DESCRIPTION DU POSTE ---
{$jobDescription}

--- CV DU CANDIDAT ---
{$cvText}
PROMPT;

    $payload = json_encode([
        'model'       => 'llama-3.3-70b-versatile',
        'messages'    => [['role' => 'user', 'content' => $prompt]],
        'temperature' => 0.2,
        'max_tokens'  => 600,
    ]);

    $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . OPENAI_API_KEY,
        ],
        CURLOPT_TIMEOUT => 30,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        throw new RuntimeException('Groq API error: HTTP ' . $httpCode . ' — ' . $response);
    }

    $data    = json_decode($response, true);
    $content = $data['choices'][0]['message']['content'] ?? '';

    $content = preg_replace('/^```(?:json)?\s*/i', '', trim($content));
    $content = preg_replace('/\s*```$/', '', $content);

    $result = json_decode($content, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new RuntimeException('Réponse IA invalide : ' . $content);
    }

    return $result;
}

// ── VÉRIFICATION MÉTHODE ────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Méthode non autorisée', 405);
}

// ── RÉCUPÉRATION DU JOB ID ──────────────────────────────
$jobId = trim($_POST['job_id'] ?? '');
if (!$jobId) {
    jsonError('Job ID manquant');
}

// ── RÉCUPÉRATION DU JOB DEPUIS LA BDD ───────────────────
try {
    $pdo  = getDB();
    $stmt = $pdo->prepare('SELECT id, titre, description FROM jobs WHERE id = ?');
    $stmt->execute([(int)$jobId]);
    $job  = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    jsonError('Erreur BDD : ' . $e->getMessage(), 500);
}

if (!$job) {
    jsonError('Job introuvable (ID: ' . $jobId . ')');
}

$jobDescription = $job['titre'] . ' : ' . ($job['description'] ?? '');

// ── VÉRIFICATION FICHIER ────────────────────────────────
if (empty($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
    jsonError('Aucun fichier CV reçu ou erreur upload');
}

$file     = $_FILES['cv'];
$fileSize = $file['size'];
$fileTmp  = $file['tmp_name'];
$fileName = strtolower($file['name']);
$ext      = pathinfo($fileName, PATHINFO_EXTENSION);

if ($fileSize > MAX_FILE_SIZE) {
    jsonError('Fichier trop volumineux (max 5 Mo)');
}

if (!in_array($ext, ['pdf', 'doc', 'docx', 'txt'], true)) {
    jsonError('Format non supporté. Utilisez PDF, DOCX ou TXT.');
}

// ── EXTRACTION DU TEXTE ──────────────────────────────────
try {
    $cvText = match ($ext) {
        'pdf'         => extractTextFromPDF($fileTmp),
        'docx', 'doc' => extractTextFromDocx($fileTmp),
        'txt'         => file_get_contents($fileTmp),
    };
} catch (Throwable $e) {
    jsonError('Impossible d\'extraire le texte du CV : ' . $e->getMessage());
}

$cvText = trim($cvText);
if (strlen($cvText) < 50) {
    jsonError('CV trop court ou illisible. Vérifiez que le fichier contient du texte.');
}

$cvText = mb_substr($cvText, 0, 4000);

// ── SCORING IA ───────────────────────────────────────────
try {
    $scoring = scoreWithGroq($cvText, $jobDescription);
} catch (Throwable $e) {
    jsonError('Erreur IA : ' . $e->getMessage(), 500);
}

echo json_encode([
    'success' => true,
    'scoring' => $scoring,
]); 
