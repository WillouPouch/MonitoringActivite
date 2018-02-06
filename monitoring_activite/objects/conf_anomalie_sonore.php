<?php

class ConfAnomalieSonore{
	
	private $_id_conf_as;
	private $_heure_debut;
	private $_heure_fin;
	private $_seuil;
	private $_duree;
	
	//Constructeur
	public function __construct(array $conf_anomalie_sonore){
		$this->_id_conf_as = (int) $conf_anomalie_sonore['id_conf_as'];
		$this->_heure_debut = $conf_anomalie_sonore['heure_debut'];
		$this->_heure_fin = $conf_anomalie_sonore['heure_fin'];
		$this->_seuil = (int) $conf_anomalie_sonore['seuil'];
		$this->_duree = (int) $conf_anomalie_sonore['duree'];
	}

	//Getters
	public function id_conf_as() { return $this->_id_conf_as; }
	public function heure_debut() { return $this->_heure_debut; }
	public function heure_fin() { return $this->_heure_fin; }
	public function seuil() { return $this->_seuil; }
	public function duree() { return $this->_duree; }

	//Setters
	public function set_heure_debut($heure_debut){ $this->_heure_debut = $heure_debut; }
	public function set_heure_fin($heure_fin){ $this->_heure_fin = $heure_fin; }
	public function set_seuil($seuil){ $this->_seuil = (int) $seuil; }
	public function set_duree($duree){ $this->_duree = (int) $duree; }
	
}
?>