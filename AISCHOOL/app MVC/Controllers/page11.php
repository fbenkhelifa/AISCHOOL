<?php

require_once '../Models/page11.php'; // Corrected path

class Page11Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new ChatModel();
    }

    public function chat()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? null;

        try {
            if ($action === 'getSessions') {
                $sessions = $this->model->getChatSessions();
                echo json_encode(['sessions' => $sessions]);
            } elseif ($action === 'getMessages') {
                $sessionId = $input['sessionId'] ?? null;
                if (!$sessionId) {
                    throw new Exception("Session ID is missing.");
                }
                $messages = $this->model->getChatMessages($sessionId);
                echo json_encode(['messages' => $messages]);
            } elseif ($action === 'saveSession') {
                $sessionId = $input['sessionId'] ?? null;
                $title = $input['title'] ?? null;
                $messages = $input['messages'] ?? null;

                if (!$sessionId || !$title || !is_array($messages)) {
                    throw new Exception("Invalid session data.");
                }

                $this->model->saveChatSession($sessionId, $title, $messages);
                echo json_encode(['success' => true]);
            } elseif ($action === 'getResponse') {
                $messages = $input['messages'] ?? null;
                if (!$messages || !is_array($messages)) {
                    throw new Exception("Invalid messages data.");
                }

                $response = $this->model->getResponse($messages);
                echo json_encode(['reply' => $response]);
            } elseif ($action === 'deleteChat') {
                $sessionId = $input['sessionId'] ?? null;
                if (!$sessionId) {
                    throw new Exception("Session ID is missing.");
                }

                $stmt = $this->model->deleteChatSession($sessionId);
                echo json_encode(['success' => true]);
            } elseif ($action === 'deleteAllChats') {
                $this->model->deleteAllChatSessions();
                echo json_encode(['success' => true]);
            } else {
                throw new Exception("Invalid action.");
            }
        } catch (Exception $e) {
            header('Content-Type: application/json', true, 500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new Page11Controller();
    $controller->chat();
} else {
    header('Content-Type: application/json', true, 400);
    echo json_encode(['error' => '❌ إجراء غير صالح.']);
}
