<?php

include_once("activite.php");

class ActiviteManager{
	
	private $_db;
	private $_tab_activite;

	//Constructeur
	public function __construct($db){
		$this->_db = $db;
		$this->_tab_activite = array();
	}

	public function get_query_data($query){
		
		$sql = $this->_db->query($query);
		 
		while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
			array_push($this->_tab_activite, new Activite($row));
		}
		return $this->_tab_activite;
	}
	
	public function get_all(){
		return $this->get_query_data("SELECT
				id_activite,
				DATE_FORMAT(date_debut, '%d/%m/%Y %H:%i:%s' ) date_debut,
				DATE_FORMAT(date_fin, '%d/%m/%Y %H:%i:%s' ) date_fin,
				type_activite
			FROM ACTIVITE ORDER BY id_activite DESC");
	}
	
	public function get_last($nb_last_record){
		return $this->get_query_data("SELECT
				id_activite,
				DATE_FORMAT(date_debut, '%d/%m/%Y %H:%i:%s' ) date_debut,
				DATE_FORMAT(date_fin, '%d/%m/%Y %H:%i:%s' ) date_fin,
				type_activite
			FROM ACTIVITE ORDER BY id_activite DESC LIMIT $nb_last_record");
	}
	
	public function get_filter($date_debut, $date_fin, $type_activite){
		$not_first = false;
		
		$query = "SELECT
				id_activite,
				DATE_FORMAT(date_debut, '%d/%m/%Y %H:%i:%s' ) date_debut,
				DATE_FORMAT(date_fin, '%d/%m/%Y %H:%i:%s' ) date_fin,
				type_activite
			FROM ACTIVITE WHERE ";
		
		if(isset($date_debut)){
			$query .= "DATE_DEBUT >= STR_TO_DATE('".$date_debut."', '%d/%m/%Y %H:%i:%s') ";
			$not_first = true;
		}
		if(isset($date_fin)){
			if($not_first) $query .= "AND ";
			else $not_first = true;
			$query .= "DATE_FIN <= STR_TO_DATE('".$date_fin."', '%d/%m/%Y %H:%i:%s') ";
		}
		if(isset($type_activite)){
			if($not_first) $query .= "AND ";
			$query .= "type_activite = '".$type_activite."' ";
		}	
		$query .= "ORDER BY id_activite DESC";
		
		return $this->get_query_data($query);
	}
	
	
	public function get_today_tv_tranche_horaire(){
		$query = "SELECT
					t2.heure_debut heure_debut
					,IF(t2.heure_fin='23:59:59', '00:00:00',t2.heure_fin) heure_fin
					,SEC_TO_TIME( SUM( TIME_TO_SEC(TIMEDIFF(
						IF(t2.heure_fin_act>=t2.heure_fin,t2.heure_fin,t2.heure_fin_act)
						,IF(t2.heure_debut_act<=t2.heure_debut,t2.heure_debut,t2.heure_debut_act)
					)))) total_time
					,SUM( TIME_TO_SEC(TIMEDIFF(
						IF(t2.heure_fin_act>=t2.heure_fin,t2.heure_fin,t2.heure_fin_act)
						,IF(t2.heure_debut_act<=t2.heure_debut,t2.heure_debut,t2.heure_debut_act)
					))) total_sec
				FROM
				(
					SELECT
						t1.heure_debut
						,t1.heure_fin
						,IF(date_debut < CURDATE(), '00:00:00', TIME_FORMAT(date_debut, '%H:%i:%s') ) heure_debut_act
						,TIME_FORMAT(date_fin, '%H:%i:%s') heure_fin_act
					FROM
						(
							SELECT
								'00:00:00' heure_debut,
								'08:00:00' heure_fin
							FROM DUAL
							UNION ALL
							SELECT
								'08:00:00' heure_debut,
								'12:00:00' heure_fin
							FROM DUAL
							UNION ALL
							SELECT
								'12:00:00' heure_debut,
								'18:00:00' heure_fin
							FROM DUAL
							UNION ALL
							SELECT
								'18:00:00' heure_debut,
								'23:59:59' heure_fin
							FROM DUAL
						) t1
						,ACTIVITE act
					WHERE act.type_activite = 'tv_on'
					AND (act.date_debut >= CURDATE() OR act.date_fin >= CURDATE() )
					AND(
						( IF(act.date_debut < CURDATE(), '00:00:00', TIME_FORMAT(act.date_debut, '%H:%i:%s')) >= t1.heure_debut
						AND  TIME_FORMAT(act.date_debut, '%H:%i:%s') < t1.heure_fin
						)
						OR
						( TIME_FORMAT(act.date_fin, '%H:%i:%s') > t1.heure_debut AND TIME_FORMAT(act.date_fin, '%H:%i:%s') <= t1.heure_fin )
					)
				)t2
				GROUP BY
					t2.heure_debut
					,IF(t2.heure_fin='23:59:59', '00:00:00',t2.heure_fin)";
		$sql = $this->_db->query($query);
		return $sql->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function get_today_zone(){
		$query = "SELECT
				t1.type_activite
				,SEC_TO_TIME( SUM( TIME_TO_SEC(TIMEDIFF(
					t1.heure_fin_act
					,t1.heure_debut_act
				)))) total_time
				,SUM( TIME_TO_SEC(TIMEDIFF(
					t1.heure_fin_act
					,t1.heure_debut_act
				))) total_sec
			FROM (
				SELECT
					act.type_activite
					,IF(date_debut < CURDATE(), '00:00:00', TIME_FORMAT(date_debut, '%H:%i:%s') ) heure_debut_act
					,TIME_FORMAT(date_fin, '%H:%i:%s') heure_fin_act
				FROM ACTIVITE act
				WHERE act.type_activite IN ('salon', 'cuisine', 'chambre')
				AND (act.date_debut >= CURDATE() OR act.date_fin >= CURDATE() )
			) t1
			GROUP BY t1.type_activite";
		$sql = $this->_db->query($query);
		return $sql->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function get_last_id_activite(){
		$sql = $this->_db->query("SELECT MAX(id_activite) id_activite FROM ACTIVITE");
		$row = $sql->fetch(PDO::FETCH_ASSOC);
		return $row['id_activite'];
	}
	
	
}
?>