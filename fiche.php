<?php
	session_start();  
	if (!isset($_SESSION['login']) OR !isset($_SESSION['pass'])) 
		{
			header("location: ./") ; 
		}
	else 
		{
			include("include/config.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="ISO-8859-1" />
	<title>
		BookCase - Gestion de fiches lectures
	</title>
	
	<link rel="stylesheet" type="text/css" href="styles/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="styles/style.css" media="all" />
	<link rel="stylesheet" type="text/css" href="styles/jquery.dataTables.css" >
	
	<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/bookCase.js"></script>
	<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
</head>

<body>



	<header>
		<div style="float:right;" class="login">
			<img src="<?php print($_SESSION['image']);?>" style="float:left; margin-left: 10px;" width="50">
			<form name="logout" action="logout.php" method="POST">
				<p><img src="styles/img/puce5.png" width="10"> Bonjour <strong><a href="<?php print("profile.php?user=".$_SESSION['login'])?>"><?php print($_SESSION['nom']." ".$_SESSION['prenom']); ?></a></strong>
				<p class="submit-p"><input class="login-submit" type="submit" value="Me déconnecter"></p>
			</form>
		</div>
		<h1>
			<img alt="" src="images/logo.png"/>
			<span><font color="#5F84BC" size="7">B</font>ook<font color="#5F84BC" size="7">C</font>ase</span> <font size="4"><?php include("include/version");?></font>
		</h1>
		<p class="sous-titre">
			<strong>Gestion de fiches lectures</strong>
		</p>
		
		
	</header><!-- #entete -->

	<div id="contenu">
		<aside id="menu"><h2>Menu</h2>
				<a href="./"><img src="styles/img/puce1.png" class="puce"> Accueil</a>
				<a href="livres.php"><img src="styles/img/puce2.png" class="puce"> Livres</a>
				<a href="fiche.php" class="current"><img src="styles/img/puce3.png" class="puce"> Mes fiches lecture</a>
				<a href="<?php print("profile.php?user=".$_SESSION['login'])?>"><img src="styles/img/puce4.png" class="puce"> Mon profil</a>
				<?php
					if($_SESSION['role']=='admin')
					{
						print("<a href=\"admin.php\"><img src=\"styles/img/puce5.png\" class=\"puce\"> Administration</a>");
					}
				?>
			
		</aside><!-- #navigation -->
		<article>		
			<h2><img src="styles/img/icone_fiche.png" width="25"> Mes fiches lecture</h2>
			
			<?php
				if (isset($_GET['idUser'], $_GET['idLivre']))
				{
					$reqSuppFiche = mysql_query("DELETE FROM lecture WHERE idUser={$_GET['idUser']} AND idLivre={$_GET['idLivre']}");
					if ($reqSuppFiche)
					{
						print("Fiche Supprimée");
					}
					else
					{
						print("Problème de suppression");
					}
				}
			?>
			<div class="conteneur">
			<table class="allBooks" id="fiches-lecture">
				<thead><tr>
					<th>Titre</th>
					<th>Auteur</th>
					<th>Genre</th>
					<th>Date lecture</th>
					<th>Appréciation</th>
					<th>Action</th>
				</tr></thead>
				<tbody>
			
			<?php
				$requeteFicheConsult = mysql_query("SELECT * 
													FROM livre,lecture
													WHERE livre.id=lecture.idLivre
														AND idUser=".$_SESSION['id']);
				$i=0;
				while($fiche = mysql_fetch_object($requeteFicheConsult))
				{
					$i++;
					if($i%2 == 0)
					{
						print("<tr class=\"bg\">");
					}
					else
					{
						print("<tr>");
					}
					$date = explode('-', $fiche->dateLecture);
					$date = $date[2]."/".$date[1]."/".$date[0];
					
					$reqAuteurs = mysql_query("SELECT nom FROM livre,auteur,ecritpar
												WHERE livre.id=ecritpar.idLivre	
												AND auteur.id=ecritpar.idAuteur
												AND livre.id='{$fiche->id}'");
					
					print("<td>{$fiche->titre}</td><td>");
					while($auteur = mysql_fetch_object($reqAuteurs))
					{
						print($auteur->nom." ");
					}
					print("</td><td>");
					
					$reqGenres = mysql_query("SELECT libelle FROM livre,livregenre,genre
												WHERE livre.id=livregenre.idLivre
												AND livregenre.idGenre=genre.id
												AND livre.id='{$fiche->id}'");
					while($genre=mysql_fetch_object($reqGenres))
					{
						print($genre->libelle." ");
					}
					print("</td>
							<td>{$date}</td>
							<td>{$fiche->appreciation}</td>
							<td align=\"center\"><a href=\"javascript:supprime_fiche_livre('{$fiche->idLivre}', '{$fiche->idUser}','".addslashes($fiche->titre)."', '{$fiche->dateLecture}')\" title=\"Supprimer '{$fiche->titre}' de mes fiches\"><img src=\"images/suppr.jpg\"</a></td>
							</tr>");
				}
			?>
				</tbody>
			</table>
			</div>
		</article><!-- #contenu -->
	</div>
	<footer>
		<?php include("include/footer"); ?>
	</footer>



</body>
</html>

<?php
	}
?>