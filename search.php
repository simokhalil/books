<!DOCTYPE html>
<html>
	<head>
		<meta charset="iso-8859-1">
		<link rel="stylesheet" type="text/css" href="styles/style.css" media="all" />
	</head>
<?php

include("include/config.php");
 
//recherche des résultats dans la base de données
$result =   mysql_query('SELECT titre,nom FROM livre,auteur WHERE auteur.id=livre.idAuteur AND titre LIKE \'' . safe( $_GET['q'] ) . '%\' LIMIT 0,20' );
 
// affichage d'un message "pas de résultats"
if( mysql_num_rows( $result ) == 0 )
{
?>
    <h3 style="text-align:center; margin:10px 0;">Pas de r&eacute;sultats pour cette recherche</h3>
	<a href="#">Ajouter un livre</a>
<?php
}
else
{
    // parcours et affichage des résultats
    while( $post = mysql_fetch_object( $result ))
    {
    ?>
        <p>
            <h3><?php print($post->titre); ?></h3>
            <?php print($post->nom); ?><br>
			<a href="#">Ajouter à mes lectures</a>
            <!--<p class="url"><?php echo $post->guid; ?></p>-->
        </p>
    <?php
    }
}
 
/*****
fonctions
*****/
function safe($var)
{
	$var = mysql_real_escape_string($var);
	$var = addcslashes($var, '%_');
	$var = trim($var);
	$var = htmlspecialchars($var);
	return $var;
}
?>