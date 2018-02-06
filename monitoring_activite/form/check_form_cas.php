<?php

session_start();

include_once("../objects/conf_anomalie_sonore_manager.php");
include_once("../utils/bdd_connection.php");


$errors = array();
$type_activite_manager = new ConfAnomalieSonoreManager($db);
$delete_all = false;

/*------------------*/
/* Données vides ?? */
/*------------------*/

if( isset($_POST) && empty($_POST) ){
	$delete_all = true;
	$db->query("DELETE FROM CONF_ANOMALIE_SONORE");
}
else{
	for( $i=0 ; $i<count($_POST["id_conf_as"]) ; $i++ ){
		
		if( empty($_POST["heure_debut"][$i]) || empty($_POST["heure_fin"][$i]) ) {
			array_push($errors, "Un champ du formulaire est vide ! Tous les champs doivent être renseignés.");
			break;
		}
		//Cas particulier où 'seuil' et 'durée' peuvent valoir '0'. La fonction empty() considère le 0 comme vide !
		if( $_POST["seuil"][$i] == "" || $_POST["duree"][$i] == "" ){
			array_push($errors, "Un champ du formulaire est vide ! Tous les champs doivent être renseignés.");
			break;
		}
		
	}
}

/*------------------------------------------------*/
/*Tous les champs sont remplis mais pas contrôler */
/*------------------------------------------------*/
if( count($errors) == 0 && !$delete_all ){
	
	$tab_heure_debut = array();
	$tab_heure_fin = array();
	$tranche_horaire_ok = true;
	
	for( $i=0 ; $i<count($_POST["id_conf_as"]) ; $i++ ){
		
		$heure_debut_ok = false;
		$heure_fin_ok = false;
		
		if( !preg_match("/^[0-9]{2}\:[0-9]{2}\:[0-9]{2}$/",$_POST["heure_debut"][$i]) ) array_push($errors, "Mauvais format pour l'heure de début '".$_POST["heure_debut"][$i]."' (format : jj/mm/aaaa hh:mm:ss) !");
		else $heure_debut_ok = true;
		
		if( !preg_match("/^[0-9]{2}\:[0-9]{2}\:[0-9]{2}$/",$_POST["heure_fin"][$i]) ) array_push($errors, "Mauvais format pour l'heure de fin '".$_POST["heure_fin"][$i]."' (format : jj/mm/aaaa hh:mm:ss) !");
		else $heure_fin_ok = true;
		
		if( $heure_debut_ok && $heure_fin_ok ){
			
			//Si ancun pb de recoupement de tranche horaire n'a été détecté
			if($tranche_horaire_ok){
				
				$heure_debut = DateTime::createFromFormat("H:i:s", $_POST["heure_debut"][$i]);
				$alter_heure_fin = false;
				if ( $_POST["heure_fin"][$i] == "00:00:00" ){
					$_POST["heure_fin"][$i] = "23:59:59";
					$alter_heure_fin = true;
				}
				$heure_fin = DateTime::createFromFormat("H:i:s", $_POST["heure_fin"][$i]);
				
				if( !($_POST["heure_debut"][$i] == "00:00:00" && $_POST["heure_fin"][$i] == "23:59:59") && $heure_debut >= $heure_fin ){
					array_push($errors, "L'heure de début '".$_POST["heure_debut"][$i]."' doit être inférieure à l'heure de fin '".$_POST["heure_fin"][$i]."'");
					if($alter_heure_fin) $_POST["heure_fin"][$i] = "00:00:00";
					break;
				}
				
				//Si deux intervalles horaires se recoupent
				for($j=0 ; ($i>0 && $j<$i) ; $j++){
					
					if( ($heure_debut >= $tab_heure_debut[$j] && $heure_debut < $tab_heure_fin[$j]) || ($heure_fin > $tab_heure_debut[$j] && $heure_fin <= $tab_heure_fin[$j]) ){
						array_push($errors, "Les plages horaires ne doivent pas se recouper.");
						$tranche_horaire_ok = false;
						if($alter_heure_fin) $_POST["heure_fin"][$i] = "00:00:00";
						break;
					}
				}
				
				array_push($tab_heure_debut, $heure_debut);
				array_push($tab_heure_fin, $heure_fin);
				if($alter_heure_fin) $_POST["heure_fin"][$i] = "00:00:00";
			}
		}
		
		if(  preg_match("/^0*([1-9]\d*)$/",$_POST["seuil"][$i], $matches) ) $_POST["seuil"][$i] = $matches[1]; //Enleve les extra 0 en debut de str
		else array_push($errors, "Seuil '".$_POST["seuil"][$i]."' incorrect ! Il est doit être entier et strictement positif.");
		
		if(  preg_match("/^0*([1-9]\d*)$/",$_POST["duree"][$i], $matches) ) $_POST["duree"][$i] = $matches[1]; //Enleve les extra 0 en debut de str
		else array_push($errors, "Durée '".$_POST["duree"][$i]."' incorrect ! Il est doit être entier et strictement positif.");
		
	}
}

/*--------------------------------------------------------------*/
/* Tous les champs sont contrôlés								*/
/* Contrôle de la cohérence de l'ensemble des plages horaires	*/
/*--------------------------------------------------------------*/

//S'il n'y a pas d'erreur
//-----------------------
if( count($errors)==0 ){
	
	$_SESSION["OK"] = true;	
	
	//On commence par supprimer les CAS à supprimer
	//---------------------------------------------
	$tab_id_bdd = array();
	for( $i=0 ; $i<count($_POST["id_conf_as"]) ; $i++ ){
		if($_POST["id_conf_as"][$i] != -1) array_push($tab_id_bdd, $_POST["id_conf_as"][$i]);
	}
	
	$query = "DELETE FROM CONF_ANOMALIE_SONORE WHERE id_conf_as NOT IN(";
	for($i=0 ; $i<count($tab_id_bdd) ; $i++){
		if($i!=0) $query .= ",";
		$query .= $tab_id_bdd[$i];
	}
	$query .=")";
	$db->query($query);
	
	//On modifie les CAS à modifier
	//-----------------------------
	for($i=0 ; $i<count($tab_id_bdd) ; $i++){
		$cas_form_index =  array_search($tab_id_bdd[$i], $_POST["id_conf_as"]); //-------------------------------------------------------------!!!!!!!!!!!!!
		$query = "UPDATE CONF_ANOMALIE_SONORE SET
			heure_debut = TIME_FORMAT('".$_POST["heure_debut"][$cas_form_index ]."', '%H:%i:%s')
			,heure_fin = TIME_FORMAT('".$_POST["heure_fin"][$cas_form_index ]."', '%H:%i:%s')
			,duree = ".$_POST["duree"][$cas_form_index ]."
			,seuil = ".$_POST["seuil"][$cas_form_index ]."
			WHERE id_conf_as = ".$tab_id_bdd[$i];
		$db->query($query);
	}
	
	//On ajoute les nouveaux CAS
	//-----------------------------
	for( $i=0 ; $i<count($_POST["id_conf_as"]) ; $i++ ){
		if($_POST["id_conf_as"][$i] == -1){
			$query = "INSERT INTO CONF_ANOMALIE_SONORE
				(id_conf_as
				,heure_debut
				,heure_fin
				,seuil
				,duree)
			VALUES
				(NULL
				,'".$_POST["heure_debut"][$i]."'
				,'".$_POST["heure_fin"][$i]."'
				,".$_POST["seuil"][$i]."
				,".$_POST["duree"][$i].")
			;";
			$db->query($query);
		}
	}
	
	
}
//S'il y a des erreurs
//--------------------
else{
	$_SESSION["ERROR"] = $errors;
	$_SESSION["OK"] = false;
	$_SESSION["DATA"] = $_POST;
}

header('Location: /monitoring_activite/views/cas_view.php#cas_form');

?>
