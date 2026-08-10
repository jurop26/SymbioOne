<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: kontakt.html');
    exit;
}

$to = 'info@symbio.cz';

// Sanitize inputs
$name    = htmlspecialchars(strip_tags(trim($_POST['name']    ?? '')));
$email   = filter_var(trim($_POST['email']   ?? ''), FILTER_SANITIZE_EMAIL);
$phone   = htmlspecialchars(strip_tags(trim($_POST['phone']   ?? '')));
$message = htmlspecialchars(strip_tags(trim($_POST['message'] ?? '')));

if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$message) {
    header('Location: kontakt.html?status=error');
    exit;
}

$subject = "Zpráva z webu od: $name";
$body    = "Jméno: $name\nE-mail: $email\nTelefon: $phone\n\nZpráva:\n$message";
$headers = "From: noreply@symbio.cz\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8";

if (mail($to, $subject, $body, $headers)) {
    header('Location: kontakt.html?status=ok');
} else {
    header('Location: kontakt.html?status=error');
}
exit;
