<?php
session_start();
$titre="Enregistrement";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");

?>

<?php 
if(empty($_POST['password']) && empty($_POST['confirm'])):?>
	<form  action="connexion.php" method="post" enctype="multipart/form-data">
<div class="form-group">
<label class="col-4" for="password">*Mot de passe :</label>
<input class="col-7" type="password" class="form-control" id="password" name="password" placeholder="mot de passe">
</div>
<div class="form-group">
<label class="col-4" for="confirm">*Retaper le mot de passe :</label>
<input class="col-7" type="password" class="form-control" id="confirm" name="confirm" placeholder="mot de passe">
</div>

<p><input type="submit" value="envoyer" name="submit" /></p></form>

<?php endif; ?>
<?php 
$token = $_GET['token'];
$password_hashed = ($_POST['password']);
$confirm = ($_POST['confirm']);
$pass = password_hash($password_hashed, PASSWORD_BCRYPT);
$confirm = $pass;

$query=$db->prepare('SELECT code, mail FROM recuperation 
LEFT JOIN forum_membres ON recuperation.mail = forum_membres.membre_email
WHERE recuperation.code = :token');
$query->bindValue(':token',$token,PDO::PARAM_INT);
$query->execute();

//Vérification du mdp
if ($pass != $confirm || empty($confirm) || empty($pass))
{
     $mdp_erreur = "Votre mot de passe et votre confirmation diffèrent ou sont vides";
     $i++;
}else{

$query=$db->prepare('UPDATE forum_membres
SET membre_mdp = :password_hashed WHERE forum_membres.membre_email = :mail');
$query->bindValue(':password_hashed',$password_hashed,PDO::PARAM_STR);
$query->execute();
$query->CloseCursor();
}