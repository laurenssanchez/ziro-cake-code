	<script>
		$('#fechasInicioFin').daterangepicker({
		    "showDropdowns": false,
		    "opens": "center",
		    ranges: {
		        'Hoy': [moment(), moment()],
		        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		        'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
		        'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
		        'Este mes': [moment().startOf('month'), moment()],
		        'El mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		    },
		    "locale": {
		        "format": "YYYY-MM-DD",
		        "separator": " - ",
		        "applyLabel": "Aplicar",
		        "cancelLabel": "Cancelar",
		        "fromLabel": "Desde",
		        "toLabel": "Hasta",
		        "customRangeLabel": "Definir rango",
		        "weekLabel": "W",
		        "daysOfWeek": [
		            "Do",
		            "Lu",
		            "Ma",
		            "Mi",
		            "Ju",
		            "Vi",
		            "Sa"
		        ],
		        "monthNames": [
		            "Enero",
		            "Febrero",
		            "Marzo",
		            "Abril",
		            "Mayo",
		            "Junio",
		            "Julio",
		            "Agosto",
		            "Septiembre",
		            "Octubre",
		            "Noviembre",
		            "Diciembre"
		        ],
		        "firstDay": 1
		    },
		    "alwaysShowCalendars": true,
		     "startDate": "<?php echo isset($fechaInicioReporte) ? $fechaInicioReporte : date("Y-m-d"); ?>",
		     "endDate": "<?php echo isset($fechaFinReporte) ? $fechaFinReporte : date("Y-m-d"); ?>",
		    "maxDate": "<?php echo date("Y-m-d") ?>"
		}, function(start, end, label) {	    
			$("#input_date_inicio,#input_date_inicio_empresa").val(start.format('YYYY-MM-DD'));
			$("#input_date_fin,#input_date_fin_empresa").val(end.format('YYYY-MM-DD'));
		});


	    
	</script>

