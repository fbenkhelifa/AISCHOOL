<?php

require_once "../Models/page10.php";

class Page10Controller {
    public function handleRequest() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);

            $model = new Page10Model();

            if (isset($input['keyword'])) {
                $keyword = trim($input['keyword']);
                if ($keyword === '') {
                    $this->sendErrorResponse("EMPTY_KEYWORD", "Keyword cannot be empty.");
                    return;
                }

                $response = $model->generateQuiz($keyword);
                $this->sendResponse($response);
            } elseif (isset($input['questions'], $input['answers'])) {
                $questions = $input['questions'];
                $answers = $input['answers'];

                $response = $model->evaluateAnswers($questions, $answers);
                $this->sendResponse($response);
            } else {
                $this->sendErrorResponse("INVALID_REQUEST", "Invalid request.");
            }
        } else {
            $this->sendErrorResponse("INVALID_METHOD", "Only POST requests are allowed.");
        }
    }

    private function sendResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    private function sendErrorResponse($errorCode, $errorMessage) {
        $this->sendResponse([
            "status" => "error",
            "error_code" => $errorCode,
            "error_message" => $errorMessage
        ]);
    }
}

$controller = new Page10Controller();
$controller->handleRequest();
