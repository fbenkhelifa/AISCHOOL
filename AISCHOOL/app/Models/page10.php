<?php

class Page10Model {
    private $llmKey;

    public function __construct() {
        $this->llmKey = getenv('TOGETHER_API_KEY') ?: '';
    }

    public function generateQuiz(string $keyword): array {
        $apiUrl = "https://api.together.xyz/v1/chat/completions";

        if ($this->llmKey === '') {
            return [
                "status" => "error",
                "error_message" => "Missing TOGETHER_API_KEY configuration."
            ];
        }

        $prompt = "أنت أستاذ خبير في المنهاج الجزائري. مهمتك إنشاء اختبار مكوّن من 5 أسئلة على الأقل في موضوع \"{$keyword}\". يجب أن يكون الإخراج بصيغة JSON فقط، وبهذا التنسيق المحدد:
{
    \"status\": \"success\",
    \"quiz\": {
        \"title\": \"عنوان الاختبار\",
        \"description\": \"وصف الاختبار\",
        \"questions\": [
            {
                \"id\": \"q1\",
                \"question\": \"نص السؤال\",
                \"type\": \"text|radio|checkbox\",
                \"options\": [
                    {
                        \"value\": \"الخيار الأول\",
                        \"label\": \"النص المعروض للخيار الأول\"
                    }
                ],
                \"correct_answer\": \"الإجابة الصحيحة\",
                \"explanation\": \"شرح الإجابة الصحيحة\"
            }
        ]
    }
}";

        $data = [
            "model" => "meta-llama/Llama-4-Maverick-17B-128E-Instruct-FP8",
            "messages" => [
                ["role" => "system", "content" => "You are a JSON-only assistant."],
                ["role" => "user", "content" => $prompt]
            ],
            "safety_model" => "meta-llama/Meta-Llama-Guard-3-8B",
            "response_format" => [
                "type" => "json_schema",
                "schema" => [
                    "type" => "object",
                    "properties" => [
                        "status" => ["type" => "string", "enum" => ["success", "failure"]],
                        "quiz" => [
                            "type" => "object",
                            "properties" => [
                                "title" => ["type" => "string"],
                                "description" => ["type" => "string"],
                                "questions" => [
                                    "type" => "array",
                                    "items" => [
                                        "type" => "object",
                                        "properties" => [
                                            "id" => ["type" => "string"],
                                            "question" => ["type" => "string"],
                                            "type" => ["type" => "string", "enum" => ["text", "radio", "checkbox"]],
                                            "options" => [
                                                "type" => "array",
                                                "items" => [
                                                    "type" => "object",
                                                    "properties" => [
                                                        "value" => ["type" => "string"],
                                                        "label" => ["type" => "string"]
                                                    ],
                                                    "required" => ["value", "label"],
                                                    "additionalProperties" => false
                                                ]
                                            ],
                                            "correct_answer" => ["type" => "string"],
                                            "explanation" => ["type" => "string"]
                                        ],
                                        "required" => ["id", "question", "type", "correct_answer", "explanation"],
                                        "additionalProperties" => false
                                    ]
                                ]
                            ],
                            "required" => ["title", "description", "questions"],
                            "additionalProperties" => false
                        ]
                    ],
                    "required" => ["status", "quiz"],
                    "additionalProperties" => false
                ]
            ]
        ];

        $headers = [
            "Authorization: Bearer {$this->llmKey}",
            "Content-Type: application/json"
        ];

        try {
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) throw new Exception("Connection error: $curlError");
            if ($httpCode !== 200) throw new Exception("API error: HTTP $httpCode");

            $decodedResponse = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Error parsing API response. Raw response: " . $response);
            }

            // Extract the content field from the choices array
            $content = $decodedResponse['choices'][0]['message']['content'] ?? null;
            if (!$content) {
                throw new Exception("Missing 'content' field in API response. Raw response: " . json_encode($decodedResponse));
            }

            // Decode the JSON string inside the content field
            $quizData = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Error decoding 'content' field. Raw content: " . $content);
            }

            // Check if the 'status' key exists
            if (!isset($quizData['status'])) {
                throw new Exception("Missing 'status' key in decoded content. Raw content: " . json_encode($quizData));
            }

            if ($quizData['status'] !== 'success') {
                throw new Exception("Failed to generate quiz: " . ($quizData['error_message'] ?? 'Unknown error'));
            }

            return [
                "status" => "success",
                "quiz" => $quizData['quiz']
            ];
        } catch (Exception $e) {
            // Log the error for debugging
            error_log("Error in generateQuiz: " . $e->getMessage());

            return [
                "status" => "error",
                "error_message" => "Error generating quiz: " . $e->getMessage()
            ];
        }
    }

    public function evaluateAnswers(array $questions, array $answers): array {
        $apiUrl = "https://api.together.xyz/v1/chat/completions";

        if ($this->llmKey === '') {
            return [
                "status" => "error",
                "error_message" => "Missing TOGETHER_API_KEY configuration."
            ];
        }

        $prompt = "أنت أستاذ خبير في المنهاج الجزائري. ستصلك قائمة بالأسئلة مع:
- الإجابة النموذجية لكل سؤال
- إجابة التلميذ

الأسئلة:
" . json_encode($questions, JSON_UNESCAPED_UNICODE) . "

إجابات التلميذ:
" . json_encode($answers, JSON_UNESCAPED_UNICODE) . "

مهمتك:
1. قارن بين إجابة التلميذ والإجابة النموذجية.
2. وزّع 20 نقطة على الأسئلة بحسب الأهمية.
3. حدّد لكل سؤال: صحيح أم خاطئ.
4. أنشئ تقريراً بصيغة JSON فقط، وبهذا التنسيق المحدد:
{
    \"status\": \"success\",
    \"evaluation\": {
        \"score\": \"الدرجة النهائية\",
        \"feedback\": [
            {
                \"question\": \"نص السؤال\",
                \"student_answer\": \"إجابة التلميذ\",
                \"correct_answer\": \"الإجابة الصحيحة\",
                \"correct\": true|false,
                \"explanation\": \"شرح الإجابة الصحيحة\"
            }
        ],
        \"advice\": \"نصائح لتحسين الأداء\"
    }
}";

        $data = [
            "model" => "meta-llama/Llama-4-Maverick-17B-128E-Instruct-FP8",
            "messages" => [
                ["role" => "system", "content" => "You are a JSON-only assistant."],
                ["role" => "user", "content" => $prompt]
            ],
            "safety_model" => "meta-llama/Meta-Llama-Guard-3-8B",
            "response_format" => ["type" => "json_object"]
        ];

        $headers = [
            "Authorization: Bearer {$this->llmKey}",
            "Content-Type: application/json"
        ];

        try {
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) throw new Exception("Connection error: $curlError");
            if ($httpCode !== 200) throw new Exception("API error: HTTP $httpCode");

            $decodedResponse = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Error parsing API response. Raw response: " . $response);
            }

            // Extract the content field from the choices array
            $content = $decodedResponse['choices'][0]['message']['content'] ?? null;
            if (!$content) {
                throw new Exception("Missing 'content' field in API response. Raw response: " . json_encode($decodedResponse));
            }

            // Decode the JSON string inside the content field
            $evaluationData = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Error decoding 'content' field. Raw content: " . $content);
            }

            // Check if the 'status' key exists
            if (!isset($evaluationData['status'])) {
                throw new Exception("Missing 'status' key in decoded content. Raw content: " . json_encode($evaluationData));
            }

            if ($evaluationData['status'] !== 'success') {
                throw new Exception("Failed to evaluate answers: " . ($evaluationData['error_message'] ?? 'Unknown error'));
            }

            return [
                "status" => "success",
                "evaluation" => $evaluationData['evaluation']
            ];
        } catch (Exception $e) {
            return [
                "status" => "error",
                "error_message" => "Error evaluating answers: " . $e->getMessage()
            ];
        }
    }
}


