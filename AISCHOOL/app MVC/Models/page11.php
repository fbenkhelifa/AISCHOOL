<?php

require_once '../../config/database.php'; // Include the Database class

class ChatModel
{
    public function __construct()
    {
        try {
            $db = Database::connect('AISCHOOL');
            file_put_contents('php://stderr', "Database connection successful.\n");
        } catch (Exception $e) {
            file_put_contents('php://stderr', "Database connection failed: " . $e->getMessage() . "\n");
            throw $e;
        }
    }

    public function getResponse($messages)
    {
        if (empty($messages)) {
            return "⚠️ لا توجد رسائل لمعالجتها.";
        }

        $apiUrl = "https://api.together.xyz/v1/chat/completions";
        $apiKey = getenv('TOGETHER_API_KEY') ?: '';
        if ($apiKey === '') {
            throw new Exception("❌ إعدادات التوثيق غير مكتملة. يُرجى ضبط TOGETHER_API_KEY.");
        }

        $headers = [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ];

        $data = [
            "model" => "meta-llama/Llama-4-Maverick-17B-128E-Instruct-FP8",
            "messages" => $messages
        ];

        try {
            file_put_contents('php://stderr', "Sending API request: " . json_encode($data) . "\n");

            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if (!empty($curlError)) {
                throw new Exception("❌ خطأ في الاتصال: $curlError");
            }

            if ($httpCode !== 200) {
                throw new Exception("❌ خطأ في API: HTTP $httpCode");
            }

            $decodedResponse = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("❌ خطأ في تحليل استجابة API.");
            }

            return nl2br($decodedResponse['choices'][0]['message']['content'] ?? '⚠️ خطأ في الاستجابة.');
        } catch (Exception $e) {
            file_put_contents('php://stderr', "Exception in getResponse(): " . $e->getMessage() . "\n");
            throw $e;
        }
    }

    public function saveChatSession($sessionId, $title, $messages)
    {
        try {
            $db = Database::connect('AISCHOOL');
            $jsonMessages = json_encode($messages, JSON_UNESCAPED_UNICODE);
            if ($jsonMessages === false) {
                throw new Exception("❌ خطأ في ترميز الرسائل.");
            }

            $stmt = $db->prepare("INSERT INTO chat_sessions (session_id, title, messages) VALUES (:session_id, :title, :messages)
                                  ON DUPLICATE KEY UPDATE title = :title, messages = :messages");
            $stmt->execute([
                ':session_id' => $sessionId,
                ':title' => $title,
                ':messages' => $jsonMessages
            ]);
        } catch (Exception $e) {
            file_put_contents('php://stderr', "Error saving chat session: " . $e->getMessage() . "\n");
            throw new Exception("❌ خطأ أثناء حفظ الجلسة.");
        }
    }

    public function getChatSessions()
    {
        try {
            $db = Database::connect('AISCHOOL');
            $stmt = $db->query("SELECT session_id, title FROM chat_sessions ORDER BY id DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("❌ خطأ أثناء استرجاع الجلسات.");
        }
    }

    public function getChatMessages($sessionId)
    {
        try {
            $db = Database::connect('AISCHOOL');
            $stmt = $db->prepare("SELECT messages FROM chat_sessions WHERE session_id = :session_id");
            $stmt->execute([':session_id' => $sessionId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? json_decode($result['messages'], true) : [];
        } catch (Exception $e) {
            throw new Exception("❌ خطأ أثناء استرجاع الرسائل.");
        }
    }

    public function deleteChatSession($sessionId)
    {
        try {
            $db = Database::connect('AISCHOOL');
            $stmt = $db->prepare("DELETE FROM chat_sessions WHERE session_id = :session_id");
            $stmt->execute([':session_id' => $sessionId]);
        } catch (Exception $e) {
            throw new Exception("❌ خطأ أثناء حذف الجلسة: " . $e->getMessage());
        }
    }

    public function deleteAllChatSessions()
    {
        try {
            $db = Database::connect('AISCHOOL');
            $db->exec("DELETE FROM chat_sessions");
        } catch (Exception $e) {
            throw new Exception("❌ خطأ أثناء حذف جميع الجلسات: " . $e->getMessage());
        }
    }

    public function renameChatSession($sessionId, $newTitle)
    {
        throw new Exception("Method not implemented.");
    }

    public function getSuggestedTitle()
    {
        throw new Exception("Method not implemented.");
    }
}


