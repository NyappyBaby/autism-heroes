<?php
session_start();
$titre="Enregistrement";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");
echo '<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> Enregistrement';

if ($id!=0) erreur(ERR_IS_CO);
?>
<?php
if (empty($_POST['pseudo']) && empty($_POST['email']) && empty($_POST['password']) && empty($_POST['confirm'])) // Si on la variable est vide, on peut considérer qu'on est sur la page de formulaire
: ?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <meta  http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<div class="container">
    <div class="row justify-content-center d-flex">
<div class="card">
	<h1 class="text-center my-4">Inscription 1/2</h1>
	<form  action="register.php" method="post" enctype="multipart/form-data">
	<fieldset><legend class="col-4">Identifiants</legend>
    <div class="form-group">
    <label class="col-4" for="inputAddress">* Pseudo :</label>
    <input class="col-7" type="text" class="form-control" id="pseudo" name="pseudo" placeholder="ex1225">
  </div>
  <div class="form-group">
    <label class="col-4" for="password">*Mot de passe :</label>
    <input class="col-7" type="password" class="form-control" id="password" name="password" placeholder="mot de passe">
  </div>
  <div class="form-group">
    <label class="col-4" for="confirm">*Retaper le mot de passe :</label>
    <input class="col-7" type="password" class="form-control" id="confirm" name="confirm" placeholder="mot de passe">
  </div>
	</fieldset>
	<fieldset><legend class="col-4">Contacts</legend>
    <div class="form-group">
    <label class="col-4" for="confirm">*Votre email :</label>
    <input class="col-7" type="text" class="form-control" id="email" name="email" placeholder="exemple@exemple.fr">
  </div>
	</fieldset>
    <fieldset><legend class="col-4">Optionnel :</legend>
    <div class="form-group">
    <label class="col-4" for="avatar">Choisissez votre avatar :</label>
    <input class="col-7" type="file" id="avatar" name="avatar">
  </div>
    <label for="signature" class="col-4">Signature :</label>
    <textarea class="col-7" cols="40" rows="4" name="signature" id="signature">La signature est limitée à 200 caractères</textarea>
	</fieldset>
	<p>Les champs précédés d un * sont obligatoires</p>
	<p><input type="submit" value="S'inscrire" name="submit" /></p></form>
    </div>
</div>
</div>
</div>
	</body>
	</html>
	
	


<?php
else :?> //On est dans le cas traitement
<?php
    $pseudo_erreur1 = NULL;
    $pseudo_erreur2 = NULL;
    $mdp_erreur = NULL;
    $email_erreur1 = NULL;
    $email_erreur2 = NULL;
    $signature_erreur = NULL;
    $avatar_erreur = NULL;
    $avatar_erreur1 = NULL;
    $avatar_erreur2 = NULL;
    $avatar_erreur3 = NULL;

//On récupère les variables
$i = 0;
$temps = time(); 
$pseudo= $_POST['pseudo'];
$signature = $_POST['signature'];
$email = $_POST['email'];
$password_hashed = ($_POST['password']);
$confirm = ($_POST['confirm']);
$pass = password_hash($password_hashed, PASSWORD_BCRYPT);
$confirm = $pass;
 
//Vérification du pseudo
$query=$db->prepare('SELECT COUNT(*) AS nbr FROM forum_membres WHERE membre_pseudo =:pseudo');
$query->bindValue(':pseudo',$pseudo, PDO::PARAM_STR);
$query->execute();
$pseudo_free=($query->fetchColumn()==0)?1:0;
$query->CloseCursor();
if(!$pseudo_free)
{
    $pseudo_erreur1 = "Votre pseudo est déjà utilisé par un membre";
    $i++;
}

if (strlen($pseudo) < 3 || strlen($pseudo) >= 15)
{
    $pseudo_erreur2 = "Votre pseudo doit avoir entre 3 et 15 caractères";
    $i++;
}

//Vérification du mdp
if ($pass != $confirm || empty($confirm) || empty($pass))
{
    $mdp_erreur = "Votre mot de passe et votre confirmation diffèrent, ou sont vides";
    $i++;
}

$query=$db->prepare('SELECT COUNT(*) AS nbr FROM forum_membres WHERE membre_pseudo =:pseudo');
$query->bindValue(':pseudo',$pseudo, PDO::PARAM_STR);
$query->execute();
$pseudo_free=($query->fetchColumn()==0)?1:0;

    //Vérification de l'adresse email

    //Il faut que l'adresse email n'ait jamais été utilisée
    $query=$db->prepare('SELECT COUNT(*) AS nbr FROM forum_membres WHERE membre_email =:mail');
    $query->bindValue(':mail',$email, PDO::PARAM_STR);
    $query->execute();
    $mail_free=($query->fetchColumn()==0)?1:0;
    $query->CloseCursor();
    
    if(!$mail_free)
    {
        $email_erreur1 = "Votre adresse email est déjà utilisée par un membre";
        $i++;
    }
    //On vérifie la forme maintenant
    if (!preg_match("#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$#", $email) || empty($email))
    {
        $email_erreur2 = "Votre adresse E-Mail n'a pas un format valide";
        $i++;
    }
  
    //Vérification de la signature
    if (strlen($signature) > 200)
    {
        $signature_erreur = "Votre signature est trop longue";
        $i++;
    }

    //Vérification de l'avatar :
    if (!empty($_FILES['avatar']['size']))
    {
        //On définit les variables :
        $maxsize = 10024; //Poid de l'image
        $maxwidth = 100; //Largeur de l'image
        $maxheight = 100; //Longueur de l'image
        $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'bmp' ); //Liste des extensions valides
        
        if ($_FILES['avatar']['error'] > 0)
        {
                $avatar_erreur = "Erreur lors du transfert de l'avatar : ";
        }
        if ($_FILES['avatar']['size'] > $maxsize)
        {
                $i++;
                $avatar_erreur1 = "Le fichier est trop gros : (<strong>".$_FILES['avatar']['size']." Octets</strong>    contre <strong>".$maxsize." Octets</strong>)";
        }

        $image_sizes = getimagesize($_FILES['avatar']['tmp_name']);
        if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight)
        {
                $i++;
                $avatar_erreur2 = "Image trop large ou trop longue : 
                (<strong>".$image_sizes[0]."x".$image_sizes[1]."</strong> contre <strong>".$maxwidth."x".$maxheight."</strong>)";
        }
        
        $extension_upload = strtolower(substr(  strrchr($_FILES['avatar']['name'], '.')  ,1));
        if (!in_array($extension_upload,$extensions_valides) )
        {
                $i++;
                $avatar_erreur3 = "Extension de l'avatar incorrecte";
        }
    }

   if ($i==0)
   :?>
   <?php
	echo'<h1>Inscription terminée</h1>';
        echo'<p>Bienvenue '.stripslashes(htmlspecialchars($_POST['pseudo'])).' vous êtes maintenant inscrit sur le forum</p>
	<p>Cliquez <a href="./index.php">ici</a> pour revenir à la page d accueil</p>';
	
        //La ligne suivante sera commentée plus bas
	$nomavatar=(!empty($_FILES['avatar']['size']))?move_avatar($_FILES['avatar']):''; 
   
        $query=$db->prepare('INSERT INTO forum_membres (membre_pseudo, membre_mdp, membre_email, membre_avatar,
        membre_signature, membre_inscrit,   
        membre_derniere_visite)
        VALUES (:pseudo, :pass, :email,:nomavatar, :signature, :temps, :temps)');
	$query->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
	$query->bindValue(':pass', $pass, PDO::PARAM_STR);
	$query->bindValue(':email', $email, PDO::PARAM_STR);
	$query->bindValue(':nomavatar', $nomavatar, PDO::PARAM_STR);
	$query->bindValue(':signature', $signature, PDO::PARAM_STR);
	$query->bindValue(':temps', $temps, PDO::PARAM_INT);
    $query->execute();

	//Et on définit les variables de sessions
        $_SESSION['pseudo'] = $pseudo;
        $_SESSION['id'] = $db->lastInsertId(); ;
        $_SESSION['level'] = 2;
        $query->CloseCursor();
    ?>
    <?php else :?>
    <?php 
        echo'<h1>Inscription interrompue</h1>';
        echo'<p>Une ou plusieurs erreurs se sont produites pendant l incription</p>';
        echo'<p>'.$i.' erreur(s)</p>';
        echo'<p>'.$pseudo_erreur1.'</p>';
        echo'<p>'.$pseudo_erreur2.'</p>';
        echo'<p>'.$mdp_erreur.'</p>';
        echo'<p>'.$email_erreur1.'</p>';
        echo'<p>'.$email_erreur2.'</p>';
        echo'<p>'.$signature_erreur.'</p>';
        echo'<p>'.$avatar_erreur.'</p>';
        echo'<p>'.$avatar_erreur1.'</p>';
        echo'<p>'.$avatar_erreur2.'</p>';
        echo'<p>'.$avatar_erreur3.'</p>';
       
        echo'<p>Cliquez <a href="./register.php">ici</a> pour recommencer</p>';
    ?>
<?php endif; ?>

<label>Se souvenir de moi ?</label><input type="checkbox" name="souvenir" /><br />
<?php
//Message
$message = "Bienvenue sur mon super forum !";
//Titre
$titre = "Inscription à mon super forum !";

mail($_POST['email'], $titre, $message);
?>
<?php endif; ?>
</div>
</body>
</html>
