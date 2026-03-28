<?php

require_once "../Models/page8.php";

class Page8Controller {
    public function generateExercises() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $module = $_POST['module'] ?? 'غير محدد';
            $numExercises = intval($_POST['num_exercises'] ?? 1);
            $difficulty = $_POST['difficulty'] ?? 'متوسط';
            $includeSolutions = isset($_POST['include_solutions']);

            $model = new Page8Model();
            $exercises = $model->generateExercises($module, $numExercises, $difficulty, $includeSolutions);

            header('Content-Type: application/json');
            echo json_encode(["exercises" => $exercises]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid request"]);
        }
    }
}

$controller = new Page8Controller();
$controller->generateExercises();
