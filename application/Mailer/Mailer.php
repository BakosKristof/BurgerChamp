<?php

namespace Application\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Mailer
{
    public static function Send($email, $subject, $body)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'mail.nethely.hu';
            $mail->SMTPAuth   = true;
            $mail->Username   = getenv("EMAIL_ADDR");
            $mail->Password   = getenv("EMAIL_PASSWORD");
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->setFrom(getenv("EMAIL_ADDR"), getenv("EMAIL_NAME"));
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->send();
        } catch (Exception $e) {
            echo "Az email küldése nem sikerült. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
        return true;
    }
    public static function MailTemplate($title, $message)
    {
        return '
         <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; text-align: center;">
    <div style="background-color: black; display: flex; justify-content: center; padding: 5px 0;">
        <img src="https://code1-web.paraghtibor.hu/static/media/logo.054ff8c9fa696cfba909.png" style="width: 25%;" alt="Logo">
    </div>
    <h2 style="text-align: center; width: 100%; margin-left: auto; margin-right: auto;">' . $title . '</h2>
    <div style="padding: 0 15px; text-align: center; width: 100%; margin-left: auto; margin-right: auto;">' . $message . '</div>
    <div style="background-color: black; display: flex; justify-content: center; padding: 5px 0; color: #fff;">
        <p style="margin: 0;">2025 SzalkaCar</p>
    </div>
</body>
</html>

         ';
    }
}
