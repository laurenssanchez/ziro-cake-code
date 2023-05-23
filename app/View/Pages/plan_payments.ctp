<?php $totalCapitalDeuda = floatval($priceValue); ?>
<?php $ramdomVals = $couteValue ?>

<table class="table table-striped">
	<thead>
		<tr>
			<th>Cuota</th>
			<th>Fecha  <?php echo $couteValue==1 ? 'pago' : 'pagos' ?></th>
			<th>Capital</th>
			<th>Intereses</th>
			<th>Otros</th>
			<th>Cuota</th>
			<th>Saldo</th>
		</tr>
	</thead>
	<tbody>
		<?php $j = 0;
		$ultimoCap = 0; ?>

		<?php for ($i = 1; $i <= intval($couteValue); $i++) : ?>
			<?php
			pr($frecuency);
			//$interesesT = ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["interes_rate"]) / 100) / 30) * $days;
			//Fin Interes corriente

			//otros intereses
			//$interesesOT = ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["others_rate"]) / 100) / 30) * $days;

			//capital
			//$CapitalN = $creditInfo["Credit"]["quota_value"] - $interesesOT - $interesesT;

			$intereses 		= round($priceValue * ($data_credit["intRate"] / $frecuency));
			$interesesOtro 	= round($priceValue * ($data_credit["intOther"] / $frecuency));
			$capitalC       = $data_credit["cuote"] - $intereses - $interesesOtro;
			$totalCapitalDeuda	-= $capitalC;
			$priceValue     	-= $capitalC;
			?>
			<tr>
				<!-- cuota -->
				<td><?php echo $i + $j ?></td>

				<!-- Fecha pagos -->
				<?php if ($frecuency == 1) : ?>
					<td><?php echo date("d-m-Y", strtotime("+$i month")) ?></td>
				<?php elseif($frecuency == 3) : ?>
					<?php $days=45 +1; ?>
					<td><?php echo date("d-m-Y", strtotime("+$days  days")) ?></td>
				<?php elseif($frecuency == 4) : ?>
					<?php $days=60 +1; ?>
					<td><?php echo date("d-m-Y", strtotime("+$days  days")) ?></td>
				<?php else : ?>
					<?php $days = (($i + $j) * 15); ?>
					<td><?php echo date("d-m-Y", strtotime("+$days days")) ?></td>
				<?php endif ?>

				<!-- Capital -->
				<td>
					<?php
						if (round($totalCapitalDeuda) < 0 || round($totalCapitalDeuda) < 1000) {
							if ($ultimoCap == 0) {
								echo number_format(round($capitalC));
							} else {
								echo number_format(round($ultimoCap));
							}
						} else {
							echo number_format(round($capitalC));
						}

					?>
				</td>

				<!-- Intereses -->
				<td><?php echo number_format(round($intereses)) ?></td>

				<!-- Otros -->
				<td><?php echo number_format(round($interesesOtro)) ?></td>

				<!-- Cuota -->
				<td><?php echo number_format(round($data_credit["cuote"])) ?></td>

				<td>
					<?php echo round($totalCapitalDeuda) < 0 || round($totalCapitalDeuda) < 1000 ? 0 : number_format(round($totalCapitalDeuda)) ?>
				</td>

				<!-- Saldo -->
				<?php $ultimoCap = round($totalCapitalDeuda); ?>
			</tr>

		<?php endfor; ?>
	</tbody>
</table>

<?php if (isset($this->request->data["final"])) : ?>
	<?php if ($totalActual < $priceValue) : ?>
		<a href="javascript:void(0)" class="btn btn-primary float-right requestFinal mb-4">
			Solicitar cupo
		</a>
	<?php else : ?>
		<span class="bg-danger float-right mb-4 p-1 requestFinal text-white">
			Actualmente posees un preaprobado de $ <?php echo number_format($totalActual) ?> que cubre el valor solicitado.
		</span>
	<?php endif ?>
<?php endif ?>
