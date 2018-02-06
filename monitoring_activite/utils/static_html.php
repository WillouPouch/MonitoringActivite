<?php

function start_html($title, $is_index_page){
	
	echo '
	<!DOCTYPE html>
	<html>
	<head>

	  <!-- Basic Page Needs  ––––––––––––––––––––––––––---->
	  <meta charset="utf-8">
	  <title>Monitoring d\'activité</title>
	  <link rel="icon" type="image/png" href="/monitoring_activite/images/favicon.png">
	  <!-- Mobile Specific Metas  ––––––––––––––––––––––--->
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <!-- CSS  ––––––––––––––––––––––––––––––––––––––––--->
	  <link rel="stylesheet" href="/monitoring_activite/css/normalize.css">
	  <link rel="stylesheet" href="/monitoring_activite/css/skeleton.css">
	  <link rel="stylesheet" href="/monitoring_activite/css/monitoring_activite.css">
	  <link rel="stylesheet" href="/monitoring_activite/datetimepicker/jquery.datetimepicker.css">

	</head>
	<body>
		
		<div class="container">
			<h1 class="u-full-width">'.$title.'</h1>';
	if( !$is_index_page ) {
		echo '
			<div class="row">
				<a href="/monitoring_activite/index.php"><button class="green_btn u-full-width" id="accueil_btn">Accueil</button></a>
			</div>
		';
	}
	
}

function end_html($is_index_page){
	
	echo '
	
		<footer class="row">
			<div id="div_footer_img" class="u-full-width"><img src="../images/isen-logo.jpg" alt="logo ISEN-Yncrea"></div>
			<div id="div_footer_text" class="u-full-width">
			
				<p class="u-full-width">
				Projet année Master 1 (2016-2017) : Rénald Morice et Wilfried Pouchous.
				<br>ISEN Brest, Groupe Yncrea (Ecole d\'ingénieurs des Hautes Technologies et du Numérique).
				</p>
			</div>
		</footer>
	
		</div>
	<!-- End Document –––––––––––––––––––––––––––––––––-->
		<script type="text/javascript" src="/monitoring_activite/datetimepicker/jquery.js"></script>
		<script type="text/javascript" src="/monitoring_activite/datetimepicker/build/jquery.datetimepicker.full.js"></script>
		<script type="text/javascript" src="/monitoring_activite/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="/monitoring_activite/js/monitoring_activite.js"></script>';
	if($is_index_page) echo '<script type="text/javascript" src="/monitoring_activite/js/accueil.js"></script>';
	if($is_index_page){
		echo '<!-- Graphs -->
		<script type="text/javascript" src="/monitoring_activite/js/highcharts.js"></script>
		<script type="text/javascript" src="/monitoring_activite/js/exporting.js"></script>
		<script type="text/javascript" src="/monitoring_activite/js/graph.js"></script>';
	}
	echo '</body>
	</html>';
}

?>