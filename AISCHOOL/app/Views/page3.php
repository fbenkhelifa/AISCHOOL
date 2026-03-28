<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/AISCHOOL/public/css/page3.css">
    <link rel="icon" href="/AISCHOOL/images/logo.png" type="image/png">
</head>
<body>
    <div class="container">
    <img src="/AISCHOOL/images/logo.png" class="logo" alt="logo">
    <h1>تسجيل الدخول</h1>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">اسم المستخدم:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">كلمة المرور:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">تسجيل الدخول</button>
        </form>
    </div>
</body>
</html>