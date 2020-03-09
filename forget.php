<?php 

session_start();
$titre="Enregistrement";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");


$token = $_GET['token'];
//Vérification de l'existance du token dans la base de données + vérification de date de demande de nouveau mot de passe + vérification de changement déjà effectué.
$query=$db->prepare("SELECT token FROM forum_membres WHERE token = :token");
$query->bindValue(':token', $token, PDO::PARAM_STR);
$query->execute();
$data = $query->fetch();

if($data == 1 && $query[0]["flag"] == 1 && strtotime($query[0]["date_demande"]) > time()-(3600*24)):?>
    <div class="form-group">
    <label class="col-4" for="password">*Mot de passe :</label>
    <input class="col-7" type="password" class="form-control" id="password" name="password" placeholder="mot de passe">
  </div>
  <div class="form-group">
    <label class="col-4" for="confirm">*Retaper le mot de passe :</label>
    <input class="col-7" type="password" class="form-control" id="confirm" name="confirm" placeholder="mot de passe">
  </div>
  <p><input type="submit" value="Valider" name="submit" /></p>

  <?php 
  $password_hashed = ($_POST['password']);
  $confirm = ($_POST['confirm']);
  $pass = password_hash($password_hashed, PASSWORD_BCRYPT);

  $query->$db('UPDATE forum_membres SET membre_mdp = "$pass" WHERE membre_id = :id'); 
  $query->execute(); ?>

<?php else :?>
//Redirectionvers page d'erreur
<?php endif; ?>