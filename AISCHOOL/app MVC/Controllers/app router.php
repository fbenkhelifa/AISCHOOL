<?php

    // $userInfo = getUserInfo($db);
    if (!isset($_GET["page"]) && !isset($_SESSION["page"])) {
        require('./app MVC/Views/البرنامج.php'); // Corrected path
    } elseif (isset($_GET["page"])) {
        $_SESSION["page"] = $_GET["page"];
        header('location:index.php');
    } else {
        if ($_SESSION["page"] == "البرنامج") {
            require('./app MVC/Views/البرنامج.php'); // Corrected path
        } elseif ($_SESSION["page"] == "المصادر") {
            require('./app MVC/Views/المصادر.php'); // Corrected path
        } elseif ($_SESSION["page"] == "الحساب") {
            require('./app MVC/Views/الحساب.php'); // Corrected path
        } elseif ($_SESSION["page"] == "تدرب") {
            require('./app MVC/Views/تدرب.php'); // Corrected path
        } elseif ($_SESSION["page"] == "اختبرني") {
            require('./app MVC/Views/اختبرني.php'); // Corrected path
        } elseif ($_SESSION["page"] == "الدردشة") {
            require('./app MVC/Views/الدردشة.php'); // Corrected path
        } elseif ($_SESSION["page"] == "الصفحة الرئيسية") {
            unset($_SESSION["module"]);
            header('location:index.php');
        }
    }
    
?>