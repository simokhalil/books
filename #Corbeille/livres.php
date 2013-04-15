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
	<script src="js/jquery-1.9.1.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/style.css" media="all" />
	
	
	
	<script type="text/Javascript" >
   /*function ajout(element){
      var formulaire = document.getElementById("auteur");
      // Crée un nouvel élément de type "input"
      var champ = document.createElement("input");
      // Les valeurs encodée dans le formulaire seront stockées dans un tableau
      champ.name = "champs[]";
      champ.type = "text";
        
      // On crée un nouvel élément de type "p" et on insère le champ l'intérieur.
      var bloc = document.createElement("p");
      bloc.appendChild(champ);
      formulaire.insertBefore(bloc, element);
	  formmulaire.insertAfter(champ, element);
   }*/

</script>
	
	
	
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
					// on envoie la valeur recherchée en GET au fichier de traitement
					$.ajax({
						type : 'GET', // envoi des données en GET ou POST
						url : 'search.php' , // url du fichier de traitement
						data : 'q='+$(this).val() , // données à envoyer en  GET ou POST
						beforeSend : function() { // traitements JS à faire AVANT l'envoi
							$field.after('<center><img src="images/loading.gif" alt="loader" id="loader"/></center>'); // ajout d'un loader pour signifier l'action
						},
						success : function(data){ // traitements JS à faire APRES le retour de search.php
							$('#loader').remove(); // on enleve le loader
							$('#results').html(data); // affichage des résultats dans le bloc
						}
					});
				}		
			});
		});
		
		
		/*$(document).ready(function(){  
			$("#auteur_1").keyup(function(){
				var n = $("#cpt").val();
				var link = $('<input>', {  
					type: 'text',  
					id: 'auteur'+n,    
				});  

				$('#AuteursAdditionnels').html(link);  
				$("#cpt").val() = n+1;
			});
		});  */
		
		function auteurAct(i,action) {
			var i2 = i + 1;
			var i1 = i - 1;
			if (action == "ajout")
			{
				//document.getElementById('auteurs').innerHTML += '<input type="text" name="auteur_'+i+'" id="auteur_'+i+'"><a href="javascript:auteurAct('+i2+', \'ajout\')" id="lienAjout_'+i2+'"><img src="images/auteur_add.png" alt="Ajouter un champs" title="Ajouter un champs auteur" width="20"></a> <a href="javascript:auteurAct('+i+', \'suppr\')" id="lienSuppr_'+i+'"><img src="images/auteur_remove.png" title="Supprimer un champs auteur" width="20"></a></span></span>';
				$('#auteur_'+i1).after('<tr id="auteur_'+i+'"><td>Auteur '+i+'</td><td><input type="text" name="auteur_'+i+'"><a href="javascript:auteurAct('+i2+', \'ajout\')" id="lienAjout_'+i2+'"><img src="images/auteur_add.png" alt="Ajouter un champs" title="Ajouter un champs auteur" width="20"></a> <a href="javascript:auteurAct('+i+', \'suppr\')" id="lienSuppr_'+i+'"><img src="images/auteur_remove.png" title="Supprimer un champs auteur" width="20"></a></span></td></tr>');
				$('#cpt').val(i);
				$('#lienAjout_'+i).hide();
				$('#lienSuppr_'+i1).hide();
			}
			if (action == "suppr")
			{
				$('#auteur_'+i).remove();
				$('#lienSuppr_'+i).remove();
				$('#lienAjout_'+i2).remove();
				$('#lienAjout_'+i).show();
				$('#lienSuppr_'+i1).show();
				$('#cpt').val(i1);
			}
		}
		
		
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
		<aside>
			<a href="./">Accueil</a>
			<a href="livres.php" class="current">Livres</a>
			<a href="#">Mes livres</a>
				<?php
					if($_SESSION['role']=='admin')
					{
						print("<a href=\"#\">Administration</a>");
					}
				?>
		</aside><!-- #navigation -->
		
		<div style="height:100%; display:block;">
			<h2>Livres</h2>
			
			<form class="ajax" action="search.php" method="get">
				<label for="q">Rechercher un article</label>
				<input type="text" name="q" id="q" class="search-input" autocomplete="off"/>
		
			</form> 
	 
			<!-- La div qui contiendra les résultats de recherche -->
			<div id="results"></div>
		
			<div class="addBook">
			<table><h2>Ajouter un livre</h2>
				<form action="addBook" method="POST" class="login">
					<tr>
						<td><label>Titre : </label></td>
						<td><input type="text"></td>
					</tr>
					<tr  id="auteur_1">
						<td><label>Auteur : </label></td>
						<td><input type="texte" name="auteur_1">
						<a href="javascript:auteurAct(2, 'ajout')" id="lienAjout_2"><img src="images/auteur_add.png" alt="Ajouter un champs" width="20"></a></td>
					</tr>
					<tr>
						<input type="hidden" value="1" id="cpt">
						<td colspan=2><input type="submit" value="Ajouter" class="login-submit"></td>
					</tr>
				</form>
			</table>
			
		</div>
	</article><!-- #contenu -->
	
	<footer>
		<?php include("include/footer"); ?>
	</footer>
</body>
</html>

<?php
	}
?>