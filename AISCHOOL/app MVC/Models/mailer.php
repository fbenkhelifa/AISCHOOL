<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->isHTML(true);
$mail->Host = "smtp.gmail.com";
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->Username = "test.emailsender.moh@gmail.com";
$mail->password = "M072oo3#";

function emailAuth($email,$name,$token){

    $mail->setFrom($email, $name);
    $mail->addAddress('u4276887@gmail.com');

    $mail->Subject = "AISCHOOL EMAIL AUTHONTIFICATION";
    $mail->Body = "
        <pr>مرحبًا بكم في</pr>
        <h1>AISCHOOL</h1> 
             <pr>
            يهدف موقعنا إلى مساعدة الطلاب في تحسين مستواهم الأكاديمي باستخدام تقنيات الذكاء الاصطناعي.
            يوفر الموقع مجموعة من الأدوات المتطورة، مثل توليد الاختبارات الذكية، تصحيح الإجابات تلقائيًا، 
            تقديم تحليلات مفصلة حول الأداء، واقتراح خطط دراسية مخصصة بناءً على احتياجات كل طالب. كما يمكنكم الوصول إلى موارد تعليمية
            شاملة لمساعدتكم على تحقيق أفضل النتائج. انضموا إلينا اليوم واجعلوا التعلم أكثر كفاءة وسهولة!</pr>
        <pr>Click <a href='http://127.0.0.1/Tests/AISCHOOL/index.php?token=$token'>here</a> to authentify your account<pr>
    ";

    $mail->sent();

}


?>
