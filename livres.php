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
	<script src="js/jquery-1.9.1.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/style.css" media="all" />
	
	<script>
		$(document).ready( function() {
			// détection de la saisie dans le champ de recherche
			$('#q').keyup( function(){
				$field = $(this);
				$('#results').html(''); // on vide les resultats
				$('#loader').remove(); // on retire le loader
			 
				// on commence à traiter à partir du 2ème caractère saisi
				if( $field.val().length > 1 )
				{
					// on envoie la valeur recherché en GET au fichier de traitement
					$.ajax({
						type : 'GET', // envoi des données en GET ou POST
						url : 'search.php' , // url du fichier de traitement
						data : 'q='+$(this).val() , // données à envoyer en  GET ou POST
						beforeSend : function() { // traitements JS à faire AVANT l'envoi
							$field.after('<img src="images/loading.gif" alt="loader" id="loader"/>'); // ajout d'un loader pour signifier l'action
						},
						success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
							$('#loader').remove(); // on enleve le loader
							$('#results').html(data); // affichage des résultats dans le bloc
						}
					});
				}		
			});
		});
</script>

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
				<li><a href="./">Accueil</a></li>
				<li><a href="livres.php" class="current">Livres</a></li>
				<li><a href="#">Mes livres</a></li>
				<?php
					if($_SESSION['role']=='admin')
					{
						print("<li><a href=\"#\">Administration</a></li>");
					}
				?>
			</ul>
		</nav><!-- #navigation -->
		
		<h2>Livres</h2>
		
		<form class="ajax" action="search.php" method="get">
			<label for="q">Rechercher un article</label>
			<input type="text" name="q" id="q" class="search-input" autocomplete="off"/>
	
		</form> 
 
		<!-- La div qui contiendra les résultats de recherche -->
		<div id="results"></div>
		
	</article><!-- #contenu -->
	
	<footer>
		<?php include("include/footer"); ?>
	</footer>
</body>
</html>

<?php
	}
?>