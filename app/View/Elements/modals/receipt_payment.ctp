<div class="modal fade" id="receipt_payment" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Liquidación de Crédito</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="bodyReciboPago">
				<div class="invoice recibopago">
					<header>
						<div class="row">
							<div class="col-md-4"><img src="<?php echo $this->Html->url("/img/credishop.svg") ?>" /></div>
							<div class="col-md-8 company-details">
								<h2><b>ZÍRO</b></h2>
								<div>DIRECCIÓN: Av Santander 65 - 15 Local 115,</div>
								<div>Manizales, Colombia</div>
								<div>CELULAR: (+57) 3209860583</div>
							</div>
						</div>
					</header>
					<main>
						<div class="row contacts">
							<div class="col invoice-to">
								<h2 class="m-0"><b>Nelfi Torres Alvarez</b></h2>
								<h2 class="m-0"><b>THE ROSELINE</b></h2>
								<h2 class="m-0">43989066</h2>
								<h2 class="m-0">Cra 47 # 53-19 Bello</h2>
								<h2 class="m-0">Cuenta para pagos Ahorros Bancolombia 91204010833 </h2>
							</div>
							<div class="col invoice-details">
								<h1 class="invoice-id">Recibo # 000558</h1>
								<div class="date">Fecha Aprobación: 15 de Agosto de 2020</div>
								<div class="date">Fecha Retiro de Productos: 22 de Agosto de 2020</div>
							</div>
						</div>

						<table class="table ">
							<thead class="thead-light">
								<tr>
									<th>CLIENTE</th>
									<th>DOCUMENTO</th>
									<th>SOLICITÓ</th>
									<th>FECHA SOLICITUD PAGO</th>
									<th>TIPO</th>
									<th class="text-center">VALOR</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="">Carmen Perez Acosta</td>
									<td class="">1017205101</td>
									<td class="">Camila Suárez - Contabilidad</td>
									<td class="">22 de Agosto de 2020</td>
									<td class="">PAGO1</td>
									<td class="text-center">$ 250.000</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="3"></td>
									<td colspan="2">Comisión 5%</td>
									<td class="text-center">- $12.500</td>
								</tr>
								<tr>
									<td colspan="3"></td>
									<td colspan="2">Estudio de Crédito</td>
									<td class="text-center">- $6.000</td>
								</tr>
								<tr>
									<td colspan="3"></td>
									<td colspan="2">* Otros Cargos</td>
									<td class="text-center">- $15.500</td>
								</tr>
								<tr>
									<td colspan="3"></td>
									<td colspan="2">IVA</td>
									<td class="text-center">- $8.300</td>
								</tr>
								<tr>
									<td colspan="3"></td>
									<td colspan="2">TOTAL A PAGAR</td>
									<td class="text-center">$205.500</td>
								</tr>
							</tfoot>
						</table>
						<h2 class="m-0"><b>*Discrimación de Otros Cargos Cobrados</b></h2>
						<table class="table othersdebts">
				          <tbody>
				            <tr>
				              <td>Autenticación Notaría</td>
				              <td>$12.000</td>
				              <td>19 de Julio de 2020</td>
				            </tr>
				            <tr>
				              <td>Impresiones y Fotocopias</td>
				              <td>$2.500</td>
				              <td>11 de Agosto de 2020</td>
				            </tr>
				          </tbody>
				        </table>
					</main>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"> Cerrar</button>
				</div>
			</div>
		</div>
	</div>
</div>
