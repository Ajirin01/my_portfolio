<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // echo json_encode($_POST);
    $captcha_response = $_POST['g-recaptcha-response'];
    
    // exit;
    $secret_key = '6LfIVNIpAAAAAOoU_kVba4XrXMJWakJYbeDdMhPm';
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $secret_key,
        'response' => $captcha_response
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($result, true);

    if ($response['success'] == true) {
        // Your email sending logic here
        $from =  "ajirinibi.com.ng";
        $subject = $_POST['subject'];
        $message = "Subject: " . $subject . "\n";
        $message .= "From: " . $_POST['name'] . "\n";
        $message .= "Reply-To: " . $_POST['email'] . "\n\n"; // Adding an extra newline before the message
        $message .= $_POST['message'];
    
        // echo json_encode($message);
        // exit;
    
        if (mail("contact@ajirinibi.com.ng", $subject, $message, $from)) {
            // $_SESSION['flash_message'] = "Message successfully sent. We shall get back to you soon. Thank you!";
            header("Location: index.html?success=Message successfully sent. We shall get back to you soon. Thank you!");
            exit;
        } else {
            // $_SESSION['flash_message'] = "Error occurred! Please try again.";
            header("Location: index.html?errro=Error occurred! Please try again.");
            exit;
        }
    } else {
        header("Location: index.html?errro=Error occurred! reCAPTCHA verification failed.");
        exit;
        // $_SESSION['flash_message'] = "Error occurred! reCAPTCHA verification failed.";
    }
    
    // Redirect back to index.html
    // header("Location: index.html");
    
} else {
    // $_SESSION['flash_message'] = "POST method is required";
    header("Location: index.html?error=POST method is required");
    exit;
}

?>
