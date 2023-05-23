<?php if (!is_null($layout)): ?>
    <div class="page-title">
        <div class="title_left">
            <h3><?php echo __('Visualizando').' '.__('Detalle del crédito'); ?></h3>
        </div>

        <div class="title_right">
            <div class="col-md-8 col-sm-8  form-group pull-right top_search">
                <a class="btn btn-sm btn-fill btn-success"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
                    <i class="fa fa-list-alt"></i>
                    <?php echo __('Listar solicitudes'); ?>
                </a>
                <a class="btn btn-sm btn-fill btn-warning"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
                    <i class="fa fa-plus-circle"></i>
                    <?php echo __('Adicionar pago'); ?>
                </a>
            </div>
        </div>
    </div>
<?php endif ?>
<div class="clearfix"></div>
<div class="row">
<?php if (in_array(AuthComponent::user("role"), [1,2,3,4,6,7,9,11])): ?>
    <div class="col-md-12">
        <div class="x_panel">
            <div class="table-responsive">
                <h3 class="text-center"><b>Datos del clientes</b></h3>
                <table class="table table-condensed">
                    <tbody>
                        <tr>
                            <th>
                                Cliente
                            </th>
                            <td class="upper">
                                <?php echo $creditRequest["Customer"]["name"] ?> <?php echo $creditRequest["Customer"]["last_name"] ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Identificación
                            </th>
                            <td>
                                <?php echo $creditRequest["Customer"]["identification"] ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Dirección:</th>
                            <td class="upper">
                                <?php echo $creditRequest["Customer"]["CustomersAddress"]["0"]["address"] ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Teléfono</th>
                            <td>
                                <?php echo $creditRequest["Customer"]["CustomersPhone"]["0"]["phone_number"] ?>
                            </td>
                        </tr>
                        <?php if ($creditRequest["Credit"]["juridico"] == 1): ?>
                            <tr>
                                <th>Estado Cliente</th>
                                <td>
                                    <span class="text-danger">Bloqueo juridico</span>
                                </td>
                            </tr>


                        <?php endif ?>
                         <?php if (isset($totalCredit)): ?>
                            <tr>
                                <th>Deuda actual</th>
                                <td>
                                    <span class="text-danger">$ <?php echo number_format($totalCredit); ?></span>
                                </td>
                            </tr>
                        <?php endif ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
<?php endif ?>
    <div class="<?php echo AuthComponent::user("role") == 5 ? "col-md-12 col-sm-12" : "col-md-12 col-sm-12" ?>">
        <div class="x_panel">
                <h3 class="text-center"><b>Información del crédito / </b>
                 <?php if (isset($totalCredit)): ?>
                            Deuda actual <p class="valuedebt"> <b><span class="text-danger">$<?php echo number_format($totalCredit); ?></span></b></p>
                <?php endif ?>
                </h3>
            <div class="row">
                <div class="col-md-6">
            <div class="table-responsive">
                <table class="table table-condensed">
                    <tbody>
                        <tr>
                            <th>Número de obligación</th>
                            <td>
                                <b><?php echo $creditInfo["CreditsRequest"]["code_pay"]; ?></b>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Total aprobado:
                            </th>
                            <td>
                                $ <?php echo number_format($creditInfo["Credit"]["value_aprooved"]) ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Total retirado
                            </th>
                            <td class="<?php echo $creditInfo["Credit"]["value_request"] == 0 ? "text-success" : "textdanger" ?>">
                                $ <?php echo number_format($creditInfo["Credit"]["value_request"]) ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Frecuencia de pago
                            </th>
                            <td>
								<!-- Frecuencia -->
								<?php
									if ($creditInfo["Credit"]["type"] == 1)
										$tipoCredito= "Mensual";
									else if($creditInfo["Credit"]["type"] == 3)
										$tipoCredito= "45 días";
									else if($creditInfo["Credit"]["type"] == 4)
										$tipoCredito= "60 días";
									else
										$tipoCredito= "Quincenal";
								?>
                                <?php echo $tipoCredito ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Número de cuotas:
                            </th>
                            <td>
                                <?php echo $creditInfo["Credit"]["number_fee"] ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Tasa de interes:</th>
                            <td>
                                <?php echo $creditInfo["Credit"]["interes_rate"] ?> %
                            </td>
                        </tr>
                        <tr>
                            <th>Total crédito</th>
                            <td>
                                $ <?php echo number_format($creditInfo["Credit"]["quota_value"] * $creditInfo["Credit"]["number_fee"]); ?>
                            </td>
                        </tr>

                        <?php if ( (isset($user["User"]["state"]) && $user["User"]["state"] == 0) || $creditInfo["Credit"]["juridico"] == 1 ): ?>
                            <tr>

                                <th>Estado Cliente</th>
                                <td>
                                    <span class="text-danger">Bloqueo juridico</span>
                                </td>
								<td>
								<a href="/" class="card-link btn btn-outline-secondary btn-sm revertJuridico" data-quote="<?php echo $creditInfo["Credit"]["id"] ?>" data-credit="<?php echo $creditInfo["Credit"]["customer_id"] ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="revertir de cobro jurídico">
									<i class="fa fa-arrow-left"></i> revertir de Jurídico
								</a>
								</td>
                            </tr>
                        <?php endif; ?>


                    </tbody>
                </table>
            </div>

                </div>
                <div class="col-md-6">
            <div class="table-responsive">
                <table class="table table-condensed">
                    <tbody>

                        <tr>
                            <th>Tasa otros cargos</th>
                            <td>
                                <?php echo $creditInfo["Credit"]["others_rate"] ?> %
                            </td>
                        </tr>
                        <?php if (in_array(AuthComponent::user("role"), [1,2,3])): ?>
                            <tr>
                                <th>Tasa de mora </th>
                                <td>
                                    <?php echo $creditInfo["Credit"]["debt_rate"] ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Valor cuota:</th>
                            <td>$ <?php echo number_format($creditInfo["Credit"]["quota_value"]) ?></td>
                        </tr>
                        <tr>
                            <th>Fecha de solicitud:</th>
                            <td>
                                <?php echo date("d-m-Y H:i A",strtotime($creditInfo['CreditsRequest']['created'])); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha de aprobación:</th>
                            <td>
                                <?php echo date("d-m-Y H:i A",strtotime($creditInfo['CreditsRequest']['date_admin'])); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha máxima de pago:</th>
                            <td>
                                <?php echo date("d-m-Y",strtotime($creditInfo['Credit']['deadline'])); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Tienda</th>
                            <td><?php echo $creditRequest["ShopCommerce"]["Shop"]["social_reason"] ?> - <?php echo $creditRequest["ShopCommerce"]["name"] ?></td>
                        </tr>

                    </tbody>
                </table>
            </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="table-responsive">
                <h3 class="text-center"><b>Plan de pagos</b></h3>
                <table class="table table-condensed">
                    <thead>
                        <th>#</th>
						<th>Valor a capital</th>
	                    <th>Valor interes</th>
	                    <th>Valor otros cargos</th>
	                    <th>Intereses de Mora</th>
					    <th>Total Abonado</th>
	                    <th class="">Total a pagar cuota</th>
						<!-- <th>Total deuda</th> -->
						<th>Fecha límite</th>
						<th>Estado del pago</th>
						<th>Fecha pago</th>
						<th>Dias en Mora</th>
						</tr>
                        <?php

                            $estadoUser = true;

                            if(isset($user["User"]["state"]) && $user["User"]["state"] == 0){
                                $estadoUser = false;
                            }

                            if ($creditInfo["Credit"]["juridico"] == 1) {
                                $estadoUser = false;
                            }

                         ?>
                        <?php if ( in_array(AuthComponent::user("role"), [6] ) && ( $estadoUser )  ): ?>
                            <th>A pagar</th>
                        <?php endif ?>
                    </thead>
                    <tbody>
                        <?php foreach ($quotes as $key => $value): ?>
                            <tr class="<?php echo $value["CreditsPlan"]["credit_old"] == 10 ? "cuotadicional"  : "" ?>">
                                <td><?php echo $value["CreditsPlan"]["number"] ?></td>
                                <td>$ <?php echo number_format($value["CreditsPlan"]["capital_value"]) ?></td>
                                <td>$ <?php echo number_format($value["CreditsPlan"]["interest_value"]) ?></td>
                                <td>$ <?php echo number_format($value["CreditsPlan"]["others_value"]) ?></td>
                                <td>
									<!-- echo $totalCredit <= 1 || $value["CreditsPlan"]["state"] == 1 ? 0 : number_format($value["CreditsPlan"]["others_add"]+$value["CreditsPlan"]["interest_add"]+$value["CreditsPlan"]["debt_add"]
                                    + $value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"])-->
                                    $ <?php

									echo  number_format($value["CreditsPlan"]["others_add"]+$value["CreditsPlan"]["interest_add"]+$value["CreditsPlan"]["debt_add"]
                                    + $value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"])

									?>
								</td>
								<td>$<?php  echo  is_null($value["CreditsPlan"]["TotalAbo"])?0:number_format($value["CreditsPlan"]["TotalAbo"]) ?></td>
                                <td>
                                    <?php
                                       // $capital = $value["CreditsPlan"]["capital_value"]-$value["CreditsPlan"]["capital_payment"];
                                        //$interes = $value["CreditsPlan"]["interest_value"]-$value["CreditsPlan"]["interest_payment"];
                                        //$others = $value["CreditsPlan"]["others_value"]-$value["CreditsPlan"]["others_payment"];


										$capital = $value["CreditsPlan"]["capital_value"]; //-$value["CreditsPlan"]["capital_payment"];
										$interes = $value["CreditsPlan"]["interest_value"];//-$value["CreditsPlan"]["interest_payment"];
										$others  = $value["CreditsPlan"]["others_value"];//-$value["CreditsPlan"]["others_payment"];
										$TotalAbonado = $value["CreditsPlan"]["capital_payment"] + $value["CreditsPlan"]["interest_payment"] + $value["CreditsPlan"]["others_payment"];

										$MyFechaQuota = new DateTime(date("Y-m-d",strtotime($value["CreditsPlan"]["deadline"])));

										$MyFechaPago  = new DateTime(date("Y-m-d",strtotime($value["CreditsPlan"]["date_payment"])));



										$MyfechaActual =  new DateTime(date("Y-m-d"));

										$FechaComparar =  ($value["CreditsPlan"]["state"] == 0 ? $creditInfo["Credit"]["juridico"]==1 ? (new DateTime(date("Y-m-d",strtotime($creditInfo["Credit"]["date_juridico"])))):$MyfechaActual:$MyFechaPago);

                                       // echo $FechaComparar->format("d-m-Y");

										$dias = 0;

										if ($MyFechaQuota <= $FechaComparar) {

											$deadline = $MyFechaQuota;

											$nowDate =  $FechaComparar;//new DateTime(date("Y-m-d"));
											$difference = $deadline->diff($nowDate);
											$days = $difference->days;
											$dias = $days;
										}else{
											$dias = 0;
										}



                                    ?>
                                    <!--$ <?php echo $totalCredit <= 1 || $value["CreditsPlan"]["state"] == 1 ? 0 : number_format($capital+$interes+$others+$value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"]+$value["CreditsPlan"]["others_add"]+$value["CreditsPlan"]["interest_add"]+$value["CreditsPlan"]["debt_add"]) ?></td>-->
									$  <?php

                                        $cuotaNormal = $capital;

                                        $valorCuota  = ($cuotaNormal+$others+$interes+$value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"]) - (is_null($value["CreditsPlan"]["TotalAbo"])?0:$value["CreditsPlan"]["TotalAbo"]);

                                        if ($valorCuota <= 1) {
                                            $valorCuota = 0;
                                        }


									 echo $value["CreditsPlan"]["state"] == 1 ? 0 :number_format($valorCuota) ?></td>
                                <td>
                                    <?php echo date("d-m-Y",strtotime($value['CreditsPlan']['deadline'])); ?>
                                </td>
                                <td><?php echo $value["CreditsPlan"]["state"] == 0 ? "No pago" : "Pagado"; ?></td>


									<td><?php echo $value["CreditsPlan"]["state"] == 0 ? "" :$value["CreditsPlan"]["date_payment"] ;?>
                                    <?php $value["CreditsPlan"]["days"] = $value["CreditsPlan"]["days"] < 0 ? 0 : $value["CreditsPlan"]["days"]; ?>
									<td><?php echo $value["CreditsPlan"]["state"] == 0 ? $dias : $dias ?></td>

                                <td>
                                    <?php if ($value["CreditsPlan"]["state"] == 0 && in_array(AuthComponent::user("role"), [6]) && ( $estadoUser ) && $valorCuota > 0 ): ?>

                                        <a href="<?php echo $this->Html->url(["controller"=>"credits","action"=>"payment_detail",$this->Utilidades->encrypt($creditInfo["CreditsRequest"]["id"]),"view"]) ?>" class="btn btn-secondary">Ir a pagar</a>
                                    <?php endif ?>
                                </td>

                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if (AuthComponent::user("role") == 11 && isset($totalCredit) && $totalCredit > 0): ?>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 text-center">
        <div class="x_panel">
            <div class="page-title">
                <form action="" method="POST" id="formPaymentTotal">
                    <input type="hidden" name="type" value="2">
                    <input type="hidden" id="creditIDData" name="credit_id" value="<?php echo $this->Utilidades->encrypt($creditRequest["Credit"]["id"]); ?>">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>
                                    Realizar pago o abono
                                </th>
                                <td>
                                    <b><span class="moresize">$ <?php echo number_format($totalCredit) ?></span></b>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Escribir valor
                                </th>
                                <td>
                                    <input name="value" class="form-control" type="number" id="otherValueNum" min="1" placeholder="Ingresa el valor a pagar" max="<?php echo $totalCredit ?>">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="p-4">
                                    <button type="submit" id="paymentFinal" class="btn btn-success btn-block">
                                        Pagar
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php endif ?>

<?php echo $this->Html->script("informes/cobranzas/admin.js?".rand(),           array('block' => 'AppScript'));?>
