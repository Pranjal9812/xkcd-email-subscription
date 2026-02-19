<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

/**
 * Generate a 6-digit numeric verification code.
 */
function generateVerificationCode(): string {
    return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

/**
 * Send a verification code to an email via PHPMailer.
 */
function sendVerificationEmail(string $email, string $code): bool {
    // Store email + code for later verification
    file_put_contents(__DIR__ . '/codes.txt', "$email,$code" . PHP_EOL, FILE_APPEND | LOCK_EX);

    return sendEmail($email, "Your Verification Code", "<p>Your verification code is: <strong>{$code}</strong></p>");
}

/**
 * Verify the provided code matches the expected one.
 */
function verifyCode(string $email, string $code): bool {
    $file = __DIR__ . '/codes.txt';

    // Read stored verification codes
    $codes = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($codes as $entry) {
        $data = explode(',', $entry);

        // Ensure array has both email & code before accessing indices
        if (count($data) < 2) {
            continue; // Skip invalid entries
        }

        list($storedEmail, $storedCode) = $data;

        if ($storedEmail === $email && $storedCode === $code) {
            return true;
        }
    }

    return false;
}

/**
 * Register an email by storing it in a file.
 */
function registerEmail($email): bool {
    $file = __DIR__ . '/registered_emails.txt';
    return file_put_contents($file, $email . PHP_EOL, FILE_APPEND | LOCK_EX) !== false;
}

/**
 * Unsubscribe an email by removing it from the list.
 */
function unsubscribeEmail(string $email): bool {
    $file = __DIR__ . '/registered_emails.txt';

    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!in_array($email, $emails)) return false;

    $emails = array_filter($emails, fn($e) => $e !== $email);
    return file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL) !== false;
}

/**
 * Send an unsubscribe confirmation email via PHPMailer.
 */
function sendUnsubscribeEmail(string $email, string $code): bool {
    return sendEmail($email, "Confirm Un-subscription", "<p>To confirm un-subscription, use this code: <strong>{$code}</strong></p>");
}

/**
 * Fetch random XKCD comic and format data as HTML.
 */
function fetchAndFormatXKCDData(): string {
    $randomComicID = mt_rand(1, 2800);
    $url = "https://xkcd.com/{$randomComicID}/info.0.json";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $jsonData = curl_exec($ch);
    curl_close($ch);

    if ($jsonData === false) return "<p>Error fetching XKCD comic.</p>";

    $data = json_decode($jsonData, true);

    //  Log the XKCD comic title and time
    $logLine = date('Y-m-d H:i:s') . " - {$data['title']}\n";
    file_put_contents(__DIR__ . '/xkcd_log.txt', $logLine, FILE_APPEND | LOCK_EX);

    return "<h2>{$data['title']}</h2>
            <img src='{$data['img']}' alt='{$data['title']}'>
            <p>{$data['alt']}</p>
            <p><a href='https://xkcd.com/{$randomComicID}'>View on XKCD</a></p>";
}


/**
 * Send the formatted XKCD updates to registered emails via PHPMailer.
 */
function sendXKCDUpdatesToSubscribers(): void {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (empty($emails)) return;

    $comicHTML = fetchAndFormatXKCDData();

    foreach ($emails as $email) {
        sendEmail($email, "Your XKCD Comic", $comicHTML);
    }
}

/**
 * Universal function to send emails using PHPMailer (SMTP).
 */
function sendEmail(string $recipient, string $subject, string $body): bool {
    $mail = new PHPMailer(true);

    try {
        $dotenv = parse_ini_file(__DIR__ . '/.env'); // Load environment variables

        if (!$dotenv || !isset($dotenv['SMTP_USER']) || !isset($dotenv['SMTP_PASS'])) {
            throw new Exception("Missing SMTP credentials in .env file");
        }

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $dotenv['SMTP_USER'];
        $mail->Password = $dotenv['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($dotenv['SMTP_USER'], 'XKCD Subscription');
        $mail->addAddress($recipient);

        // Enable debugging for testing (set to 0 for production)
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';

        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}
