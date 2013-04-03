<?php
	session_start ();  
	if (isset($_SESSION['login']) && isset($_SESSION['pass'])) 
		{
			header("location: home.php") ; 
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
		<link rel="stylesheet" type="text/css" href="styles/base.css" media="all" />
		<link rel="stylesheet" type="text/css" href="styles/style.css" media="screen" />
	</head>
	<body>
		<div id="login-global">
			<div id="entete">
				<h1>
			<img alt="" src="images/logo.gif" width="50"/>
			<span>bOOk</span> <font size="4"><?php include("include/version");?></font>
		</h1>
		<p class="sous-titre">
			<strong>Gestion de fiches lectures</strong>
		</p>
			</div><!-- #entete -->			
			
			<div id="login-contenu">
				<?php
				
					
					// test si les variables sont définies
					if (isset($_POST['login']) && isset($_POST['pass'])) 
						{ 
							include("include/config.php");
							
							$login = addslashes($_POST['login']);
							$pass = addslashes(md5($_POST['pass']));
							
							$sql = "SELECT * FROM users WHERE login='$login' AND pass='$pass'";
							$requete = mysql_query( $sql, $cnx ) or die (mysql_error());
					
							if($result= mysql_fetch_object($requete))
							{
								session_start ();
								// enregistrement des paramètres de l'admin
								$_SESSION['login'] = $login; 
								$_SESSION['pass'] = $pass;
								$_SESSION['nom'] = $result->nom;
								$_SESSION['prenom'] = $result->prenom;
								
								// redirection vers l'accueil
								header ('location: home.php'); 
							} 
							else 
							{ 
								// pas reconnu
								print("<center>Nom d'utilisateur ou mot de passe incorrect!</center>"); 
								// redirection vers la page d'authentification
								//print '<meta http-equiv="refresh" content="0;URL=index.php">'; 
							}  
						}  
					else 
						{ 
							echo("");
						}  
				?> 	
				
				<center><div class="login"><b><font size="4" color="#ffffff">Connexion</font></b><br>
				<table>
					<form action="index.php" method="POST">
						<tr>
							<td><font color="#ADAD8E" size="1">Nom d'utilisateur : </font></td>
						</tr>
						<tr>
							<td><input autocomplete="off" class="login-input" type="text" name="login"></td>
						</tr>
						
						<tr>
							<td><br><font color="#ADAD8E" size="1">Mot de passe : </font></td>
						</tr>
						<tr>
							<td><input autocomplete="off" class="login-input" type="password" name="pass"></td>
						</tr>
						<br>
						<tr align="center">
							<td colspan="2"><br><input class="login-submit" type="submit" value="Connexion"><br><br></td>
						</tr>
					</form>
				</table></div>
				S'inscrire
				</center>
				
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