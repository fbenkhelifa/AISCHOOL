<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>توليد التمارين</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
    <link rel="icon" href="/AISCHOOL/images/logo.png" type="image/png">
    <link rel="stylesheet" href="/AISCHOOL/public (index,css)/CSS/page8.css">
    <script src="https://cdn.jsdelivr.net/npm/showdown/dist/showdown.min.js"></script>
    <script>
        window.MathJax = {
            tex: {
                inlineMath: [['$', '$'], ['\\(', '\\)']],
                displayMath: [['$$', '$$'], ['\\[', '\\]']],
                packages: {'[+]': ['mhchem']}
            },
            options: {
                skipHtmlTags: ['script', 'noscript', 'style', 'textarea', 'pre']
            }
        };
    </script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
</head>
<body>
    <div class="navbar">
        <a href="page5.php">المواد الدراسية</a>
        <a href="page6.php">البرنامج</a>
        <a href="page7.php">المصادر</a>
        <a href="page8.php" class="highlight">تدرب</a>
        <a href="page10.php">اختبرني</a>
        <a href="page11.php">الدردشة</a>
        <a href="page4.php">الحساب</a>
    </div>
    <div class="container">
        <h1>توليد التمارين</h1>
        <form id="exerciseForm">
            <div class="form-group">
                <label for="module">الموضوع:</label>
                <input type="text" id="module" name="module" placeholder="مثال: الدوال العددية" required>
            </div>
            <div class="form-group">
                <label for="num_exercises">عدد التمارين:</label>
                <input type="number" id="num_exercises" name="num_exercises" min="1" required>
            </div>
            <div class="form-group">
                <label>مستوى الصعوبة:</label>
                <div class="radio-group">
                    <label><input type="radio" name="difficulty" value="سهل" required> سهل</label>
                    <label><input type="radio" name="difficulty" value="متوسط"> متوسط</label>
                    <label><input type="radio" name="difficulty" value="صعب"> صعب</label>
                </div>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="include_solutions"> تضمين الحلول</label>
            </div>
            <button type="submit">إنشاء التمارين</button>
        </form>
        <div id="exercises-list"></div>
    </div>
    <script src="/AISCHOOL/public (index,css)/JS/page8.js"></script>
</body>
</html>