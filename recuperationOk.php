<?php
session_start();
$titre = "Pass oublié";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");

?>
<form action="recuperationOk.php?token=<?=$_GET['token']?>" method="post" enctype="multipart/form-data">
     <?php
     if (empty($_POST['password']) && empty($_POST['confirm'])) : ?>

          <div class="form-group">
               <label class="col-4" for="password">*Mot de passe :</label>
               <input class="col-7" type="password" class="form-control" id="password" name='password' placeholder="mot de passe">
          </div>
          <div class="form-group">
               <label class="col-4" for="confirm">*Retaper le mot de passe :</label>
               <input class="col-7" type="password" class="form-control" id="confirm" name='confirm' placeholder="mot de passe">
          </div>

          <p><input type="submit" value="envoyer" name="submit" /></p>
</form>


<?php else : 
     
          if (isset($_GET['token'])) {
               $token = $_GET['token'];
          };
          $password_hashed = ($_POST['password']);
          $confirm = ($_POST['confirm']);
          $pass = password_hash($password_hashed, PASSWORD_BCRYPT);


          if ($password_hashed != $confirm || empty($confirm) || empty($password_hashed)) {
               echo "Votre mot de passe et votre confirmation diffèrent ou sont vides, veuillez recommencer";
          } else {

               
               $query = $db->prepare('SELECT * FROM forum_membres
               LEFT JOIN recuperation ON forum_membres.membre_email = recuperation.mail
               WHERE recuperation.code = :token');
               $query->bindValue(':token', $_GET['token'], PDO::PARAM_INT);
               $query->execute();
               $data = $query->fetch();
               $query->CloseCursor();

               $mail = $data['membre_email'];


               $query = $db->prepare('UPDATE forum_membres
               SET  membre_mdp = :mdp
               WHERE membre_email=:mail');
               $query->bindValue(':mdp', $pass, PDO::PARAM_STR);
               $query->bindValue(':mail',$mail,PDO::PARAM_STR);
               $query->execute();
               $query->CloseCursor();


               echo 'Changement effectué vous pouvez vous connecter';
               
          }

     


endif; ?>