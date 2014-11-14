$(document).ready( function() {
	/* Maintenir la hauteur du menu = à celle de l'article */
	$("#menu").height( $("#contenu").height() );
	
	/* Enlève le warning si JS est activé. Si JS est désactivé, le warning reste affiché*/
	$('.warningJS').hide();
	
	/* Validation du formulaire d'ajout d'un livre */
	$('#formAjoutLivre').submit(function(){
		if( $('#titre').val() == '' || $('#auteur_1').val() == '' || $('#cpt-genres').val() == '0')
		{
			alert("Merci de renseigner les champs obligatoires!");
			return false;
		}
		else
		{
			return true;
		}
	});
	
	/* Activation du datePicker pour dater les fiches lecture*/
	$('#mydate').datepicker();
	
	/* Activation des tooltips */
	$( document ).tooltip();
	
	/* Activation des onglets et des rubriques dans l'administration */
	$('#tabs').tabs();
	$('.accordion').accordion({ 
		active: 5, 
		collapsible: true,
		heightStyle: "content"
	});
	
	/* Afficher tous les livres dispo */
	$('#btn-allBooks').click(function(){
		$(location).attr('href',"allBooks.php");
	});
	
	/* Quand on clique sur le bouton "Rechercher" */
	$('#btn-rechercher').click(function(){
		$field = $('#q');
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
					$('#results').html('<center><img src="images/loading.gif" alt="loader" id="loader"/></center>'); // ajout d'un loader pour signifier l'action
				},
				success : function(data){ // traitements JS à faire APRES le retour de search.php
					$('#loader').remove(); // on enleve le loader
					$('#results').html(data); // affichage des résultats dans le bloc
					$("#menu").height($("#contenu").height());
				}
			});
		}		
	});

	/* Recherche d'un livre dans la base au fur et à mesure que l'on saisit dans la barre de recherche */
	$('#q').keyup( function(){	// détection de la saisie dans le champ de recherche
		$field = $(this);
		$('#results').html(''); // vide les resultats
		$('#loader').remove(); // retire le loader
	 
		// on commence à traiter à partir du 2ème caractère saisi
		if( $field.val().length > 1 )
		{
			// on envoie la valeur recherchée en GET au fichier de traitement
			$.ajax({
				type : 'GET', // envoi des données en GET ou POST
				url : 'search.php' , // url du fichier de traitement
				data : 'q='+$(this).val() , // données à envoyer en  GET ou POST
				beforeSend : function() { // traitements JS à faire AVANT l'envoi
					$('#results').html('<center><img src="images/loading.gif" alt="loader" id="loader"/></center>'); // ajout d'un loader pour signifier l'action
				},
				success : function(data){ // traitements JS à faire APRES le retour de search.php
					$('#loader').remove(); // on enleve le loader
					$('#results').html(data); // affichage des résultats dans le bloc
					$("#menu").height($("#contenu").height());
				}
			});
		}		
	});
	
	/* Recherche de propositions sur Amazon lors de l'ajout d'un livre au fur et à mesure que l'on saisit dans le champs 'titre'*/
	$('#titre').keyup( function(){
		$field = $(this);
		$('#propositions').html(''); // on vide les resultats
		$('#propositions').slideUp(1);
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
					$('#propositions').slideDown(300);
				}
			});
		}
	});

	/* Recherche des auteurs au fur et à mesure de la saisie */
	$("#auteur_1").autocomplete({
		source: "searchAuteur.php",
		minLength: 2,
		select: function(data, ui) {
			$(this).val(data);
			//alert(ui.item.value);
			$donnee = ui.item.value;
			$.ajax({
				type : 'GET', // envoi des données en GET
				url : 'searchGenre.php' , // url du fichier de traitement
				data : 'auteur='+$donnee , // données à envoyer
				beforeSend : function() { // traitements JS à faire AVANT l'envoi
					$('#loaderGenre').show(); // ajout d'un loader pour signifier l'action
				},
				success : function(datas){ // traitements JS à faire APRES le retour de search.php
					$('#loaderGenre').hide();
					//var tmp = datas.split(",");
					var genre = jQuery.parseJSON(datas);
					$.each(genre, function(i, val){
						//alert(val);
						var id = $('#cpt-genres').val();
						id++;
						$('<input>').attr({
							type: 'hidden',
							id: 'genre_'+id,
							name: 'genre_'+id,
							value: val
						}).appendTo('#formAjoutLivre');
						$('#genresPreSelec').append("<div class=\"genres\" id=\"genre_"+id+"_div\"><a class=\"genre-suppr\" onClick=\"javascript:supprime_genre('genre_"+id+"');\">X</a>"+val+"</div>");
						$('#cpt-genres').val(id);
					});
				}
			});
		}
	});
	
	/* Activation de l'autocompletion pour les genres */
	$('#genreAdd').autocomplete({
				source: "searchGenre.php",
				minLength: 2,
				select: function(data) {
					$(this).val(data);
				}
			});
	
	/* Activation des dataTables */
	var oTable = $('.allBooks').dataTable({
		"sPaginationType": "full_numbers",
		"iDisplayLength": 5,
		"aLengthMenu": [[3, 5, 10, -1], [3, 5, 10, "Tout"]],
		"oLanguage" : {
			"sProcessing": "Chargement...",
			"sLengthMenu": "Afficher _MENU_ enregistrements",
			"sZeroRecords": "No matching records found",
			"sInfo": "Enregistrements _START_ à _END_ sur _TOTAL_",
			"sInfoEmpty": "Page 0 de 0 sur 0 entries",
			"sInfoFiltered": "(filtrer sur _MAX_ total enregistrements)",
			"sInfoPostFix": "",
			"sSearch": "Recherche:",
			"sUrl": "",
			"oPaginate": {
				"sFirst":    "Premier",
				"sPrevious": "Precedent",
				"sNext":     "Suivant",
				"sLast":     "Dernier"
			}
		}
	});
});

/* Ouverture du formulaire d'ajout de livre */
function ajout_livre_ouvrir(){
	$('#ajoutLivre').slideDown(300);
	var val = $('#q').val();
	$('#titre').val(val);
	$("#menu").height( $("#contenu").height() );
}

/* Fermeture du formulaire d'ajout de livres */
function ajout_livre_fermer(){
	$('#ajoutLivre').slideUp(300);
	$('#titre').val("");
	$('#auteur_1').val("");
	$('#editeur').val("");
	$('#nbPages').val("");
	$('#annee').val("");
	$('#imgLivre').html("");
	$('#img-Livre').val("");
	$('#resume-Livre').val("");
}

/* Relancer la recherche Amazon */
function rechAmazon()
{
	$('#auteur_1').val("");
	$('#editeur').val("");
	$('#nbPages').val("");
	$('#annee').val("");
	$('#imgLivre').html("");
	$('#img-Livre').val("");
	$('#resume-Livre').val("");
	$('#genresPreSelec').html("");
	$field = $('#titre');
	$('#propositions').html(''); // on vide les resultats
	$('#propositions').slideUp(1);
	$('#loader2').hide();
	// on commence à traiter à partir du 2ème caractère saisi
	if( $field.val().length > 1 )
	{
		// on envoie la valeur recherchée en GET au fichier de traitement
		$.ajax({
			type : 'GET', // envoi des données en GET ou POST
			url : 'alim_base_amazon.php' , // url du fichier de traitement
			data : 'q='+$field.val() , // données à envoyer en  GET ou POST
			beforeSend : function() { // traitements JS à faire AVANT l'envoi
				$('#loader2').show(); // ajout d'un loader pour signifier l'action
			},
			success : function(data){ // traitements JS à faire APRES le retour de search.php
				$('#loader2').hide();
				$('#propositions').html(data); // affichage des résultats dans le bloc
				$('#propositions').slideDown(300);
			}
		});
	}
}

/* Ouverture du formulaire d'ajout d'une fiche lecture */
function ajout_fiche_ouvrir(id){
	$('#ajoutFiche').show("slow");
	$('#id_livre').val(id);
	$('#mydate').glDatePicker().delay(1000);
}

/* fermeture du formulaire d'ajout d'une fiche lecture */
function ajout_fiche_fermer(){
	$('#ajoutFiche').hide("slow");
}

/* Pré-remplissage du formulaire d'ajout de livre => Quand l'utilisateur clique sur une proposition Amazon */
function lienPreRemp(titre){
	//alert(titre);
	$('#propositions').slideUp(300);
	$.ajax({
		type : 'GET', // envoi des données en GET ou POST
		dataType:"json",
		url : 'alim_base_amazon.php' , // url du fichier de traitement
		data : 'titre='+(titre) , // données à envoyer en  GET ou POST
		beforeSend: function(){
			$('#loader2').show();
		},
		success : function(data){		// traitements JS à faire APRES le retour de search.php
			// alert(data.titre);
			$('#loader2').hide();
			$('#titre').val(data.titre);
			$('#auteur_1').val(data.auteur);
			$('#editeur').val(data.editeur);
			$('#nbPages').val(data.nbPages);
			$('#annee').val(data.annee);
			//$('#propositions').fadeOut("slow");
			$('#imgLivre').html("<center><img src=\""+data.image+"\" height=\"170\"></center>");
			$('#img-Livre').val(data.image);
			$('#resume-Livre').val(data.resume);
			$auteur = $('#auteur_1').val();
			$.ajax({
				type : 'GET', // envoi des données en GET
				url : 'searchGenre.php' , // url du fichier de traitement
				data : 'auteur='+$auteur , // données à envoyer
				beforeSend : function() { // traitements JS à faire AVANT l'envoi
					$('#loaderGenre').show(); // ajout d'un loader pour signifier l'action
				},
				success : function(datas){ // traitements JS à faire APRES le retour de search.php
					$('#loaderGenre').hide();
					//var tmp = datas.split(",");
					var genre = jQuery.parseJSON(datas);
					$.each(genre, function(i, val){
						//alert(val);
						var id = $('#cpt-genres').val();
						id++;
						$('<input>').attr({
							type: 'hidden',
							id: 'genre_'+id,
							name: 'genre_'+id,
							value: val
						}).appendTo('#formAjoutLivre');
						$('#genresPreSelec').append("<div class=\"genres\" id=\"genre_"+id+"_div\"><a class=\"genre-suppr\" onClick=\"javascript:supprime_genre('genre_"+id+"');\">X</a>"+val+"</div>");
						$('#cpt-genres').val(id);
					});

				}
			});
         }
    });
}

/* Suppression d'une fiche lecture */
function supprime_fiche_livre(idLivre,idUser,titre,date)
{
	var confirmation = confirm( "Livre : "+titre+"\nLu le : "+date+"\n\nVoulez vous vraiment supprimer cette fiche ?" ) ;
	if( confirmation )
		{
			document.location.href = "fiche.php?idLivre="+idLivre+"&idUser="+idUser ;
		}
}

/* Ajout de genres lors de l'ajout d'un livre */
function ajoute_genre()
{
	var val = $('#genreAdd').val();
	if(val != '')
	{
		var i = $('#cpt-genres').val();
		i++;
		var id = "genre_"+i;
		$('<input>').attr({
			type: 'hidden',
			id: id,
			name: id,
			value: val
		}).appendTo('#formAjoutLivre');
		$('#genresPreSelec').append("<div class=\"genres\" id=\""+id+"_div\"><a class=\"genre-suppr\" onClick=\"javascript:supprime_genre('"+id+"');\">X</a>"+val+"</div>");
		$('#cpt-genres').val(i);
		$('#genreAdd').val('');
	}
}

/* Suppression d'un genre lors de l'ajout d'un livre */
function supprime_genre(id)
{
	$('#'+id).remove();
	$('#'+id+'_div').remove();
}

/* Fermeture des notifications */
function notif_close()
{
	$('#notif').hide('slow');
}

/* Ajout automatique de champs auteurs */
function ajouter_auteur(n)
{
	var n1 = n-1;
	var n2 = n+1;
	if($('#auteur_'+n1).val() != '')
	{
		if(!$('#ligne_auteur_'+n).length)
		{
			$('#ligne_auteur_'+n1).after("<tr id=\"ligne_auteur_"+n+"\"><td>Auteur "+n+"</td><td><input type=\"text\" class=\"auteur\" id=\"auteur_"+n+"\" name=\"auteur_"+n+"\" onkeyUp=\"javascript:ajouter_auteur("+n2+");\"></td></tr>");
			$('#cpt-auteurs').val(n);
			$('#auteur_'+n).autocomplete({
				source: "searchAuteur.php",
				minLength: 2,
				select: function(data) {
					$(this).val(data);
				}
			});
		}
	}
	else
	{
		if($('#ligne_auteur_'+n).length && $('#auteur_'+n).val().length == 0)
		{
			$('#ligne_auteur_'+n).remove();
		}
	}
}

/* Actions dans l'administration */
//confirmation des actions
function confirme_action()
{
	if(confirm("Etes-vous sûr?"))
	{
		return true;
	}
	else{
		return false;
	}
	
}
function supprime_user(id)
{
	//alert(id);
	if(confirme_action())
	{
		$.ajax({
			type : 'POST',
			url : 'admin_req.php' ,
			data : 'userDelete='+(id) ,
			success : function(data){
				$('#msg_succes').html("Utilisateur supprimé! <a href=\"javascript:location.reload()\">Raffraichir</a>");
				$('#msg_succes').fadeIn(300);
			}
		});
	}
}

function desactive_user(id)
{
	if(confirme_action())
	{
		$.ajax({
			type : 'POST',
			url : 'admin_req.php' ,
			data : 'userDisable='+(id) ,
			success : function(data){
				$('#msg_succes').html("Utilisateur désactivé! <a href=\"javascript:location.reload()\">Raffraichir</a>");
				$('#msg_succes').fadeIn(300);
			}
		});
	}
}

function active_user(id)
{
	if(confirme_action())
	{
		$.ajax({
			type : 'POST',
			url : 'admin_req.php' ,
			data : 'userEnable='+(id) ,
			success : function(data){
				$('#msg_succes').html("Utilisateur désactivé! <a href=\"javascript:location.reload()\">Raffraichir</a>");
				$('#msg_succes').fadeIn(300);
			}
		});
	}
}

function supprime_livre(id)
{
	if(confirme_action())
	{
		$.ajax({
			type : 'POST',
			url : 'admin_req.php' ,
			data : 'livreDelete='+(id) ,
			success : function(data){
				$('#msg_succes').html("Livre supprimé! <a href=\"javascript:location.reload()\">Raffraichir</a>");
				$('#msg_succes').fadeIn(300);
			}
		});
	}
}

function active_livre(id)
{
	if(confirme_action())
	{
		$.ajax({
			type : 'POST',
			url : 'admin_req.php' ,
			data : 'livreEnable='+(id) ,
			success : function(data){
				$('#msg_succes').html("Livre Approuvé! <a href=\"javascript:location.reload()\">Raffraichir</a>");
				$('#msg_succes').fadeIn(300);
			}
		});
	}
}