<?php
session_start();
$titre="Messages Privés";
$balises = true;
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/bbcode.php");
include("includes/menu.php");


date_default_timezone_set('Europe/Paris');
$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';

?>
<?php
switch($action) //On switch sur $action
{
case "consulter": //Si on veut lire un message
 
 echo'<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> <a href="./messagesprives.php">Messagerie privée</a> --> Consulter un message</p>';
 $id_mess = (int) $_GET['id']; //On récupère la valeur de l'id
 echo '<h1>Consulter un message</h1><br /><br />';

 //La requête nous permet d'obtenir les infos sur ce message :
 $query = $db->prepare('SELECT  mp_expediteur, mp_receveur, mp_titre,               
 mp_time, mp_text, mp_lu, membre_id, membre_pseudo, membre_avatar, membre_inscrit, membre_signature
 FROM forum_mp
 LEFT JOIN forum_membres ON membre_id = mp_expediteur
 WHERE mp_id = :id');
 $query->bindValue(':id',$id_mess,PDO::PARAM_INT);
 $query->execute();
 $data=$query->fetch();

 // Attention ! Seul le receveur du mp peut le lire !
 if ($id !== $data['mp_receveur']){
    echo $data['mp_text']; 
    echo $data['membre_pseudo'];
  } 

  
echo'
  <td>
  <a href="./messagesprives.php?action=repondre&amp;id='.$data['mp_expediteur'].'">Répondre</a></td>
  <td>';
break;
     
//D'autres cas viendront s'ajouter ici par la suite


 ?>   

 <div class="btn btn-primary"> Répondre </div>
 <table>     
    <tr>
    <th class="vt_auteur"><strong>Auteur</strong></th>             
    <th class="vt_mess"><strong>Message</strong></th>       
    </tr>
    <tr>
    <td>
    <?php echo'<strong>
    <a href="./voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">
    '.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a></strong></td>
    <td>Posté à '.date('H\hi \l\e d M Y',$data['mp_time']).'</td>';
    ?>
    </tr>
    <tr>
    <td>
    <?php
        
    //Ici des infos sur le membre qui a envoyé le mp
    echo'<p><img src="'.$data['membre_avatar'].'" alt="" />
    <br />Membre inscrit le '.date('d/m/Y',$data['membre_inscrit']).'
    </td><td>';
        
    echo code(nl2br(stripslashes(htmlspecialchars($data['mp_text'])))).'
    <hr />'.code(nl2br(stripslashes(htmlspecialchars($data['membre_signature'])))).'
    </td></tr></table>';

    if ($data['mp_lu'] == 0) //Si le message n'a jamais été lu
    {
        $query->CloseCursor();
        $query=$db->prepare('UPDATE forum_mp 
        SET mp_lu = mp_lu + 1
        WHERE mp_id= :id');
        $query->bindValue(':id',$id_mess, PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();
    }
        
break; //La fin !
?>
<?php
case "nouveau": //Nouveau mp
       
   echo'<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> <a href="./messagesprives.php">Messagerie privée</a> --> Ecrire un message</p>';
   echo '<h1>Nouveau message privé</h1><br /><br />';
   ?>
   <form method="post" action="postok.php?action=nouveaump" name="formulaire">
       <div class="ml-4">
   <div class="row">
   <label for="to">Envoyer à : </label>
   <input type="text" size="30" id="to" name="to" />
   </div>
   <div class="row">
   <label for="titre">Titre : </label>
   <input type="text" size="80" id="titre" name="titre" />
    </div>
<div class="row">
   <input type="button" id="gras" name="gras" value="Gras" onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
   <input type="button" id="italic" name="italic" value="Italic" onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
   <input type="button" id="souligné" name="souligné" value="Souligné" onClick="javascript:bbcode('[s]', '[/s]');return(false)" />
   <input type="button" id="lien" name="lien" value="Lien" onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
</div>
<div >
   <img src="./css/images/smileys/heureux.png" title="heureux" alt="heureux" onClick="javascript:smilies(' :D ');return(false)" />
    <img src="./css/images/smileys/rire.gif" title="lol" alt="lol" onClick="javascript:smilies(' :lol: ');return(false)" />
    <img src="./css/images/smileys/pleure.png" title="triste" alt="triste" onClick="javascript:smilies(' :triste: ');return(false)" />
    <img src="./css/images/smileys/soleil.png" title="cool" alt="cool" onClick="javascript:smilies(' :frime: ');return(false)" />
    <img src="./css/images/smileys/hihi.png" title="rire" alt="rire" onClick="javascript:smilies(' XD ');return(false)" />
    <img src="./css/images/smileys/blink.gif" title="confus" alt="confus" onClick="javascript:smilies(' :s ');return(false)" />
    <img src="./css/images/smileys/waw.png" title="choc" alt="choc" onClick="javascript:smilies(' :o ');return(false)" />
    <img src="./css/images/smileys/angry.gif" title="angry" alt="angry" onClick="javascript:smilies(' :angry: ');return(false)" />
    <img src="./css/images/smileys/siffle.png" title="siffle" alt="siffle" onClick="javascript:smilies(' :siffle: ');return(false)" />
    <img src="./css/images/smileys/langue.png" title="langue" alt="langue" onClick="javascript:smilies(' :langue: ');return(false)" />
    <img src="./css/images/smileys/clin.png" title="clin" alt="clin" onClick="javascript:smilies(' :clin: ');return(false)" />
    <img src="./css/images/smileys/ange.png" title="ange" alt="ange" onClick="javascript:smilies(' :ange: ');return(false)" />
    <img src="./css/images/smileys/diable.png" title="diable" alt="diable" onClick="javascript:smilies(' :diable: ');return(false)" />
    <img src="./css/images/smileys/huh.png" title="huh" alt="huh" onClick="javascript:smilies(' :huh: ');return(false)" />
    <img src="./css/images/smileys/magicien.png" title="magicien" alt="magicien" onClick="javascript:smilies(' :magicien: ');return(false)" />
    <img src="./css/images/smileys/mechant.png" title="mechant" alt="mechant" onClick="javascript:smilies(' :mechant: ');return(false)" />
    <img src="./css/images/smileys/ninja.png" title="ninja" alt="ninja" onClick="javascript:smilies(' :ninja: ');return(false)" />
    <img src="./css/images/smileys/pinch.png" title="pinch" alt="pinch" onClick="javascript:smilies(' :pinch: ');return(false)" />
    <img src="./css/images/smileys/pirate.png" title="pirate" alt="pirate" onClick="javascript:smilies(' :pirate: ');return(false)" />
    <img src="./css/images/smileys/rouge.png" title="rouge" alt="rouge" onClick="javascript:smilies(' :rouge: ');return(false)" />
    <img src="./css/images/smileys/unsure.gif" title="unsure" alt="unsure" onClick="javascript:smilies(' :unsure: ');return(false)" />
    <img src="./css/images/smileys/zorro.png" title="zorro" alt="zorro" onClick="javascript:smilies(' :zorro: ');return(false)" /> 
    </div>
    <div class="row">
    <textarea cols="80" rows="8" id="message" name="message"></textarea>
</div>
<div class="row">
   <input type="submit" name="submit" value="Envoyer" />
   <input type="reset" name="Effacer" value="Effacer" />
</div>
</div>
   </form>

<?php   
break;

case "repondre": //On veut répondre
   echo'<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> <a href="./messagesprives.php">Messagerie privée</a> --> Ecrire un message</p>';
   echo '<h1>Répondre à un message privé</h1><br /><br />';

   $dest = (int) $_GET['id'];
   ?>
   <form method="post" action="postok.php?action=repondremp&amp;dest=<?php echo $dest ?>" name="formulaire">
   <p>
   <label for="titre">Titre : </label><input type="text" size="80" id="titre" name="titre" />
   <br /><br />
   <input type="button" id="gras" name="gras" value="Gras" onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
   <input type="button" id="italic" name="italic" value="Italic" onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
   <input type="button" id="souligné" name="souligné" value="Souligné" onClick="javascript:bbcode('[s]', '[/s]');return(false)" />
   <input type="button" id="lien" name="lien" value="Lien" onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
   <br /><br />
   <img src="./css/images/smileys/heureux.png" title="heureux" alt="heureux" onClick="javascript:smilies(' :D ');return(false)" />
    <img src="./css/images/smileys/rire.gif" title="lol" alt="lol" onClick="javascript:smilies(' :lol: ');return(false)" />
    <img src="./css/images/smileys/pleure.png" title="triste" alt="triste" onClick="javascript:smilies(' :triste: ');return(false)" />
    <img src="./css/images/smileys/soleil.png" title="cool" alt="cool" onClick="javascript:smilies(' :frime: ');return(false)" />
    <img src="./css/images/smileys/hihi.png" title="rire" alt="rire" onClick="javascript:smilies(' XD ');return(false)" />
    <img src="./css/images/smileys/blink.gif" title="confus" alt="confus" onClick="javascript:smilies(' :s ');return(false)" />
    <img src="./css/images/smileys/waw.png" title="choc" alt="choc" onClick="javascript:smilies(' :o ');return(false)" />
    <img src="./css/images/smileys/angry.gif" title="angry" alt="angry" onClick="javascript:smilies(' :angry: ');return(false)" />
    <img src="./css/images/smileys/siffle.png" title="siffle" alt="siffle" onClick="javascript:smilies(' :siffle: ');return(false)" />
    <img src="./css/images/smileys/langue.png" title="langue" alt="langue" onClick="javascript:smilies(' :langue: ');return(false)" />
    <img src="./css/images/smileys/clin.png" title="clin" alt="clin" onClick="javascript:smilies(' :clin: ');return(false)" />
    <img src="./css/images/smileys/ange.png" title="ange" alt="ange" onClick="javascript:smilies(' :ange: ');return(false)" />
    <img src="./css/images/smileys/diable.png" title="diable" alt="diable" onClick="javascript:smilies(' :diable: ');return(false)" />
    <img src="./css/images/smileys/huh.png" title="huh" alt="huh" onClick="javascript:smilies(' :huh: ');return(false)" />
    <img src="./css/images/smileys/magicien.png" title="magicien" alt="magicien" onClick="javascript:smilies(' :magicien: ');return(false)" />
    <img src="./css/images/smileys/mechant.png" title="mechant" alt="mechant" onClick="javascript:smilies(' :mechant: ');return(false)" />
    <img src="./css/images/smileys/ninja.png" title="ninja" alt="ninja" onClick="javascript:smilies(' :ninja: ');return(false)" />
    <img src="./css/images/smileys/pinch.png" title="pinch" alt="pinch" onClick="javascript:smilies(' :pinch: ');return(false)" />
    <img src="./css/images/smileys/pirate.png" title="pirate" alt="pirate" onClick="javascript:smilies(' :pirate: ');return(false)" />
    <img src="./css/images/smileys/rouge.png" title="rouge" alt="rouge" onClick="javascript:smilies(' :rouge: ');return(false)" />
    <img src="./css/images/smileys/unsure.gif" title="unsure" alt="unsure" onClick="javascript:smilies(' :unsure: ');return(false)" />
    <img src="./css/images/smileys/zorro.png" title="zorro" alt="zorro" onClick="javascript:smilies(' :zorro: ');return(false)" /> 
   <br /><br />
   <textarea cols="80" rows="8" id="message" name="message"></textarea>
   <br />
   <input type="submit" name="submit" value="Envoyer" />
   <input type="reset" name="Effacer" value="Effacer"/>
   </p></form>
   <?php
break;

    case "supprimer":
       
    //On récupère la valeur de l'id
    $id_mess = (int) $_GET['id'];
    //Il faut vérifier que le membre est bien celui qui a reçu le message
    $query=$db->prepare('SELECT mp_receveur
    FROM forum_mp WHERE mp_id = :id');
    $query->bindValue(':id',$id_mess,PDO::PARAM_INT);
    $query->execute();
    $data = $query->fetch();
    //Sinon la sanction est terrible :p
    if ($id != $data['mp_receveur']);
    $query->CloseCursor(); 

    //2 cas pour cette partie : on est sûr de supprimer ou alors on ne l'est pas
    $sur = (int) $_GET['sur'];
    //Pas encore certain
    if ($sur == 0)
    {
    echo'<p>Etes-vous certain de vouloir supprimer ce message ?<br />
    <a href="./messagesprives.php?action=supprimer&amp;id='.$id_mess.'&amp;sur=1">
    Oui</a> - <a href="./messagesprives.php">Non</a></p>';
    }
    //Certain
    else
    {
        $query=$db->prepare('DELETE from forum_mp WHERE mp_id = :id');
        $query->bindValue(':id',$id_mess,PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor(); 
        echo'<p>Le message a bien été supprimé.<br />
        Cliquez <a href="./messagesprives.php">ici</a> pour revenir à la boite
        de messagerie.</p>';
    }

    break;

default: //Si jamais c'est aucun de ceux-là, c'est qu'il y a eu un problème :o


echo'<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> <a href="./messagesprives.php">Messagerie privée</a>';
echo '<h1>Messagerie Privée</h1><br /><br />';

$query=$db->prepare('SELECT mp_lu, mp_id, mp_expediteur, mp_titre, mp_time, membre_id, membre_pseudo
FROM forum_mp
LEFT JOIN forum_membres ON forum_mp.mp_expediteur = forum_membres.membre_id
WHERE mp_receveur = :id ORDER BY mp_id DESC');
$query->bindValue(':id',$id,PDO::PARAM_INT);
$query->execute();
echo'<p><a href="./messagesprives.php?action=nouveau">
<img src="css/images/nouveaump.png" alt="Nouveau" title="Nouveau message" class="icone" /> Nouveau message
</a></p>';
if ($query->rowCount()>0)
{
    ?>
    <table>
    <tr>
    <th></th>
    <th class="mp_titre"><strong>Titre</strong></th>
    <th class="mp_expediteur"><strong>Expéditeur</strong></th>
    <th class="mp_time"><strong>Date</strong></th>
    <th><strong>Action</strong></th>
    <th><strong>Action</strong></th>
    </tr>

    <?php
    //On boucle et on remplit le tableau
    while ($data = $query->fetch())
    {
        echo'<tr>';
        //Mp jamais lu, on affiche l'icone en question
        if($data['mp_lu'] == 0)
        {
        echo'<td ><img class="icone" src="css/images/courrier.png" alt="Non lu"  /></td>';
        }
        else //sinon une autre icone
        {
        echo'<td class="icone"><img src="css/images/nouveaump.png" alt="Déja lu"  /></td>';
        }
        echo'<td id="mp_titre">
        <a href="./messagesprives.php?action=consulter&amp;id='.$data['mp_id'].'">
        '.stripslashes(htmlspecialchars($data['mp_titre'])).'</a></td>
        <div>
        <td id="mp_expediteur">
        <a href="./voirprofil.php?action=consulter&amp;m='.$data['membre_id'].'">
        '.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a></td>
        </div>
        <td id="mp_time">'.date('H\hi \l\e d M Y',$data['mp_time']).'</td>
        <td>
        <a href="./messagesprives.php?action=repondre&amp;id='.$data['mp_expediteur'].'">Répondre</a></td>
        <td>
        <a href="./messagesprives.php?action=supprimer&amp;id='.$data['mp_id'].'&amp;sur=0">supprimer</a></td></tr>';
    } //Fin de la boucle
    $query->CloseCursor();
    echo '</table>';

} //Fin du if
else
{
    echo'<p>Vous n avez aucun message privé pour l instant, cliquez
    <a href="./index.php">ici</a> pour revenir à la page d index</p>';
}
} //Fin du switch
?>
</div>
</body>
</html>


