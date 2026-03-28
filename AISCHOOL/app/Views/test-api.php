<?php
// تنفيذ البحث عند طلب POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = $_POST['query'] ?? '';
    $apiKey = getenv('SERPER_API_KEY') ?: '';
    if ($apiKey === '') {
        echo json_encode(["error" => "SERPER_API_KEY is not configured."]);
        exit;
    }

    $postData = json_encode([
        'q' => $query,
        'gl' => 'dz', // البلد: الجزائر
        'hl' => 'ar', // اللغة: عربية
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
        echo json_encode(["error" => curl_error($ch)]);
    } else {
        echo $result;
    }
    curl_close($ch);
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>بحث الويب - Aischool</title>
    <style>
        body { font-family: Arial; margin: 30px; background: #f5f5f5; direction: rtl; }
        input, button { padding: 10px; font-size: 16px; margin-top: 10px; }
        ul { list-style: none; padding: 0; }
        li { background: white; margin: 10px 0; padding: 15px; border-radius: 8px; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
        a { color: #007BFF; text-decoration: none; font-weight: bold; }
        p { color: #333; }
    </style>
</head>
<body>

<h2>🔍 بحث عبر Google Serper API</h2>
<p>أدخل موضوعًا للحصول على نتائج موثوقة:</p>
<input type="text" id="query" placeholder="مثال: الذكاء الاصطناعي في التعليم" style="width: 60%;">
<button onclick="searchWeb()">ابحث</button>

<div id="results"></div>

<script>
function searchWeb() {
    const query = document.getElementById('query').value;
    if (!query) return alert("الرجاء إدخال موضوع البحث");

    fetch('', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({ query: query })
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            document.getElementById("results").innerHTML = "<p style='color:red'>فشل الاتصال: " + data.error + "</p>";
            return;
        }

        const results = data.organic || [];
        let html = "<ul>";
        results.forEach(item => {
            html += `<li>
                <a href="${item.link}" target="_blank">${item.title}</a>
                <p>${item.snippet || ''}</p>
            </li>`;
        });
        html += "</ul>";
        document.getElementById("results").innerHTML = results.length ? html : "<p>لم يتم العثور على نتائج.</p>";
    })
    .catch(err => {
        document.getElementById("results").innerHTML = "<p style='color:red'>فشل في الاتصال بالخدمة.</p>";
        console.error(err);
    });
}
</script>

</body>
</html>


