<?php
session_start();

// CSRF Protection: Check if the session token is set and compare it with the post token
$sessionToken = $_SESSION['csrf_token'] ?? '';
$postToken = $_POST['csrf_token'] ?? '';

if (!$sessionToken || !$postToken || $sessionToken !== $postToken) {
    http_response_code(403);
    echo "Invalid CSRF token. Please try again.";
    exit;
}

// Reset token
unset($_SESSION['csrf_token']);

// Only process POST requests.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify the reCAPTCHA response (Google reCAPTCHA in this example)
    $recaptchaSecret = 'your_secret_key'; // Replace with your actual secret key
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($recaptchaSecret) . '&response=' . urlencode($recaptchaResponse));
    $responseData = json_decode($verifyResponse);
    if (!$responseData->success) {
        // reCAPTCHA validation failed
        http_response_code(400);
        echo "reCAPTCHA validation failed, please try again.";
        exit;
    }

    // Get the form fields and remove whitespace.
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r", "\n"), array(" ", " "), $name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Validate and sanitize
    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($message)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Oops! There was a problem with your submission. Please complete the form and try again.";
        exit;
    }

    // Sanitize the message
    $message = filter_var($message, FILTER_SANITIZE_STRING);

    // Set the recipient email address.
    $recipient = "techwiseteacher01@gmail.com";

    // Set the email subject.
    $subject = "New contact from $name";

    // Build the email content.
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Message:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $name <$email>";

    // Ensure the email is sent securely using SMTP and encrypt the message if possible
    // This part depends on your server setup and whether you are using a library like PHPMailer or SwiftMailer

    // Send the email.
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        // Set a 200 (okay) response code.
        http_response_code(200);
        echo "Thank You! Your message has been sent.";
    } else {
        // Set a 500 (internal server error) response code.
        http_response_code(500);
        echo "Oops! Something went wrong and we couldn't send your message.";
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}

?>