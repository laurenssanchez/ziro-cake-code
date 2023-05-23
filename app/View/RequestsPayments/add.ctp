<?php

	$total = 0;
	$totalComision = 0;
	$comision_percentaje = $config["Config"]["comision"];

	foreach ($requestsNoPayment as $key => $value) {
		$total+=$value["Request"]["value"];
	}

	$totalComision = $total * ($comision_percentaje / 100  );
?>
<div class="page-title">
	<div class="title_left">
		<h3><?php echo __('Solicitar pago de recaudos WEB'); ?></h3>
	</div>
</div>
<div class="clearfix"></div>
<hr>
<h5><b>Pagos recibidos</b></h5>
<table class="table table-striped table-hover">
	<tbody>
		<?php foreach ($requestsNoPayment as $key => $value): ?>
			<tr>
				<td>
					<b>Cédula:  <?php echo $value["Request"]["identification"]; ?></b><br>
					<b>Código:  <?php echo $value["Request"]["code"]; ?></b><br>
					<b>Fecha pago:  <?php echo $value["Request"]["date_payment"]; ?></b><br>
				</td>
				<td>
					<i class="fa fa-plus"></i> $<?php echo number_format($value["Request"]["value"],"2",".",",") ?>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
<hr>
<?php echo $this->Form->create('RequestsPayment', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left')); ?>
<?php echo $this->Form->input('value', array('class' => 'form-control border-input','label'=>false,'div'=>false,"value"=>($total-$totalComision),"type" => "hidden")); ?>
<?php echo $this->Form->input('comision_percentaje', array('class' => 'form-control border-input','label'=>false,'div'=>false,"value"=>$comision_percentaje,"type" => "hidden")); ?>
<?php echo $this->Form->input('comision_value', array('class' => 'form-control border-input','label'=>false,'div'=>false,"value"=>$totalComision,"type" => "hidden")); ?>
<?php echo $this->Form->input('date_payment', array('class' => 'form-control border-input','label'=>false,'div'=>false,"value"=>null,"type" => "hidden")); ?>
<?php echo $this->Form->input('state', array('class' => 'form-control border-input','label'=>false,'div'=>false,"value"=>0,"type" => "hidden")); ?>
<?php echo $this->Form->input('shop_commerce_id', array('class' => 'form-control border-input','label'=>false,'div'=>false,"value"=>$this->Utilidades->decrypt($shopCommerceId),"type" => "hidden")); ?>
<h5><b>Datos a cobrar</b></h5>
<table class="table table-striped table-hover">
	<tbody>
		<tr>
			<th>
				Porcentaje de comisión
			</th>
			<td>
				<?php echo $comision_percentaje ?>%
			</td>
		</tr>
		<tr>
			<th>
				Valor total recaudado
			</th>
			<td>
				$<?php echo number_format($total,"2",".",",") ?>
			</td>
		</tr>
		<tr>
			<th>
				Comisión Zíro
			</th>
			<td>
				<?php echo number_format($totalComision,"2",".",","); ?>
			</td>
		</tr>
		<tr>
			<th>
				Total a cobrar
			</th>
			<td>
				<?php echo number_format($total-$totalComision,"2",".",","); ?>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><button type="submit" class="btn btn-success">Solicitar pago</button></td>
		</tr>
	</tbody>
</table>

<?php echo $this->Form->end(); ?>




