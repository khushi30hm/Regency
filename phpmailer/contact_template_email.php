<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';

$mail = new PHPMailer(true);

try {
   
    $mail->setFrom('khushitiwari2415@gmail.com', 'Message from Paradise Hotel'); 
    $mail->addAddress('khushi.hospitalityminds@gmail.com', 'Khushi');             // Receiver's Email Address and Name
    // $mail->addReplyTo('noreply@Paradise.com', 'Message from Paradise Hotel');      // Reply-to Email Address and Name
    
    $mail->isHTML(true);
    $mail->Subject = 'Message from Paradise Hotel';  // Email Subject    

    // Email verification function
    function isEmail($email_contact) {
        return(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/", $email_contact));
    }

    // Form fields
    $name_contact = $_POST['name_contact'];
    $lastname_contact = $_POST['lastname_contact'];
    $email_contact = $_POST['email_contact'];
    $phone_contact = $_POST['phone_contact'];
    $message_contact = $_POST['message_contact'];

    // Validation checks
    if (trim($name_contact) == '') {
        echo '<div class="error_message">You must enter your Name.</div>';
        exit();
    } else if (trim($lastname_contact) == '') {
        echo '<div class="error_message">Please enter your Last Name.</div>';
        exit();
    } else if (trim($email_contact) == '') {
        echo '<div class="error_message">Please enter a valid email address.</div>';
        exit();
    } else if (!isEmail($email_contact)) {
        echo '<div class="error_message">You have entered an invalid e-mail address.</div>';
        exit();
    } else if (trim($phone_contact) == '') {
        echo '<div class="error_message">Please enter a valid phone number.</div>';
        exit();
    } else if (!is_numeric($phone_contact)) {
        echo '<div class="error_message">Phone number can only contain numbers.</div>';
        exit();
    } else if (trim($message_contact) == '') {
        echo '<div class="error_message">Please enter your message.</div>';
        exit();
    }

    // Email content setup
    $email_html = file_get_contents('template-email.html');
    $e_content = "You have been contacted by <strong>$name_contact $lastname_contact</strong> with the following message:<br><br>$message_contact<br><br>You can contact $name_contact via email at $email_contact or by phone at $phone_contact";
    $body = str_replace(array('message'), array($e_content), $email_html);
    $mail->MsgHTML($body);

    $mail->CharSet = 'UTF-8';

    $mail->send();

    // Confirmation email to form submitter
    $mail->ClearAddresses();
    $mail->addAddress($email_contact);
    $mail->isHTML(true);
    $mail->Subject = 'Confirmation';
    $email_html_confirm = file_get_contents('confirmation.html');
    $body = str_replace(array('message'), array($e_content), $email_html_confirm);
    $mail->MsgHTML($body);

    $mail->CharSet = 'UTF-8';
    $mail->Send();

    // Success message
    echo '<div id="success_page">
            <div class="icon icon--order-success svg">
                 <svg xmlns="http://www.w3.org/2000/svg" width="72px" height="72px">
                  <g fill="none" stroke="#8EC343" stroke-width="2">
                     <circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
                     <path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
                  </g>
                 </svg>
             </div>
            <h5>Thank you!<span>Request successfully sent!</span></h5>
        </div>';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
