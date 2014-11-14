<?php
	$host="localhost";
	$user="root";
	$password="abcd1234";
	$base="test";

	$cnx = mysql_connect($host, $user, $password );

	$db  = mysql_select_db($base,$cnx);
	
?>