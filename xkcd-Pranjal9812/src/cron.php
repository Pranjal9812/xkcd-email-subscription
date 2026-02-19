<?php
require_once 'functions.php'; // Include necessary functions

// Get list of registered emails
$file = __DIR__ . '/registered_emails.txt';
$emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if (!$emails) {
    die("No registered emails found.");
}

// Fetch XKCD comic data
$content = fetchAndFormatXKCDData();

// Send XKCD email to all registered users
foreach ($emails as $email) {
    sendVerificationEmail($email, $content); // Using sendVerificationEmail() to send XKCD
}

echo "XKCD emails sent successfully!";
?>
