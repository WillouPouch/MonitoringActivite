<?php

session_start();

ini_set('display_errors', 1);

include_once("../utils/bdd_connection.php");
include_once("../utils/static_html.php");
include_once("../objects/conf_anomalie_sonore_manager.php");
include_once("../objects/conf_anomalie_sonore_display.php");


/*-----*/
/* Var */
/*-----*/
$cas_manager = new ConfAnomalieSonoreManager($db);
$tab_cas = $cas_manager->get_all();
$cas_display = new ConfAnomalieSonoreDisplay($db);

if( isset($_SESSION["ERROR"]) ) $errors = $_SESSION["ERROR"];
if( isset($_SESSION["DATA"]) ) $form_data = $_SESSION["DATA"];
if( isset($_SESSION["OK"]) ) $form_ok = $_SESSION["OK"];

unset($_SESSION["ERROR"]);
unset($_SESSION["DATA"]);
unset($_SESSION["OK"]);

/*------*/
/* HTML */
/*------*/
start_html("Configuration des anomalies sonores", false);

echo '<h3>Données actuelles en vigueur</h3>';
echo $cas_display->get_all_cas(null);

echo '<h3 id="cas_form">Modifier les configurations</h3>';

echo '<div id="div_remark"><u class="red_font">ATTENTION :</u> l\'heure de minuit ne peut pas être comprise dans un intervalle. Il faudra séparer cet intervalle en deux parties !</div>';

echo '
	<div class="row">
		<form action="../form/check_form_cas.php" method="post">';

/* Affichage du panel des erreurs */
if(isset($errors)){
	echo '<div id="div_errors" class="u-full-width"><ul>';
	foreach($errors as $error) echo '<li>Erreur : '.$error.'</li>';
	echo '</ul></div>';
}

if(isset($form_ok) && $form_ok){
	echo '<div id="div_msg_ok" class="u-full-width">Succès : données enregistrées !</div>';
}

echo '
			<table class="u-full-width">
				<thead>
					<tr>
						<th class="id_cas">id</th>
						<th>Heure de début</th>
						<th>Heure de fin</th>
						<th>Seuil (dB)</th>
						<th>Durée (sec)</th>
					</tr>
				</thead>
				<tbody>';
				
if(!isset($form_ok) || $form_ok){	
	foreach ($tab_cas as $cas){ 
		echo '
				<tr>
					<td class="id_cas"><input type="text" value="'.$cas->id_conf_as().'" name="id_conf_as[]"/></td>
					<td><input type="text" placeholder="--:--:--" value="'.$cas->heure_debut().'" name="heure_debut[]" class="u-full-width Tpicker"/></td>
					<td><input type="text" placeholder="--:--:--" value="'.$cas->heure_fin().'" name="heure_fin[]" class="u-full-width Tpicker"/></td>
					<td><input type="text" placeholder="entier positif" value="'.$cas->seuil().'" name="seuil[]" class="u-full-width"/></td>
					<td><input type="text" placeholder="entier positif" value="'.$cas->duree().'" name="duree[]" class="u-full-width"/></td>
					<td><button class="delete_cas_row red_btn" type="button">Supprimer</button></td>
				</tr>';
	}
} else {
	for( $i=0 ; $i<count($form_data["id_conf_as"]) ; $i++ ){
		echo '
				<tr>
					<td class="id_cas"><input type="text" value="'.$form_data["id_conf_as"][$i].'" name="id_conf_as[]"/></td>
					<td><input type="text" placeholder="--:--:--" value="'.(isset($form_data["heure_debut"][$i])?$form_data["heure_debut"][$i]:"").'" name="heure_debut[]" class="u-full-width Tpicker"/></td>
					<td><input type="text" placeholder="--:--:--" value="'.(isset($form_data["heure_fin"][$i])?$form_data["heure_fin"][$i]:"").'" name="heure_fin[]" class="u-full-width Tpicker"/></td>
					<td><input type="text" placeholder="entier positif" value="'.(isset($form_data["seuil"][$i])?$form_data["seuil"][$i]:"").'" name="seuil[]" class="u-full-width"/></td>
					<td><input type="text" placeholder="entier positif" value="'.(isset($form_data["duree"][$i])?$form_data["duree"][$i]:"").'" name="duree[]" class="u-full-width"/></td>
					<td><button class="delete_cas_row red_btn" type="button">Supprimer</button></td>
				</tr>';
	}
}

echo '
				</tbody>
			</table>
			<div id="submit_cas" class="row">
				<button id="add_cas_row" type="button">Ajouter une ligne</button>
				<input class="button-primary" type="submit" value="Valider">
			</div>
		</form>
	</div>';



end_html(false);

?>