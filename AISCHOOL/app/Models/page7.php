<?php

class Page7Model {
    public function getResources($keyword) {
        $apiUrl = "https://api.langflow.astra.datastax.com/lf/d4febca9-3c76-4e51-9486-012f920ae0e3/api/v1/run/6a9bd98c-c4b7-43b9-b2a3-3a8be472d45a?stream=false";

        $data = [
            "input_value" => $keyword,
            "output_type" => "text",
            "input_type" => "chat"
        ];

        $astraBearerToken = getenv('ASTRA_BEARER_TOKEN') ?: '';
        if ($astraBearerToken === '') {
            return "⚠️ إعدادات التوثيق غير مكتملة. يُرجى ضبط ASTRA_BEARER_TOKEN.";
        }

        $headers = [
            "Authorization: Bearer " . $astraBearerToken,
            "Content-Type: application/json"
        ];

        $maxRetries = 3;
        $retryDelay = 2;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $ch = curl_init($apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 504 && $attempt < $maxRetries) {
                    sleep($retryDelay);
                    continue;
                }

                if ($httpCode !== 200) {
                    throw new Exception("HTTP $httpCode");
                }

                $decodedResponse = json_decode($response, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception("Invalid JSON response.");
                }

                return $decodedResponse['output'] ?? '⚠️ لم يتم العثور على موارد.';
            } catch (Exception $e) {
                if ($attempt === $maxRetries) {
                    return "❌ حدث خطأ أثناء البحث: " . $e->getMessage();
                }
            }
        }
    }
}


