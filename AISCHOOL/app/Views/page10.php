<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبرني</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/AISCHOOL/public/css/page10.css">
    <link rel="icon" href="/AISCHOOL/images/logo.png" type="image/png">
    <script src="/AISCHOOL/public/js/page10.js" defer></script>
</head>

<body>
    <div class="navbar">
        <a href="page5.php">المواد الدراسية</a>
        <a href="page6.php">البرنامج</a>
        <a href="page7.php">المصادر</a>
        <a href="page8.php">تدرب</a>
        <a href="page10.php" class="highlight">اختبرني</a>
        <a href="page11.php">الدردشة</a>
        <a href="page4.php">الحساب</a>
    </div>
    <div class="container">
        <h1>اختبرني</h1>
        <form id="keywordForm">
            <label for="keyword">أدخل الكلمة المفتاحية:</label>
            <input type="text" id="keyword" name="keyword" required>
            <button type="submit">إرسال</button>
        </form>
        <p id="waitingMessage" style="display: none; color: blue;">يرجى الانتظار حتى يتم معالجة الكلمة المفتاحية...</p>
        <div id="results"></div>
        <p id="answerWaitingMessage" style="display: none; color: blue;">يرجى الانتظار حتى يتم معالجة الإجابة...</p>
    </div>
</body>
</html>