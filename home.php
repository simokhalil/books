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
	<meta charset="ISO-8859-1" />
	<title>
		bOOk - Gestion de fiches lectures
	</title>
	
	<link rel="stylesheet" type="text/css" href="styles/style.css" media="all" />
</head>

<body>



	<header>
		<div style="float:right;" class="login">
			<form name="logout" action="logout.php" method="POST">
				<p>Bonjour <strong><?php print($_SESSION['nom']." ".$_SESSION['prenom']); ?></strong>
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
	
	
	
	<article>
		<nav>
			<ul>
				<li><a href="./" class="current">Accueil</a></li>
				<li><a href="livres.php">Livres</a></li>
				<li><a href="#">Mes livres</a></li>
				<?php
					if($_SESSION['role']=='admin')
					{
						print("<li><a href=\"#\">Administration</a></li>");
					}
				?>
			</ul>
		</nav><!-- #navigation -->
		<h2>Derniers livres ajoutés</h2>
		
		<p>Bonjour et bienvenue sur bOOk.<br><br>
		Cette application représente un système de gestion de bibliothèque, et ce de façon communautaire.<br><br>
		En effet, chaque utilisateur peut ajouter des fiches-livres. Ces fiches sont visibles par tous les autres utilisateurs et peuvent être complétées.<br><br>
		
		Une fois que vous lisez un livre, vous remplissez une fiche de lecture. Elle vous est propre et vous permet où vous en êtes dans vos lectures.</p>
		<br><br>
		
		<a href="#">Lien de test</a>
	</article><!-- #contenu -->
	
	<footer>
		<?php include("include/footer"); ?>
	</footer>



</body>
</html>

<?php
	}
?>