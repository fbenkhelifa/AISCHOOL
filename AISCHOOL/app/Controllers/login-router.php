<?php

    $db = dbConnect("AISCHOOL");
    if (!isset($_SESSION['userName']) && !isset($_POST['submit'])) {
        if (!isset($_GET['isSigning']) && !isset($_GET['isLogging'])) {
            require('./app/Views/home-page.html'); // Corrected path
        } elseif (isset($_GET['isSigning'])) {
            require('./app/Views/signup.html'); // Corrected path
        } elseif (isset($_GET['isLogging'])) {
            require('./app/Views/login.html'); // Corrected path
        }
    } elseif (isset($_POST['submit'])) { 
        require('./app/Models/login-function.php'); // Corrected path
        if (isset($_POST['isSigning'])) {
            signing($db);
        } elseif (isset($_POST['isLogging'])) {
            logging($db);
        }
    } else {
        require('./app/Controllers/main-page-router.php'); // Corrected path
    }

?>