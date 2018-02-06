<?php

include_once("type_activite_manager.php");


class TypeActiviteDisplay{
	
	private $_type_activite_manager;

	//Constructeur
	public function __construct($db){
		$this->_type_activite_manager = new TypeActiviteManager($db);
	}
	
	
	public function get_all_type_activite($select_classes, $focus){
		echo $this->html_select_type_activite($this->_type_activite_manager->get_all(), $select_classes, $focus);
	}
	
	
	private function html_select_type_activite($tab_type_activite, $select_classes, $focus){

		$html = '<label for="type_activite">Type d\'activité : </label> <select class="'. $select_classes .'" name="type_activite">
			<option value=""></option>';
		
		foreach ($tab_type_activite as $type_activite){
			$html .= '<option value="'.$type_activite->type_activite().'"';
			if($type_activite->type_activite()==$focus) $html .= ' selected';
			$html .= '>'.$type_activite->label().'</option>';
		}
		$html .= '</select>';
		
		return $html;
	}
	
}
?>