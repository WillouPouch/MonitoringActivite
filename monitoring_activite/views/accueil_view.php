<?php

session_start();

ini_set('display_errors', 1);

include_once("../utils/bdd_connection.php");
include_once("../utils/static_html.php");
include_once("../objects/activite_display.php");
include_once("../objects/anomalie_sonore_display.php");
include_once("../objects/conf_anomalie_sonore_display.php");
include_once("../objects/activite_manager.php");
include_once("../objects/anomalie_sonore_manager.php");

/*---*/
/*VAR*/
/*---*/
$nb_activite = 5;
$nb_anomalie_sonore = 5;

$activite_manager = new ActiviteManager($db);
$anomalie_sonore_manager = new AnomalieSonoreManager($db);

$activite_display = new ActiviteDisplay($db);
$anomalie_sonore_display = new AnomalieSonoreDisplay($db);
$cas_display = new ConfAnomalieSonoreDisplay($db);

$tab_tv_tranche_horaire = $activite_manager->get_today_tv_tranche_horaire();
$tab_zone = $activite_manager->get_today_zone();
$tab_anomalie_sonore = $anomalie_sonore_manager->get_last($nb_anomalie_sonore);

/*---------------------*/
/*DEBUT DE FICHIER HTML*/
/*---------------------*/
start_html("Monitoring d'activité", true);

echo '<input type="hidden" id="last_id_activite" value="'.$activite_manager->get_last_id_activite().'">';
echo '<input type="hidden" id="last_id_as" value="'.$anomalie_sonore_manager->get_last_id_as().'">';

/*----------------*/
/*Données en live */
/*----------------*/

echo '<h3 class="u-full-width">Données en direct</h3>
<div id="data_live" class="u-full-width">
<p>Les nouvelles données s\'affichent ici !</p>
</div>';

/*------------------*/
/*VARIABLES DU JOUR */
/*------------------*/
echo '<h3 class="u-full-width">Résumé du jour</h3>';

echo '<div class="row">
	<div class="six columns">
		<h6 class="thin-border">Temps passé devant la <span class="red_text">télévision</span> aujourd\'hui</h6>
		<p class="board_var">';
$seconds = 0;
foreach($tab_tv_tranche_horaire as $tranche_horaire ){
	$seconds += $tranche_horaire["total_sec"];
}
$hours = floor($seconds / 3600);
$mins = floor($seconds / 60 % 60);
$secs = floor($seconds % 60);
echo ($hours<10?"0":"").$hours.":".($mins<10?"0":"").$mins.":".($secs<10?"0":"").$secs;	
echo '</p>
	</div>
	<div class="six columns">
		<h6 class="thin-border">Temps passé dans le <span class="red_text">salon</span> aujourd\'hui</h6>
		<p class="board_var">';
$tps_salon = "00:00:00";
foreach($tab_zone as $zone ){
	if($zone["type_activite"]=="salon") $tps_salon = $zone["total_time"];
}
echo $tps_salon;
echo '</p>
	</div>
</div>';

echo '<div class="row">
	<div class="six columns">
		<h6 class="thin-border">Temps passé dans la <span class="red_text">cuisine</span> aujourd\'hui</h6>
		<p class="board_var">';
$tps_cuisine = "00:00:00";
foreach($tab_zone as $zone ){
	if($zone["type_activite"]=="cuisine") $tps_cuisine = $zone["total_time"];
}
echo $tps_cuisine;
echo '</p>
	</div>
	<div class="six columns">
		<h6 class="thin-border">Temps passé dans la <span class="red_text">chambre</span> aujourd\'hui</h6>
		<p class="board_var">';
$tps_cuisine = "00:00:00";
foreach($tab_zone as $zone ){
	if($zone["type_activite"]=="chambre") $tps_cuisine = $zone["total_time"];
}
echo $tps_cuisine;
echo '</p>
	</div>
</div>';


echo '<div class="row">
	<div class="six columns">
		<h6 class="thin-border"><span class="red_text">Nombre d\'anomalies sonores</span> aujourd\'hui</h6>
		<p class="board_var">';
echo $anomalie_sonore_manager->get_today_nb_as();	
echo '</p>
	</div>
	<div class="six columns">
		<h6 class="thin-border"><span class="red_text">Niveau moyen</span> des anomalies sonores du jour</h6>
		<p class="board_var">';
$mean_lvl_as = $anomalie_sonore_manager->get_today_mean_lvl_as();
echo (empty($mean_lvl_as)?"0":$mean_lvl_as) . " dB";	
echo '</p>
	</div>
</div>';

/*-------------------*/
/*DERNIERES ACTIVITES*/
/*-------------------*/
//Boutons ------------
echo'
	<h3 class="u-full-width">Activités</h3>
	<div class="row">
		<div class="six columns">
			<a href="/monitoring_activite/views/activite_view.php"><button class="u-full-width">Visualiser toutes les activités</button></a>
		</div>
		<div class="six columns">
			<button id="btn_last_act" class="u-full-width button-primary display_tab">Afficher les '.$nb_activite.' dernières activités</button>
			<input type="hidden" value="'.$nb_activite.'">
		</div>
	</div>';
//Tableau ------------
$activite_display->get_last_activite($nb_activite, "display_none");

/*----------*/
/*GRAPHIQUES*/
/*----------*/

foreach($tab_tv_tranche_horaire as $tranche_horaire ){
	
	echo '<input class="graph_tv_data" type="hidden" name="';
	if($tranche_horaire["heure_debut"]=="00:00:00") echo '00h-08h"';
	else if($tranche_horaire["heure_debut"]=="08:00:00") echo '08h-12h"';
	else if($tranche_horaire["heure_debut"]=="12:00:00") echo '12h-18h"';
	else if($tranche_horaire["heure_debut"]=="18:00:00") echo '18h-00h"';
	echo 'value="'.($tranche_horaire["total_sec"]/60).'">';
	
}
$total_sec = 0;
foreach($tab_zone as $zone ) $total_sec += $zone["total_sec"];
foreach($tab_zone as $zone ){
	echo '<input class="graph_zone_data" type="hidden" name="'.$zone["type_activite"].'" value="'.(($zone["total_sec"]/$total_sec)*100).'">';
}

echo '
	<br/>
	<div class="row">
		<!-- Graph TV (colonne) -->
		<div class="six columns thin-border">
			<div id="graph_tv" style="width: 100%; height: auto; margin: 0 auto;"></div>
		</div>
		<!-- Graph Zone (camembert) -->
		<div class="six columns thin-border">
			<div id="graph_zon" style="width: 100%; height: auto; margin: 0 auto;"></div>
		</div>
	</div>';

/*---------------------------*/
/*DERNIERES ANOMALIES SONORES*/
/*---------------------------*/
//Boutons ------------
echo '
	<h3 class="u-full-width">Anomalies sonores</h3>
	<div class="row">
		<div class="six columns">
			<a href="/monitoring_activite/views/anomalie_sonore_view.php"><button class="u-full-width">Visualiser toutes les anomalies sonores</button></a>
		</div>
		<div class="six columns">
			<button id="btn_last_ano" class="u-full-width button-primary display_tab">Afficher les '.$nb_anomalie_sonore.' dernières anomalies sonores</button>
			<input type="hidden" value="'.$nb_anomalie_sonore.'">
		</div>
	</div>';
//Tableau ------------
$anomalie_sonore_display->get_last_anomalie_sonore($nb_anomalie_sonore, "display_none");

/*---------------------------*/
/*GRAPHIQUE ANOMALIES SONORES*/
/*---------------------------*/
$i=1;
foreach($tab_anomalie_sonore as $anomalie_sonore ){
	echo '<input class="graph_as" type="hidden" value="Anomalie '.$i.'">';
	echo '<input class="graph_as_seuil" type="hidden" value="'.$anomalie_sonore->seuil().'">';
	echo '<input class="graph_as_niveau" type="hidden" value="'.$anomalie_sonore->niveau().'">';
	$i++;
}

echo '
	<br/>
	<div class="row">
		<div class="twelve columns thin-border">
			<div id="graph_as" style="width: 100%; height: auto; margin: 0 auto;"></div>
		</div>
	</div>';

/*----------------------*/
/*CONF ANOMALIES SONORES*/
/*----------------------*/
//Boutons ------------
echo '
	<h3 class="u-full-width">Configuration des anomalies sonores</h3>
	<div class="row">
		<div class="six columns">
			<a href="/monitoring_activite/views/cas_view.php"><button class="u-full-width">Editer</button></a>
		</div>
		<div class="six columns">
			<button id="btn_cas" class="u-full-width button-primary display_tab">Afficher tableau</button>
		</div>
	</div>';
//Tableau ------------
$cas_display->get_all_cas("display_none");
	
/*-------------------*/
/*FIN DE FICHIER HTML*/
/*-------------------*/
end_html(true);

?>