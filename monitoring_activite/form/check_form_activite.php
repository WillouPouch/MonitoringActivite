<?php

session_start();

include_once("../objects/type_activite_manager.php");
include_once("../utils/bdd_connection.php");


$errors = array();
$type_activite_manager = new TypeActiviteManager($db);

/*----------------------*/
/* Contrôle des données */
/*----------------------*/

$date_debut_ok = false;
$date_fin_ok = false;


//Contrôle de la présence d'au moins 1 champ
if( empty($_POST["date_debut"]) && empty($_POST["date_fin"]) && empty($_POST["type_activite"]) ){
	header('Location: /monitoring_activite/views/activite_view.php');
	exit();
}

//Contrôle des dates
if( !empty($_POST["date_debut"]) ){
	$_SESSION["form_activite"]["date_debut"] = $_POST["date_debut"];
	if ( !preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $_POST["date_debut"]) ) array_push($errors, "La date de début n'est pas au bon format (jj/mm/aaaa hh:mm:ss) !");
	else $date_debut_ok = true;
}

if( !empty($_POST["date_fin"]) ){
	$_SESSION["form_activite"]["date_fin"] = $_POST["date_fin"];
	if ( !preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $_POST["date_fin"]) ) array_push($errors, "La date de fin n'est pas au bon format (jj/mm/aaaa hh:mm:ss) !");
	else $date_fin_ok = true;
}

if( $date_debut_ok && $date_fin_ok ){
	$date_debut = DateTime::createFromFormat("d/m/Y H:i:s", $_POST["date_debut"]);
	$date_fin = DateTime::createFromFormat("d/m/Y H:i:s", $_POST["date_fin"]);
	if( $date_debut > $date_fin ) array_push($errors, "La date de début ne peut pas être supérieure à la date de fin.");
}
//Contrôle du type d'activité
if( !empty($_POST["type_activite"]) ){
	$_SESSION["form_activite"]["type_activite"] = $_POST["type_activite"];
	if( empty($type_activite_manager->get_label($_POST["type_activite"])) ) array_push($errors, "Ce type d'activité n'existe pas.");
}

//S'il y a des erreurs
if( count($errors) > 0){
	$_SESSION["ERROR"] = $errors;
	$_SESSION["OK"] = false;
}
else $_SESSION["OK"] = true;

header('Location: /monitoring_activite/views/activite_view.php');

?>
