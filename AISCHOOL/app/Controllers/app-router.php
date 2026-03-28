<?php

    // $userInfo = getUserInfo($db);
    if (!isset($_GET["page"]) && !isset($_SESSION["page"])) {
        require('./app/Views/البرنامج.php'); // Corrected path
    } elseif (isset($_GET["page"])) {
        $_SESSION["page"] = $_GET["page"];
        header('location:index.php');
    } else {
        if ($_SESSION["page"] == "البرنامج") {
            require('./app/Views/البرنامج.php'); // Corrected path
        } elseif ($_SESSION["page"] == "المصادر") {
            require('./app/Views/المصادر.php'); // Corrected path
        } elseif ($_SESSION["page"] == "الحساب") {
            require('./app/Views/الحساب.php'); // Corrected path
        } elseif ($_SESSION["page"] == "تدرب") {
            require('./app/Views/تدرب.php'); // Corrected path
        } elseif ($_SESSION["page"] == "اختبرني") {
            require('./app/Views/اختبرني.php'); // Corrected path
        } elseif ($_SESSION["page"] == "الدردشة") {
            require('./app/Views/الدردشة.php'); // Corrected path
        } elseif ($_SESSION["page"] == "الصفحة الرئيسية") {
            unset($_SESSION["module"]);
            header('location:index.php');
        }
    }
    
?>