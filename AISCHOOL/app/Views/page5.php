<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المواد الدراسية</title>
    <link rel="stylesheet" href="/AISCHOOL/public/css/page5.css">
    <link rel="icon" href="/AISCHOOL/images/logo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['userName'])) {
        header('Location: /AISCHOOL/app/Views/home-page.html'); // Redirect to home page if not logged in
        exit;
    }

    require_once '../../config/database.php';
    require_once '../Models/db_functions.php';

    $db = Database::connect('AISCHOOL');
    $viewModules = getModules($db, $_SESSION['userName']);
    ?>

    <h2>المواد الدراسية</h2>
    <ul>
        <?php echo $viewModules; ?>
    </ul>
</body>
</html>