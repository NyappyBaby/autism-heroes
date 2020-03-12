<?php
session_start();
$titre="Liste des membres";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");

//A partir d'ici, on va compter le nombre de members
//pour n'afficher que les 25 premiers
$query=$db->query('SELECT COUNT(*) AS nbr FROM forum_membres');
$data = $query->fetch();

$total = $data['nbr'] +1;
$query->CloseCursor();
$MembreParPage = 25;
$NombreDePages = ceil($total / $MembreParPage);
echo '<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> 
<a href="./memberlist.php">Liste des membres</a></p>';

//Nombre de pages

$page = (isset($_GET['page']))?intval($_GET['page']):1;

//On affiche les pages 1-2-3, etc.
echo 'Page : ';
for ($i = 1 ; $i <= $NombreDePages ; $i++)
{
    if ($i == $page) //On ne met pas de lien sur la page actuelle
    {
        echo $i;
    }
    else
    {
        echo '<p><a href="memberlist.php?page='.$i.'">'.$i.'</a></p>';
    }
}
echo '</p>';

$premier = ($page - 1) * $MembreParPage;

//Le titre de la page
echo '<h1>Liste des membres</h1><br /><br />';
?>
<?php
//Tri

$convert_order = array('membre_pseudo', 'membre_inscrit', 'membre_post', 'membre_derniere_visite'); 
$convert_tri = array('ASC', 'DESC');
//On récupère la valeur de s
if (isset ($_POST['s'])) $sort = $convert_order[$_POST['s']];
else $sort = $convert_order[0];
//On récupère la valeur de t
if (isset ($_POST['t'])) $tri = $convert_tri[$_POST['t']];
else $tri = $convert_tri[0];

?>

<?php
//Requête

$query = $db->prepare('SELECT membre_id, membre_pseudo, membre_inscrit, membre_derniere_visite
FROM forum_membres
LIMIT :premier, :membreparpage');
$query->bindValue(':premier',$premier,PDO::PARAM_INT);
$query->bindValue(':membreparpage',$MembreParPage, PDO::PARAM_INT);
$query->execute();

if ($query->rowCount() > 0)
{
?>
       <table>
       <tr>
       <th class="pseudo"><strong>Pseudo</strong></th>             
     
       <th class="inscrit"><strong>Inscrit depuis le</strong></th>
                          
       <th><strong>Connecté</strong></th>             

       </tr>
       <?php
       //On lance la boucle
       
       while ($data = $query->fetch())
       {
           echo '<tr><td>
           <a href="./voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">
           '.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a></td>
         
           <td>'.date('d/m/Y',$data['membre_inscrit']).'</td>';
           if ($id!=0) echo '<td>oui</td>'; 
           else echo '<td>non</td>';
           echo '</tr>';
       }
       $query->CloseCursor();
       ?>
       </table>
       <?php
}
else //S'il n'y a pas de message
{
    echo'<p>Ce forum ne contient aucun membre actuellement</p>';
}
?>
</div>
</body></html>
