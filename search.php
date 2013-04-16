<!DOCTYPE html>
<html>
	<head>
		<meta charset="iso-8859-1">
		<link rel="stylesheet" type="text/css" href="styles/style.css" media="all" />
	</head>
<?php

include("include/config.php");
include("include/amazonAPI.php");
 
//recherche des résultats dans la base de données
$result =   mysql_query('SELECT livre.image,nbPages,titre,nom 
							FROM livre,auteur,ecritpar
							WHERE auteur.id=ecritpar.idAuteur 
								AND livre.id=ecritpar.idLivre 
								AND titre LIKE \'' . safe( $_GET['q'] ) . '%\' LIMIT 0,20' );

 
// affichage d'un message "pas de résultats"
if( mysql_num_rows( $result ) == 0 )
{
?>
    <h3 style="text-align:center; margin:10px 0;">Pas de r&eacute;sultats pour cette recherche</h3>
	<p> Le livre que vous recherchez n'est pas encore répértorié dans notre base...
	<a href="javascript:ajout_livre_ouvrir();" id="lien_ajouter_livre">Ajouter un livre</a></p>
<?php
}
else
{
    // parcours et affichage des résultats
    while(@$post = mysql_fetch_object($result))
    {/*
		$obj = new AmazonProductAPI();
		
		try
		{
		$keyword = strtr($post->titre,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
		$result = $obj->getItemByKeyword($keyword);
			
			//print($result);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		
		//print_r($result);*/
	?>
	<p><table><tr><td>
	<?php
		
		//echo "<img src=\"" . $result->Items->Item->MediumImage->URL . "\" />";
		echo "<img src=\"".$post->image."\" height=\"180\"/>";
	?>
	</td><td valign="top">
	<?php
	/*	
		echo "<br>Titre : {$result->Items->Item->ItemAttributes->Title}<br>";
		echo "<br>Lien : <a href=\"{$result->Items->Item->DetailPageURL}\">Voir sur Amazon</a><br>";
		echo "<br><br>Auteur : {$result->Items->Item->ItemAttributes->Author}<br>";
		echo "<br><br>Prix : {$result->Items->Item->ItemAttributes->ListPrice->FormattedPrice} / {$result->Items->Item->OfferSummary->LowestNewPrice->FormattedPrice}<br>";
		echo "<br><br>Pages : {$result->Items->Item->ItemAttributes->NumberOfPages}<br>";
		echo "Date de publication : {$result->Items->Item->ItemAttributes->PublicationDate}<br>";
		echo "Editeur : {$result->Items->Item->ItemAttributes->Publisher}<br>";
		echo "Classement des meilleures ventes Amazon : {$result->Items->Item->SalesRank} ventes<br><br><br><br><br>";
		echo "Résumé : {$result->Items->Item->EditorialReviews->EditorialReview->Content}<br><br>";
		echo "ASIN : {$result->Items->Item->ASIN}<br>";
		echo "<br><img src=\"" . $result->Items->Item->SmallImage->URL . "\" />";
		echo "<br><img src=\"" . $result->Items->Item->MediumImage->URL . "\" />";
		echo "<br><img src=\"" . $result->Items->Item->LargeImage->URL . "\" /><br>";
	*/	
    ?>
        <p>
            <h3><?php print($post->titre); ?></h3>
            <?php  /*
			
			echo "<br>Auteur : {$result->Items->Item->ItemAttributes->Author}<br>";
			echo "Pages : {$result->Items->Item->ItemAttributes->NumberOfPages}<br>";
			echo "Date de publication : {$result->Items->Item->ItemAttributes->PublicationDate}<br>";
			echo "Editeur : {$result->Items->Item->ItemAttributes->Publisher}<br>";
			//echo "Résumé : {$result->Items->Item->EditorialReviews->EditorialReview->Content}<br><br>";
			
			*/
			print("<i>".$post->nom."</i><br />");
			print("Nombre de pages : ".$post->nbPages."<br />");
			
			print("Genre : ");
			
			print("<br />");
			
			?>
			
			<br /><br />
			<a href="javascript:ajout_fiche_ouvrir();" id="lien_ajouter_fiche">Ajouter à mes fiches lectures</a>
            <!--<p class="url"><?php echo $post->guid; ?></p>-->
        </p>
	</td></tr></table></p>
	
    <?php
	$titre = $post->titre;
			$genre = mysql_query('SELECT libelle FROM livre,livregenre,genre WHERE livre.id=livregenre.idLivre AND genre.id=livregenre.idGenre AND livre.titre = \''.$titre.'\'');
			while(mysql_fetch_object($genre))
			{
				print($genre->libelle." ");
			}			
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