<?php
session_start();
$titre="Profil";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");
//On récupère la valeur de nos variables passées par URL
$action = isset($_GET['action'])?htmlspecialchars($_GET['action']):'consulter';
$membre = isset($_GET['m'])?(int) $_GET['m']:'';
?>

<?php
//On regarde la valeur de la variable $action
switch($action)
{
    //Si c'est "consulter"
    case "consulter":
       //On récupère les infos du membre
       $query=$db->prepare('SELECT membre_pseudo, membre_avatar,
       membre_email, membre_signature,
       membre_inscrit
       FROM forum_membres WHERE membre_id=:membre');
       $query->bindValue(':membre',$membre, PDO::PARAM_INT);
       $query->execute();
       $data=$query->fetch();

       //On affiche les infos sur le membre
       echo '<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> 
       profil de '.stripslashes(htmlspecialchars($data['membre_pseudo']));
       echo'<h1>Profil de '.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</h1>';
       
       echo'<img src="'.$data['membre_avatar'].'"
       alt="Ce membre n a pas d avatar" />';
       
       echo'<p><strong>Adresse E-Mail : </strong>
       <a href="mailto:'.stripslashes($data['membre_email']).'">
       '.stripslashes(htmlspecialchars($data['membre_email'])).'</a><br />';
 
       echo'Signature :
       '.stripslashes(htmlspecialchars($data['membre_signature'])).'
       <br /><br />';

       echo'Ce membre est inscrit depuis le
       <strong>'.date('d/m/Y',$data['membre_inscrit']).'</strong>
       <br /><br />';

    
       
       $query->CloseCursor();
       break;
       
 ?>


<?php
    //Si on choisit de modifier son profil
    case "modifier":
    if (empty($_POST['sent'])) // Si on la variable est vide, on peut considérer qu'on est sur la page de formulaire
    {
        //On commence par s'assurer que le membre est connecté
        if ($id==0) erreur ('Vous devez être connecté pour accéder à votre compte');

        //On prend les infos du membre
        $query=$db->prepare('SELECT membre_pseudo, membre_email, membre_signature,
        membre_avatar
        FROM forum_membres WHERE membre_id=:id');
        $query->bindValue(':id',$id,PDO::PARAM_INT);
        $query->execute();
        $data=$query->fetch();
        echo '<div class="container">';
        echo '<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> Modification du profil';
    
       
        echo '<div class="row my-5">';
        if (verif_auth(ADMIN)) {
            echo'<a class="row btn btn-primary mr-3" href="admin.php">Admin</a>';
        };
        echo '<a href="messagesprives.php"><div class="btn btn-dark mr-2">Messages Privés</div></a>';
       
        
        echo '<a href="memberlist.php"><div class="btn btn-dark mr-2">Liste des membre</div></a>';
     
        
        echo '<a href="amis.php"><div class="btn btn-dark mr-2">Liste des amis</div></a>'.'<a class="mt-2 ml-5" href="deconnexion.php">Déconnexion</a>';
        echo '</div>';
        echo '<div class="row justify-content-center">';
        echo '<h1>Modifier son profil</h1>';
        echo '</div>';
        
        echo '<form method="post" action="voirprofil.php?action=modifier" enctype="multipart/form-data">
       
 
        <legend class="mt-2">Identifiants</legend>
        <div class="row my-2">
        Pseudo : <strong>'.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</strong><br />
        </div>      
        <div class="row my-2">
        <label for="password">Nouveau mot de Passe :</label>
        <input type="password" name="password" id="password" /><br />
        </div>
        <div class="row my-2">
        <label for="confirm">Confirmer le mot de passe :</label>
        <input type="password" name="confirm" id="confirm"  />
        </div>
        
        
        <div class="row my-2">
        <legend>Contacts</legend>
        </div>
        <div class="row my-2">
        <label for="email">Votre adresse E_Mail :</label>
        <input type="text" name="email" id="email"
        value="'.stripslashes($data['membre_email']).'" /><br />
        </div>
 
       
 
        <div class="row my-2">
        <legend>Profil sur le forum</legend>
        </div>
        <div class="row my-2">
        <label for="avatar">Changer votre avatar :</label>
        <input type="file" name="avatar" id="avatar" />
        (Taille max : 50 mo)<br /><br />
        </div>
        <div class="row my-2">
        <label><input type="checkbox" name="delete" value="Delete" />
        Supprimer l avatar</label>
        Avatar actuel :
        <img src="'.$data['membre_avatar'].'"
        alt="pas d avatar" />
        </div>
        <div class="row my-2">
        <label for="signature">Signature :</label>
        <textarea cols="40" rows="4" name="signature" id="signature">
        '.stripslashes($data['membre_signature']).'</textarea>
        </div>
     
     
        <div class="row my-2">
        <p>
        <input type="submit" value="Modifier son profil" />
        <input type="hidden" id="sent" name="sent" value="1" />
        </p></div></form>';
        $query->CloseCursor();   
      
        echo '</div>';
        
    }  

  
    else //Cas du traitement
    {
     //On déclare les variables 

    $mdp_erreur = NULL;
    $email_erreur1 = NULL;
    $email_erreur2 = NULL;

    $signature_erreur = NULL;
    $avatar_erreur = NULL;
    $avatar_erreur1 = NULL;
    $avatar_erreur2 = NULL;
    $avatar_erreur3 = NULL;

    //Encore et toujours notre belle variable $i :p
    $i = 0;
    $temps = time(); 
    $signature = $_POST['signature'];
    $email = $_POST['email'];

    $password_hashed = ($_POST['password']);
    $confirm = ($_POST['confirm']);
    $pass = password_hash($password_hashed, PASSWORD_BCRYPT);
    $confirm = $pass;


    //Vérification du mdp
    if ($pass != $confirm || empty($confirm) || empty($pass))
    {
         $mdp_erreur = "Votre mot de passe et votre confirmation diffèrent ou sont vides";
         $i++;
    }

    //Vérification de l'adresse email
    //Il faut que l'adresse email n'ait jamais été utilisée (sauf si elle n'a pas été modifiée)

    //On commence donc par récupérer le mail
    $query=$db->prepare('SELECT membre_email FROM forum_membres WHERE membre_id =:id'); 
    $query->bindValue(':id',$id,PDO::PARAM_INT);
    $query->execute();
    $data=$query->fetch();
    if (strtolower($data['membre_email']) != strtolower($email))
    {
        //Il faut que l'adresse email n'ait jamais été utilisée
        $query=$db->prepare('SELECT COUNT(*) AS nbr FROM forum_membres WHERE membre_email =:mail');
        $query->bindValue(':mail',$email,PDO::PARAM_STR);
        $query->execute();
        $mail_free=($query->fetchColumn()==0)?1:0;
        $query->CloseCursor();
        if(!$mail_free)
        {
            $email_erreur1 = "Votre adresse email est déjà utilisé par un membre";
            $i++;
        }

        //On vérifie la forme maintenant
        if (!preg_match("#^[a-z0-9A-Z._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email) || empty($email))
        {
            $email_erreur2 = "Votre nouvelle adresse E-Mail n'a pas un format valide";
            $i++;
        }
    }
  

    //Vérification de la signature
    if (strlen($signature) > 200)
    {
        $signature_erreur = "Votre nouvelle signature est trop longue";
        $i++;
    }
 
 
    //Vérification de l'avatar
 
    if (!empty($_FILES['avatar']['size']))
    {
        //On définit les variables :
        $maxsize = 10000000; //Poid de l'image
        $maxwidth = 3000; //Largeur de l'image
        $maxheight = 3000; //Longueur de l'image
        //Liste des extensions valides
        $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'bmp' );
 
        if ($_FILES['avatar']['error'] > 0)
        {
        $avatar_erreur = "Erreur lors du tranfsert de l'avatar : ";
        }
        if ($_FILES['avatar']['size'] > $maxsize)
        {
        $i++;
        $avatar_erreur1 = "Le fichier est trop gros :
        (<strong>".$_FILES['avatar']['size']." Octets</strong>
        contre <strong>".$maxsize." Octets</strong>)";
        }
 
        $image_sizes = getimagesize($_FILES['avatar']['tmp_name']);
        if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight)
        {
        $i++;
        $avatar_erreur2 = "Image trop large ou trop longue :
        (<strong>".$image_sizes[0]."x".$image_sizes[1]."</strong> contre
        <strong>".$maxwidth."x".$maxheight."</strong>)";
        }
 
        $extension_upload = strtolower(substr(  strrchr($_FILES['avatar']['name'], '.')  ,1));
        if (!in_array($extension_upload,$extensions_valides) )
        {
                $i++;
                $avatar_erreur3 = "Extension de l'avatar incorrecte";
        }
    }


    echo '<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> Modification du profil';
    echo '<h1>Modification d\'un profil</h1>';

 
    if ($i == 0) // Si $i est vide, il n'y a pas d'erreur
    {
        if (!empty($_FILES['avatar']['size']))
        {
                $nomavatar=move_avatar($_FILES['avatar']);
                $query=$db->prepare('UPDATE forum_membres
                SET membre_avatar = :avatar 
                WHERE membre_id = :id');
                $query->bindValue(':avatar',$nomavatar,PDO::PARAM_STR);
                $query->bindValue(':id',$id,PDO::PARAM_INT);
                $query->execute();
                $query->CloseCursor();
        }
 
        //Une nouveauté ici : on peut choisis de supprimer l'avatar
        if (isset($_POST['delete']))
        {
                $query=$db->prepare('UPDATE forum_membres
		SET membre_avatar=0 WHERE membre_id = :id');
                $query->bindValue(':id',$id,PDO::PARAM_INT);
                $query->execute();
                $query->CloseCursor();
        }
 
        echo'<h1>Modification terminée</h1>';
        echo'<p>Votre profil a été modifié avec succès !</p>';
        echo'<p>Cliquez <a href="./index.php">ici</a> 
        pour revenir à la page d accueil</p>';
 
        //On modifie la table
 
        $query=$db->prepare('UPDATE forum_membres
        SET  membre_mdp = :mdp, membre_email=:mail,
        membre_signature=:sign
        WHERE membre_id=:id');
        $query->bindValue(':mdp',$pass,PDO::PARAM_STR);
        $query->bindValue(':mail',$email,PDO::PARAM_STR);
     
   
        $query->bindValue(':sign',$signature,PDO::PARAM_STR);
    
        $query->bindValue(':id',$id,PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();
    }
    else
    {
        echo'<h1>Modification interrompue</h1>';
        echo'<p>Une ou plusieurs erreurs se sont produites pendant la modification du profil</p>';
        echo'<p>'.$i.' erreur(s)</p>';
        echo'<p>'.$mdp_erreur.'</p>';
        echo'<p>'.$email_erreur1.'</p>';
        echo'<p>'.$email_erreur2.'</p>';
      
        echo'<p>'.$signature_erreur.'</p>';
        echo'<p>'.$avatar_erreur.'</p>';
        echo'<p>'.$avatar_erreur1.'</p>';
        echo'<p>'.$avatar_erreur2.'</p>';
        echo'<p>'.$avatar_erreur3.'</p>';
        echo'<p> Cliquez <a href="./voirprofil.php?action=modifier">ici</a> pour recommencer</p>';
    }
} //Fin du else
    break;
 
default; //Si jamais c'est aucun de ceux là c'est qu'il y a eu un problème :o
echo'<p>Cette action est impossible</p>';
 
} //Fin du switch
?>
</div>
</body>
</html>
</html>





