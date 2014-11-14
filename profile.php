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
	
	<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="js/bookCase.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	
	<link rel="stylesheet" type="text/css" href="styles/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="styles/style.css" media="all" />
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
			<a href="fiche.php"><img src="styles/img/puce3.png" class="puce"> Mes fiches lecture</a>
			<a href="<?php print("profile.php?user=".$_SESSION['login'])?>" class="current"><img src="styles/img/puce4.png" class="puce"> Mon profil</a>
			<?php
				if($_SESSION['role']=='admin')
				{
					print("<a href=\"admin.php\"><img src=\"styles/img/puce5.png\" class=\"puce\"> Administration</a>");
				}
			?>
		</aside><!-- #navigation -->
		
		<article>		
			<?php
				$requete = mysql_query("SELECT * FROM users WHERE login = '".$_SESSION['login']."'");
				$result = mysql_fetch_object($requete);
			?>	
			<h2><img src="styles/img/icone_profil.png" width="25"> Profile de '<?php print($result->login);?>'</h2>
			<?php
				/* Modification du nom et/ou prénom */
				if(isset($_POST["nom"]) OR isset($_POST["prenom"]))
				{
					$requete = mysql_query("UPDATE users SET nom='".$_POST['nom']."', prenom='".$_POST['prenom']."' WHERE login='".$_SESSION['login']."'");
					if ($requete)
					{
						print("<p id=\"notif\" class=\"notif-succes\">Modification réussie!<a id=\"notif-close\" href=\"#\" class=\"notif-close\">Close</a></p>"); 
					}
					$requete = mysql_query("SELECT * FROM users WHERE login = '".$_SESSION['login']."'");
					$result = mysql_fetch_object($requete);
				}
				
				/* Modification du mot de passe */
				if(isset($_POST["pass"]) AND isset($_POST["pass2"]))
				{
					if($_POST["pass"] == $_POST["pass2"])
					{
						if($_POST["pass"] != $_SESSION['login'])
						{
							$requete = mysql_query("UPDATE users SET pass='".md5($_POST['pass'])."' WHERE login='".$_SESSION['login']."'");
							if ($requete)
							{
								print("<p id=\"notif\" class=\"notif-succes\">Modification réussie!<a id=\"notif-close\" href=\"#\" class=\"notif-close\">Close</a></p>"); 
							}
							$requete = mysql_query("SELECT * FROM users WHERE login = '".$_SESSION['login']."'");
							$result = mysql_fetch_object($requete);
						}
						else
						{
							print("<p id=\"notif\" class=\"notif-erreur\">Le mdp doit être différent du nom d'utilisateur!<a id=\"notif-close\" href=\"#\" class=\"notif-close\">Close</a></p>");
						}
					}
					else
					{
						print("<p id=\"notif\" class=\"notif-erreur\">Les deux mots de passe sont différents!<a id=\"notif-close\" href=\"#\" class=\"notif-close\">Close</a></p>");
					}
				}
				
				/* Modification de l'image de profil */
				if(isset($_FILES['image']))
				{ 
					$dossier = 'images/users/';
					$fichier = basename($_SESSION['login'].".png");
					$taille_maxi = 100000;
					$taille = filesize($_FILES['image']['tmp_name']);
					$extensions = array('.png', '.gif', '.jpg', '.jpeg');
					$extension = strrchr($_FILES['image']['name'], '.'); 
					//Début des vérifications de sécurité...
					if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
					{
						 $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt ou doc...';
					}
					if($taille>$taille_maxi)
					{
						 $erreur = 'Le fichier est trop gros...';
					}
					if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
					{
						if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
						{
							$reqMajPhoto = mysql_query("UPDATE users SET image ='".$dossier.$fichier."' WHERE id='{$_SESSION['id']}'");
							if($reqMajPhoto)
							{
								$_SESSION['image'] = $dossier.$fichier;
							}
							//print("Upload effectué avec succès !");
							print("<p id=\"notif\" class=\"notif-succes\">Votre photo de profil a été mise à jour! <a id=\"notif-close\" href=\"#\" class=\"notif-close\">Close</a></p>");
						}
						else //Sinon (la fonction renvoie FALSE).
						{
							print("<p id=\"notif\" class=\"notif-erreur\">Une erreur s'est produite! Merci de réessayer...<a id=\"notif-close\" href=\"#\" class=\"notif-close\">Close</a></p>");
						}
					}
					else
					{
						 echo $erreur;
					}
				}

			?>
			<center><img src="<?php print($result->image);?>" width="100"></center><br>
			<div class="accordion">
				<h3>Nom et Prénom</h3>			
				
				<center><form action="#" method="POST">
					<table>
						<tr>
							<td><label>Nom: </label></td>
							<td><input class="chapms-saisie" type="text" value="<?php print($result->nom);?>" name="nom"></td>
						</tr>
						<tr>
							<td><label>Prénom: </label></td>
							<td><input  class="chapms-saisie" type="text" value="<?php print($result->prenom);?>" name="prenom"></td>
						</tr>
						<tr>
							<td colspan="2" align="center"><input type="submit" value="Enregistrer le nom/prénom" class="login-submit"></td>
						</tr>
					</table>
				</form></center>
				
				<h3>Mot de passe</h3>
				
				<center><form action="#" method="POST">
					<table>
						<tr>
							<td><label>Nouveau mot de passe: </label></td>
							<td><input  class="chapms-saisie" type="password" name="pass"></td>
						</tr>
						<tr>
							<td><label>Confirmez: </label></td>
							<td><input  class="chapms-saisie" type="password" name="pass2"></td>
						</tr>
						<tr>
							<td colspan="2" align="center"><input type="submit" value="Changer le mot de passe" class="login-submit"></td>
						</tr>
					</table>
				</form></center>
				
				<h3>Photo de profil</h3>
				
				<center>
					<form action="#" method="POST" enctype="multipart/form-data">
						<table>
							<tr>
								<td><label>Photo de profil: </label></td>
								<input type="hidden" name="MAX_FILE_SIZE" value="100000">
								<td><input type="file" name="image"></td>
							</tr>
							<tr>
								<td colspan="2" align="center"><input type="submit" value="Enregistrer la photo" class="login-submit"></td>
							</tr>
						</table>
					</form>
				</center>
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