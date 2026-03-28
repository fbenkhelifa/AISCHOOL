<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الحساب</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/AISCHOOL/public (index,css)/CSS/page4.css">
    <link rel="icon" href="/AISCHOOL/images/logo.png" type="image/png">
</head>
<body>
    <div class="navbar">
        <a href="page5.php">الصفحة الرئيسية</a>
        <a href="page3.php">تسجيل الدخول</a>
        <a href="page7.php">المصادر</a>
        <a href="page8.php">تدرب</a>
        <a href="page10.php">اختبرني</a>
        <a href="page11.php">الدردشة</a>
        <a href="page4.php" class="highlight">الحساب</a>
    </div>
    <div class="container">
        <h1>إدارة الحساب</h1>
        <form action="update_account.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="email">البريد الإلكتروني:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">كلمة المرور الجديدة:</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="photo">الصورة الشخصية:</label>
                <input type="file" id="photo" name="photo" accept="image/*">
            </div>
            <div class="form-group">
                <label for="additional_info">معلومات إضافية:</label>
                <textarea id="additional_info" name="additional_info" rows="4"></textarea>
            </div>
            <button type="submit">تحديث الحساب</button>
        </form>
        <form action="/AISCHOOL/index.php" method="post" style="margin-top: 20px;">
            <input type="hidden" name="logout" value="true">
            <button type="submit" style="background-color: red; color: white;">تسجيل الخروج</button>
        </form>
    </div>
</body>
</html>