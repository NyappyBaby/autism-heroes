<?php
//Cette fonction doit être appelée avant tout code html
session_start();

//On donne ensuite un titre à la page, puis on appelle notre fichier debut.php
$titre = "Informations";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");


?>

<?php


//Cette requête permet d'obtenir tout sur le forum
$query=$db->prepare('SELECT info_id, info_titre, info_content FROM informations');
$query->execute();
$data=$query->fetch();

echo '<a class="btn" href="informationsOk.php">Nouveau poste</a>';
?>
<div class="row jutify-content-center">
<?php while($data=$query->fetch()) : ?>
    
    <div class="card col-3">
    <h2 class="text-center"><?= $data['info_titre']?></h2>
    <div class="text-center"><?= $data['info_content']?></div>
    </div>
  
<?php endwhile; ?> 
</div>

