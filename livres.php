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
	<link rel="stylesheet" type="text/css" href="styles/style.css" media="all" />	
	<script src="js/jquery-1.9.1.js"></script>
	
	<script src="js/bookCase.js"></script>
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
			<img alt="" src="images/logo.gif" width="50"/>
			<span><font color="#5F84BC" size="7">B</font>ook<font color="#5F84BC" size="7">C</font>ase</span> <font size="4"><?php include("include/version");?></font>
		</h1>
		<p class="sous-titre">
			<strong>Gestion de fiches lectures</strong>
		</p>
	</header><!-- #entete -->
	
	<div id="contenu">
		<aside id="menu"><h2>Menu</h2>
			<a href="./"><img src="styles/img/puce1.png" width="10"> Accueil</a>
				<a href="livres.php" class="current"><img src="styles/img/puce2.png" width="10"> Livres</a>
				<a href="fiche.php"><img src="styles/img/puce3.png" width="10"> Mes fiches lecture</a>
				<a href="<?php print("profile.php?user=".$_SESSION['login'])?>"><img src="styles/img/puce4.png" width="10"> Mon profil</a>
				<?php
					if($_SESSION['role']=='admin')
					{
						print("<a href=\"admin.php\"><img src=\"styles/img/puce5.png\" width=\"10\"> Administration</a>");
					}
				?>
		</aside>
		
		<article>
			<h2><img src="styles/img/icone_livre.png" width="25"> Livres</h2>
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			<form action="search.php" method="get">
				<label for="q">Rechercher un livre</label>
				<input type="text" name="q" id="q" class="search-input" autocomplete="off"/>
				<a href="javascript:liste_livres();" style="margin-right: 30px; font-size: .8em; margin-top:18px;">tout afficher/masquer</a>		
			</form> 
			<br>
			<!-- La div qui contiendra les résultats de recherche -->
			<div id="results"></div>
		
			<div id="ajoutLivre">
				<a id="notif-close" href="javascript:ajout_livre_fermer();" class="notif-close">Close</a>
				<table style="width:100%"><h2>Ajouter un livre</h2>
					<form action="addBook" method="POST">
						<tr>
							<td><label>Titre : </label><div id="loader2" style="display:none;float:right;width:16px; height:16px;background:#F2EDE7 url(images/loading1.gif) center bottom;margin-bottom:-15px;padding:0;"></div></td>
							<td><input type="text" id="titre" name="titre"></td>
						</tr>
						<tr>
							<td colspan="2"><div id="propositions" style="background:#F9F9F9; padding:0; margin:0;"></div></td>
						</tr>
						<tr  id="auteur_1">
							<td><label>Auteur : </label></td>
							<td><input type="text" name="auteur_1">
							<a href="javascript:auteurAct(2, 'ajout')" id="lienAjout_2"><img src="images/auteur_add.png" alt="Ajouter un champs" width="20"></a></td>
						</tr>
						<tr>
							<td><label>Editeur : </label></td>
							<td><input type="text" name="editeur"></td>
						</tr>
						<tr>
							<td><label>Nombre de pages : </label></td>
							<td><input type="text" name="nbPages"></td>
						</tr>
						<tr>
							<td><label>Année de parrution : </label></td>
							<td><input type="text" name="annee"></td>
						</tr>
						<tr>
							<td><label>Genre : </label></td>
							<td><input type="text" name="genre"></td>
						</tr>
						<tr>
							<input type="hidden" value="1" id="cpt">
							<td colspan="2" align="center"><input type="submit" value="Ajouter" class="login-submit"></td>
						</tr>
					</form>
				</table>
			</div>
			
			<div id="ajoutFiche">
				<a id="notif-close" href="javascript:ajout_fiche_fermer();" class="notif-close">Close</a>
				<table style="width:100%"><h2>Ajouter une fiche lecture</h2>
					<form action="addBook" method="POST">
						<tr>
							<td><label>Date : </label></td>
							<td><input type="text" id="date" name="titre"> <i>(jj/mm/aaaa)</i></td>
						</tr>
						<tr>
							<td><label>Appréciation : </label></td>
							<td><textarea rows="6" cols="50"></textarea>
						</tr>
						<tr>
							<td colspan="2" align="center"><input type="submit" value="Ajouter" class="login-submit"></td>
						</tr>
					</form>
				</table>
			</div>
			
			<div id="listeLivres" style="display:none;">
				<table style="width:90%; margin: 10px auto;">
					<tr style="background: #AAAAAA; font-weight: bold; text-align: center;">
						<td>Titre</td> <td>Auteur</td> <td>Nb Pages</td> <td>Genre</td>
					</tr>
				<?php
					$requete = mysql_query("SELECT * FROM livre,genre,auteur,ecritpar,livregenre WHERE genre.id=livregenre.idGenre AND livregenre.idLivre=livre.id AND ecritpar.idLivre=livre.id AND ecritpar.idAuteur=auteur.id");
					$i=0;
					while($result=mysql_fetch_object($requete))
					{
						$i++;
						if($i%2==1)
						{
							print("<tr bgcolor=\"#DFDFDF\"><td>{$result->titre}</td> <td>{$result->nom}</td> <td>{$result->nbPages}</td> <td>{$result->libelle}</td></tr>");
						}
						else
						{
							print("<tr><td>{$result->titre}</td> <td>{$result->nom}</td> <td>{$result->nbPages}</td> <td>{$result->libelle}</td></tr>");
						}
					}
				?>
				</table>
			</div>
		</article>
	</div>
	
	

	<footer>
		<?php include("include/footer"); ?>
	</footer>
</body>
</html>

<?php
	}
?>