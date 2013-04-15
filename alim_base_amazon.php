<!DOCTYPE html>
<html>
	<head>
		<meta charset="iso-8859-1">
		
	</head>
<?php

include("include/config.php");
include("include/amazonAPI.php");

print("<center><i>Séléctionnez un item ou continuez la saisie manuelle</i></center><br>");
if (isset($_GET["q"]))
{	
	$obj = new AmazonProductAPI();
	
	try
	{
		$keyword = strtr($_GET['q'],'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
		$result = $obj->getItemByKeyword($keyword);
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}
	
	@$items = $result->Items->Item;
	
	print("<ul>");
	for($i=0; $i<count($items); $i++)
	{
		// if (strtolower($items[$i]->ItemAttributes->Title) == "le rouge et le noir")
		if ($i<5)
		{
			$titre = utf8_encode($items[$i]->ItemAttributes->Title);
			$auteur = utf8_encode($items[$i]->ItemAttributes->Author);
			echo "<li class=\"lienAmazon\" onClick=\"lienPreRemp(this);\"><a href=\"#\" id=\"{$titre}\">{$titre}</a>  <i>({$auteur})</i></li>";
		}
	}
	print("</ul>");
}
	
else
{
	$obj = new AmazonProductAPI();
	
	try
	{
		$keyword = strtr($_GET['titre'],'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
		$result = $obj->getItemByKeyword($keyword);
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}
	$items = $result->Items->Item;
	for($i=0; $i<count($items); $i++)
	{
		if (strtolower($items[$i]->ItemAttributes->Title) == "le rouge et le noir")
		{
			echo "<br>Titre : {$result->Items->Item->ItemAttributes->Title}<br>";
			echo "<br>Auteur : {$result->Items->Item->ItemAttributes->Author}<br>";
			echo "Pages : {$result->Items->Item->ItemAttributes->NumberOfPages}<br>";
			echo "Date de publication : {$result->Items->Item->ItemAttributes->PublicationDate}<br>";
			echo "Editeur : {$result->Items->Item->ItemAttributes->Publisher}<br>";
		}
	}
}

?>