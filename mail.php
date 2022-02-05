<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'config.php';

if (isset($_POST["email"])) {
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
$emailTo = $_POST["email"];

$code = uniqid(true);
$query = $con->prepare("INSERT INTO resetPassword (code, email) VALUES(:cd, :em)");
        $query->bindValue(":cd", $code);
        $query->bindValue(":em", $emailTo);
    
//         //just for debuging SQL queries
     // var_dump($query->errorInfo());
        $query->execute();

if (!$query){
    exit("ERROR");
}
try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'rosca.aurelian@gmail.com';                     //SMTP username
    $mail->Password   = 'ppppp';                               //SMTP password
    $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('noreply@roscasend.com', 'Mailer');
    $mail->addAddress($emailTo);     //Add a recipient
    $mail->addReplyTo('noreply@roscasend.com', 'Information');


    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);   
    $url = "https://" . $_SERVER["HTTP_HOST"] .dirname($_SERVER["PHP_SELF"]) . "/resetPassword.php?code=$code";

    //Set email format to HTML
    $mail->Subject = 'Your password reset link';
    $mail->Body    = "<h1>You requested a password reset</h1>
    Click <a href='$url'> this link </a> to do so";
    $mail->AltBody = "This is the body in plain text for";

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
exit();
}
?>