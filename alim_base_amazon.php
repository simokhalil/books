<?php

include("include/config.php");
include("include/amazonAPI.php");


if (isset($_GET["q"]))
{	
	$obj = new AmazonProductAPI();
	
	try
	{
		$keyword = strtr($_GET['q'],'àáâãäçèéêëìíîïñòóôõöùúûüıÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜİ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
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
		if ($i<5)
		{
			$titre = utf8_decode($items[$i]->ItemAttributes->Title);
			$auteur = $items[$i]->ItemAttributes->Author;
			$idTitre = strtr($titre,'àáâãäçèéêëìíîïñòóôõöùúûüıÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜİ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
			echo "<li class=\"lienAmazon\"><a href=\"#\" onClick=\"javascript:lienPreRemp(this.id);\" id=\"".$idTitre."\">".utf8_encode($titre)."</a>  <i>({$auteur})</i></li>";
		}
	}
	print("</ul>");
}
	
else
{
	$obj = new AmazonProductAPI();
	
	try
	{
		$titre = $_GET['titre'];
		$result = $obj->searchProducts($titre); //on effectue la recherche sur Amazon par titre
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}
	$items = $result->Items->Item->ItemAttributes;
	$anneeProd = explode('-', $items->PublicationDate);
	
	if(isset($result->Items->Item->EditorialReviews->EditorialReview->Content))
	{
		$tab = array('titre'=> htmlspecialchars($items->Title), 'auteur'=>"{$items->Author}", 'editeur'=>"{$items->Publisher}", 'nbPages'=>"{$items->NumberOfPages}", 'annee'=>"{$anneeProd[0]}", 'image'=>"{$result->Items->Item->LargeImage->URL}", 'resume'=>"{$result->Items->Item->EditorialReviews->EditorialReview->Content}");
	}
	else{
		$tab = array('titre'=> htmlspecialchars($items->Title), 'auteur'=>"{$items->Author}", 'editeur'=>"{$items->Publisher}", 'nbPages'=>"{$items->NumberOfPages}", 'annee'=>"{$anneeProd[0]}", 'image'=>"{$result->Items->Item->LargeImage->URL}", 'resume'=>"");
	}
	echo json_encode($tab);
			// echo $result->Items->Item->ItemAttributes->Title;
			// echo "<br>Auteur : {$result->Items->Item->ItemAttributes->Author}<br>";
			// echo "Pages : {$result->Items->Item->ItemAttributes->NumberOfPages}<br>";
			// echo "Date de publication : {$result->Items->Item->ItemAttributes->PublicationDate}<br>";
			// echo "Editeur : {$result->Items->Item->ItemAttributes->Publisher}<br>";
}
?>