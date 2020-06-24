<?php
session_start();
$titre="Voir un sujet";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");
include("includes/bbcode.php");
 

date_default_timezone_set('Europe/Paris');
//On récupère la valeur de t
$topic = (int) $_GET['t'];


//A partir d'ici, on va compter le nombre de messages pour n'afficher que les 15 premiers
$query=$db->prepare('SELECT topic_titre, topic_post, forum_topic.forum_id,
forum_name, auth_view, auth_topic, auth_post 
FROM forum_topic 
LEFT JOIN forum_forum ON forum_topic.forum_id = forum_forum.forum_id 
WHERE topic_id = :topic');
$query->bindValue(':topic',$topic,PDO::PARAM_INT);
$query->execute();
$data=$query->fetch();


if (!verif_auth($data['auth_view']))
{
    erreur(ERR_AUTH_VIEW);
}



$forum=$data['forum_id']; 
$nombreDeMessagesParPage = 15;
$totalDesMessages = $data['topic_post'] + 1;
$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);

?>
<?php
echo '<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> 

<a  href="./voirforum.php?f='.$forum.'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
 --> <a  href="./voirtopic.php?t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>';
echo '<h1 class="text-center">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</h1><br /><br />';
?>

<?php
//Nombre de pages
$page = (isset($_GET['page']))?intval($_GET['page']):1;

//On affiche les pages 1-2-3 etc...
echo '<p>Page : ';
for ($i = 1 ; $i <= $nombreDePages ; $i++)
{
    if ($i == $page) //On affiche pas la page actuelle en lien
    {
    echo $i;
    }
    else
    {
    echo '<a href="voirtopic.php?t='.$topic.'&page='.$i.'">
    ' . $i . '</a> ';
    }
}
echo'</p>';
$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;


if (verif_auth($data['auth_post']))
{
//On affiche l'image répondre
echo'<a class="btn btn-primary ml-2 mb-2" href="./poster.php?action=repondre&amp;t='.$topic.'">Répondre
</a>';
}

if (verif_auth($data['auth_topic']))
{
//On affiche l'image nouveau topic
echo'<a class="btn btn-primary ml-1 mb-2" href="./poster.php?action=nouveautopic&amp;f='.$data['forum_id'].'">Nouveau topic
</a>';
}

echo'<div class="col-12 bglight">';
$query->CloseCursor(); 
//Enfin on commence la boucle !
?>
<?php
$query=$db->prepare('SELECT post_id ,post_createur , post_texte , post_time ,
membre_id, membre_pseudo, membre_inscrit, membre_avatar,membre_signature
FROM forum_post
LEFT JOIN forum_membres ON forum_membres.membre_id = forum_post.post_createur
WHERE topic_id =:topic
ORDER BY post_id
LIMIT :premier, :nombre');
$query->bindValue(':topic',$topic,PDO::PARAM_INT);
$query->bindValue(':premier',(int) $premierMessageAafficher,PDO::PARAM_INT);
$query->bindValue(':nombre',(int) $nombreDeMessagesParPage,PDO::PARAM_INT);
$query->execute();
 
//On vérifie que la requête a bien retourné des messages
if ($query->rowCount()<1)
{
        echo'<p>Il n y a aucun post sur ce topic, vérifiez l url et reessayez</p>';
}
else
{
        //Si tout roule on affiche notre tableau puis on remplit avec une boucle
        ?><table>
        <tr>
        <th class="vt_auteur"><strong>Auteurs</strong></th>             
        <th class="vt_mess"><strong>Messages</strong></th>       
        </tr>
        <?php
        while ($data = $query->fetch())
        {
?>
<?php
//On commence à afficher le pseudo du créateur du message :
         //On vérifie les droits du membre
         //(partie du code commentée plus tard)
         echo'<tr><td class="bgPost border-secondary mt-3 border-bottom">
         <a class="ml-2 decoration-none size" href="./voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">
         '.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a></td>';
           
         /* Si on est l'auteur du message, on affiche des liens pour
         Modérer celui-ci.
         Les modérateurs pourront aussi le faire, il faudra donc revenir sur
         ce code un peu plus tard ! */     
   
         if ($id == $data['post_createur'] || verif_auth(ADMIN) || verif_auth(MODO))
         {
         echo'<td class="border-bottom border-secondary bgPost" id=p_'.$data['post_id'].'>Posté à '.date('H\hi \l\e d M y',$data['post_time']).'
         <a class="btn btn-primary" href="./poster.php?p='.$data['post_id'].'&amp;action=delete">
         Supprimer</a>   
         <a class="btn btn-primary" href="./poster.php?p='.$data['post_id'].'&amp;action=edit">
         Editer</a></td></tr>';
         }
         else
         {
         echo'<td class="border-bottom border-secondary bgPost">
         Posté à '.date('H\hi \l\e d M y',$data['post_time']).'
         </td></tr>';
         }
       

         //Détails sur le membre qui a posté
         echo'<tr><td class="border-bottom border-secondary bgPost" >
         <img class="avatar ml-2"src="./css/images/avatars/'.$data['membre_avatar'].'" alt="" />
         <div class="row ml-2 mr-3 mb-3"> Membre inscrit le '.date('d/m/Y',$data['membre_inscrit']).'</div>'.'</td>';
               
         //Message
         echo'<td class="border-bottom border-secondary border-left">'.code(nl2br(stripslashes(htmlspecialchars($data['post_texte'])))).'
         <br /><hr  class="border-top border-secondary" /><span>'.code(nl2br(stripslashes(htmlspecialchars($data['membre_signature'])))).'</span></td></tr>'; 
        
         } //Fin de la boucle ! \o/
         $query->CloseCursor();
           ?>
       
</table>
<?php
   
       
        //On ajoute 1 au nombre de visites de ce topic
        $query=$db->prepare('UPDATE forum_topic
        SET topic_vu = topic_vu + 1 WHERE topic_id = :topic');
        $query->bindValue(':topic',$topic,PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();

} //Fin du if qui vérifiait si le topic contenait au moins un message
?> 


</div>          
</div>
</div>
<?php include('includes/footer.php') ?>
</body>
</html>
