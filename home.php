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
	
	<link rel="stylesheet" type="text/css" href="styles/liquidcarousel.css" media="all" />
	<link rel="stylesheet" type="text/css" href="styles/style.css" media="all" />
	<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
	<!--<script type="text/javascript" src="js/jquery-ui.custom.js"></script>-->
	<script type="text/javascript" src="js/bookCase.js"></script>
</head>

<body>

	<div class="warningJS">Javascript est désactivé sur votre navigateur. Or, cette application s'appuye dessus pour bien fonctionner. Merci de l'activer</div>

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
				<a href="./" class="current"><img src="styles/img/puce1.png" class="puce"> Accueil</a>
				<a href="livres.php"><img src="styles/img/puce2.png" class="puce"> Livres</a>
				<a href="fiche.php"><img src="styles/img/puce3.png" class="puce"> Mes fiches lecture</a>
				<a href="<?php print("profile.php?user=".$_SESSION['login'])?>"><img src="styles/img/puce4.png" class="puce"> Mon profil</a>
				<?php
					if($_SESSION['role']=='admin')
					{
						print("<a href=\"admin.php\"><img src=\"styles/img/puce5.png\" class=\"puce\"> Administration</a>");
					}
				?>
			
		</aside><!-- #navigation -->
		<article>		
			<h2><img src="styles/img/icone_accueil.png" width="25"> Accueil</h2>
			<h2 class="rubrique">Derniers livres ajoutés</h2>
			
			
			<?php
				
				$sql= mysql_query("SELECT * FROM livre WHERE actif=1 ORDER BY dateAjout DESC");
				$i = 0;
				while(($result = mysql_fetch_object($sql)) && ($i<4))
				{
			?>
				<a href="<?php print("ficheLivre.php?idLivre={$result->id}");?>">
				<div class="unLivre">
					<div class="imageLivre"><img src="<?php print("{$result->image}"); ?>"></div>
					<?php
						$sql2 = mysql_query("SELECT titre FROM livre WHERE id={$result->id}");
						$titre = mysql_fetch_object($sql2);
					?>
					<div class="titreLivre"><?php print($titre->titre);?></div>
				</div>
				</a>
			<?php
					$i++;
				}
			?>

			<h3 class="rubrique" style="clear:both;">Derniers livres lus</h3>
			
			<?php
				
				$sql = mysql_query("SELECT * FROM lecture,livre WHERE lecture.idLivre=livre.id AND idUser={$_SESSION['id']} ORDER BY dateLecture DESC");
				if(mysql_num_rows($sql) == 0)
				{
					print("<i>Aucun livre lu pour le moment</i>");
				}
				else
				{
					$i = 0;
					while(($result = mysql_fetch_object($sql)) && ($i<4))
					{
			?>
						<a href="<?php print("ficheLivre.php?idLivre={$result->id}");?>">
						<div class="unLivre">
							<div class="imageLivre"><img src="<?php print("{$result->image}"); ?>" height="180"></div>
							<?php
								$sql2 = mysql_query("SELECT titre FROM livre WHERE id={$result->id}");
								$titre = mysql_fetch_object($sql2);
							?>
							<div class="titreLivre"><?php print($titre->titre);?></div>
						</div></a>
			<?php
						$i++;
					}
				}
			?>
			
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