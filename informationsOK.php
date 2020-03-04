<?php
session_start();
$titre="InformationsOK";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");


// Si le membre n'est pas connecté, il est arrivé ici par erreur
if ($id==0) erreur(ERR_IS_CO);
?>

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
	<h1 class="text-center my-4">Card</h1>
	<form  action="informationsOk.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
    <label class="col-4" for="inputAddress">* Titre :</label>
    <input class="col-7" type="text" class="form-control" id="pseudo" name="titre" placeholder="ex1225">
  </div>
  <div class="form-group">
    <label class="col-4" for="inputcontent">* Text :</label>
    <input class="col-7" type="text" class="form-control" id="pseudo" name="content" placeholder="ex1225">
  </div>
  <p><input type="submit" value="Valider" name="submit" /></p></form>
<?php

    $title = $_POST['titre'];
    $content = $_POST['content'];
    
  

    if (empty($title) || empty($content))
    {
        echo'<p>Votre message ou votre titre est vide';
    }
    else //Si jamais le message n'est pas vide
    {
?>
<?php
        //On entre la card dans la base de donnée
      
        $query=$db->prepare('INSERT INTO informations
        (info_titre, info_content)
        VALUES(:titre, :content)');
        $query->bindValue(':titre', $title, PDO::PARAM_STR);
        $query->bindValue(':content', $content, PDO::PARAM_STR);
        $query->execute();
       
    


        //Et un petit message
        echo'<p>Votre message a bien été ajouté!<br /><br />Cliquez <a href="./index.php">ici</a> pour revenir à l index du forum<br />
        Cliquez <a href="./informations.php">ici</a> pour le voir</p>';
    }
 

?>