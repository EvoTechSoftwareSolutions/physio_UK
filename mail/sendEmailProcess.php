<?php

require "../mail/SMTP.php";
require "../mail/PHPMailer.php";
require "../mail/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

// Function to sanitize and validate input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Define variables
$name = $email = $phone = $subject = $message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate each input
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $subject = sanitizeInput($_POST['subject']);
    $message = sanitizeInput($_POST['message']);
   

    // Validate Name
    if (empty($name)) {
        $errors[] = "Name is required";
    }

    
    // Validate Email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Validate Mobile
    // if (empty($phone)) {
    //     $errors[] = "Mobile is required";
    // } elseif (!preg_match('/^\d{10}$/', $phone)) {
    //     $errors[] = "Mobile should be a 10-digit number";
    // }
    

    // Validate Subject
    if (empty($subject)) {
        $errors[] = "Subject is required";
    }

     // Validate Message
     if (empty($message)) {
        $errors[] = "Message is required";
    }

    // If no errors, you can proceed with further actions
    if (empty($errors)) {
        
    $mail = new PHPMailer;
    $mail->IsSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'et.website.message@gmail.com';
    $mail->Password = 'glalywegifqhgjhf';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->setFrom('et.website.message@gmail.com', 'Client Message');
    $mail->addReplyTo('et.website.message@gmail.com', 'Client Message');
    $mail->addAddress('evotech.softwaresolutions@gmail.com');
    $mail->isHTML(true);
    $mail->Subject = 'Client message';
    $bodyContent = '<h1>Customer Message</h1>
    <h3>'.$name.'</h3>
    <h3>'.$email.'</h3>
    <h3>'.$phone.'</h3>
    <h3>'.$subject.'</h3>
    <h3>'.$message.'</h3>';
    $mail->Body    = $bodyContent;

    if (!$mail->send()) {
        echo 'Service Unavailable. Please try again later';
    } else {
        echo 'Message Sent successfully';
    }
    } else {
        echo $errors[0];
    }
}