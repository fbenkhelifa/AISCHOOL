<?php
    if (!isset($_GET["module"]) && !isset($_SESSION["module"])) {
        $viewModules = getModules($db, $_SESSION['userName']);
        require('./app/Views/main page.php'); // Corrected path
    } else {
        if (!isset($_SESSION["module"])) {
            $_SESSION["module"] = $_GET["module"];
        }
        require('./app/Controllers/app-router.php'); // Corrected path
    }
?>