<?php

session_start();

ini_set('display_errors', 1);

include_once("../utils/bdd_connection.php");
include_once("../utils/static_html.php");
include_once("../objects/activite_display.php");
include_once("../objects/type_activite_display.php");


/*-----*/
/* Var */
/*-----*/

$type_activite_display = new TypeActiviteDisplay($db);
$activite_display = new ActiviteDisplay($db);

if( isset($_SESSION["OK"]) ) $form_ok = $_SESSION["OK"];
else $form_ok = false;
if( isset($_SESSION["ERROR"]) ) $errors = $_SESSION["ERROR"];

if( isset($_SESSION["form_activite"]["date_debut"]) ) $date_debut = $_SESSION["form_activite"]["date_debut"];
if( isset($_SESSION["form_activite"]["date_fin"]) ) $date_fin = $_SESSION["form_activite"]["date_fin"];
if( isset($_SESSION["form_activite"]["type_activite"]) ) $type_activite = $_SESSION["form_activite"]["type_activite"];

unset($_SESSION["OK"]);
unset($_SESSION["ERROR"]);
unset($_SESSION["form_activite"]);

/*------*/
/* HTML */
/*------*/
start_html("Activités", false);


echo '<button class="u-full-width button-primary display_form" >';
if( isset($errors) ) echo 'Masquer le formulaire';
else echo 'Afficher le formulaire';
echo '</button>';

echo '<form action="../form/check_form_activite.php" method="post" class=" thin-border ';
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
		</div>';
echo $type_activite_display->get_all_type_activite( "u-full-width", (isset($type_activite)?$type_activite:null) );

echo '<input class="button-primary" type="submit" value="Rafraîchir">
</form>';


//Tableau ------------

//S'il n'y a pas d'erreur
if(!isset($errors)){
	//Si le formulaire a été rempli correctement
	if( $form_ok ){
		$activite_display->get_filter_activite(
			(isset($date_debut)?$date_debut:null)
			,(isset($date_fin)?$date_fin:null)
			,(isset($type_activite)?$type_activite:null)
			,null);
	}
	//Si le formulaire n'a pas été rempli
	else echo $activite_display->get_all_activite(null);
}

end_html(false);

?>