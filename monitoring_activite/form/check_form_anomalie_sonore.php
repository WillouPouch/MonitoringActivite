<?php

session_start();

include_once("../utils/bdd_connection.php");


$errors = array();

/*----------------------*/
/* Contrôle des données */
/*----------------------*/

$date_debut_ok = false;
$date_fin_ok = false;


//Contrôle de la présence d'au moins 1 champ
if( empty($_POST["date_debut"]) && empty($_POST["date_fin"]) && empty($_POST["niveau_inf"]) && empty($_POST["niveau_sup"]) ){
	header('Location: /monitoring_activite/views/anomalie_sonore_view.php');
	exit();
}

//Contrôle des dates
if( !empty($_POST["date_debut"]) ){
	$_SESSION["form_anomalie_sonore"]["date_debut"] = $_POST["date_debut"];
	if ( !preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $_POST["date_debut"]) ) array_push($errors, "La date de début n'est pas au bon format (jj/mm/aaaa hh:mm:ss) !");
	else $date_debut_ok = true;
}

if( !empty($_POST["date_fin"]) ){
	$_SESSION["form_anomalie_sonore"]["date_fin"] = $_POST["date_fin"];
	if ( !preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $_POST["date_fin"]) ) array_push($errors, "La date de fin n'est pas au bon format (jj/mm/aaaa hh:mm:ss) !");
	else $date_fin_ok = true;
}

if( $date_debut_ok && $date_fin_ok ){
	$date_debut = DateTime::createFromFormat("d/m/Y H:i:s", $_POST["date_debut"]);
	$date_fin = DateTime::createFromFormat("d/m/Y H:i:s", $_POST["date_fin"]);
	if( $date_debut > $date_fin ) array_push($errors, "La date de début ne peut pas être supérieure à la date de fin.");
}
//Contrôle du(des) niveau(x)
if( !empty($_POST["niveau_inf"]) ){
	
	$_SESSION["form_anomalie_sonore"]["niveau_inf"] = $_POST["niveau_inf"];
	if( !floatval($_POST["niveau_inf"]) || floatval($_POST["niveau_inf"])<=0 ) array_push($errors, "Le niveau sonore inférieur doit être numérique et positif.");
}
if( !empty($_POST["niveau_sup"]) ){
	$_SESSION["form_anomalie_sonore"]["niveau_sup"] = $_POST["niveau_sup"];
	if( !floatval($_POST["niveau_sup"]) || floatval($_POST["niveau_sup"])<=0 ) array_push($errors, "Le niveau sonore supérieur doit être numérique et positif.");
}

//S'il y a des erreurs
if( count($errors) > 0){
	$_SESSION["ERROR"] = $errors;
	$_SESSION["OK"] = false;
}
else $_SESSION["OK"] = true;

header('Location: /monitoring_activite/views/anomalie_sonore_view.php');

?>
