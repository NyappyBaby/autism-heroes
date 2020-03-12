<?php
session_start();
$titre="Enregistrement";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php';
require 'C:\laragon\www\autism-heroesV3\vendor\phpmailer\phpmailer\src\Exception.php';
require 'C:\laragon\www\autism-heroesV3\vendor\phpmailer\phpmailer\src\PHPMailer.php';
require 'C:\laragon\www\autism-heroesV3\vendor\phpmailer\phpmailer\src\SMTP.php';
?>


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contact</title>
</head>

<body>
    <h1>Contact</h1>
    <form method="post">
        <div class="container">
        <div class="row">
        <label>Email</label>
        <input type="email" name="email" required><br>
</div>
<div class="row">
        <label>Message</label>
        <textarea name="message" required></textarea><br>
</div>
        <input type="submit">
</div>
    </form>
    <?php
    if (isset($_POST['message'])) {
        $position_arobase = strpos($_POST['email'], '@');
        if ($position_arobase === false)
            echo '<p>Votre email doit comporter un arobase.</p>';
        else {
            $retour = mail('autism.heroesweb@gmail.com', 'Envoi depuis la page Contact', $_POST['message'], 'From: ' . $_POST['email']);
            if($retour)
                echo '<p>Votre message a été envoyé.</p>';
            else
                echo '<p>Erreur.</p>';
        }
    }
    ?>
</body>
</html>