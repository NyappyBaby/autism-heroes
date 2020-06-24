<?php
//Cette fonction doit être appelée avant tout code html
session_start();

//On donne ensuite un titre à la page, puis on appelle notre fichier debut.php
$titre = "Informations";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");


?>
<div class="row">
    <div class="col-12">
        <img  class="imgnews" src="./css/images/informations.png" alt="news">
    </div>
</div>
<h1 class="text-center main-titre">Retrouvez les différents articles ci-dessous</h1>
<?php


if(verif_auth(ADMIN))
{
echo '<a class="btn btn-primary" href="informationsOk.php">Nouveau poste</a>';
}else{

}


//Cette requête permet d'obtenir tout sur le forum
$query=$db->prepare('SELECT info_id, info_titre, info_content, info_image FROM informations');
$query->execute();
$data=$query->fetch();


?>



<div class="row justify-content-center mt-5">
<?php while($data=$query->fetch()) : ?>
    
    <div class="card  col-6 col-lg-3 mx-5 mr-lg-1 mt-5 "><a class="decoration-none" href="./articles.php?id=<?= $data['info_id'] ?>">
        <h2 class="text-center decoration-none my-3"><?= stripslashes(htmlspecialchars($data['info_titre']))?></h2>
        <img class="img-card" src="./css/images/avatars/<?=$data['info_image']?>"/>
        <div class="text-center decoration-none mb-5"><?= stripslashes(htmlspecialchars(substr($data['info_content'], 0, 250))) ?></div></a>
    </div> 
  
<?php endwhile;  ?> 
</div>

<?php include("includes/footer.php");?>

