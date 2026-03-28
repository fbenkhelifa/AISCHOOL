<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المصادر</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
    <link rel="icon" href="/AISCHOOL/images/logo.png" type="image/png">
    <link rel="stylesheet" href="/AISCHOOL/public (index,css)/CSS/page7.css">
    <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <script>
        async function fetchResources() {
            const keyword = document.getElementById('keyword').value;
            const resourcesList = document.getElementById('resources-list');
            resourcesList.innerHTML = '<li>جارٍ البحث...</li>';

            try {
                const response = await fetch('/AISCHOOL/app MVC/Controllers/page7.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ query: keyword })
                });
                const data = await response.json();

                if (data.error) {
                    resourcesList.innerHTML = `<li class="error">${data.error}</li>`;
                } else {
                    const results = data.organic || [];
                    resourcesList.innerHTML = results.length
                        ? results.map(item => `
                            <li>
                                <a href="${item.link}" target="_blank">${item.title}</a>
                                <p>${item.snippet || ''}</p>
                            </li>`).join('')
                        : '<li>⚠️ لم يتم العثور على موارد.</li>';
                }
            } catch (error) {
                resourcesList.innerHTML = `<li class="error">❌ حدث خطأ أثناء البحث: ${error.message}</li>`;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('fetchResourcesButton').addEventListener('click', (e) => {
                e.preventDefault();
                fetchResources();
            });
        });
    </script>
</head>
<body>
    <div class="navbar">
        <a href="page5.php">المواد الدراسية</a>
        <a href="page6.php">البرنامج</a>
        <a href="page7.php" class="highlight">المصادر</a>
        <a href="page8.php">تدرب</a>
        <a href="page10.php">اختبرني</a>
        <a href="page11.php">الدردشة</a>
        <a href="page4.php">الحساب</a>
    </div>
    <div class="container">
        <h1>المصادر</h1>
        <form id="search-form">
            <div class="form-group">
                <label for="keyword">أدخل الكلمة المفتاحية:</label>
                <input type="text" id="keyword" name="keyword" placeholder="مثال: الذكاء الاصطناعي" required>
            </div>
            <button type="submit" id="fetchResourcesButton">بحث</button>
        </form>
        <div id="resources-list" class="resources-list"></div>
    </div>
</body>
</html>