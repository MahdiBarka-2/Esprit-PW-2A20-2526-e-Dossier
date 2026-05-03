<?php
/**
 * AIService - Handles communication with the Google Gemini API.
 */
class AIService {
    private $apiKey = "AIzaSyBJc_-O_wlb36Eh4H9IcBzz9wGGG-rhDAc"; 
    private $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent";

    public function generateContent($prompt) {
        $models = [
            "gemini-2.5-flash",
            "gemini-2.0-flash"
        ];

        foreach ($models as $model) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent?key=" . $this->apiKey;
            
            $data = [
                "contents" => [["parts" => [["text" => $prompt]]]]
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $response = curl_exec($ch);
            $err = curl_errno($ch);
            curl_close($ch);

            if ($err) continue;

            $result = json_decode($response, true);
            
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                return $result['candidates'][0]['content']['parts'][0]['text'];
            }
        }

        return json_encode(['error' => "AI Error: All available models reached their limit. Please try again in a few minutes."]);
    }

    /**
     * Specifically for generating a summary and content based on a title.
     */
    /**
     * Specifically for generating a summary and content based on a title.
     */
    public function assistPublication($title) {
        $prompt = "Write a professional and engaging blog post content based on this title: '$title'. 
                   Also, provide a short 2-sentence summary. 
                   Format the output as JSON with keys 'content' and 'summary'.";
        return $this->generateContent($prompt);
    }

    /**
     * Moderates a comment by checking for toxicity, hate speech, or spam.
     * Returns 'Approved' or 'Flagged'.
     */
    public function moderateComment($text) {
        $prompt = "Act as a professional content moderator for a government digital platform. 
                   Analyze this comment: '$text'.
                   If it contains hate speech, extreme toxicity, offensive insults, or blatant spam, return only the word 'Flagged'.
                   Otherwise, return only the word 'Approved'.";
        
        $response = $this->generateContent($prompt);
        
        // Clean and validate response
        $decision = trim(strip_tags($response));
        if (stripos($decision, 'Flagged') !== false) return 'Flagged';
        return 'Approved';
    }

    /**
     * Generates a strategic insight report based on a publication and its citizen feedback.
     */
    public function generateStrategicInsight($title, $content, $comments) {
        $commentsText = "";
        foreach ($comments as $index => $c) {
            $commentsText .= ($index + 1) . ". User " . $c['utilisateur'] . ": " . $c['contenu'] . "\n";
        }

        $prompt = "Act as a helpful analyst. 
                   Analyze this publication and the citizen feedback.
                   
                   TITLE: '$title'
                   CONTENT: '$content'
                   
                   COMMENTS:
                   $commentsText
                   
                   Write a clear, medium-length report. Use SIMPLE WORDS but make it very useful.
                   Use real details from the comments to provide specific information.
                   
                   Use these icons and headers:
                   🎯 HOW PEOPLE FEEL: (2-3 simple but clear sentences explaining the public mood)
                   ⚠️ MAIN CONCERNS: (Exactly 3 simple bullet points identifying the biggest issues)
                   💡 WHAT TO DO NOW: (Exactly 3 simple, useful steps the administration should take)
                   
                   Keep it easy to read, direct, and useful.";
                   
        return $this->generateContent($prompt);
    }
}
