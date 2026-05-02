<?php
/**
 * AIService - Handles communication with the Google Gemini API.
 */
class AIService {
    private $apiKey = "AIzaSyARCki5DDq66ZtxUK3yyW5eIoOvdtfR3po"; 
    private $apiUrl = "https://generativelanguage.googleapis.com/v1/models/gemini-2.0-flash:generateContent";

    public function generateContent($prompt) {
        $models = [
            "gemini-2.5-flash",
            "gemini-2.5-flash-lite",
            "gemini-2.0-flash",
            "gemini-2.0-flash-lite"
        ];

        foreach ($models as $model) {
            $url = "https://generativelanguage.googleapis.com/v1/models/$model:generateContent?key=" . $this->apiKey;
            
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

            if ($err) continue; // Try next model if connection fails

            $result = json_decode($response, true);
            
            // If we got content, return it immediately
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                return $result['candidates'][0]['content']['parts'][0]['text'];
            }
            
            // If it's a quota/not found error, continue to next model
            if (isset($result['error'])) {
                continue;
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
}
