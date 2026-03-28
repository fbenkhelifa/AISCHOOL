<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الدردشة مع LLM</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/AISCHOOL/public/css/page11.css">
    <link rel="icon" href="/AISCHOOL/images/logo.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/showdown/dist/showdown.min.js"></script> <!-- Include Showdown.js -->
    <script src="/AISCHOOL/public/js/page11.js" defer></script>
</head>
<body>
    <div class="navbar">
        <a href="page5.php">المواد الدراسية</a>
        <a href="page6.php">البرنامج</a>
        <a href="page7.php">المصادر</a>
        <a href="page8.php">تدرب</a>
        <a href="page10.php">اختبرني</a>
        <a href="page11.php" class="highlight">الدردشة</a>
        <a href="page4.php">الحساب</a>
    </div>
    <button id="sidebarToggle" class="sidebar-toggle">➤</button>
    <div class="sidebar">
        <button id="newChatButton" class="new-chat-button">+ دردشة جديدة</button>
        <button id="deleteAllChatsButton" class="new-chat-button" style="background-color: #e74c3c;">🗑️ حذف جميع الدردشات</button>
        <h2>سجل الدردشة</h2>
        <ul id="chatHistoryList" class="chat-history">
            <!-- Example of a chat item with delete functionality -->
            <li class="chat-item">
                <span>دردشة 04/05/2025, 20:40:11</span>
                <button class="delete-icon" title="حذف الدردشة" onclick="deleteChat('session_id')">🗑️</button>
            </li>
        </ul>
    </div>
    <div class="container">
        <div class="chat-box" id="chatBox"></div> <!-- Ensure this ID matches the JavaScript -->
        <form id="chatForm" class="chat-input">
            <input type="text" id="userInput" placeholder="✍️ اكتب رسالتك هنا..." required>
            <button type="submit">إرسال</button>
        </form>
    </div>
</body>
</html>
