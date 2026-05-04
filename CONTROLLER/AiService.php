<?php
/**
 * SERVICE IA OPTIMISÉ - E-DOSSIER
 * Centralise toute l'intelligence administrative du système.
 */
class AiService {

    /**
     * Configuration centrale : Keywords, Templates et Temps d'attente.
     * Cette structure permet une maintenance facile sans modifier le code logique.
     */
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

    /**
     * ANALYSE COMPRÉHENSIVE
     * Détecte le type, vérifie la complétude et estime le temps en une seule passe.
     */
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

    /**
     * SUPPORT DE DÉCISION (Génération de message dynamique)
     */
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
            // FALLBACK : Si le mail réel échoue, on garde une trace dans le log
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
