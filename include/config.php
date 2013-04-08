<?php
	
	$host="localhost";
	$user="root";
	$password="Simokhalil1809";
	$base="book";
	
	
	//connection au serveur
	$cnx = mysql_connect( $host, $user, $password ) or die ("<b>Erreur de connexion</b><br>".mysql_error()) ;
	
	//sélection de la base de données:
	$db  = mysql_select_db($base,$cnx) or die ("<b>Erreur de sélection</b><br>".mysql_error()) ;
?>