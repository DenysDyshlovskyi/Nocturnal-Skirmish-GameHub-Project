<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

function sendMail($mailReceiver, $mailSubject, $mailBody, $mailBodyAlt){
    require '../config/mail_cred.php';
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->CharSet = "UTF-8";
        $mail->Encoding = 'base64';
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.mailersend.net';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'MS_lQ4E4r@trial-z86org8w6kngew13.mlsender.net';                     //SMTP username
        $mail->Password   = $mailpassword;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
    
        //Recipients
        $mail->setFrom('MS_lQ4E4r@trial-z86org8w6kngew13.mlsender.net', 'GameHub');
        $mail->addAddress($mailReceiver);
    
        //Content
        $mail->isHTML(true);
        $mail->Subject = $mailSubject;
        $mail->Body    = $mailBody;
        $mail->AltBody = $mailBodyAlt;
    
        $mail->send();
    } catch (Exception $e) {
    };
};