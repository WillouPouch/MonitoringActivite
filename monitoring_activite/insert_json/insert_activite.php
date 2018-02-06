<?php

    session_start();
    
    require_once("../utils/functions.php");
    require_once("../utils/bdd_connection.php"); // Contient le tableau clé-valeur 'state'
    
    //Petites infos sur le JSON (les tableaus, objets, clés-valeurs, etc..)
    //http://www.dyn-web.com/tutorials/php-js/json/decode.php 
    //   
    //JSON reçu :
	//{ "activite" : [
	//	{"date_debut": "31/01/2017 12:30:57",
    //  "date_fin": "31/01/2017 12:31:05",
    //  "type_activite": "tv_on"}
	//  ,
	//	{"date_debut": "01/02/2017 09:50:05",
    //  "date_fin": "01/02/2017 09:50:12",
    //  "type_activite": "tps_cuisine"}
	//]}
    
    if(!get_post_json($_POST["json"], $data, $state)){
        echo json_encode($state);
        die();
    }
    
    //Début d'une transaction SQL : la BDD revient à son état normal après validation (commit) ou non (rollback) de la transaction car la transaction prend fin à ce moment.
    $db->beginTransaction();
    
    $sql = $db->prepare("INSERT ACTIVITE(date_debut,date_fin,type_activite) VALUES (STR_TO_DATE(:date_debut, '%d/%m/%Y %H:%i:%s'),STR_TO_DATE(:date_fin, '%d/%m/%Y %H:%i:%s'),:type_activite)");
    
    
    foreach($data->activite as $activite){
		
        $sql->bindValue(":date_debut", $activite->date_debut, PDO::PARAM_STR);
        $sql->bindValue(":date_fin", $activite->date_fin, PDO::PARAM_STR);
        $sql->bindValue(":type_activite", $activite->type_activite, PDO::PARAM_STR);
		
		run_sql($sql, $state);
		if($state["STATE"]=="KO") break;
    }
    
    if($state["STATE"]=="OK") $db->commit();
    else $db->rollBack();
    
    echo json_encode($state);
    
?> 