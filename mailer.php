<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Load our connect_db.php
require 'connect_db.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

// Construct the mail content
$html = "<h1>Daily Digest</h1>";
$html .= "<h4>Revisiting articles and knowledges</h3>";
$html .= "<br><hr><br>";
$pagecount = 4;
for ($i=0; $i < $pagecount; $i++) {
	$page = getRandomPage();
	$html .= "<h3>{$page['name']}</h3>";
	$html .= '<p><a href="'.$ini['search_url'].urlencode('"'.$page['name'].'"').'" target="_blank">Read More (Search Page)</a></p>';
	$html .= '<p>~~~</p>';
}
try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp-relay.sendinblue.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    //$mail->SMTPAutoTLS = false;
    $mail->Username   = $ini['smtp_email'];                     // SMTP username
    $mail->Password   = $ini['smtp_pass'];                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom($ini['from_email'], 'BookStack Random Mailer');
    $mail->addAddress($ini['host_email'], 'Franky Hung');     // Add a recipient

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = "Treasure Hoard's Daily Digest";
    $mail->Body    = $html;
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
