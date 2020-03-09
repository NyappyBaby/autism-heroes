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


$id = $_GET['id'];
//Cette requête permet d'obtenir tout sur le forum
$query=$db->prepare('SELECT info_id, info_titre, info_content, info_image FROM informations WHERE info_id = :id');
$query->bindValue(':id', $id, PDO::PARAM_INT);
$query->execute();
$data=$query->fetch();


?>
<?php if($data['info_id'] = $id) :?>

 
    <h1 class="text-center my-5"><?= stripslashes(htmlspecialchars($data['info_titre']))?></h1>
    <div class="row justify-content-center">
    <img class="col-4 img-card" src="<?=$data['info_image']?>"/>
    <div class="col-6"><?= htmlspecialchars($data['info_content']) ?>
    </div>
    </div>
    </a>


<?php endif; ?>





  
