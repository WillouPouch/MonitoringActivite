<?php

    // Permet d'exécuter une requête SQL en renvoyant les infos
    //sur le bon déroulement ou non de la requête
    function run_sql(&$sql, &$state){
    
        if($sql->execute()) $state["STATE"]="OK";
        else {
            $state["STATE"]="KO";
            $state["ERROR"]=$sql->errorInfo()[2];
        }

    }

    // Permet de récupérer le json envoyé par une requête HTTP POST
    // tout en assurant la validité de celui-ci
    function get_post_json(&$post_json, &$json, &$state){

		if (empty($post_json)) {
			$state["STATE"]='KO';
            $state["ERROR"]='JSON - No received data !';
            return false;
		}
		

        $json = json_decode($post_json);

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return true;
            break;
            default:
                $state["STATE"]='KO';
                $state["ERROR"]=' JSON - '. json_last_error_msg();
                return false;
            break;
        }
        
    }

?> 
