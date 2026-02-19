<?php
require_once 'functions.php'; // Include the functions file

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['unsubscribe_email']);

    // Generate a verification code
    $code = generateVerificationCode();
    
    // Send verification email
    if (sendVerificationEmail($email, $code)) {
        echo "<p>Verification code sent to your email. Please enter it below:</p>";
        echo "<form method='POST'>
                <input type='hidden' name='unsubscribe_email' value='{$email}'>
                <input type='text' name='verification_code' maxlength='6' required placeholder='Enter verification code'>
                <button type='submit' id='submit-verification'>Verify</button>
              </form>";
    } else {
        echo "<p>Error sending verification code. Please try again.</p>";
    }
}

// Process verification code
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verification_code'])) {
    $email = trim($_POST['unsubscribe_email']);
    $code = trim($_POST['verification_code']);

    // Verify the code
    if (verifyCode($email, $code)) {
        if (unsubscribeEmail($email)) {
            echo "<p>You have been unsubscribed successfully.</p>";
        } else {
            echo "<p>Error unsubscribing. Please try again.</p>";
        }
    } else {
        echo "<p>Invalid verification code. Try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Unsubscribe from XKCD Emails</title>
</head>
<body>
    <h2>Unsubscribe from XKCD Emails</h2>
    <form method="POST" action="unsubscribe.php">
        <input type="email" name="unsubscribe_email" required placeholder="Enter your email">
        <button type="submit" id="submit-unsubscribe">Unsubscribe</button>
    </form>
</body>
</html>
