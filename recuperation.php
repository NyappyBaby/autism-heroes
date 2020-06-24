<?php
session_start();
$titre="Pass oublié";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php';
require 'C:\laragon\www\autism-heroesV3\vendor\phpmailer\phpmailer\src\Exception.php';
require 'C:\laragon\www\autism-heroesV3\vendor\phpmailer\phpmailer\src\PHPMailer.php';
require 'C:\laragon\www\autism-heroesV3\vendor\phpmailer\phpmailer\src\SMTP.php';

echo '<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> Pass oublié';

if ($id!=0) erreur(ERR_IS_CO);
?>
<?php
if (empty($_POST['recup_email'])) // Si on la variable est vide, on peut considérer qu'on est sur la page de formulaire
: ?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <meta  http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mot de passe oublié</title>
</head>
<div class="container">
    <div class="row justify-content-center d-flex">
<div class="">
	<h1 class="text-center my-4">Mot de passe oublié</h1>
	<form  action="recuperation.php" method="post" enctype="multipart/form-data">

    <div class="form-group">
    <label class="col-4" for="confirm">*Votre email :</label>
    <input class="col-7" type="text" class="form-control" id="email" name="recup_email" placeholder="exemple@exemple.fr">
    </div>
	<p><input type="submit" value="Envoyer" name="recup_submit" /></p></div></form>
    </div>
</div>
</div>
</div>
	</body>
	</html>
	
	


<?php
else :?> //On est dans le cas traitement
<?php

    $email_erreur1 = NULL;
    $email_erreur2 = NULL;
 

//On récupère les variables

$email = $_POST['recup_email'];

$code = random_int ( 100000 , 500000);




    //Vérification de l'adresse email

    $query=$db->prepare('SELECT COUNT(*) AS nbr FROM forum_membres WHERE membre_email =:mail');
    $query->bindValue(':mail',$email, PDO::PARAM_STR);
    $query->execute();
    $mail_free=($query->fetchColumn()==0)?1:0;
    $query->CloseCursor();

    if(!$mail_free)
    {
    
  
    //On vérifie la forme maintenant
    if (!preg_match("#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$#", $email) || empty($email))
    {
        $email_erreur2 = "Votre adresse E-Mail n'a pas un format valide";
        $i++;
    }

?>
   <?php

	
        $query=$db->prepare('INSERT INTO recuperation (id,mail, code)
        VALUES (0,:mail, :code)');
	$query->bindValue(':mail', $email, PDO::PARAM_STR);

	$query->bindValue(':code', $code, PDO::PARAM_INT);
    $query->execute();

	//Et on définit les variables de sessions
    
        $_SESSION['id'] = $db->lastInsertId(); ;
      
        $query->CloseCursor();
    ?>
   
    



<?php

if(empty($mail)) {
//Message
$message = "Bienvenue sur le site autism-heroes, votre inscription est bien prise en compte!";
//Titre
$titre = "Inscription";



$mail = new PHPMailer();
$mail->CharSet = 'UTF-8';
$to               = $email;
$username         = 'testchiantemail@gmail.com';
$password         = 'Vanna30032012';
echo 'Welcome to Laragon Mail Analyzer...';
$subject          = 'Mot de passe oublié';
$body             = '<p>Pour modifier votre mot de passe veuillez suivre le lien suivant : http://127.0.0.1/autism-heroesV3/recuperationOk.php?token='.$code.'</p>';

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

} else {
    echo'erreur'; 
}
    } else {
        echo'mail existe pas';
    }
?>
<?php endif; ?>
</div>
</body>
</html>
