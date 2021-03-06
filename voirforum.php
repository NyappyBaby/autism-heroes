<?php
session_start();
$titre="Voir un forum";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");


date_default_timezone_set('Europe/Paris');
//On récupère la valeur de f
$forum = (int) $_GET['f'];

//A partir d'ici, on va compter le nombre de messages
//pour n'afficher que les 25 premiers
$query=$db->prepare('SELECT forum_name, forum_topic, auth_view, auth_topic FROM forum_forum WHERE forum_id = :forum');
$query->bindValue(':forum',$forum,PDO::PARAM_INT);
$query->execute();
$data=$query->fetch();

if (!verif_auth($data['auth_view']))
{
erreur(ERR_AUTH_VIEW);
}


$totalDesMessages = $data['forum_topic'] + 1;
$nombreDeMessagesParPage = 15;
$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);
?>
<?php
echo '<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> 
<a href="./voirforum.php?f='.$forum.'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>';

//Nombre de pages


$page = (isset($_GET['page']))?intval($_GET['page']):1;

//On affiche les pages 1-2-3, etc.
echo '<p>Page : ';
for ($i = 1 ; $i <= $nombreDePages ; $i++)
{
    if ($i == $page) //On ne met pas de lien sur la page actuelle
    {
    echo $i;
    }
    else
    {
    echo '
    <a href="voirforum.php?f='.$forum.'&amp;page='.$i.'">'.$i.'</a>';
    }
}
echo '</p>';


$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;

//Le titre du forum
echo '<h1>'.stripslashes(htmlspecialchars($data['forum_name'])).'</h1><br /><br />';


if (verif_auth($data['auth_topic']))
{
//Et le bouton pour poster
echo'<a class="btn btn-primary" href="./poster.php?action=nouveautopic&amp;f='.$forum.'">Nouveau Topic
</a>';
}
$query->CloseCursor();
?>

<?php
//On prend tout ce qu'on a sur les topics normaux du forum


$query=$db->prepare('SELECT forum_topic.topic_id, topic_post, topic_titre, topic_createur, topic_vu, topic_time,
Mb.membre_pseudo AS membre_pseudo_createur, forum_post.post_id, forum_post.post_createur, forum_post.post_time, Ma.membre_pseudo AS membre_pseudo_last_posteur FROM forum_topic
LEFT JOIN forum_membres Mb ON Mb.membre_id = forum_topic.topic_createur
LEFT JOIN forum_post ON forum_topic.topic_id = forum_post.topic_id
LEFT JOIN forum_membres Ma ON Ma.membre_id = forum_post.post_createur 
WHERE  forum_topic.forum_id = :forum 
ORDER BY post_time DESC
LIMIT :premier ,:nombre');

$query->bindValue(':forum',$forum,PDO::PARAM_INT);
$query->bindValue(':premier',(int) $premierMessageAafficher,PDO::PARAM_INT);
$query->bindValue(':nombre',(int) $nombreDeMessagesParPage,PDO::PARAM_INT);
$query->execute();
echo'<div class="mt-5">';
if ($query->rowCount()>0)
{
?>
        <table>
        <tr>
        <th></th>
        <th class="titre bg-light border-bottom border-secondary"><strong>Titre</strong></th>             
        <th class="reponses bg-light border-bottom border-secondary"><strong>Réponses </strong></th>    
        <th class="nombrevu bg-light border-bottom border-secondary"><strong>Vus</strong></th>
        <th class="auteur bg-light border-bottom border-secondary"><strong>Auteur</strong></th>     
        <th class="derniermessage bg-light border-bottom border-secondary"><strong>Dernier message  </strong></th>
        </tr>
        <?php
        //On lance la boucle
        
        if ($data = $query->fetch())
        {
                //Ah bah tiens... re vla l'echo de fou
                echo'
                <div class="row justify-content-center"><tr><td></td>
                
                <td class="titre mb-2">
                <strong><img class="icone" src="css/images/dossier.webp"><a class="decoration-none" href="./voirtopic.php?t='.$data['topic_id'].'"                 
                title="Topic commencé à
                '.date('H\hi \l\e d M,y',$data['topic_time']).'">
                '.stripslashes(htmlspecialchars($data['topic_titre'])).'</a></strong></td>

                <td class="reponses ml-2 text-center mb-2">'.$data['topic_post'].'</td>

                <td class="nombrevu ml-5 mb-2">'.$data['topic_vu'].'</td>
            

                <td><a href="./voirprofil.php?m='.$data['topic_createur'].'
                &amp;action=consulter">
                '.stripslashes(htmlspecialchars($data['membre_pseudo_createur'])).'</a></td>';


               	//Selection dernier message
		$nombreDeMessagesParPage = 15;
		
		

                echo '<td class="derniermessage mb-2">
                A <a href="./voirtopic.php?t='.$data['topic_id'].'&amp;page='.$page.'#p_'.$data['post_id'].'">'.date('H\hi \l\e d M y',$data['post_time']).'<br>'.'</a><a href="./voirprofil.php?m='.$data['post_createur'].'&amp;action=consulter">
                '.stripslashes(htmlspecialchars($data['membre_pseudo_last_posteur'])).'</a>'
                .'</td></tr>'.'</td></tr></div>';
                
        }

       
        ?>
        </table>
        
        <?php
}
else //S'il n'y a pas de message
{
        echo'<p>Ce forum ne contient aucun sujet actuellement</p>';
}
$query->CloseCursor();

?>
</div>
</div>
<?php include('includes/footer.php') ?>
</body></html>

