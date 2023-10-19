<?php 
require 'vendor/autoload.php';

$phpmailer = new PHPMailer();

$phpmailer->isSMTP();

// Saa SMTP-palvelimen tiedot ympäristömuuttujista
$phpmailer->Host = getenv('MAIL_HOST');
$phpmailer->SMTPAuth = true;
$phpmailer->Port = getenv('MAIL_PORT');
$phpmailer->Username = getenv('MAIL_USERNAME');
$phpmailer->Password = getenv('MAIL_PASSWORD');

?>