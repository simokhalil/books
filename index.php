<?php
if(!file_exists('include/installed'))
{
	header("location:install/");
}
else
{
	session_start ();  
	if (isset($_SESSION['login']) && isset($_SESSION['pass'])) 
		{
			header("location: home.php") ; 
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
		
		<link rel="stylesheet" type="text/css" href="styles/base.css" media="screen" />

		<script src="js/jquery-1.9.1.js"></script>
		<script language="javascript">
			function inscription()
			{
				document.getElementById("connexion").style.display = "none";
				document.getElementById("inscription").style.display = "block";
			}
			
			function connexion()
			{
				document.getElementById("connexion").style.display = "block";
				document.getElementById("inscription").style.display = "none";
			}
		</script>
	</head>
	
	<body>
			<header>
				<h1>
					<img alt="" src="images/logo.png"/>
					<span><font color="#5F84BC" size="7">B</font>ook<font color="#5F84BC" size="7">C</font>ase</span> <font size="4"><?php include("include/version");?></font>
				</h1>
				<p class="sous-titre">
					<strong>Gestion de fiches lectures</strong>
				</p>
			</header><!-- #entete -->
	<?php
		if (file_exists('install'))
		{
			print("<div class=\"notif-erreur\">Merci de supprimer/renommer le dossier 'install' avant de pouvoir utiliser l'application...</div>");
		}
		else
		{
	?>
			<article>
				<?php
				
					// test si les variables de connexion sont d�finies
					if (isset($_POST['login']) && isset($_POST['pass'])) 
					{ 
						//nettoyage des variables
						$login = addslashes($_POST['login']);
						$pass = addslashes(md5($_POST['pass']));
						
						//construction de la requ�te
						$sql = "SELECT * FROM users WHERE login='{$login}' AND pass='{$pass}' AND actif=1";
						$requete = mysql_query( $sql);
						
						//ex�cution de la requete
						if($result= mysql_fetch_object($requete))
						{
							session_start ();
							// enregistrement des param�tres dans la session
							$_SESSION['login'] = $login;
							$_SESSION['pass'] = $pass;
							$_SESSION['nom'] = $result->nom;
							$_SESSION['prenom'] = $result->prenom;
							$_SESSION['role'] = $result->role;
							$_SESSION['image'] = $result->image;
							$_SESSION['id'] = $result->id;
							
							// redirection vers l'accueil
							header ('location: home.php'); 
						} 
						else 
						{ 
							// pas reconnu
							$errorMSG = "Nom d'utilisateur ou mot de passe incorrect!";
							print("<p id=\"notif\" class=\"notif-erreur\">".$errorMSG." <a id=\"notif-close\" href=\"#\" class=\"notif-close\">Close</a></p>"); 
						}  
					}  
					else
					{
						if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['reglogin']) && isset($_POST['regpass']) && isset($_POST['regpass2']))
						{
							//nettoyage et enregistrement des variables
							$nom = strToUpper($_POST['nom']);
							$prenom = addslashes($_POST['prenom']);
							$login = addslashes($_POST['reglogin']);
							$pass = addslashes(md5($_POST['regpass']));
							$pass2 = addslashes(md5($_POST['regpass2']));
							
					
							// variables de gestion d'erreur mises � FALSE (d�sactiv�es dans unpremier temps)
							$error = FALSE;
							$registerOK = FALSE;
								
							// On regarde si tout les champs sont remplis, sinon, on affiche un message � l'utilisateur.
							if($login == NULL OR $nom == NULL OR $prenom == NULL OR $pass == NULL OR $pass2 == NULL)
							{
								// On met la variable $error � TRUE
								$error = TRUE;
								// On �crit le message � afficher :
								$errorMSG = "Tout les champs doivent �tre remplis !";
							}
							
							// Sinon, si les deux mots de passes correspondent :
							elseif($pass == $pass2)
							{
								// On regarde si le mot de passe et le nom de compte ne sont pas les m�mes
								if($login != $pass)
								{
									//Si c'est bon on regarde dans la base de donn�e si le nom de compte est d�j� utilis� :
									$sql = "SELECT login FROM users WHERE login = '".$login."' ";
									$sql = mysql_query($sql);
									// On compte combien de valeur � pour nom de compte celui tap� par l'utilisateur.
									$sql = mysql_num_rows($sql);
								
									// Si $sql est �gal � 0 (c'est-�-dire qu'il n'y a pas de nom de compte avec la valeur tap�e par l'utilisateur
									if($sql == 0)
									{								   
										// Si tout va bien on regarde si le mot de passe n'ex�de pas 50 caract�res.
										if(strlen($pass) < 50)
										{
											// Si tout va bien on regarde si le nom de compte n'ex�de pas 50 caract�res.
											if(strlen($login) < 50)
											{
												// Si tout ce passe correctement, on peut maintenant l'inscrire dans la base de donn�es :
												$requete = "INSERT INTO users (id, login, nom, prenom, pass, role) VALUES ('','$login','$nom','$prenom','$pass', 'user')";
												$requete = mysql_query($requete);
											   
												// Si la requ�te s'est bien effectu�e :
												if($requete)
												{
													// On met la variable $registerOK � TRUE
													$registerOK = TRUE;
													// On affiche un message pour le dire que l'inscription s'est bien d�roul�e :
													$registerMSG = "Inscription r�ussie !";
													  
													// On le met des variables de session pour stocker le nom de compte et le mot de passe :
													//$_SESSION["login"] = $login;
													//$_SESSION["pass"] = $pass;
												}
										   
												// Sinon on affiche un message d'erreur. 
												else
												{					   
												  $error = TRUE;
												  $errorMSG = "Erreur dans la requ�te SQL<br/>".$sql."<br/>";
												}
											}
												
											// Sinon on fais savoir � l'utilisateur qu'il a mis un nom de compte trop long.
											else
											{
												$error = TRUE;
												$errorMSG = "Votre nom compte ne doit pas d�passer <strong>50 caract�res</strong> !";
												$login = NULL;
											}
										}
										// Si le mot de passe d�passe 50 caract�res on le fait savoir
										else
										{
											$error = TRUE;
											$errorMSG = "Votre mot de passe ne doit pas d�passer <strong>50 caract�res</strong> !";
											$pass = NULL;
										}
									}
									// Sinon on affiche un message d'erreur lui disant que ce nom de compte est d�j� utilis�.
									else
									{
										$error = TRUE;
										$errorMSG = "Le nom de compte <strong>".$login."</strong> est d�j� utilis� !";
										$login = NULL;
									}
								}
								// Sinon on fais savoir � l'utilisateur qu'il doit changer le mot de passe ou le nom de compte
								else
								{
									$error = TRUE;
									$errorMSG = "Le nom de compte et le mot de passe doivent �tres diff�rents !";
								}
							}
							// Sinon si les deux mots de passes sont diff�rents :      
							elseif($pass != $pass2)
							{
								$error = TRUE;	 
								$errorMSG = "Les deux mots de passes sont diff�rents !";
								$pass = NULL;
							}		
						
							mysql_close($cnx);
							   
							//On affiche les erreurs :
							if($error == TRUE)
							{ 
								print("<p id=\"notif\" class=\"notif-erreur\">".$errorMSG." <a id=\"notif-close\" href=\"#\" class=\"notif-close\">Close</a></p>"); 
							}
						
							// Si l'inscription s'est bien d�roul�e on affiche le succ�s :
							if($registerOK == TRUE)
							{ 
								print("<p id=\"notif\" class=\"notif-succes\">".$registerMSG." <a id=\"notif-close\" href=\"#\" class=\"notif-close\">Close</a></p>"); 
							}
						}
						else 
						{ 
							print("");
						} 
					}
				
				?> 	
				
				<center>
				<div id="connexion">
					<div class="login">
						<h2 class="login-titre">Connexion</h2>
						
						<form action="./" method="POST">
							<p class="login-p">
								<label>Nom d'utilisateur : </label>
								<input autocomplete="off" class="login-input" type="text" name="login">
							</p>
							<p class="login-p">
								<label>Mot de passe : </label>
								<input autocomplete="off" class="login-input" type="password" name="pass">
							</p>
							<p class="submit-p">
								<input class="login-submit" type="submit" value="Me connecter">
							</p>
						</form>
					</div>
					Pas de compte? <a class="login-link" id="regLink" onClick="inscription___();">S'inscrire</a>
				</div>
				
				<div id="inscription">
					<div class="login">
						<h2 class="login-titre">Inscription</h2>
						
						<form action="index.php" method="POST">
							<p class="login-p">
								<label>Nom : </label>
								<input autocomplete="off" class="login-input" type="text" name="nom">
							</p>
							<p class="login-p">
								<label>Pr�nom : </label>
								<input autocomplete="off" class="login-input" type="text" name="prenom">
							</p>
							<p class="login-p">
								<label>Nom d'utilisateur : </label>
								<input autocomplete="off" class="login-input" type="text" name="reglogin">
							</p>
							<p class="login-p">
								<label>Mot de passe : </label>
								<input autocomplete="off" class="login-input" type="password" name="regpass">
							</p>
							<p class="login-p">
								<label>Confirmer : </label>
								<input autocomplete="off" class="login-input" type="password" name="regpass2">
							</p>
							<p class="submit-p">
								<input class="login-submit" type="submit" value="M'inscrire">
							</p>
						</form>
					
					</div>
					D�j� un compte? <a class="login-link" id="connectLink" onClick="connexion___();">Se connecter</a>
				</div>
				</center>
				
			</article> <!-- contenu-login -->

			<footer>
				<?php include("include/footer"); ?>
			</footer>

		<script>
			$("#regLink").click(function () {
				$("#connexion").hide("fast");
				$("#inscription").show("slow");
			});
			
			$("#connectLink").click(function () {
				$("#connexion").show("slow");
				$("#inscription").hide("fast");
			});
			
			$("#notif-close").click(function(){
				$("#notif").hide('slow');
			});
		</script>
	<?php
		}
	?>
	</body>
</html>

<?php
	}
}
?>