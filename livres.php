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
	<link rel="stylesheet" type="text/css" href="styles/jquery-ui.css">
	
	<script src="js/jquery-1.9.1.js"></script>
	<script src="js/jquery-ui.js"></script>
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
				<a href="livres.php" class="current"><img src="styles/img/puce2.png" class="puce"> Livres</a>
				<a href="fiche.php"><img src="styles/img/puce3.png" class="puce"> Mes fiches lecture</a>
				<a href="<?php print("profile.php?user=".$_SESSION['login'])?>"><img src="styles/img/puce4.png" class="puce"> Mon profil</a>
				<?php
					if($_SESSION['role']=='admin')
					{
						print("<a href=\"admin.php\"><img src=\"styles/img/puce5.png\" class=\"puce\"> Administration</a>");
					}
				?>
		</aside>
		
		<article>
			<h2><img src="styles/img/icone_livre.png" width="25"> Livres</h2>			
			<label for="q">Rechercher un livre</label>
			<input type="text" name="q" id="q" class="search-input" autocomplete="off"/>
			
			<center><button class="login-submit" id="btn-rechercher">Rechercher</button> 
			<button class="login-submit" id="btn-allBooks">Tout afficher</button></center>
			<br>
			<a href="javascript:liste_livres();" style="margin-right: 30px; font-size: .8em; margin-top:18px; display:none;">tout afficher/masquer</a>		
			<br>
			<!-- La div qui contiendra les résultats de recherche -->
			<div id="results"></div>
		
			<div id="ajoutLivre">
				<a id="notif-close" href="javascript:ajout_livre_fermer();" class="notif-close">Close</a>
				<table style="width:100%"><h2>Ajouter un livre</h2>
					<form action="#" method="POST" id="formAjoutLivre">
						<tr>
							<td id="imgLivre" align="center" colspan="2"></td>
							<input type="hidden" id="img-Livre" name="img-Livre">
						</tr>
						<tr>
							<td><label>Titre * : </label><div id="loader2" class="loader-petit"></div></td>
							<td><input type="text" id="titre" name="titre"><a href="javascript:rechAmazon();"><img src="images/reload.png" width="17" title="Effacer le formulaire et relancer la recherche" ></a></td>
						</tr>
						<tr>
							<td colspan="2"><div id="propositions" style="background:#F9F9F9; padding:0; margin:0;"></div></td>
						</tr>
						<tr  id="ligne_auteur_1">
							<td><label>Auteur * : </label></td>
							<td><input type="text" name="auteur_1" class="auteur" id="auteur_1" onkeyUp="javascript:ajouter_auteur(2);"></td>
						</tr>
						<tr>
							<td><label>Editeur : </label></td>
							<td><input type="text" name="editeur" id="editeur"></td>
						</tr>
						<tr>
							<td><label>Nombre de pages : </label></td>
							<td><input type="text" name="nbPages" id="nbPages"></td>
						</tr>
						<tr>
							<td><label>Année de parrution : </label></td>
							<td><input type="text" name="annee" id="annee"></td>
						</tr>
						<tr>
							<td><label>Résumé : </label></td>
							<td><textarea cols="30" rows="6" id="resume-Livre" name="resume-Livre"></textarea></td>
						</tr>
						<tr>
							<td width="250" valign="top"><label>Genre * : </label><div id="loaderGenre" class="loader-petit"></div></td>
							<td>
								<div id="genresPreSelec"></div>
								<div style="clear:both;"><input type="text" id="genreAdd"><span onClick="javascript:ajoute_genre();"><img src="images/ajouter.png"></span></div>
							</td>
						</tr>
						<tr>
							<input type="hidden" value="1" id="cpt-auteurs" name="cpt-auteurs">
							<input type="hidden" value="0" id="cpt-genres" name="cpt-genres">
							<td colspan="2" align="center"><input type="submit" value="Ajouter" class="login-submit" id="btn-envoi-form"></td>
						</tr>
					</form>
				</table>
			</div>
			
			<?php
				/*********************************/
				/* Ajout d'un livre dans la base */
				/*********************************/
				if (isset($_POST['titre']))
				{
					/* Si des données sont vides */
					if (empty($_POST['editeur'])) {$_POST['editeur'] = "";}
					if (empty($_POST['nbPages'])) {$_POST['nbPages'] = "";}
					if (empty($_POST['annee'])) {$_POST['annee'] = "";}
					if (empty($_POST['resume'])) {$_POST['resume'] = "";}
					
					/* Protection des données (apostrophes...) */
					$titre = addslashes($_POST['titre']);
					$editeur = addslashes($_POST['editeur']);
					$resume = addslashes($_POST['resume-Livre']);
					
					/* Le livre existe déjà dans la base? */
					$req = mysql_query("SELECT * FROM livre WHERE titre = '{$titre}'");
					$livre = mysql_num_rows($req);
					
					$errorAjoutLivre = NULL;
					$errorAjoutGenre = NULL;
					$errorAjoutAuteur = NULL;
					$idNewLivre = NULL;
					$idNewGenre = NULL;
					$idNewAuteur = NULL;
					
					
					/* Il n'existe pas */
					if($livre==0 || $livre==NULL)
					{
						/* Ajout du livre dans la base */
						$reqAjout = mysql_query("INSERT INTO livre (titre, editeur, nbPages, datePublication, resume)
													VALUES ('{$titre}', '{$editeur}', '{$_POST['nbPages']}', '{$_POST['annee']}', '{$resume}')");
						
						/* On récupère l'id du livre nouvellement ajouté */
						$idNewLivre=mysql_insert_id();
						if($reqAjout)
						{
							$errorAjoutLivre = 0;
							/* Récupération de la pochette du livre depuis Amazon, le renommer avec l'id du livre et modif de la base */
							if(isset($_POST['img-Livre']) && !empty($_POST['img-Livre']))
							{
								copy($_POST['img-Livre'], "images/livres/{$idNewLivre}.jpg");
								mysql_query("UPDATE livre SET image='images/livres/{$idNewLivre}.jpg' WHERE id='{$idNewLivre}'");
							}
						}
						else
						{
							$errorAjoutLivre = 1;
						}
					}
					/* Le livre est déjà dans la base */
					else
					{
						print("<p id=\"notif\" class=\"notif-erreur\">Ce livre existe déjà dans notre base!<a id=\"notif-close\" href=\"javascript:notif_close();\" class=\"notif-close\">Close</a></p>");
						$livre = mysql_fetch_object($req);
						$idNewLivre = $livre->id;
						$errorAjoutLivre = 2;
					}
					
					/*****************************************************************/
					/* Faire le lien entre le(s) genre(s) sélectionné(s) et le livre */
					/*****************************************************************/
					if ($errorAjoutLivre != 1)
					{
						for($i=1;$i<=$_POST['cpt-genres'];$i++)
						{
							if(isset($_POST['genre_'.$i]) && !empty($_POST['genre_'.$i]))
							{
								$genre = $_POST['genre_'.$i];
								/* Le genre existe dans la base? */
								$reqGenreExiste = mysql_query("SELECT * FROM genre WHERE libelle='{$genre}'");
								// Si le genre n'existe pas 
								if(mysql_num_rows($reqGenreExiste) == 0 || mysql_num_rows($reqGenreExiste) == NULL) 
								{
									$reqNewGenre = mysql_query("INSERT INTO genre(libelle) VALUES ('{$genre}')");
									if($reqNewGenre)
									{
										$errorAjoutGenre = 0;
										/* On lie le genre nouvellement ajouté au livre */
										$idNewGenre = mysql_insert_id();
										$reqLivreGenre = mysql_query("INSERT INTO livregenre(idLivre, idGenre) VALUES ('{$idNewLivre}','{$idNewGenre}')");
									}
									else
									{
										$errorAjoutGenre = 1;
									}
								}
								//Sinon (Si le genre existe dans la base)
								else
								{
									/* On récupère son id et on le lie au livre */
									$reqGenreExiste = mysql_fetch_object($reqGenreExiste);
									mysql_query("INSERT INTO livregenre(idLivre, idGenre) VALUES ('{$idNewLivre}','{$reqGenreExiste->id}')");
									$errorAjoutGenre = 0;
								}
							}
						}
						
						/***************************************************/
						/* Faire le lien entre le(s) auteur(s) et le livre */
						/***************************************************/
						for($i=1;$i<=$_POST['cpt-auteurs'];$i++)
						{
							if(isset($_POST['auteur_'.$i]) && !empty($_POST['auteur_'.$i]))
							{
								/* L'auteur existe déjà dans la base? */
								$reqAuteurExistant = mysql_query("SELECT * FROM auteur WHERE nom='".$_POST['auteur_'.$i]."'");
								// Si l'auteur n'existe pas
								if(mysql_num_rows($reqAuteurExistant) == 0 || mysql_num_rows($reqAuteurExistant) == NULL)
								{
									$reqNewAuteur = mysql_query("INSERT INTO auteur(nom) VALUES ('".$_POST['auteur_'.$i]."')");
									if($reqNewAuteur)
									{
										$errorAjoutAuteur = 0;
										$idNewAuteur = mysql_insert_id();
										mysql_query("INSERT INTO ecritpar(idLivre, idAuteur) VALUES ('{$idNewLivre}','{$idNewAuteur}')");
									}
									else
									{
										$errorAjoutAuteur = 1;
									}
								}
								// Sinon (si l'auteur existe)
								else 
								{
									/* On récupère son id et le lie au livre */
									$reqAuteurExistant = mysql_fetch_object($reqAuteurExistant);
									mysql_query("INSERT INTO ecritpar(idLivre, idAuteur) VALUES ('{$idNewLivre}','{$reqAuteurExistant->id}')");
									$errorAjoutAuteur = 0;
								}
							}
						}						
					}
					if($errorAjoutLivre != 1 && $errorAjoutGenre != 1 && $errorAjoutAuteur != 1)
					{
						if($errorAjoutLivre != 2)
						{
							print("<p id=\"notif\" class=\"notif-succes\">Le livre a été ajouté avec suscès! <a id=\"notif-close\" href=\"#\" class=\"notif-close\">Close</a></p>");
						}
					}
					else
					{
						print("<p id=\"notif\" class=\"notif-erreur\">Une erreur s'est produite lors de l'ajout du livre!<a id=\"notif-close\" href=\"#\" class=\"notif-close\">Close</a></p>");
					}
				}
			?>
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