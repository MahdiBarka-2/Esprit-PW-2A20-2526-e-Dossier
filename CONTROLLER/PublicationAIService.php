<?php

class PublicationAIService
{
    private $apiKey = "AIzaSyCv6H40dUFO8wCttdOOVy6lHbhpdTI3FjU";
    private $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent";

    public function generateContent($prompt)
    {
        $models = [
            "gemini-2.5-flash",
            "gemini-2.0-flash",
            "gemini-2.5-flash-lite"
        ];

        $lastError = "No response from AI models.";

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
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err = curl_errno($ch);
            curl_close($ch);

            if ($err) {
                $lastError = "Connection Error: " . curl_error($ch);
                continue;
            }

            $result = json_decode($response, true);

            if ($httpCode === 200 && isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                return $result['candidates'][0]['content']['parts'][0]['text'];
            }

            if (isset($result['error']['message'])) {
                $lastError = $result['error']['message'];
                if (stripos($lastError, 'leaked') !== false) {
                    $lastError = "CRITICAL: YOUR API KEY IS LEAKED AND BANNED. You must create a new one at https://aistudio.google.com/app/apikey and paste it here.";
                }
                if ($httpCode === 403 || $httpCode === 401)
                    break;
            } else {
                $lastError = "HTTP $httpCode - " . (string) $response;
            }
        }

        return json_encode(['error' => "AI Error: $lastError"]);
    }

    public function assistPublication($title)

    {
        $prompt = "Write a professional blog post content based on this title: '$title'. 
                   Use SIMPLE WORDS that anyone can understand. 
                   Do NOT use any HTML tags (like <h2>, <p>, <ul>, etc.). 
                   Use plain text only.
                   Provide a short 2-sentence summary at the end. 
                   Format the output as JSON with keys 'content' and 'summary'.";
        return $this->generateContent($prompt);
    }

    public function moderateComment($text)

    {
        $prompt = "Act as a professional content moderator for a government digital platform. 
                   Analyze this comment: '$text'.
                   If it contains hate speech, extreme toxicity, offensive insults, or blatant spam, return only the word 'Flagged'.
                   Otherwise, return only the word 'Approved'.";

        $response = $this->generateContent($prompt);

        $decision = trim(strip_tags($response));
        if (stripos($decision, 'Flagged') !== false)
            return 'Flagged';
        return 'Approved';
    }

    public function generateStrategicInsight($title, $content, $comments)

    {
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
                   
                   Write a clear, medium-length report. Use VERY SIMPLE WORDS.
                   Do NOT use any HTML tags. Use plain text and icons only.
                   Use real details from the comments to provide specific information.
                   
                   Use these icons and headers:
                   🎯 PUBLIC FEELING: (2-3 simple sentences)
                   ⚠️ MAIN CONCERNS: (Exactly 3 simple bullet points)
                   💡 STEPS TO TAKE: (Exactly 3 simple, useful steps)
                   
                   Keep it easy to read, direct, and useful.";

        return $this->generateContent($prompt);
    }
}
