$( document ).ready(function() {
 
	$( ".display_tab" ).click(function() {
		
		var div_table = $(this).parent().parent().next();
		
		if( div_table.is(":hidden") ){
			div_table.slideDown("fast");
			$(this).html("Masquer tableau");
		}
		else{
			div_table.slideUp("fast");
			if($(this).attr("id")=="btn_last_act") $(this).html("Afficher les "+$(this).next().attr("value")+" dernières activités");
			if($(this).attr("id")=="btn_last_ano") $(this).html("Afficher les "+$(this).next().attr("value")+" dernières anomalies sonores");
			if($(this).attr("id")=="btn_cas") $(this).html("Afficher tableau");
		}
		
	});
	
	$( ".display_form" ).click(function() {
		
		var div_table = $(this).next();
		
		if( div_table.is(":hidden") ){
			div_table.slideDown("fast");
			$(this).html("Masquer le formulaire");
		}
		else{
			div_table.slideUp("fast");
			$(this).html("Afficher le formulaire");
		}
		
	});
	
	//Avoir les datetimepicker en fr
	$.datetimepicker.setLocale('fr');
	
	$('.DTpicker').datetimepicker({
		formatTime:'H:i',
		format: "d/m/Y H:i:00", /*Supprimer les secondes */
		step:30
	});
	
	$('.Tpicker').datetimepicker({
		datepicker:false,
		formatTime:'H:i',
		format: 'H:i:00', /*Supprimer les secondes */
		step:30
	});
	
});

$(document).on('click', '.delete_cas_row', function(e) {
	$(this).closest("tr").remove();
});

$(document).on('click', '#add_cas_row', function(e) {
	
	$(this).parent().parent().find("tbody").append('<tr><td class="id_cas"><input type="text" value="-1" name="id_conf_as[]"/></td><td><input type="text" placeholder="--:--:--" name="heure_debut[]" class="u-full-width newTpicker"/></td><td><input type="text" placeholder="--:--:--" name="heure_fin[]" class="u-full-width newTpicker"/></td><td><input type="text" placeholder="entier positif" name="seuil[]" class="u-full-width"/></td><td><input type="text" placeholder="entier positif" name="duree[]" class="u-full-width"/></td><td><button class="delete_cas_row red_btn" type="button">Supprimer</button></td></tr>');
	$('.newTpicker').datetimepicker({
		datepicker:false,
		formatTime:'H:i',
		format: 'H:i:00', /*Supprimer les secondes */
		step:30
	});
	$('.newTpicker').addClass('Tpicker').removeClass('newTpicker');
});