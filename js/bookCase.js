$(document).ready( function() {
	
	$("#notif-close").click(function(){
		$("#notif").hide('slow');
	});
	
	/* Recherche d'un livre dans la base */
	$('#q').keyup( function(){	// détection de la saisie dans le champ de recherche
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
	
	/* Maintenir la hauteur du menu = à celle de l'article */
	$("#menu").height( $("#contenu").height() );
	
	
	/* Recherche d'éléments sur Amazon lors de l'ajout d'un livre */
	$('#titre').keyup( function(){
		$field = $(this);
		$('#propositions').html(''); // on vide les resultats
		$('#loader2').hide();
	 
		// on commence à traiter à partir du 2ème caractère saisi
		if( $field.val().length > 1 )
		{
			// on envoie la valeur recherchée en GET au fichier de traitement
			$.ajax({
				type : 'GET', // envoi des données en GET ou POST
				url : 'alim_base_amazon.php' , // url du fichier de traitement
				data : 'q='+$(this).val() , // données à envoyer en  GET ou POST
				beforeSend : function() { // traitements JS à faire AVANT l'envoi
					$('#loader2').show(); // ajout d'un loader pour signifier l'action
				},
				success : function(data){ // traitements JS à faire APRES le retour de search.php
					$('#loader2').hide();
					$('#propositions').html(data); // affichage des résultats dans le bloc
				}
			});
		}
	});
	
	$('.lienAmazon').click( function(){
		alert("ok");
	});

});

/*function lienPreRemp(titre){
	val = titre.id;
	alert(val);
	//$('#propositions').html(val);
}*/

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

function ajout_livre_ouvrir(){
	$('#ajoutLivre').toggle("slow");
	var val = $('#q').val();
	$('#titre').val(val);
}

function ajout_livre_fermer(){
	$('#ajoutLivre').hide("slow");
}

function ajout_fiche_ouvrir(){
	$('#ajoutFiche').show("slow");
}

function ajout_fiche_fermer(){
	$('#ajoutFiche').hide("slow");
}

function liste_livres(){
	$('#listeLivres').toggle("slow");
}