<?php
session_start();
$titre="Connexion";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");
echo '<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> Connexion';
?>
<?php
echo '<h1 class="row justify-content-center my-5">Connexion</h1>';
?>
<div class="text-center">
<?php
if ($id!=0) erreur(ERR_CO);
?>
</div>

<?php
if (!isset($_POST['pseudo']) && !isset($_POST['password'])) //On est dans la page de formulaire
:?>

	<form method="post" action="connexion.php">


	
	<p>
		<div  class="row justify-content-center mt-5 mb-3">
	<label for="pseudo">Pseudo :</label><input name="pseudo" type="text" id="pseudo" /><br />
</div>
<div  class="row justify-content-center mb-5">
	<label for="password">Mot de Passe :</label><input type="password" name="password" id="password" />
	</p>
	</div>

	<div  class="row justify-content-center mb-3">
	<p><input  type="submit" value="Connexion" /></p></form>
	</div>
	<div  class="row justify-content-center">
	<a href="./register.php">Pas encore inscrit ?</a>
	</div>
	 
	</div>
	</body>
	</html>';


<?php
else : ?>
<?php
	
    $message='';
    if (empty($_POST['pseudo']) || empty($_POST['password']) ) //Oublie d'un champ
    {
        $message = '<p class="text-center">une erreur s\'est produite pendant votre identification.
	Vous devez remplir tous les champs</p>
	<p class="text-center">Cliquez <a href="./connexion.php">ici</a> pour revenir</p>';
    }
    else //On check le mot de passe
    {
        $query=$db->prepare('SELECT membre_mdp, membre_id, membre_rang, membre_pseudo
        FROM forum_membres WHERE membre_pseudo = :pseudo' );
		$query->bindValue(':pseudo',$_POST['pseudo'], PDO::PARAM_STR);
        $query->execute();
		$data=$query->fetch();
		
		if(password_verify($_POST['password'], $data['membre_mdp'])){ // Acces OK !
			if ($data['membre_rang'] == 0) //Le membre est banni
			{
				$message="<p class='text-center'>Vous avez été banni, impossible de vous connecter sur ce forum</p>";
			} else {
	    $_SESSION['pseudo'] = $data['membre_pseudo'];
	    $_SESSION['level'] = $data['membre_rang'];
	    $_SESSION['id'] = $data['membre_id'];
	    $message = '<p class="text-center">Bienvenue '.$data['membre_pseudo'].', 
			vous êtes maintenant connecté!</p>
			<p class="text-center">Cliquez <a href="./index.php">ici</a> 
            pour revenir à la page d accueil</p>';
            
            if (isset($_POST['souvenir']))
            {
            $expire = time() + 365*24*3600;
            setcookie('pseudo', $_SESSION['pseudo'], $expire); 
			}
		}
     
    }
	else // Acces pas OK !
	{
	    $message = '<p class="text-center">Une erreur s\'est produite 
	    pendant votre identification.<br /> Le mot de passe ou le pseudo 
            entré n\'est pas correcte.</p><p class="text-center">Cliquez <a href="./connexion.php">ici</a> 
	    pour revenir à la page précédente
	    <br /><br />Cliquez <a  href="./index.php">ici</a> 
	    pour revenir à la page d accueil</p>';
	}
    $query->CloseCursor();
    }
    echo $message.'</div></body></html>';

?>
<?php endif; ?>
<input type="hidden" name="page" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />


