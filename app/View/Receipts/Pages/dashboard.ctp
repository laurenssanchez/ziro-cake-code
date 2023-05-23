<!-- top tiles -->
<div class="container">

  <div class="row row-end">
<div class="col-md-12">
    <h3>Rango de fechas seleccionado para los valores generados</h3>

  </div>
    <div class="col-md-12 mb-3">

      <?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'form-inline')); ?>
      <div class="rangofechas input-group ">
        <input type="date" name="dateIni" value="<?php echo $dateIni; ?>" id="input_date_inicio" placeholder="Desde" style="display: none">
        <input type="text" value="<?php echo $dateIni; ?>" id="fechasInicioFin" class="form-control">
        <input type="date" name="dateEnd" value="<?php echo $dateEnd ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none">
        <span class="input-group-btn">
          <button class="btn-secondary btn text-white btn-block mt-2" style="display:none" id="btn_find_adviser" type="submit">Filtrar Fechas</button>
        </span>
        <?php if (isset($fechas)): ?>
          <a href="<?php echo $this->Html->url(["controller" => "pages", "action" => "dashboard","?"=>["dateIni" => date("Y-m-d",strtotime("-6 month")),"dateEnd" => date("Y-m-d") ]]) ?>" class="btn btn-warning btn-block mt-2">Borrar fechas <i class="fa fa-times"></i></a>
        <?php endif ?>
      </div>
    </form>
  </div>
    <hr>
  <div class="col-md-12">
    <div class="row">

    <div class="col-md-3 col-sm-4 green">
      <div class="x_panel tile">
        <div class="x_title">
          <h2>Total Créditos Aprobados</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div class="tile_stats_count">
            <div class="count">$<?php echo number_format($totalApprove) ?></div>
            <span class="count_bottom">Correspondientes a <b><?php echo $CounttotalApprove ?> Créditos</b></span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-4 red">
      <div class="x_panel tile">
        <div class="x_title">
          <h2>Créditos Negados</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div class="tile_stats_count">
            <div class="count">$<?php echo number_format($totalNoApprove) ?></div>
            <span class="count_bottom">Correspondientes a <b><?php echo $CounttotalNoApprove ?> Créditos</b></span>
          </div>
        </div>
      </div>
    </div>



      <div class="col-md-3 col-sm-4 orange">
        <div class="x_panel tile ">
          <div class="x_title">
            <h2>Recaudos por Cobrar</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
           <div class="tile_stats_count">
            <div class="count">$<?php echo number_format($totalNoCommerce) ?></div>
            <span class="count_bottom">Correspondientes a <b><?php echo $CounttotalNoCommerce ?> Pagos</b></span>
          </div>
        </div>

      </div>
    </div>


    <div class="col-md-3 col-sm-4 orange">
      <div class="x_panel tile">
        <div class="x_title">
          <h2>Saldo por Pagar a Proveedores</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div class="tile_stats_count">
            <div class="count">$<?php echo number_format($totalNoShop) ?></div>
            <span class="count_bottom">Correspondientes a <b><?php echo $CounttotalNoShop ?> Solicitudes</b></span>
          </div>
        </div>
      </div>
    </div>

  <div class="col-md-3 col-sm-4 gray">
        <div class="x_panel tile">
          <div class="x_title">
            <h2>Total Créditos Finalizados</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="tile_stats_count">
              <div class="count">$<?php echo number_format($totalPaymentCredit) ?></div>
              <span class="count_bottom">Correspondientes a <b><?php echo $CounttotalPaymentCredit ?> Créditos</b></span>
            </div>
          </div>
        </div>
      </div>

    <div class="col-md-3 col-sm-4 gray">
      <div class="x_panel tile">
        <div class="x_title">
          <h2>Total Cartera / Retirado</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div class="tile_stats_count">
            <div class="count">$<?php echo number_format($totalDisburment) ?></div>
            <span class="count_bottom">Correspondientes a <b><?php echo $CounttotalDisburment ?> Créditos</b></span>
          </div>
        </div>
      </div>
    </div>


    <div class="col-md-3 col-sm-4 gray">
      <div class="x_panel tile">
        <div class="x_title">
          <h2>Créditos sin Desembolsar</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div class="tile_stats_count">
            <div class="count">$<?php echo number_format($totalNoDisburment) ?></div>
            <span class="count_bottom">Correspondientes a <b><?php echo $CounttotalNoDisburment ?> Créditos</b></span>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<div class="col-md-12">
  <div class="col-md-12">
    <h3>Créditos desembolsados <small>últimos 6 meses $ <?php echo number_format($totalMonths) ?> COP </small></h3>
    <hr>
  </div>


  <div class="row">
    <div class="col-md-12 col-sm-12 mb-5">
      <div class="dashboard_graph">

        <div class="col-md-9 col-sm-9 ">
         <canvas id="chart1_dashboard" height="140"></canvas>
       </div>
       <div class="col-md-3 col-sm-3 morosidad mt-1">
         <div class="x_panel tile">
          <div class="x_title">
            <h2>Índice de morosidad 30 Días</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="count red"><?php echo $days30 == 0 || $days30Total == 0 ? 0 : round(($days30Total / floatval($totalDisburment) ) * 100,2)  ?>%</div>
            <span class="count_bottom">Equivalente a <b>$<?php echo number_format($days30Total) ?></b> en mora</span>
          </div>
        </div>

        <div class="x_panel tile">
          <div class="x_title">
            <h2>Índice de morosidad 60 Días</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="count red"><?php echo $days60 == 0 || $days60Total == 0 ? 0 : round(($days60Total / floatval($totalDisburment) ) * 100,2)  ?>%</div>
            <span class="count_bottom">Equivalente a <b>$<?php echo number_format($days60Total) ?></b> en mora</span>
          </div>
        </div>

        <div class="x_panel tile">
          <div class="x_title">
            <h2>Índice de morosidad 90 Días</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="count red"><?php echo $days90 == 0 || $days90Total == 0 ? 0 : round(($days90Total / floatval($totalDisburment) ) * 100,2)  ?>%</div>
            <span class="count_bottom">Equivalente a <b>$<?php echo number_format($days90Total) ?></b> en mora</span>
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>
</div>



<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>

  var Fvalues = <?php echo json_encode($values) ?>;
  var Fmonths = <?php echo json_encode($months) ?>;
  var Fcolors = <?php echo json_encode($colors) ?>;


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
    "startDate": "<?php echo isset($dateIni) ? $dateIni : date("Y-m-d"); ?>",
    "endDate": "<?php echo isset($dateEnd) ? $dateEnd : date("Y-m-d"); ?>",
    "maxDate": "<?php echo date("Y-m-d") ?>"
  }, function(start, end, label) {


    $("#input_date_inicio,#input_date_inicio_empresa").val(start.format('YYYY-MM-DD'));
    $("#input_date_fin,#input_date_fin_empresa").val(end.format('YYYY-MM-DD'));

    if($("#btn_find_adviser").length){
      $("#btn_find_adviser").trigger('click')
    }


  });
</script>
