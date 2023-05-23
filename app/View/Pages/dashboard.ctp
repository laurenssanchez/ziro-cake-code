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

    <div class="col-md-3 col-sm-4 gray">
      <div class="x_panel tile">
        <div class="x_title">
        <h2><small>Total Cartera por Cobrar</small></h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div class="tile_stats_count">
            <div class="count">$<?php echo number_format($totalSiMora["0"]["total"]) ?></div>
            <span class="count_bottom">Correspondientes a <b><?php echo $CountTotalSiMora ?> Créditos</b></span>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<div class="col-md-12">
  <div class="col-md-12">
    <h3>Créditos desembolsados </h3>
    <hr>
  </div>


  <div class="row">
    <div class="col-md-12 col-sm-12 mb-5">
      <div class="dashboard_graph">

        <div class="col-md-9 col-sm-9 ">
         <canvas id="chart1_dashboard" height="140"></canvas>
       </div>
       <div class="col-md-3">
         <a href="" id="verIndice" class="btn btn-info btn-block">
           Ver índice de morosidad
         </a>
       </div>
       <div class="col-md-3 col-sm-3 morosidad mt-1">

      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>
</div>

<div class="modal fade" id="modalIndice" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Información de indicé de morocidad</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="bodyIndice">
        <div class="row">
          <div class="col-md-12 p-3">
            <div id="bodyFinalIndice"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
</script>

<?php $this->start("AppScript") ?>


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


  $("#verIndice").click(function(event) {
    event.preventDefault();
    $("#preloader").show();
    let ini = $("#input_date_inicio").val();
    let end = $("#input_date_fin").val();
    $.post(root+"pages/get_indice", {ini,end}, function(data, textStatus, xhr) {
      $("#bodyFinalIndice").html(data);
      $("#preloader").hide();
      $("#modalIndice").modal("show");
    });
  });


</script>

<?php $this->end() ?>
