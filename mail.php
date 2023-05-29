<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';


$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 2;
$mail->Host = 'smtp.hostinger.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'teste@maionhotel.com.br';
$mail->Password = 'E83!26ln';
$mail->setFrom('daniel@iproduce.com.br', 'Daniel Drummond');
$mail->addReplyTo('daniel@iproduce.com.br', 'Daniel Drummond');
$mail->addAddress('teste@maionhotel.com.br', 'WebSite');
$mail->Subject = 'Testing PHPMailer';
// $mail->msgHTML(file_get_contents('message.html'), __DIR__);
$mail->Body = 'This is a plain text message body';
//$mail->addAttachment('test.txt');
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'The email message was sent.';
}
?>