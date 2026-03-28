<?php

    session_start();

    if (isset($_POST['logout']) && $_POST['logout'] === 'true') {
        session_destroy();
        header('Location: /AISCHOOL/app MVC/Views/home page.html'); // Redirect to the home page
        exit;
    }

    require('./app MVC\Models\db_functions.php'); // Corrected path
    require('./config/database.php'); // Ensure database initialization

    $isConnecting = dbVerifie(dbConnect(''));

    // App Connection:
    if ($isConnecting == 'connect') {
        require('./app MVC\Controllers\login router.php'); // Corrected path
    } else {
        // Database is already initialized in database.php
        header('location:./index.php');
    }
?>