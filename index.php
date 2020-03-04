<?php
//Cette fonction doit être appelée avant tout code html
session_start();

//On donne ensuite un titre à la page, puis on appelle notre fichier debut.php
$titre = "Forum pour nos heros du quotidien";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<img class="main-image" src="css/images/FORUM.png" alt="Main image">
<?php
echo'<i>Vous êtes ici : </i><a href ="./index.php">Index du forum</a>';
?>
<h1 class="text-center mb-5">Bienvenue sur le forum</h1>
<p class="text-center mb-5">Ici vous pourrez échanger avec les autres membres, posez votre question dans l'un des thèmes proposés et la communauté se chargera de vous répondre.</p>

<?php
//Initialisation de deux variables
$totaldesmessages = 0;
$categorie = NULL;
?>
<?php

//Cette requête permet d'obtenir tout sur le forum
$query=$db->prepare('SELECT cat_id, cat_nom, 
forum_forum.forum_id, forum_forum.forum_name, forum_forum.forum_desc, forum_forum.forum_post, forum_topic, auth_view, forum_topic.topic_id, post_id, post_time, post_createur, membre_pseudo, 
membre_id 
FROM forum_categorie
LEFT JOIN forum_forum ON forum_categorie.cat_id = forum_forum.forum_cat_id
LEFT JOIN forum_post ON forum_post.post_id = forum_forum.forum_last_post_id
LEFT JOIN forum_topic ON forum_topic.topic_id = forum_post.topic_id
LEFT JOIN forum_membres ON forum_membres.membre_id = forum_post.post_createur
WHERE auth_view <= :lvl 
ORDER BY cat_ordre, forum_ordre DESC');
$query->bindValue(':lvl',$lvl,PDO::PARAM_INT);
$query->execute();
?>

<div class="card mb-5">

<table>

<?php 
//Début de la boucle
while($data = $query->fetch())
{
    //On affiche chaque catégorie
    if( $categorie != $data['cat_id'] )
    {
        //Si c'est une nouvelle catégorie on l'affiche
       
        $categorie = $data['cat_id'];
        ?>
       
        <th></th>
      
        <th class="titre border-bottom   border-secondary bg-light"><strong><?php echo stripslashes(htmlspecialchars($data['cat_nom'])); ?>
        </strong></th>             
        <th class="nombremessage border-bottom  border-secondary bg-light"><strong>Sujets</strong></th>       
        <th class="nombresujets border-bottom  border-secondary bg-light"><strong>Messages</strong></th>       
        <th class="derniermessage border-bottom   border-secondary bg-light"><strong>Dernier message</strong></th>  
        
      
        <?php
               
    }
    



    //Ici, on met le contenu de chaque catégorie
    ?>


<?php
    // Ce super echo de la mort affiche tous
    // les forums en détail : description, nombre de réponses etc...
   
    if (verif_auth($data['auth_view']))
    {
      
 
    echo'<tr><td></td>
    <td class="titre"><strong>
    <a href="./voirforum.php?f='.$data['forum_id'].'">
    '.stripslashes(htmlspecialchars($data['forum_name'])).'</a></strong>
    <br />'.nl2br(stripslashes(htmlspecialchars($data['forum_desc']))).'</td>
    <td class="nombresujets">'.$data['forum_topic'].'</td>
    <td class="nombremessages">'.$data['forum_post'].'</td>';
    
    // Deux cas possibles :
    // Soit il y a un nouveau message, soit le forum est vide
    if (!empty($data['forum_post']))
    {
         //Selection dernier message
	 $nombreDeMessagesParPage = 15;
      
         echo'<td class="derniermessage">
         '.date('H\hi \l\e d/M/Y',$data['post_time']).'<br />
         <a href="./voirprofil.php?m='.stripslashes(htmlspecialchars($data['membre_id'])).'&amp;action=consulter">'.$data['membre_pseudo'].'  </a>
         <a href="./voirtopic.php?t='.$data['topic_id'].'&amp;page='.'#p_'.$data['post_id'].'">
         </a></td></tr>';

     }
     else
     {
         echo'<td class="nombremessages">Pas de message</td></tr>';
     }
    }
     //Cette variable stock le nombre de messages, on la met à jour
     $totaldesmessages += $data['forum_post'];

     //On ferme notre boucle et nos balises
} //fin de la boucle
$query->CloseCursor();
echo '</table></div>';
?>



</div>
</div>
</body>
<?php include("includes/footer.php");?>
</html>
