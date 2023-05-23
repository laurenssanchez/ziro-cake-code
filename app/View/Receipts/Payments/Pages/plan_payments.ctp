<?php $totalCapitalDeuda = $priceValue; ?>
<?php $ramdomVals = $couteValue ?>


<table class="table table-striped">
	<thead>
		<tr>
			<th>Cuota</th>
			<th>Fecha pago</th>
			<th>Capital</th>
			<th>Intereses</th>
			<th>Otros</th>
			<th>Cuota</th>
			<th>Saldo</th>
		</tr>
	</thead>
	<tbody>
		<?php $j = 0; ?>
		<?php  for($i = 1; $i<= intval($couteValue); $i++ ): ?>
			<?php 

				$intereses 		= round($priceValue*($data_credit["intRate"]/$frecuency));
				$interesesOtro 	= round($priceValue*($data_credit["intOther"]/$frecuency));
				$capitalC       = $data_credit["cuote"] - $intereses - $interesesOtro;
				$totalCapitalDeuda	-= $capitalC;
				$priceValue     	-= $capitalC;
			?>
			<tr>
				<td><?php echo $i+$j ?></td>
				<?php if ($frecuency == 1): ?>
					<td><?php echo date("d-m-Y",strtotime("+$i month")) ?></td>
				<?php else: ?>					
					<?php $days = (($i+$j)*15); ?>
					<td><?php echo date("d-m-Y",strtotime("+$days days")) ?></td>
				<?php endif ?>
				<td><?php echo number_format(round($capitalC)) ?></td>
				<td><?php echo number_format(round($intereses)) ?></td>
				<td><?php echo number_format(round($interesesOtro)) ?></td>
				<td><?php echo number_format(round($data_credit["cuote"])) ?></td>
				<td><?php echo round($totalCapitalDeuda) < 0 || round($totalCapitalDeuda) < 1 ? 0 : number_format(round($totalCapitalDeuda)) ?></td>
			</tr>

		<?php endfor; ?>
	</tbody>
</table>
<?php if (isset($this->request->data["final"]) ): ?>
	<?php if ($totalActual < $priceValue ): ?>
		<a href="javascript:void(0)" class="btn btn-primary float-right requestFinal mb-4">
			Solicitar cupo
		</a>
	<?php else: ?>
		<span class="bg-danger float-right mb-4 p-1 requestFinal text-white">
			Actualmente posees un preaprobado de $ <?php echo number_format($totalActual) ?> que cubre el valor solicitado.
		</span>
	<?php endif ?>
	
<?php endif ?>
