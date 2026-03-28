<?php

    function logging($db) {
        array_map('trim', $_POST);
        array_map('htmlspecialchars', $_POST);
        array_map('addslashes', $_POST);

        $login = $db->prepare('SELECT userName FROM user WHERE userName = ? AND password = ? ');
        $login->execute(array($_POST['username'], hash("sha256", $_POST["password"])));
        if ($userName = $login->fetch()) {
            $_SESSION["userName"] = $userName[0];
            header('location:../index.php'); // Redirect to the main page
        } else {
            $_SESSION["login_error"] = "اسم المستخدم او كلمة المرور خاطئة"; // Store error message in session
            header('location:../app/Views/login.html'); // Redirect back to login page
        }
        exit;
    }

    function signing($db) {
        if (isset($_POST['full_name']) && isset($_POST['choices']) && isset($_POST['password']) && isset($_POST['email'])) {
            array_map('trim', $_POST);
            array_map('htmlspecialchars', $_POST);
            array_map('addslashes', $_POST);

            if ($_POST['choices'] == 10 && isset($_POST['specialization31'])) {
                $educationLvl = 10 + $_POST['specialization31'];
            } elseif ($_POST['choices'] > 10 && isset($_POST['specialization32_33'])) {
                $educationLvl = $_POST['choices'] + $_POST['specialization32_33'];
            } else {
                $educationLvl = $_POST['choices'];
            }

            $userName = $_POST['full_name'];
            $password = hash('sha256', $_POST['password']);
            $email = $_POST['email'];

            // Check if the email already exists
            $emailCheck = $db->prepare('SELECT COUNT(*) FROM USER WHERE userEmail = ?');
            $emailCheck->execute([$email]);
            if ($emailCheck->fetchColumn() > 0) {
                $_SESSION['signup_error'] = "❌ البريد الإلكتروني مستخدم بالفعل.";
                header('location:../app/Views/signup.html'); // Redirect back to sign-up page
                exit;
            }

            $signing = $db->prepare('INSERT INTO USER (userName, userEmail, password, educationalLvl, rank, moyenne, progressionLvl, level, badge) VALUES 
                    (?, ?, ?, ?, 0, 0, 0, 0, 1)');
            $signing->execute([$userName, $email, $password, $educationLvl]);

            $_SESSION['signup_success'] = "✅ تم إنشاء الحساب بنجاح.";
            header('location:../app/Views/signup.html'); // Redirect back to sign-up page
            exit;
        }
    }

?>
