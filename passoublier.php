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
<form  action="passoublier.php" method="post" enctype="multipart/form-data">
<div class="form-group">
    <label class="col-4" for="confirm">*Votre email :</label>
    <input class="col-7" type="text" class="form-control" id="email" name="email" placeholder="exemple@exemple.fr">
  </div>
  <p><input type="submit" value="Valider" name="submit" /></p>
</form>

  <?php 

//Récuperer email depuis variable $_POST
$email = $_POST['email'];
//Verification existance de l'email sur la base de données
$query=$db->prepare("SELECT COUNT(*) FROM forum_membres WHERE membre_email = :email");
$query->bindValue(':email', $email, PDO::PARAM_STR);
$query->execute();
$data=$query->fetch();

$token = uniqid();
//Si le mail existe
if($data['membre_email'] == 1){
//Génerer un token unique et le stocker dans la base de données
$query=$db->prepare("INSERT INTO token FROM forum_membres WHERE token = :token");
$query->bindValue(':token', $token, PDO::PARAM_STR);
$query->execute();
//Créer le message a envoyer par mail avec un lien menant vers la page permettant la modification de mot de passe
//Envoyer le mail
$mail = new PHPMailer();
$mail->CharSet = 'UTF-8';
$to               = $email;
$username         = 'testchiantemail@gmail.com';
$password         = 'Vanna30032012';
echo 'Welcome to Laragon Mail Analyzer...';
$subject          = 'Inscription';
$body             = '<p>Veuillez suivre le lien suivant pour modifier le mot de passe : <a href="localhost/autism-heroesV3/forget.php?'.$token.'Ici</a></p>';

$mail->IsSMTP();
$mail->SMTPOptions = array(
    'ssl' => array(
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true
    )
);
$mail->SMTPDebug  = 2;                     
$mail->SMTPAuth   = true;                  
$mail->SMTPSecure = 'tls';                 
$mail->Host       = 'smtp.gmail.com';      
$mail->Port       = 587;                   
$mail->Username   = $username;  
$mail->Password   = $password;            

$mail->SetFrom($username);
$mail->Subject    = $subject;
$mail->MsgHTML($body);
$address = $to;
$mail->AddAddress($address);
if(!$mail->Send()) {
  echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
  echo 'Message sent successfully!';
}
//Rediriger vers une page confirmant l'envoie d'email
}else{ //Sinon
//Rediriger vers la page précédent, ajouter un message d'erreur comme quoi l'utilisateur n'existe pas
}

?>
