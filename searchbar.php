<?php
session_start();
$titre = "Pass oublié";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");


if (isset($_GET["s"]) && $_GET["s"] === "Rechercher") {
  $_GET["terme"] = htmlspecialchars($_GET["terme"]);
  $terme = $_GET['terme'];
  $terme = trim($terme);
  $terme = strip_tags($terme);
}

if (isset($terme)) {
  $terme = strtolower($terme);
  $select_terme = $db->prepare("SELECT informations.info_titre, informations.info_content, news.news_titre, news.news_content  FROM informations, news WHERE informations.info_titre LIKE ? OR informations.info_content LIKE ? OR news.news_titre LIKE ? OR news.news_content LIKE ?");
  $select_terme->execute(array("%" . $terme . "%", "%" . $terme . "%", "%" . $terme . "%", "%" . $terme . "%"));
} else {
  $message = "Vous devez entrer votre requete dans la barre de recherche";
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Les résultats de recherche</title>
</head>

<body>
  <?php
  while ($terme_trouve = $select_terme->fetch()) {
    $contenu_informations = substr($terme_trouve['info_content'], 0, 300);
    $contenu_news = substr($terme_trouve['news_content'], 0, 300);
    echo "<div><h2>" . $terme_trouve['info_titre'] . "</h2><p> " . $contenu_informations . "</p>";
    echo "<div><h2>" . $terme_trouve['news_titre'] . "</h2><p> " . $contenu_news . "</p>";
  }
  $select_terme->closeCursor();
  ?>
</body>

</html>