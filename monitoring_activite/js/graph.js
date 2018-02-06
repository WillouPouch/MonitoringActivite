$(document).ready(function () {
	
	// Récupération des données
	var tab_tv_tanche_horaire = new Array();
	var tab_tv_data = new Array();
	var tab_zone = new Array();
	var tab_as = new Array();
	var tab_as_seuil = new Array();
	var tab_as_niveau = new Array();
	
	$( ".graph_tv_data" ).each(function() {
		tab_tv_tanche_horaire.push( $(this).attr("name") );
		tab_tv_data.push( Number($(this).attr("value")) );
	});
	
	$( ".graph_zone_data" ).each(function() {
		tab_zone.push({
			name:  $(this).attr("name"),
			y:  Number($(this).attr("value"))
		});
	});
	
	$( ".graph_as" ).each(function() {
		tab_as.push( $(this).attr("value") );
	});
	
	$( ".graph_as_seuil" ).each(function() {
		tab_as_seuil.push( Number($(this).attr("value")) );
	});
	
	$( ".graph_as_niveau" ).each(function() {
		tab_as_niveau.push( Number($(this).attr("value")) );
	});
	
  // GRAPHIQUE ANOMALIE SONORE
  Highcharts.chart('graph_as', {
    chart: {
        type: 'column',
        backgroundColor: null
    },
    title: {
        text: '5 dernières anomalies par rapport à leur seuil'
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: tab_as
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Volume sonore (dB)'
        }
    },
    tooltip: {
        shared: true
    },
    plotOptions: {
        column: {
            grouping: false,
            shadow: false,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Seuil fixé',
        color: 'rgba(144, 237, 125,1)',
        data: tab_as_seuil,
        pointPadding: 0.3,
        pointPlacement: 0
    }, {
        name: 'Intensité de l\'anomalie',
        color: 'rgba(186,60,61,.9)',
        data: tab_as_niveau,
        pointPadding: 0.4,
        pointPlacement: 0
    }]
  });

  // GRAPHIQUE TV
  Highcharts.chart('graph_tv', {
    chart: {
        type: 'column',
        backgroundColor: null
    },
    title: {
        text: 'Visionnage TV du jour sur plusieurs tranches horaires'
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: tab_tv_tanche_horaire
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Durée (minute)'
        }
    },
    series: [{
        name: 'TV',
        data: tab_tv_data
    }]
  });

  // GRAPHIQUE ZONE
  Highcharts.chart('graph_zon', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie',
        backgroundColor: null
    },
    title: {
        text: 'Présence du jour dans les différentes zones de la pièce (en %)'
    },
    credits: {
    		enabled: false
		},
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Pourcentage',
        colorByPoint: true,
        data: tab_zone
    }]
  });
});
