<?php
//Load Composer's autoloader
require __DIR__ . '/vendor/autoload.php';

function sendMail($mailReceiver, $mailSubject, $mailBody){
    require dirname(dirname(__FILE__)) . "/config/mail_cred.php";
    $resend = Resend::client($mail_api);
    $resend->emails->send([
        'from' => 'GameHub & Nocturnal Skirmish <support@nocskir.com>',
        'to' => [$mailReceiver],
        'subject' => $mailSubject,
        'html' => $mailBody,
    ]);
};