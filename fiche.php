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
				<a href="fiche.php" class="current"><img src="styles/img/puce3.png" width="10"> Mes fiches lecture</a>
				<a href="<?php print("profile.php?user=".$_SESSION['login'])?>"><img src="styles/img/puce4.png" width="10"> Mon profil</a>
				<?php
					if($_SESSION['role']=='admin')
					{
						print("<a href=\"admin.php\"><img src=\"styles/img/puce5.png\" width=\"10\"> Administration</a>");
					}
				?>
			
		</aside><!-- #navigation -->
		<article>		
			<h2><img src="styles/img/icone_fiche.png" width="25"> Mes fiches lecture</h2>
			
			
			
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