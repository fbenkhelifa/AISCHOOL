<?php

    $db = dbConnect("AISCHOOL");
    if (!isset($_SESSION['userName']) && !isset($_POST['submit'])) {
        if (!isset($_GET['isSigning']) && !isset($_GET['isLogging'])) {
            require('./app MVC/Views/home page.html'); // Corrected path
        } elseif (isset($_GET['isSigning'])) {
            require('./app MVC/Views/sign up.html'); // Corrected path
        } elseif (isset($_GET['isLogging'])) {
            require('./app MVC/Views/log in.html'); // Corrected path
        }
    } elseif (isset($_POST['submit'])) { 
        require('./app MVC/Models/loging function.php'); // Corrected path
        if (isset($_POST['isSigning'])) {
            signing($db);
        } elseif (isset($_POST['isLogging'])) {
            logging($db);
        }
    } else {
        require('./app MVC/Controllers/main Page router.php'); // Corrected path
    }

?>