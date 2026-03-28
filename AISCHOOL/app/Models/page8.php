<?php

class Page8Model {
    private $llmKey;

    public function __construct() {
        $this->llmKey = getenv('TOGETHER_API_KEY') ?: '';
    }

    public function generateExercises($module, $numExercises, $difficulty, $includeSolutions) {
        $apiUrl = "https://api.together.xyz/v1/chat/completions";

        $prompt = "أنت أستاذ بالمدارس الجزائرية تتقن جميع المواد التعليمية الجزائرية تساعد الطلاب على الدراسة بإنشاء تمارين هادفة تكون تحت المنهاج المعتمد في المدارس الجزائرية. قم بإنشاء $numExercises تمرينًا في موضوع $module بمستوى صعوبة $difficulty.";
        if ($includeSolutions) $prompt .= " يجب أن تتضمن التمارين الحلول.";

        $data = [
            "model" => "meta-llama/Llama-4-Maverick-17B-128E-Instruct-FP8",
            "messages" => [["role" => "user", "content" => $prompt]],
            "safety_model" => "meta-llama/Meta-Llama-Guard-3-8B"
        ];

        if ($this->llmKey === '') {
            return "⚠️ إعدادات التوثيق غير مكتملة. يُرجى ضبط TOGETHER_API_KEY.";
        }

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

            if ($curlError) throw new Exception("❌ خطأ في الاتصال: $curlError");
            if ($httpCode !== 200) throw new Exception("❌ خطأ في API: HTTP $httpCode");

            $decodedResponse = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) throw new Exception("❌ خطأ في تحليل استجابة API.");

            return $decodedResponse['choices'][0]['message']['content'] ?? '⚠️ لم يتم العثور على تمارين.';
        } catch (Exception $e) {
            return "❌ حدث خطأ أثناء إنشاء التمارين: " . $e->getMessage();
        }
    }
}

