<?php

session_start();

ini_set('display_errors', 1);

include_once("../utils/bdd_connection.php");
include_once("../utils/static_html.php");
include_once("../objects/anomalie_sonore_display.php");


/*-----*/
/* Var */
/*-----*/
$anomalie_sonore_display = new AnomalieSonoreDisplay($db);

if( isset($_SESSION["OK"]) ) $form_ok = $_SESSION["OK"];
else $form_ok = false;
if( isset($_SESSION["ERROR"]) ) $errors = $_SESSION["ERROR"];

if( isset($_SESSION["form_anomalie_sonore"]["date_debut"]) ) $date_debut = $_SESSION["form_anomalie_sonore"]["date_debut"];
if( isset($_SESSION["form_anomalie_sonore"]["date_fin"]) ) $date_fin = $_SESSION["form_anomalie_sonore"]["date_fin"];
if( isset($_SESSION["form_anomalie_sonore"]["niveau_inf"]) ) $niveau_inf = $_SESSION["form_anomalie_sonore"]["niveau_inf"];
if( isset($_SESSION["form_anomalie_sonore"]["niveau_sup"]) ) $niveau_sup = $_SESSION["form_anomalie_sonore"]["niveau_sup"];

unset($_SESSION["OK"]);
unset($_SESSION["ERROR"]);
unset($_SESSION["form_anomalie_sonore"]);

/*------*/
/* HTML */
/*------*/
start_html("Anomalies sonores", false);

echo '<button class="u-full-width button-primary display_form" >';
if( isset($errors) ) echo 'Masquer le formulaire';
else echo 'Afficher le formulaire';
echo '</button>';

echo '<form action="../form/check_form_anomalie_sonore.php" method="post" class=" thin-border ';
if(!isset($errors)) echo 'display_none';
echo '">';

/* Affichage du panel des erreurs */
if(isset($errors)){
	echo '<div id="div_errors" class="u-full-width"><ul>';
	foreach($errors as $error) echo '<li>Erreur : '.$error.'</li>';
	echo '</ul></div>';
}
/*--------------------------------*/

echo '	<div class="row">
			<div class="six columns">
				<label>Date de début : <label>
				<input type="text" placeholder="--/--/-- --:--:--" value="'.(isset($date_debut)?$date_debut:"").'" name="date_debut" class="u-full-width DTpicker"/>
			</div>
			<div class="six columns">
				<label>Date de fin : </label>
				<input type="text" placeholder="--/--/-- --:--:--" value="'.(isset($date_fin)?$date_fin:"").'" name="date_fin" class="u-full-width DTpicker"/>
			</div>
		</div>
		<div class="row">
			<div class="six columns">
				<label>Niveau sonore inférieur : </label>
				<input type="text" placeholder="niveau sonore inférieur en dB (entier positif)" value="'.(isset($niveau_inf)?$niveau_inf:"").'" name="niveau_inf" class="u-full-width"/>
			</div>
			<div class="six columns">
				<label>Niveau sonore supérieur : </label>
				<input type="text" placeholder="niveau sonore supérieur en dB (entier positif)" value="'.(isset($niveau_sup)?$niveau_sup:"").'" name="niveau_sup" class="u-full-width"/>
			</div>
		</div>';

echo '<input class="button-primary" type="submit" value="Rafraîchir">
</form>';


//Tableau ------------

//S'il n'y a pas d'erreur
if(!isset($errors)){
	//Si le formulaire a été rempli correctement
	if( $form_ok ){
		$anomalie_sonore_display->get_filter_anomalie_sonore(
			(isset($date_debut)?$date_debut:null)
			,(isset($date_fin)?$date_fin:null)
			,(isset($niveau_inf)?$niveau_inf:null)
			,(isset($niveau_sup)?$niveau_sup:null)
			,null);
	}
	//Si le formulaire n'a pas été rempli
	else echo $anomalie_sonore_display->get_all_anomalie_sonore(null);
}

end_html(false);

?>