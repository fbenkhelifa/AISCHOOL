<?php

class Page7Controller {
    public function fetchResources() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
                $query = trim($_POST['query']);
                $apiKey = getenv('SERPER_API_KEY') ?: '';
                if ($apiKey === '') {
                    throw new Exception("SERPER_API_KEY is not configured.");
                }

                $postData = json_encode([
                    'q' => $query,
                    'gl' => 'dz',
                    'hl' => 'ar',
                    'page' => 1
                ]);

                $ch = curl_init('https://google.serper.dev/search');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'X-API-KEY: ' . $apiKey,
                    'Content-Type: application/json'
                ]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    throw new Exception(curl_error($ch));
                }
                curl_close($ch);

                header('Content-Type: application/json');
                echo $result;
            } else {
                throw new Exception("Invalid request. Please provide a valid query.");
            }
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(["error" => "❌ حدث خطأ أثناء البحث: " . $e->getMessage()]);
        }
    }
}

$controller = new Page7Controller();
$controller->fetchResources();


