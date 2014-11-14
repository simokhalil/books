<?php

function aws_signed_request($region, $params, $public_key, $private_key, $associate_tag)
{
	
	/* Définition des paramètres selon Amazon:
        $region - the Amazon(r) region (ca,com,co.uk,de,fr,co.jp)
        $params - an array of parameters, eg. array("Operation"=>"ItemLookup",
                        "ItemId"=>"B000X9FLKM", "ResponseGroup"=>"Small")
        $public_key - your "Access Key ID"
        $private_key - your "Secret Access Key"
        $version (optional)
    */

    /* Paramètres */
    $method = "GET";
    $host = "ecs.amazonaws.".$region;
    $uri = "/onca/xml";
    
    $params['Service'] 			= "AWSECommerceService";
    $params['AWSAccessKeyId'] 	= $public_key;
    $params["Timestamp"]        = gmdate("Y-m-d\TH:i:s\Z");
    $params["Version"]          = "2009-03-31";
    if ($associate_tag !== NULL) {
        $params['AssociateTag'] = $associate_tag;
    }
    
    /* Les paramètres doivent être triés, sinon le hash généré par Amazon est corrompu */
    ksort($params);
    
    $canonicalized_query = array();
    foreach ($params as $param=>$value)
    {
        $param = str_replace("%7E", "~", rawurlencode($param));
        $value = str_replace("%7E", "~", rawurlencode($value));
        $canonicalized_query[] = $param."=".$value;
    }
    $canonicalized_query = implode("&", $canonicalized_query);
    
    /* Construction de la chaine à signer */
    $string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;
    
    /* calcul de la signature en utisant HMAC avec SHA256 et base64-encoding
		La fonction 'hash_hmac' n'est disponible que depuis PHP 5.1.2 */
    $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $private_key, True));
    
    /* encode la signature pour la requête */
    $signature = str_replace("%7E", "~", rawurlencode($signature));
    
    /* Construction du lien de la requête */
    $request = "http://".$host.$uri."?".$canonicalized_query."&Signature=".$signature;
    
	//print($request);
	
	/* Extracion du contenu du fichier XML retourné par Amazon */
	@$xml_response = file_get_contents($request);
    
    if ($xml_response === False)
    {
        return False;
    }
    else
    {
        /* parse XML */
        $parsed_xml = @simplexml_load_string($xml_response);
        return ($parsed_xml === False) ? False : $parsed_xml;
    }
}


/***************************************************************
****************************************************************
***************************************************************/


class AmazonProductAPI
    {
        private $public_key     = "AKIAI2TF2KI45DGSBQAQ";
        private $private_key    = "Q0C3djnRYxut3qGa5nGETZpIl2PRt/wD0GeTtivm";
        private $associate_tag  = " ";
    
        
        /*Seule la catégorie "Books" nous intéresse. Liste des catégories ici:
            http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/APPNDX_SearchIndexValues.html
        */
		const BOOKS = "Books";
        
        
        /**
         * Vérifie si le code XML reçu d'Amazon est valide
         */
        private function verifyXmlResponse($response)
        {
            if ($response === False)
            {
                throw new Exception("<center style=\"color:#ff0000;font-weight:bold;\">Connexion &agrave; Amazon impossible!</center>");
            }
            else
            {
                if (isset($response->Items->Item->ItemAttributes->Title))
                {
                    return ($response);
                }
                else
                {
                    throw new Exception("<center>Pas de propositions disponible!</center><br>");
                }
            }
        }        
        
        private function queryAmazon($parameters)
        {
            return aws_signed_request("fr", $parameters, $this->public_key, $this->private_key, $this->associate_tag);
        }
        
        /**
         * Return les détails du livre recherché
         */
        public function searchProducts($search)
        {
            $parameters = array("Operation"	=> "ItemSearch",
								"Title"         => $search,
								"SearchIndex"   => "Books",
								"ResponseGroup" => "Medium");

            $xml_response = $this->queryAmazon($parameters);
            
            return $this->verifyXmlResponse($xml_response);

        }
		
		/**
         * Return details of a product searched by keyword
         * 
         * @param string $keyword keyword to search
         * @param string $product_type type of the product
         * @return mixed simpleXML object
         */
        public function getItemByKeyword($keyword)
        {
            $parameters = array("Operation"   => "ItemSearch",
                                "Keywords"    => $keyword,
                                "SearchIndex" => "Books",
								"ResponseGroup" => "Large");
                                
            $xml_response = $this->queryAmazon($parameters);
            
            return $this->verifyXmlResponse($xml_response);
        }
	}

/*******************************************************************
********************************************************************
*******************************************************************/

/*
    $obj = new AmazonProductAPI();
    
    try
    {
        /*$result = $obj->searchProducts("les miserables",
                                       AmazonProductAPI::BOOKS,
                                       "TITLE");*/
/*		$result = $obj->getItemByKeyword("miserables");
		
		print($result);
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
    
    //print_r($result);
	
	echo "<br>Titre : {$result->Items->Item->ItemAttributes->Title}<br>";
	echo "<br>Lien : <a href=\"{$result->Items->Item->DetailPageURL}\">Voir sur Amazon</a><br>";
    echo "<br><br>Auteur : {$result->Items->Item->ItemAttributes->Author}<br>";
	echo "<br><br>Prix : {$result->Items->Item->ItemAttributes->ListPrice->FormattedPrice} / {$result->Items->Item->OfferSummary->LowestNewPrice->FormattedPrice}<br>";
	echo "<br><br>Pages : {$result->Items->Item->ItemAttributes->NumberOfPages}<br>";
	echo "Date de publication : {$result->Items->Item->ItemAttributes->PublicationDate}<br>";
	echo "Editeur : {$result->Items->Item->ItemAttributes->Publisher}<br>";
    echo "Classement des meilleures ventes Amazon : {$result->Items->Item->SalesRank} ventes<br><br><br><br><br>";
	echo "Résumé : {$result->Items->Item->EditorialReviews->EditorialReview->Content}<br><br>";
    echo "ASIN : {$result->Items->Item->ASIN}<br>";
	echo "<br><img src=\"" . $result->Items->Item->SmallImage->URL . "\" />";
	echo "<br><img src=\"" . $result->Items->Item->MediumImage->URL . "\" />";
    echo "<br><img src=\"" . $result->Items->Item->LargeImage->URL . "\" /><br>";
    
*/
?>