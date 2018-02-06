<?php

class AnomalieSonore{

	private $_id_as;
	private $_date_debut;
	private $_date_fin;
	private $_niveau;
	private $_seuil;
	private $_duree;
	private $_plage_horaire;

	//Constructeur
	public function __construct(array $anomalie_sonore){
		$this->_id_as = (int) $anomalie_sonore['id_as'];
		$this->_date_debut = $anomalie_sonore['date_debut'];
		$this->_date_fin = $anomalie_sonore['date_fin'];
		$this->_niveau = (float) $anomalie_sonore['niveau'];
		$this->_seuil = (int) $anomalie_sonore['seuil'];
		$this->_duree = (int) $anomalie_sonore['duree'];
		$this->_plage_horaire = $anomalie_sonore['plage_horaire'];
	}

	//Getters
	public function id_as() { return $this->_id_as; }
	public function date_debut() { return $this->_date_debut; }
	public function date_fin() { return $this->_date_fin; }
	public function niveau() { return $this->_niveau; }
	public function seuil() { return $this->_seuil; }
	public function duree() { return $this->_duree; }
	public function plage_horaire() { return $this->_plage_horaire; }

}
?>
