$( document ).ready(function() {
	
	//Récupérer les dernières activités
	setInterval(function() {
		
		var json = {"last_id_activite": $("#last_id_activite").attr("value")};
		
		request = $.ajax({
			url: "../select_json/live_activite.php",
			type: "post",
			data: json,
			success : function(response){
				
			var tab_act = $.parseJSON(response);
			
				for (var i = 0; i < tab_act.length; i++) {
					if(i==0){
						$("#data_live").css("background-color","#cacaca");
						$("#data_live").animate({backgroundColor: "#495663"}, 1000);
						$("#last_id_activite").attr("value", tab_act[i]["id_activite"]);
					}					
					$("#data_live").prepend("<p><span class=\"green_text_grey_bg text_space_right\">"+tab_act[i]["label"]+"</span> <span class=\"text_space_right\"><i>Date de début :</i> '"+tab_act[i]["date_debut"]+"'</span> <i>Date de fin :</i> '"+tab_act[i]["date_fin"]+"'</p>");
				}
			}
		});
	}, 2000);
	
	//Récupérer les dernières anomalies sonores
	setInterval(function() {

		var json = {"last_id_as": $("#last_id_as").attr("value")};
		
		request = $.ajax({
			url: "../select_json/live_as.php",
			type: "post",
			data: json,
			success : function(response){
				
				var tab_as = $.parseJSON(response);
				
				for (var i = 0; i < tab_as.length; i++) {
					if(i==0){
						$("#data_live").css("background-color","#cacaca");
						$("#data_live").animate({backgroundColor: "#495663"}, 1000);
						$("#last_id_as").attr("value", tab_as[i]["id_as"]);
					}	
					$("#data_live").prepend("<p><span class=\"red_text_grey_bg text_space_right\">Anomalie sonore</span> <span class=\"text_space_right\"><i>Date de début :</i> '"+tab_as[i]["date_debut"]+"'</span> <span class=\"text_space_right\"><i>Date de fin :</i> '"+tab_as[i]["date_fin"]+"'</span> <i>Niveau sonore :</i> "+tab_as[i]["niveau"]+" dB</p>");
				}
			}
		});
		
	}, 2000);
	
});