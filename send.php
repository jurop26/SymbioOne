<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: kontakt.html');
    exit;
}

$to = 'info@symbioone.cz';

// Allow redirect only to relative paths on this site
$raw_return = trim($_POST['return_url'] ?? 'kontakt.html');
$return_url = preg_match('#^[a-zA-Z0-9_./-]+\.html$#', $raw_return) ? $raw_return : 'kontakt.html';

// Sanitize inputs
$name    = htmlspecialchars(strip_tags(trim($_POST['name']    ?? '')));
$email   = filter_var(trim($_POST['email']   ?? ''), FILTER_SANITIZE_EMAIL);
$phone   = htmlspecialchars(strip_tags(trim($_POST['phone']   ?? '')));
$message = htmlspecialchars(strip_tags(trim($_POST['message'] ?? '')));

if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$message) {
    header("Location: {$return_url}?status=error");
    exit;
}

$subject = "Zpráva z webu od: $name";
$body    = "Jméno: $name\nE-mail: $email\nTelefon: $phone\n\nZpráva:\n$message";
$headers = "From: noreply@symbioone.cz\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8";

if (mail($to, $subject, $body, $headers)) {
    header("Location: {$return_url}?status=ok");
} else {
    header("Location: {$return_url}?status=error");
}
exit;
