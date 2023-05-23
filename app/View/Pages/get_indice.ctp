<div class="x_panel tile">
  <div class="x_title">
    <h2>Total desembolsado en mora</h2>
    <div class="clearfix"></div>
  </div>
  <div class="x_content">
    <div class="count red"><?php echo number_format($totalDisburment)  ?></div>
  </div>
</div>

<div class="x_panel tile">
  <div class="x_title">
    <h2>Índice de morosidad 30 Días</h2>
    <div class="clearfix"></div>
  </div>
  <div class="x_content">
    <div class="count red" <?php echo $days30 ?>><?php echo $days30 == 0 || $days30Total == 0 ? 0 : round(($days30 / floatval($days30+$days60+$days90) ) * 100,2)  ?>%</div>
    <span class="count_bottom">Equivalente a <b>$<?php echo number_format($days30Total) ?></b> en mora</span>
  </div>
</div>

<div class="x_panel tile">
  <div class="x_title">
    <h2>Índice de morosidad 60 Días</h2>
    <div class="clearfix"></div>
  </div>
  <div class="x_content">
    <div class="count red" <?php echo $days60 ?>><?php echo $days60 == 0 || $days60Total == 0 ? 0 : round(($days60 / floatval($days30+$days60+$days90) ) * 100,2)  ?>%</div>
    <span class="count_bottom">Equivalente a <b>$<?php echo number_format($days60Total) ?></b> en mora</span>
  </div>
</div>

<div class="x_panel tile">
  <div class="x_title">
    <h2>Índice de morosidad 90 Días</h2>
    <div class="clearfix"></div>
  </div>
  <div class="x_content" <?php echo $days90 ?>>
    <div class="count red"><?php echo $days90 == 0 || $days90Total == 0 ? 0 : round(($days90 / floatval($days30+$days60+$days90) ) * 100,2)  ?>%</div>
    <span class="count_bottom">Equivalente a <b>$<?php echo number_format($days90Total) ?></b> en mora</span>
  </div>
</div>