<?php
	session_start ();  
	if (!isset($_SESSION['login']) && !isset($_SESSION['pass'])) 
		{
			header("location: index.php") ; 
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
	<!-- La feuille de styles "base.css" doit être appelée en premier. -->
	<link rel="stylesheet" type="text/css" href="styles/base.css" media="all" />
	<link rel="stylesheet" type="text/css" href="styles/style.css" media="all" />
</head>

<body>

<div id="global">

	<div id="entete">
		<div style="float:right;" class="login"><center>
			<form name="logout" action="logout.php" method="POST">
				<table>
					<tr>
						<td><font color="#FFFFFF"> Connecté : <?php print($_SESSION['nom']." ".$_SESSION['prenom']); ?></font></td>
					</tr>
					<tr>
						<td><input class="login-submit" type="submit" value="Se déconnecter"></td>
					</tr>
				</table>
			</form></center>
		</div>
		<h1>
			<img alt="" src="images/logo.gif" width="50"/>
			<span>bOOk</span> <font size="4"><?php include("include/version");?></font>
		</h1>
		<p class="sous-titre">
			<strong>Gestion de fiches lectures</strong>
		</p>
	</div><!-- #entete -->
	
	<div id="navigation">
		<ul>
			<li><a href="./" class="current">Accueil</a></li>
			<li><a href="#">Livres</a></li>
			<li><a href="#">Mes livres</a></li>
		</ul>
	</div><!-- #navigation -->
	
	<div id="contenu">
		Bonjour et bienvenue bOOk.<br><br>
		Cette application représente un système de gestion de bibliothèque, et ce de façon communautaire.<br><br>
		En effet, chaque utilisateur peut ajouter des fiches-livres. Ces fiches sont visibles par tous les autres utilisateurs et peuvent être complétées.<br><br>
		<blockquote>Exemple : Chaque utilisateur peut noter un livre, la moyenne de ces notes sera visible sur les fiches-livres</blockquote><br>
		Une fois que vous lisez un livre, vous remplissez une fiche de lecture. Elle vous est propre et vous permet où vous en êtes dans vos lectures.<br><br>
	</div><!-- #contenu -->
	
	<p id="copyright">
		<?php include("include/footer"); ?>
	</p>

</div><!-- #global -->

</body>
</html>

<?php
	}
?>