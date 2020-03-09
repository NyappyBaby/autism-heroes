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


?>
<?php


if(verif_auth(ADMIN))
{
echo '<a class="btn btn-primary" href="newsok.php">Nouveau poste</a>';
}else{

}


//Cette requête permet d'obtenir tout sur le forum
$query=$db->prepare('SELECT news_id, news_titre, news_content, news_img FROM news');
$query->execute();
$data=$query->fetch();


?>



<div class="row justify-content-center mt-5">
<?php while($data=$query->fetch()) : ?>
    
    <div class="card col-3 mx-5 mt-5 "><a class="decoration-none" href="./affichagenews.php?id=<?= $data['news_id'] ?>">
        <h2 class="text-center decoration-none my-3"><?= stripslashes(htmlspecialchars($data['news_titre']))?></h2>
        <img class="img-card" src="<?=$data['news_img']?>"/>
        <div class="text-center decoration-none mb-5"><?= stripslashes(htmlspecialchars(substr($data['news_content'], 0, 250))) ?></div></a>
    </div> 
  
<?php endwhile;  ?> 
</div>

<?php include("includes/footer.php");?>

