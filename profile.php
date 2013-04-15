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

	
	<div class="intro">
		<div class="text-intro">
			<img src="images/biblio.jpg"/>
			<div class="bienvenue">
				<h2>Bonjour et bienvenue sur BookCase</h2>
				<p>Cette application représente un système de gestion de bibliothèque, et ce de façon communautaire.</p>
				<p>En effet, chaque utilisateur peut ajouter des fiches-livres. Ces fiches sont visibles par tous les autres utilisateurs et peuvent être complétées.</p>
				
				<p>Une fois que vous lisez un livre, vous remplissez une fiche de lecture. Elle vous est propre et vous permet de savoir où vous en êtes dans vos lectures.</p>
			</div>
		</div>
	</div>
	
	
	<div id="contenu">
		<aside id="menu"><h2>Menu</h2>
				<a href="./"><img src="styles/img/puce1.png" width="10"> Accueil</a>
				<a href="livres.php"><img src="styles/img/puce2.png" width="10"> Livres</a>
				<a href="fiche.php"><img src="styles/img/puce3.png" width="10"> Mes fiches lecture</a>
				<a href="<?php print("profile.php?user=".$_SESSION['login'])?>" class="current"><img src="styles/img/puce4.png" width="10"> Mon profil</a>
				<?php
					if($_SESSION['role']=='admin')
					{
						print("<a href=\"admin.php\"><img src=\"styles/img/puce5.png\" width=\"10\"> Administration</a>");
					}
				?>
			
		</aside><!-- #navigation -->
		<article>		
			
			<?php
				$requete = mysql_query("SELECT * FROM users WHERE login = '".$_SESSION['login']."'");
				$result = mysql_fetch_object($requete);
			?>	
			<h2><img src="styles/img/icone_profil.png" width="25"> Profile de '<?php print($result->login);?>'</h2>
			
			<center><img src="<?php print($result->image);?>" width="100"></center><br>
			
			<?php
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
			?>
			
			<h3 class="profil-cat">Nom et Prénom</h3>			
			
			<center><form action="#" method="POST">
				<table>
					<tr>
						<td><label>Nom: </label></td>
						<td><input class="login-input" type="text" value="<?php print($result->nom);?>" name="nom"></td>
					</tr>
					<tr>
						<td><label>Prénom: </label></td>
						<td><input  class="login-input" type="text" value="<?php print($result->prenom);?>" name="prenom"></td>
					</tr>
					<tr>
						<td colspan="2" align="center"><input type="submit" value="Enregistrer le nom/prénom" class="login-submit"></td>
					</tr>
				</table>
			</form></center>
			
			<h3 class="profil-cat">Mot de passe</h3>
			
			<center><form action="#" method="POST">
				<table>
					<tr>
						<td><label>Nouveau mot de passe: </label></td>
						<td><input  class="login-input" type="password" name="pass"></td>
					</tr>
					<tr>
						<td><label>Confirmez: </label></td>
						<td><input  class="login-input" type="password" name="pass2"></td>
					</tr>
					<tr>
						<td colspan="2" align="center"><input type="submit" value="Changer le mot de passe" class="login-submit"></td>
					</tr>
				</table>
			</form></center>
			
			<h3 class="profil-cat">Photo de profil</h3>
			
			<center><form>
				<table>
					<tr>
						<td><label>Photo de profil: </label></td>
						<td><input type="file" name="image"></td>
					</tr>
					<tr>
						<td colspan="2" align="center"><input type="submit" value="Enregistrer la photo" class="login-submit"></td>
					</tr>
				</table>
			</form></center>
			
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