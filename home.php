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
			<span>bOOk</span> <font size="4"><?php include("version");?></font>
		</h1>
		<p class="sous-titre">
			<strong>Gestion de fiches lectures</strong>
		</p>
	</div><!-- #entete -->
	
	<div id="navigation">
		<ul>
			<li><a href="./">Accueil</a></li>
		</ul>
	</div><!-- #navigation -->
	
	<div id="contenu">
		
	</div><!-- #contenu -->
	
	<p id="copyright">
		<?php include("footer"); ?>
	</p>

</div><!-- #global -->

</body>
</html>

<?php
	}
?>