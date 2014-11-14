<?php
session_start();  
if (!isset($_SESSION['login']) OR !isset($_SESSION['pass'])) 
{
	header("location: ./") ; 
}
else 
{
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<meta charset="iso-8859-1">
			<link rel="stylesheet" type="text/css" href="styles/style.css" media="all" />
		</head>
	<?php
		include("include/config.php");
		include("include/amazonAPI.php");
		
		//sécurisation contre injection de code js ou HTML
		$recherche = strip_tags($_GET['q']);
		// pour sécurisation contre injection code SQL
		$recherche = mysql_real_escape_string($recherche); 
		
		//recherche des résultats dans la base de données
		$result =   mysql_query('SELECT livre.id,livre.image,nbPages,titre,nom, editeur, datePublication
									FROM livre,auteur,ecritpar
									WHERE actif=1
										AND auteur.id=ecritpar.idAuteur 
										AND livre.id=ecritpar.idLivre 
										AND titre LIKE \'%' . $recherche . '%\' LIMIT 0,20' );


		// affichage d'un message "pas de résultats"
		if( mysql_num_rows( $result ) == 0 )
		{
	?>
	<h3 style="text-align:center; margin:10px 0;">Pas de r&eacute;sultats pour cette recherche</h3>
	<p align="center"> Le livre que vous recherchez n'est pas encore r&eacute;p&eacute;rtori&eacute; dans notre base...
	<a href="javascript:ajout_livre_ouvrir();" id="lien_ajouter_livre">Ajouter un livre</a></p>
	<?php
		}
		else
		{
			print("<ul class=\"resultRecherche\">");
			// parcours et affichage des résultats
			while(@$post = mysql_fetch_object($result))
			{
				print("<li class=\"itemRecherche\"><span class=\"couverture\">");
				print("<img src=\"".$post->image."\" height=\"70\"/>");
				print("</span>");
				print("<span class=\"details\">");
	?>
				<h3 class="titreResult">
					<a class="livres" href="ficheLivre.php?idLivre=<?php print($post->id);?>" class="resultat"><?php print($post->titre); ?></a>
				</h3>
				par <?php print("<i>".$post->nom."</i><br />"); ?>

	<?php  
				print("<br>Edité en ".$post->datePublication." par ".$post->editeur);
				print("</span>");
				print("</li>");
			}
			print("</ul>");
		}
}
?>