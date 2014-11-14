<?php
	session_start();  
	if (!isset($_SESSION['login']) OR !isset($_SESSION['pass']) OR $_SESSION['login']!= "admin") 
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
	<link rel="stylesheet" type="text/css" href="styles/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="styles/style.css" media="all" />
	<link rel="stylesheet" type="text/css" href="styles/jquery.dataTables.css" >
	
	<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/bookCase.js"></script>
	<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>

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
			<img alt="" src="images/logo.png"/>
			<span><font color="#5F84BC" size="7">B</font>ook<font color="#5F84BC" size="7">C</font>ase</span> <font size="4"><?php include("include/version");?></font>
		</h1>
		<p class="sous-titre">
			<strong>Gestion de fiches lectures</strong>
		</p>
	</header><!-- #entete -->

	<div id="contenu">
		<aside id="menu"><h2>Menu</h2>
				<a href="./"><img src="styles/img/puce1.png" class="puce"> Accueil</a>
				<a href="livres.php"><img src="styles/img/puce2.png" class="puce"> Livres</a>
				<a href="fiche.php"><img src="styles/img/puce3.png" class="puce"> Mes fiches lecture</a>
				<a href="<?php print("profile.php?user=".$_SESSION['login'])?>"><img src="styles/img/puce4.png" class="puce"> Mon profil</a>
				<?php
					if($_SESSION['role']=='admin')
					{
						print("<a href=\"admin.php\" class=\"current\"><img src=\"styles/img/puce5.png\" class=\"puce\"> Administration</a>");
					}
				?>
			
		</aside><!-- #navigation -->
		<article>		
			<h2><img src="styles/img/icone_admin.png" width="25"> Administration</h2>
				
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Utilisateurs</a></li>
					<li><a href="#tabs-2">Livres</a></li>
					<li><a href="#tabs-3">Options</a></li>
				</ul>
				
				<div id="tabs-1">
					<?php
						if(isset($_POST['nom'], $_POST['prenom'], $_POST['reglogin'], $_POST['regpass'], $_POST['regpass2'], $_POST['role']))
						{
							$error = 1;
							if($_POST['regpass'] != $_POST['regpass2'])
							{
								$MSG = "Les deux mots de passe sont différents!";
							}
							else
							{
								/* utilisateur existe déjà*/
								$reqUserExistant = mysql_query("SELECT * FROM users WHERE login= '{$_POST['reglogin']}'");
								/* non */
								if(mysql_num_rows($reqUserExistant) == 0 || mysql_num_rows($reqUserExistant) == NULL)
								{
									$pass = MD5($_POST['regpass']);
									$nom = strtoupper($_POST['nom']);
									$prenom = ucfirst($_POST['prenom']);
									$reqNewUser = mysql_query("INSERT INTO users (login, nom, prenom, pass, role) 
																VALUES ('{$_POST['reglogin']}','{$nom}','{$prenom}','{$pass}','{$_POST['role']}')");
									if($reqNewUser)
									{
										$error = 0;
										$MSG = "Utilisateur ajouté avec succès!";
									}
									else
									{
										$MSG="Un problème est survenu lors de l'ajout!";
									}
								}
								/* oui */
								else
								{
									$MSG = "Ce nom d'utilisateur existe déjà!";
								}
							}
							if($error == 1)
							{
								print("<p id=\"notif\" class=\"notif-erreur\">".$MSG."<a id=\"notif-close\" href=\"javascript:notif_close();\" class=\"notif-close\">Close</a></p>");
							}
							else
							{
								print("<p id=\"notif\" class=\"notif-succes\">".$MSG."<a id=\"notif-close\" href=\"javascript:notif_close();\" class=\"notif-close\">Close</a></p>");
							}
						}
						
						/* Incriptions */
						if(isset($_POST['inscriptions']))
						{
							if($_POST['inscriptions'] == 'active')
							{
								$reqInscriptions = mysql_query("UPDATE options SET valeur=1 WHERE options.option='inscriptions'") or die (mysql_error());
							}
							else
							{
								$reqInscriptions = mysql_query("UPDATE options SET valeur=0 WHERE options.option = 'inscriptions'") or die (mysql_error());
							}
							if($reqInscriptions)
							{
								print("<p id=\"notif\" class=\"notif-succes\">Paramètre enregistré!<a id=\"notif-close\" href=\"javascript:notif_close();\" class=\"notif-close\">Close</a></p>");
							}
							else
							{
								print("<p id=\"notif\" class=\"notif-erreur\">Une erreur s'est produite!<a id=\"notif-close\" href=\"javascript:notif_close();\" class=\"notif-close\">Close</a></p>");
							}
						}
					?>
					<div id="msg_succes" class="notif-succes" style="display:none;"></div>
					<div id="msg_echec" class="notif-succes" style="display:none;"></div>
					
					<div class="accordion">
						<h3>Utilisateurs actifs</h3>
						<div id="UsersActifs">
							<table class="allBooks" id="tabUsersActifs">
								<thead>
								<tr>
									<th>Id</th>
									<th>Login</th>
									<th>Nom</th>
									<th>Prénom</th>
									<th>Role</th>
									<th>Actions</th>
								</tr>
								</thead><tbody>
								<?php
								$reqUsers = mysql_query("SELECT * FROM users WHERE actif=1 AND login!='admin'");
								while($user = mysql_fetch_object($reqUsers))
								{
									print("<tr>
											<td>{$user->id}</td>
											<td>{$user->login}</td>
											<td>{$user->nom}</td>
											<td>{$user->prenom}</td>
											<td>{$user->role}</td>
											<td><img src=\"images/suppr.jpg\" class=\"actions\" title=\"Supprimer l'utilisateur '{$user->login}'\"  onClick=\"javascript:supprime_user({$user->id});\"><img src=\"images/desactiv.png\" class=\"actions\" title=\"Désactiver l'utilisateur '{$user->login}'\" onClick=\"javascript:desactive_user({$user->id});\"></td>
										</tr>"
									);
								}
								?>
							</tbody></table>
							
							
							
						</div>
						<h3>Utilisateurs désactivés</h3>
						<div id="UsersDesactives">
							<table class="allBooks" id="tabUsersDésactives">
								<thead>
								<tr>
									<th>Id</th>
									<th>Login</th>
									<th>Nom</th>
									<th>Prénom</th>
									<th>Role</th>
									<th>Actions</th>
								</tr>
								</thead><tbody>
								<?php
								$reqUsers = mysql_query("SELECT * FROM users WHERE actif=0");
								while($user = mysql_fetch_object($reqUsers))
								{
									print("<tr>
											<td>{$user->id}</td>
											<td>{$user->login}</td>
											<td>{$user->nom}</td>
											<td>{$user->prenom}</td>
											<td>{$user->role}</td>
											<td><img src=\"images/suppr.jpg\" class=\"actions\" title=\"Supprimer l'utilisateur '{$user->login}'\" onClick=\"javascript:supprime_user({$user->id});\"><img src=\"images/activ.png\" class=\"actions\" title=\"Activer l'utilisateur '{$user->login}'\" onClick=\"javascript:active_user({$user->id});\"></td>
										</tr>"
									);
								}
								?>
							</tbody></table>
						</div>
						<h3>Ajouter un utilisateur</h3>
						<div id="UserAjouter">
							<table class="tableAdmin">							
								<form action="#" method="POST">
									<tr>
										<td><label>Nom : </label></td>
										<td><input autocomplete="off" type="text" name="nom"></td>
									</tr>
									<tr>
										<td><label>Prénom : </label></td>
										<td><input autocomplete="off" type="text" name="prenom"></td>
									</tr>
									<tr>
										<td><label>Nom d'utilisateur : </label></td>
										<td><input autocomplete="off" type="text" name="reglogin"></td>
									</tr>
									<tr>
										<td><label>Mot de passe : </label></td>
										<td><input autocomplete="off" type="password" name="regpass"></td>
									</tr>
									<tr>
										<td><label>Confirmer : </label></td>
										<td><input autocomplete="off" type="password" name="regpass2"></td>
									</tr>
									<tr>
										<td><label>Rôle : </label></td>
										<td><input type="radio" name="role" value="admin">Administrateur <br /> <input type="radio" name="role" value="user"> Utilisateur
									</tr>
									<tr>
										<td colspan="2" align="center"><input class="login-submit" type="submit" value="Ajouter"></td>
									</tr>
								</form>
							</table>
						</div>
					
					</div>
				</div>
				
				
				<div id="tabs-2">
					<div class="accordion">
						<h3>Livres approuvés</h3>
						<div id="livresApprouves">
							<table class="allBooks" id="tabUsersActifs">
								<thead>
								<tr>
									<th>Id</th>
									<th>Titre</th>
									<th>Auteur</th>
									<th>Editeur</th>
									<th>Nb Pages</th>
									<th>Genres</th>
									<th>Actions</th>
								</tr>
								</thead><tbody>
								<?php
								$reqLivresapprouves = mysql_query("SELECT livre.id AS idLivre, titre, nom, editeur, nbPages
																	FROM livre,ecritpar,auteur
																	WHERE actif=1
																		AND livre.id=ecritpar.idLivre
																		AND ecritpar.idAuteur=auteur.id");
								while($livre = mysql_fetch_object($reqLivresapprouves))
								{
									print("<tr>
											<td>{$livre->idLivre}</td>
											<td>{$livre->titre}</td>
											<td>{$livre->nom}</td>
											<td>{$livre->editeur}</td>
											<td>{$livre->nbPages}</td>
											<td>"
										);
									$reqGenre = mysql_query("SELECT * FROM genre,livregenre WHERE genre.id=livregenre.idGenre AND livregenre.idLivre='{$livre->idLivre}'");
									while($genre=mysql_fetch_object($reqGenre))
									{
										print($genre->libelle." ");
									}
									print("</td>
											<td><img src=\"images/suppr.jpg\" class=\"actions\" title=\"Supprimer le livre '{$livre->titre}'\" onClick=\"javascript:supprime_livre({$livre->idLivre});\"></td>
										</tr>"
									);
								}
								?>
							</tbody></table>
						</div>
						
						<h3>Livres à approuver</h3>
						<div id="livresAApprouver">
							<table class="allBooks" id="tabUsersActifs">
								<thead>
								<tr>
									<th>Id</th>
									<th>Titre</th>
									<th>Auteur</th>
									<th>Editeur</th>
									<th>Nb Pages</th>
									<th>Genres</th>
									<th>Actions</th>
								</tr>
								</thead><tbody>
								<?php
								$reqLivresDesapprouves = mysql_query("SELECT livre.id AS idLivre, titre, nom, editeur, nbPages
																	FROM livre,ecritpar,auteur
																	WHERE actif=0
																		AND livre.id=ecritpar.idLivre
																		AND ecritpar.idAuteur=auteur.id");
								while($livre = mysql_fetch_object($reqLivresDesapprouves))
								{
									print("<tr>
											<td>{$livre->idLivre}</td>
											<td>{$livre->titre}</td>
											<td>{$livre->nom}</td>
											<td>{$livre->editeur}</td>
											<td>{$livre->nbPages}</td>
											<td>"
										);
									$reqGenre = mysql_query("SELECT * FROM genre,livregenre WHERE genre.id=livregenre.idGenre AND livregenre.idLivre='{$livre->idLivre}'");
									while($genre=mysql_fetch_object($reqGenre))
									{
										print($genre->libelle." ");
									}
									print("</td>
											<td><img src=\"images/suppr.jpg\" class=\"actions\" title=\"Supprimer le livre '{$livre->titre}'\" onClick=\"javascript:supprime_livre({$livre->idLivre});\"><img src=\"images/activ.png\" class=\"actions\" title=\"Approuver le livre '{$livre->titre}'\" onClick=\"javascript:active_livre({$livre->idLivre});\"></td>
										</tr>"
									);
								}
								?>
							</tbody></table>
						</div>
					</div>
				</div>
				<div id="tabs-3">
					<div class="accordion">
						<h3>Inscriptions</h3>
						<div id="livresApprouves">
							<table class="tableAdmin">							
								<form action="#" method="POST">
									<tr>
										<td><label>Nouvelles inscriptions : </label></td>
										<td><input type="radio" name="inscriptions" value="active">Activer <br /> <input type="radio" name="inscriptions" value="desactive"> Désactiver</td>
									</tr>
									<tr>
										<td colspan="2" align="center"><input class="login-submit" type="submit" value="Enregitrer"></td>
									</tr>
								</form>
							</table>
						</div>
					</div>
				</div>
			</div>
		</article><!-- #contenu -->
	</div>
	<div style="display:none;" id="test">
		Supprimer l'utilisateur?<br>
		<button class="login-submit">Oui</button> <button class="login-submit">Non</button>
	</div>
	<footer>
		<?php include("include/footer"); ?>
	</footer>
</body>
</html>

<?php
	}
?>