<?php
class AiService {

    private static $knowledgeBase = [
        'Extrait de Naissance' => [
            'keywords' => ['naissance', 'né', 'maternité', 'registre'],
            'template' => "Je souhaite obtenir un extrait de naissance. Né le [DATE] à [LIEU]. Père: [NOM], Mère: [NOM].",
            'eta' => '24h'
        ],
        'Certificat de Résidence' => [
            'keywords' => ['résidence', 'habitation', 'adresse', 'domicile'],
            'template' => "Demande de certificat de résidence. J'habite au [ADRESSE] à [VILLE] depuis le [DATE].",
            'eta' => '48h'
        ],
        'Extrait d\'acte de Marriage' => [
            'keywords' => ['mariage', 'marriage', 'époux', 'épouse', 'célébré'],
            'template' => "Je demande un extrait d'acte de mariage. Mariage célébré le [DATE] à [LIEU] entre [NOM1] et [NOM2].",
            'eta' => '3 jours'
        ],
        'Acte de Décès' => [
            'keywords' => ['décès', 'mort', 'défunt', 'cimetière'],
            'template' => "Demande d'acte de décès pour [NOM_DEFUNT], décédé le [DATE] à [LIEU].",
            'eta' => '24h'
        ],
        'Bourse d\'Études' => [
            'keywords' => ['bourse', 'études', 'étudiant', 'université', 'scolaire'],
            'template' => "Demande de bourse d'études pour l'année universitaire. Inscrit en [FILIÈRE] à [ÉTABLISSEMENT].",
            'eta' => '7 jours'
        ],
        'Logement' => [
            'keywords' => ['logement', 'social', 'appartement', 'loyer', 'habitation'],
            'template' => "Je sollicite l'attribution d'un logement social. Ma situation actuelle est : [DÉTAILS]. Revenu mensuel : [MONTANT].",
            'eta' => '15 jours'
        ],
        'Passeport / CIN' => [
            'keywords' => ['passeport', 'cin', 'identité', 'carte', 'renouvellement'],
            'template' => "Demande de renouvellement de [DOCUMENT]. Mon ancien numéro est [NUMERO].",
            'eta' => '10 jours'
        ]
    ];

    public static function analyzeRequest($description, $categoryName) {
        $description = mb_strtolower($description);
        $categoryName = mb_strtolower($categoryName);
        
        $detectedType = $categoryName; // Par défaut
        $isComplete = (strlen($description) > 20);
        $eta = '48h';

        // Recherche intelligente dans la Knowledge Base
        foreach (self::$knowledgeBase as $type => $info) {
            foreach ($info['keywords'] as $keyword) {
                if (str_contains($description, $keyword) || str_contains($categoryName, mb_strtolower($type))) {
                    $detectedType = $type;
                    $eta = $info['eta'];
                    break 2;
                }
            }
        }

        return [
            'type' => $detectedType,
            'is_complete' => $isComplete,
            'missing_fields' => $isComplete ? [] : ['Détails descriptifs', 'Informations spécifiques au document'],
            'estimated_time' => $isComplete ? $eta : 'En attente de précisions'
        ];
    }

    public static function getDecisionSupport($type, $isComplete, $description) {
        if ($isComplete) {
            $approvals = [
                "Votre demande de $type est conforme. Elle a été approuvée par nos services.",
                "Dossier complet ! Nous avons validé votre demande de $type.",
                "Félicitations, votre demande de $type a été acceptée. Traitement en cours."
            ];
            return [
                'suggestion' => 'approve',
                'reason' => "L'IA confirme que le dossier pour '$type' est complet.",
                'official_message' => $approvals[array_rand($approvals)]
            ];
        } else {
            return [
                'suggestion' => 'reject',
                'reason' => "Description trop courte ou imprécise pour une demande de '$type'.",
                'official_message' => "Bonjour, votre demande de $type est trop brève. Merci de fournir plus de détails pour que nous puissions la traiter."
            ];
        }
    }

    /**
     * AUTOMATISATION EMAIL (RÉEL via Gmail SMTP)
     */
    public static function sendEmail($to, $subject, $body) {
        require_once __DIR__ . '/../vendor/phpmailer/Exception.php';
        require_once __DIR__ . '/../vendor/phpmailer/PHPMailer.php';
        require_once __DIR__ . '/../vendor/phpmailer/SMTP.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            // Configuration Serveur
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'haddoula12@gmail.com'; 
            $mail->Password   = 'jhnkuschlwyufhab';      
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';

            // Destinataires
            $mail->setFrom('haddoula12@gmail.com', 'Municipalité E-Dossier');
            $mail->addAddress($to);

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = nl2br($body);

            $mail->send();
            return true;
        } catch (Exception $e) {
            $log = sprintf("[%s] [ERROR: %s] MAIL TO: %s | SUBJECT: %s | CONTENT: %s\n", 
                date('Y-m-d H:i:s'), $mail->ErrorInfo, $to, $subject, $body);
            file_put_contents(__DIR__ . '/../assets/mail_log.txt', $log, FILE_APPEND);
            return false;
        }
    }

    /**
     * EXPOSITION DES TEMPLATES (Pour le frontend)
     */
    public static function getTemplates() {
        return array_map(fn($item) => $item['template'], self::$knowledgeBase);
    }
}

// =========================================================================
// ── ROUTEUR DES FONCTIONNALITÉS AVANCÉES ──
// Gère : Export CSV, Notifications (Compte), Notifications (Liste)
// Ne s'exécute que si le fichier est appelé directement via une requête HTTP
// =========================================================================
if (basename($_SERVER['PHP_SELF']) === 'AiService.php' && isset($_GET['action'])) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../MODEL/Database.php';
    require_once __DIR__ . '/demandeC.php';

    $action = $_GET['action'];

    switch ($action) {

        // =====================================================================
        // FEATURE 2 : COMPTE DES NOTIFICATIONS (Badge)
        // =====================================================================
        case 'notif_count':
            $database = new Database();
            $db = $database->getConnection();
            $count = $db->query("SELECT COUNT(*) FROM demande WHERE statut = 'en_attente'")->fetchColumn();
            
            header('Content-Type: application/json');
            echo json_encode(['count' => (int)$count]);
            break;

        // =====================================================================
        // FEATURE 2 : LISTE DES NOTIFICATIONS (Popup Dropdown)
        // =====================================================================
        case 'notif_list':
            $database = new Database();
            $db = $database->getConnection();
            
            $stmt = $db->query(
                "SELECT demande.id, demande.utilisateur, demande.created_at, categorie.nom AS categorie_nom
                 FROM demande, categorie
                 WHERE demande.categorie_id = categorie.id
                   AND demande.statut = 'en_attente'
                 ORDER BY demande.created_at DESC
                 LIMIT 8"
            );
            $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            header('Content-Type: application/json');
            echo json_encode([
                'count'    => count($demandes),
                'demandes' => $demandes
            ]);
            break;

        // =====================================================================
        // FEATURE 3 : EXPORT CSV
        // =====================================================================
        case 'export_csv':
            $dc = new demandeC();
            $demandes = $dc->listeDemandes()->fetchAll();

            // En-têtes HTTP pour forcer le téléchargement
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="demandes_' . date('Y-m-d') . '.csv"');
            header('Cache-Control: no-cache');

            $fichier = fopen('php://output', 'w');
            // BOM UTF-8 pour la compatibilité Excel
            fprintf($fichier, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // En-tête CSV
            fputcsv($fichier, ['#', 'Nom', 'Email', 'Catégorie', 'Statut', 'Priorité', 'Date de soumission'], ';');

            // Lignes
            foreach ($demandes as $d) {
                $statut_lisible = match($d['statut']) {
                    'approuvee'  => 'Approuvée',
                    'rejetee'    => 'Rejetée',
                    default      => 'En attente'
                };
                fputcsv($fichier, [
                    $d['id'],
                    $d['utilisateur'],
                    $d['email'],
                    $d['categorie_nom'],
                    $statut_lisible,
                    ucfirst($d['priorite'] ?? 'normale'),
                    date('d/m/Y H:i', strtotime($d['created_at']))
                ], ';');
            }
            fclose($fichier);
            exit;

        // =====================================================================
        // PAR DÉFAUT
        // =====================================================================
        default:
            http_response_code(400);
            echo "Action non reconnue.";
            break;
    }
}
?>
