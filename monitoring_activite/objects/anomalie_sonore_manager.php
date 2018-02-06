<?php

include_once("anomalie_sonore.php");

class AnomalieSonoreManager{

	private $_db;
	private $_tab_anomalie_sonore;

	//Constructeur
	public function __construct($db){
		$this->_db = $db;
		$this->_tab_anomalie_sonore = array();
	}

	public function get_query_data($query){

		$sql = $this->_db->query($query);

		while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
			array_push($this->_tab_anomalie_sonore, new AnomalieSonore($row));
		}
		return $this->_tab_anomalie_sonore;
	}

	public function get_all(){
		return $this->get_query_data("
			SELECT
				ano.id_as,
				DATE_FORMAT(ano.date_debut, '%d/%m/%Y %H:%i:%s' ) date_debut,
				DATE_FORMAT(ano.date_fin, '%d/%m/%Y %H:%i:%s' ) date_fin,
				ROUND(ano.niveau, 2) niveau,
				cas.seuil,
				cas.duree,
				CONCAT(CONCAT(TIME_FORMAT(cas.heure_debut, '%Hh%i'), ' - '), TIME_FORMAT(cas.heure_fin, '%Hh%i')) plage_horaire
			FROM ANOMALIE_SONORE ano LEFT JOIN CONF_ANOMALIE_SONORE cas
					ON (ano.niveau >= cas.seuil
						AND DATE_FORMAT(ano.date_debut,'00/00/0000 %H:%i:%s') >= DATE_FORMAT(cas.heure_debut,'00/00/0000 %H:%i:%s')
						AND DATE_FORMAT(ano.date_fin,'00/00/0000 %H:%i:%s') <= IF(cas.heure_fin='00:00:00', DATE_FORMAT(cas.heure_fin,'00/00/0000 23:59:59'),DATE_FORMAT(cas.heure_fin,'00/00/0000 %H:%i:%s'))
					)
			ORDER BY ano.id_as DESC");
	}

	public function get_last($nb_last_record){
		return $this->get_query_data("
			SELECT
				id_as,
				DATE_FORMAT(ano.date_debut, '%d/%m/%Y %H:%i:%s' ) date_debut,
				DATE_FORMAT(ano.date_fin, '%d/%m/%Y %H:%i:%s' ) date_fin,
				ROUND(ano.niveau, 2) niveau,
				cas.seuil,
				cas.duree,
				CONCAT(CONCAT(TIME_FORMAT(cas.heure_debut, '%Hh%i'), ' - '), TIME_FORMAT(cas.heure_fin, '%Hh%i')) plage_horaire
				FROM ANOMALIE_SONORE ano LEFT JOIN CONF_ANOMALIE_SONORE cas
						ON (ano.niveau >= cas.seuil
							AND DATE_FORMAT(ano.date_debut,'00/00/0000 %H:%i:%s') >= DATE_FORMAT(cas.heure_debut,'00/00/0000 %H:%i:%s')
							AND DATE_FORMAT(ano.date_fin,'00/00/0000 %H:%i:%s') <= IF(cas.heure_fin='00:00:00', DATE_FORMAT(cas.heure_fin,'00/00/0000 23:59:59'),DATE_FORMAT(cas.heure_fin,'00/00/0000 %H:%i:%s'))
						)
				ORDER BY ano.id_as DESC LIMIT $nb_last_record");
	}

	public function get_filter($date_debut, $date_fin, $niveau_inf, $niveau_sup ){
		$not_first = false;

		$query = "SELECT
				id_as,
				DATE_FORMAT(ano.date_debut, '%d/%m/%Y %H:%i:%s' ) date_debut,
				DATE_FORMAT(ano.date_fin, '%d/%m/%Y %H:%i:%s' ) date_fin,
				ROUND(ano.niveau, 2) niveau,
				cas.seuil,
				cas.duree,
				CONCAT(CONCAT(TIME_FORMAT(cas.heure_debut, '%Hh%i'), ' - '), TIME_FORMAT(cas.heure_fin, '%Hh%i')) plage_horaire
				FROM ANOMALIE_SONORE ano LEFT JOIN CONF_ANOMALIE_SONORE cas
						ON (ano.niveau >= cas.seuil
							AND DATE_FORMAT(ano.date_debut,'00/00/0000 %H:%i:%s') >= DATE_FORMAT(cas.heure_debut,'00/00/0000 %H:%i:%s')
							AND DATE_FORMAT(ano.date_fin,'00/00/0000 %H:%i:%s') <= IF(cas.heure_fin='00:00:00', DATE_FORMAT(cas.heure_fin,'00/00/0000 23:59:59'),DATE_FORMAT(cas.heure_fin,'00/00/0000 %H:%i:%s'))
						)
				WHERE ";

		if(isset($date_debut)){
			$query .= "ano.DATE_DEBUT >= STR_TO_DATE('".$date_debut."', '%d/%m/%Y %H:%i:%s') ";
			$not_first = true;
		}
		if(isset($date_fin)){
			if($not_first) $query .= "AND ";
			else $not_first = true;
			$query .= "ano.DATE_FIN <= STR_TO_DATE('".$date_fin."', '%d/%m/%Y %H:%i:%s') ";
		}

		if( isset($niveau_inf) && isset($niveau_sup) ){
			if($not_first) $query .= "AND ";
			$not_first = true;
			$query .= "ano.niveau BETWEEN ".$niveau_inf." AND ".$niveau_sup." ";
		}
		else if(isset($niveau_inf)){
			if($not_first) $query .= "AND ";
			$not_first = true;
			$query .= "ano.niveau >= ".$niveau_inf." ";
		}
		else if(isset($niveau_sup)){
			if($not_first) $query .= "AND ";
			$not_first = true;
			$query .= "ano.niveau <= ".$niveau_sup." ";
		}

		$query .= "ORDER BY ano.id_as DESC";
		return $this->get_query_data($query);
	}
	
	public function get_today_nb_as(){
		$sql = $this->_db->query("SELECT count(*) today_nb_total_as FROM ANOMALIE_SONORE WHERE (date_debut >= CURDATE() OR date_fin >= CURDATE())");
		$row = $sql->fetch(PDO::FETCH_ASSOC);
		return $row['today_nb_total_as'];
	}
	
	public function get_today_mean_lvl_as(){
		$sql = $this->_db->query("SELECT ROUND(AVG(niveau), 2) today_nb_total_as FROM ANOMALIE_SONORE WHERE (date_debut >= CURDATE() OR date_fin >= CURDATE())");
		$row = $sql->fetch(PDO::FETCH_ASSOC);
		return $row['today_nb_total_as'];
	}
	
	public function get_last_id_as(){
		$sql = $this->_db->query("SELECT MAX(id_as) id_as FROM ANOMALIE_SONORE");
		$row = $sql->fetch(PDO::FETCH_ASSOC);
		return $row['id_as'];
	}

}
?>
