<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $code = generateVerificationCode();
    sendVerificationEmail($email, $code);
    echo "<p>Verification code sent! Please enter it below:</p>";
    echo "<form method='POST'>
            <input type='hidden' name='email' value='{$email}'>
            <input type='text' name='verification_code' maxlength='6' required placeholder='Enter verification code'>
            <button type='submit' id='submit-verification'>Verify</button>
          </form>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verification_code'])) {
    $email = trim($_POST['email']);
    $code = trim($_POST['verification_code']);

    if (verifyCode($email, $code)) {
        if (registerEmail($email)) {
            echo "<p>You have been successfully subscribed to XKCD comics!</p>";
        } else {
            echo "<p>Error registering email. Please try again.</p>";
        }
    } else {
        echo "<p>Invalid verification code. Try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Email Verification</title>
</head>
<body>
    <h2>Subscribe to XKCD Comics</h2>
    <form method="POST" action="index.php">
        <input type="email" name="email" required placeholder="Enter your email">
        <button type="submit" id="submit-email">Submit</button>
    </form>
</body>
</html>
