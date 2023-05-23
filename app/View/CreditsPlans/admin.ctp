<?php if (!empty($datosCuotas)): ?>
  <h2 class="text-center text-danger"> <b>Otros créditos en cobranza</b></h2>
  <table cellpadding="0" cellspacing="0" class="table table-striped table-hover mb-5">
    <thead class="text-primary">
      <tr>
        <!-- <th><?php echo $this->Paginator->sort('id', __('Obligación')); ?></th>-->
        <th>Obligación</th>
        <th>Cliente</th>
        <th>Cédula</th>
        <th>Mora</th>
        <!-- <th>Honorarios</th> -->
        <th>Valor Cuota</th>
        <th>
          Intereses
        </th>
        <th>Saldo Cuota</th>
        <th>Saldo Crédito</th>
        <th>Última Gestión</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($datosCuotas)): ?>
        <td class="text-center">
          No hay registro de mora
        </td>
      <?php else:
          // var_dump($credito);
          // var_dump($datosCuotas);
          ?>
        <?php $creditsData = []; ?>
        <?php foreach ($datosCuotas as $key => $value): ?>
          <?php if ($value["Credit"]["id"] == $credito["Credit"]["id"]): ?>
            <?php continue ?>
          <?php endif ?>
          <?php if (!in_array($value["Credit"]["credits_request_id"],$creditsData)): ?>
            <?php
              $creditsData[] = $value["Credit"]["credits_request_id"];
             ?>
          <?php else: ?>
            <?php echo 1; ?>
            <?php continue; ?>
          <?php endif ?>
          <tr>
            <td>
              <b><?php echo $value["Credit"]["code_pay"]; ?></b>
            </td>
            <td class="capt">
              <?php echo $value["Customer"]["name"] ?> <?php echo $value["Customer"]["last_name"] ?>
            </td>
            <td>
              <?php echo $value["Customer"]["identification"] ?>
            </td>
            <td><?php echo $value["0"]["Credit__dias"] ?> dias</td>

            <td>
              $<?php echo number_format($value["Credit"]["quota_value"]) ?>
            </td>
            <td>
              $<?php echo number_format($value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"]) ?>
            </td>
            <td>
              <?php $totalDeuda = $value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"] + ($value["CreditsPlan"]["capital_value"]-$value["CreditsPlan"]["capital_payment"]) + ($value["CreditsPlan"]["interest_value"]-$value["CreditsPlan"]["interest_payment"]) + ($value["CreditsPlan"]["others_value"]-$value["CreditsPlan"]["others_payment"]) ?>
              $<?php echo number_format($totalDeuda) ?>
            </td>
            <td>
              $<?php echo number_format($value["Credit"]["value_pending"]) ?>
            </td>
            <td>
              <?php echo strtoupper($value["User"]["name"]) ?>  <?php echo is_null($value["Credit"]["admin_date"]) ? "" : " / ".date("d-m-Y H:i:A",strtotime($value["Credit"]["admin_date"])) ?>
            </td>
            <td class="td-actions">
              <a data-toggle="modal" class="card-link btn btn-primary btn-sm text-white adminQuote" data-quote="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["id"]) ?>" data-credit="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["credit_id"]) ?>" data-datplani="<?php echo $value["CreditsPlan"]["id"] ?>" data-datplanc="<?php echo $value["CreditsPlan"]["credit_id"] ?>" data-tab="1" data-parametro="<?php echo htmlspecialchars(json_encode($this->request->query), ENT_QUOTES, 'UTF-8') ?>">
                Gestión <i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title=""></i>
              </a>
            </td>
          </tr>
        <?php endforeach ?>
      <?php endif ?>
    </tbody>
  </table>
  <hr>
<?php endif ?>
<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
  <thead class="text-primary">
    <tr>
      <th># Obligación</th>
      <th>Cliente</th>
      <th>Cédula</th>
      <th>Celular</th>
      <th>Mora</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?php echo $quote["Credit"]["code_pay"]; ?></td>
      <td class="upper"><?php echo $quote["Customer"]["name"]; ?></td>
      <td><?php echo $quote["Customer"]["identification"]; ?></td>
      <td><?php echo $quote["Customer"]["phone"]; ?></td>
      <td><?php echo $quote["CreditsPlan"]["days"]; ?> días</td>
      <td class="td-actions">

        <a href="#" class="card-link btn btn-outline-secondary btn-sm viewCustomerRequest" data-customer="<?php echo $this->Utilidades->encrypt($quote["Customer"]["id"]) ?>" data-request="<?php echo $this->Utilidades->encrypt($quote["Credit"]["credits_request_id"]) ?>"  >
          <i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver solicitud"></i>
        </a>




        <?php if (AuthComponent::user("role") != 11): ?>

          <a href="<?php echo $this->Html->url(array("controller"=>"credits_requests",'action' => 'credit_detail',$this->Utilidades->encrypt($quote["Credit"]['id']))); ?>" class="card-link btn btn-outline-secondary btn-sm detailCredit2">
            <i class="fa fa-file" data-toggle="tooltip"  data-placement="top" title="" data-original-title="Detalle del crédito"></i>
          </a>
          <a href="https://2cs.co/mas/webcall.php?ext=101&telefono=<?php echo $quote["Customer"]["phone"]?>&cliente=mascreditos" class="card-link btn btn-outline-secondary btn-sm dataCallBtn" data-quote="<?php echo $this->Utilidades->encrypt($quote["CreditsPlan"]["id"]) ?>" data-number="<?php echo $quote["Customer"]["phone"] ?>">
            <i class="fa fa-phone" data-toggle="tooltip" data-placement="top" title="" data-original-title="Llamar"></i>
          </a>
        <?php else: ?>
          <a href="<?php echo $this->Html->url(array("controller"=>"credits_requests",'action' => 'credit_detail',$this->Utilidades->encrypt($quote["Credit"]['id']))); ?>" data-toggle="tooltip"  data-placement="top" title="" data-original-title="Detallar crédito y/o abonar" class="card-link btn btn-outline-secondary btn-sm detailCredit2" title="Detallar crédito y/o abonar">
          <i class="fa fa-money"></i>
        </a>
        <?php endif ?>

        <a class="card-link btn btn-primary btn-sm text-white sendIndividualMensaje" data-number="<?php echo $quote["Customer"]["phone"]; ?>" data-id="<?php echo $this->Utilidades->encrypt($quote["CreditsPlan"]["id"]) ?>" >
            <i class="fa fa-envelope-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Enviar mensaje"></i>
        </a>

        <?php if (AuthComponent::user("role") == 9): ?>

          <a href="/" class="card-link btn btn-outline-secondary btn-sm changeJuridico" data-quote="<?php echo $this->Utilidades->encrypt($quote["CreditsPlan"]["id"]) ?>" data-credit="<?php echo $this->Utilidades->encrypt($quote["CreditsPlan"]["credit_id"]) ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Enviar a cobro jurídico">
            <i class="fa fa-arrow-right"></i> Enviar a Jurídico
          </a>
        <?php endif ?>
      </td>
    </tr>
  </tbody>
</table>
<ul class="nav nav-pills nav-justified" id="options_payments" role="tablist">
  <li class="nav-item">
    <a class="nav-link <?php echo $tab == 1 ? "active" : "" ?> tabSelect" data-tab="1" id="commitment-tab" data-toggle="tab" href="#commitment" role="tab" aria-controls="commitment" aria-selected="false">Compromisos</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $tab == 2 ? "active" : "" ?> tabSelect" data-tab="2" id="notes-tab" data-toggle="tab" href="#notes" role="tab" aria-controls="notes" aria-selected="false">Notas</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $tab == 3 ? "active" : "" ?> tabSelect" data-tab="3" id="home-tab" data-toggle="tab" href="#history" role="tab" aria-controls="home" aria-selected="true">Historial de gestión</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade <?php echo $tab == 1 ? "show active" : "" ?> " id="commitment" role="tabpanel" aria-labelledby="commitment-tab">
    <div class="row">
      <div class="col-md-6">
        <div class="title-tables">
          <h3 class="upper text-info d-inline">Compromisos generados con el cliente</h3>
        </div>
      </div>
      <div class="col-md-6 text-right">
         <a class="card-link btn btn-secondary btn-xs text-white" id="createCompromiso" data-id="<?php echo $this->Utilidades->encrypt($quote["CreditsPlan"]["id"]) ?>">
              <i class="fa fa-plus"></i> Añadir Compromiso
        </a>
      </div>
    </div>
    <div class="table-responsive">
    <table cellpadding="0" cellspacing="0" class="table table-striped table-hover mt-2">
        <thead>
          <tr>
            <th>Detalle Compromiso</th>
            <th>Fecha de Creación</th>
            <th>Fecha de Compromiso</th>
            <th>Creador</th>
            <th>Acciones</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($commitments)): ?>
            <tr>
              <td colspan="6" class="text-center">
                No hay compromisos
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($commitments as $key => $value): ?>
              <tr>
                <td>
                  <?php echo $value["Commitment"]["commitment"] ?>
                </td>
                <td>
                  <?php echo date("d-m-Y h:i:A",strtotime($value["Commitment"]["created"])) ?>
                </td>
                <td>
                  <?php echo date("d-m-Y",strtotime($value["Commitment"]["deadline"])) ?>
                </td>
                <td>
                  <?php echo $value["User"]["name"] ?>
                </td>
                <td>
                  <?php if ($value["Commitment"]["state"] == 0): ?>
                    <div class="form-group">
                    <select class="form-control form-control-sm stateCommitment" data-id="<?php echo $this->Utilidades->encrypt($value["Commitment"]["id"]) ?>">
                      <option value="">Seleccionar</option>
                      <option value="1">Cumplido</option>
                      <option value="2">Incumplido</option>
                    </select>
                  </div>
                  <?php endif ?>
                </td>
                <td>
                  <?php if ($value["Commitment"]["state"] == 0): ?>
                    <?php

                        $date1 = new DateTime(date("Y-m-d"));
                        $date2 = new DateTime($value["Commitment"]["deadline"]);
                        $diff = $date1->diff($date2);

                     ?>
                     <?php if ($diff->invert == 0): ?>
                       <span class="btn btn-success btn-sm">Faltan <?php echo $diff->days ?> días</span>
                      <?php else: ?>
                       <span class="btn btn-danger btn-sm">Retrazo de <?php echo $diff->days ?> días</span>
                     <?php endif ?>
                  <?php else: ?>
                    <span class="btn btn-secondary btn-sm"><?php echo $value["Commitment"]["state"] == 1 ? "Cumplido" : "Incumplido" ?></span>
                  <?php endif ?>
                </td>
              </tr>
            <?php endforeach ?>
          <?php endif ?>
        </tbody>
      </table>
  </div>
  </div>
  <div class="tab-pane fade <?php echo $tab == 2 ? "show active" : "" ?>" id="notes" role="tabpanel" aria-labelledby="notes-tab">
    <div class="title-tables">
      <h3 class="upper text-info d-inline">Notas</h3>
    </div>
    <?php echo $this->Form->create('Note', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left')); ?>
      <?php echo $this->Form->input('credits_plan_id', array('type' => 'hidden','label'=>false,'div'=>false,"value"=>$quote["CreditsPlan"]["id"])); ?>
      <?php echo $this->Form->input('user_id', array('type' => 'hidden','label'=>false,'div'=>false,"value"=> AuthComponent::user("id") )); ?>
      <?php echo $this->Form->input('type', array('type' => 'hidden','label'=>false,'div'=>false,"value"=> AuthComponent::user("role") == 11 ? 1 : 0 )); ?>
      <div class="form-group">
          <?php echo $this->Form->input('note', array('class' => 'form-control border-input','label'=>false,'div'=>false,"placeholder"=>"Ingresa la nota para este cliente")); ?>
      </div>
      <button type="submit" class="btn btn-success pull-right">Guardar Nota</button>

    <?php echo $this->Form->end(); ?>
    <div class="mt-4">
      <h2><b>Notas previas</b></h2>

      <?php if (empty($notes)): ?>
        <h3 class="text-center mt-3">
          No hay notas
        </h3>
      <?php else: ?>
        <?php foreach ($notes as $key => $value): ?>
          <div class="card note-list mb-3">
              <div class="card-header">
                Nota dejada el <b><?php echo date("d-m-Y h:i:a",strtotime($value["Note"]["created"])) ?></b> por <?php echo $value["User"]["name"] ?>
              </div>
              <div class="card-body">
                <p class="card-text"><?php echo $value["Note"]["note"] ?></p>
              </div>
          </div>
        <?php endforeach ?>
      <?php endif ?>
    </div>
  </div>
  <div class="tab-pane fade <?php echo $tab == 3 ? "show active" : "" ?>" id="history" role="tabpanel" aria-labelledby="history-tab">
    <div class="title-tables">
      <h3 class="upper text-info d-inline">Historial de Gestión</h3>
    </div>
    <div class="table-responsive">
      <table cellpadding="0" cellspacing="0" class="table table-striped table-hover mt-2">
          <thead>
            <tr>
              <th>Acción</th>
              <th>Fecha de gestión</th>
              <th>Creador</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($history)): ?>
              <tr>
                <td colspan="3" class="text-center">
                  No hay registro de actividad
                </td>
              </tr>
            <?php else: ?>
							<?php



								?>
              <?php foreach ($history as $key => $value): ?>
                <tr>
                  <td><?php echo $value["History"]["action"] ?></td>
                  <td><?php echo date("d-m-Y h:i:A",strtotime($value["History"]["created"])) ?></td>
                  <td><?php echo $value["User"]["name"] ?></td>
                </tr>
              <?php endforeach ?>
            <?php endif ?>
          </tbody>
        </table>
    </div>
  </div>
</div>


