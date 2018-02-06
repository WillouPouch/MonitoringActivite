<?php

include_once("activite_manager.php");
include_once("type_activite_manager.php");


class ActiviteDisplay{
	
	private $_activite_manager;
	private $_type_activite_manager;

	//Constructeur
	public function __construct($db){
		$this->_activite_manager = new ActiviteManager($db);
		$this->_type_activite_manager = new TypeActiviteManager($db);
	}
	
	public function get_filter_activite($date_debut, $date_fin, $type_activite, $div_table_classes){
		echo $this->html_table_activite($this->_activite_manager->get_filter($date_debut, $date_fin, $type_activite), $div_table_classes);
	}
	

	public function get_last_activite($nb_activite, $div_table_classes){
		echo $this->html_table_activite($this->_activite_manager->get_last($nb_activite), $div_table_classes);
	}
	
	
	public function get_all_activite($div_table_classes){
		echo $this->html_table_activite($this->_activite_manager->get_all(), $div_table_classes);
	}
	
	
	private function html_table_activite($tab_activite, $div_table_classes){

		$html = '
			<div class="row '. $div_table_classes .'">
				<table class="u-full-width">
					<thead>
						<tr>
							<th>Date de début</th>
							<th>Date de fin</th>
							<th>Type d\'activité</th>
						</tr>
					</thead>
					<tbody>';

		foreach ($tab_activite as $activite){ 
			$html .= '
				<tr>
					<td>'.$activite->date_debut().'</td>
					<td>'.$activite->date_fin().'</td>
					<td>'.$this->_type_activite_manager->get_label($activite->type_activite()).'</td>
				</tr>';
		}

		$html .= '	</tbody>
				</table>
			</div>';
		
		return $html;
	}
	
}
?>