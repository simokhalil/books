<?php
	
	$host="localhost";
	$user="root";
	$password="Simokhalil1809";
	$base="book";
	
	
	//connection au serveur
	$cnx = mysql_connect( $host, $user, $password ) or die ("<b>Erreur de connexion</b><br>".mysql_error()) ;
	
	//s�lection de la base de donn�es:
	$db  = mysql_select_db($base,$cnx) or die ("<b>Erreur de s�lection</b><br>".mysql_error()) ;
?>