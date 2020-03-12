<?php
function code($texte)
{
//Smileys
$texte = str_replace(':D ', '<img src="./css/images/smileys/heureux.png" title="heureux" alt="heureux" />', $texte);
$texte = str_replace(':lol: ', '<img src="./css/images/smileys/rire.gif" title="lol" alt="lol" />', $texte);
$texte = str_replace(':triste:', '<img src="./css/images/smileys/pleure.png" title="triste" alt="triste" />', $texte);
$texte = str_replace(':frime:', '<img src="./css/images/smileys/soleil.png" title="cool" alt="cool" />', $texte);
$texte = str_replace('XD', '<img src="./css/images/smileys/hihi.png" title="rire" alt="rire" />', $texte);
$texte = str_replace(':s', '<img src="./css/images/smileys/blink.gif" title="confus" alt="confus" />', $texte);
$texte = str_replace(':O', '<img src="./css/images/smileys/waw.png" title="choc" alt="choc" />', $texte);
$texte = str_replace(':angry:', '<img src="./css/images/smileys/angry.gif" title="angry" alt="angry" />', $texte);
$texte = str_replace(':siffle:', '<img src="./css/images/smileys/siffle.png" title="siffle" alt="siffle" />', $texte);
$texte = str_replace(':langue:', '<img src="./css/images/smileys/langue.png" title="langue" alt="langue" />', $texte);
$texte = str_replace(':clin:', '<img src="./css/images/smileys/clin.png" title="clin" alt="clin" />', $texte);
$texte = str_replace(':ange:', '<img src="./css/images/smileys/ange.png" title="ange" alt="ange" />', $texte);
$texte = str_replace(':diable:', '<img src="./css/images/smileys/diable.png" title="diable" alt="diable" />', $texte);
$texte = str_replace(':huh:', '<img src="./css/images/smileys/huh.png" title="huh" alt="huh" />', $texte);
$texte = str_replace(':magicien:', '<img src="./css/images/smileys/magicien.png" title="magicien" alt="magicien" />', $texte);
$texte = str_replace(':mechant:', '<img src="./css/images/smileys/mechant.png" title="mechant" alt="mechant" />', $texte);
$texte = str_replace(':ninja:', '<img src="./css/images/smileys/ninja.png" title="ninja" alt="ninja" />', $texte);
$texte = str_replace(':pinch:', '<img src="./css/images/smileys/pinch.png" title="pinch" alt="pinch" />', $texte);
$texte = str_replace(':pirate:', '<img src="./css/images/smileys/pirate.png" title="pirate" alt="pirate" />', $texte);
$texte = str_replace(':rouge:', '<img src="./css/images/smileys/rouge.png" title="rouge" alt="rouge" />', $texte);
$texte = str_replace(':unsure:', '<img src="./css/images/smileys/unsure.gif" title="unsure" alt="unsure" />', $texte);
$texte = str_replace(':zorro:', '<img src="./css/images/smileys/zorro.png" title="zorro" alt="zorro" />', $texte);
$texte = preg_replace('`\[quote\](.+)\[/quote\]`isU', '<div id="quote">$1</div>', $texte);






//Mise en forme du texte
//gras
$texte = preg_replace('`\[g\](.+)\[/g\]`isU', '<strong>$1</strong>', $texte); 
//italique
$texte = preg_replace('`\[i\](.+)\[/i\]`isU', '<em>$1</em>', $texte);
//souligné
$texte = preg_replace('`\[s\](.+)\[/s\]`isU', '<u>$1</u>', $texte);
//lien
$texte = preg_replace('#http://[a-z0-9._/-]+#i', '<a href="$0">$0</a>', $texte);
//etc., etc.

//On retourne la variable texte
return $texte;
}
?>
