<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><strong>Información personal</strong></a>
  </li>
  <?php if (isset($request)): ?>
    <li class="nav-item">
      <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab" aria-controls="documents" aria-selected="true"><strong>Documentos</strong></a>
    </li>
  <?php endif ?>
  <?php if (isset($request) && !empty($request["CreditsRequest"]["vars_score"]) && in_array(AuthComponent::user("role"), [1,2,3])): ?>
    <li class="nav-item">
      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><strong>Información Centrales</strong></a>
    </li>
    <?php if (!empty($request["CreditsRequest"]["response_score"])): ?>
      <li class="nav-item">
        <a class="nav-link" id="procredito-tab" data-toggle="tab" href="#procredito" role="tab" aria-controls="procredito" aria-selected="false"><strong>Respuesta centrales</strong></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="huella-tab" data-toggle="tab" href="#huella" role="tab" aria-controls="huella" aria-selected="false"><strong>Huella de búsqueda</strong></a>
      </li>
    <?php endif ?>
  <?php endif ?>
  <?php if (in_array(AuthComponent::user("role"), [1,2,3])): ?>
    <li class="nav-item">
      <a class="nav-link" id="historial-tab" data-toggle="tab" href="#historial" role="tab" aria-controls="historial" aria-selected="false"><strong>Historial de créditos</strong></a>
    </li>
  <?php endif ?>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

      <div class="container pb-5">
        <form action="/" method="POST" id="formDataCustomers">


           <div class="row">
              <div class="col-md-12">
                  <div class="content-tittles">
                    <div>
                      <h5 class="mt-3 mb-3"><b>Solicitud diligenciada <?php echo empty($request["CreditsRequest"]["empresa_id"]) ? " por el cliente" : "por la empresa" ?> </b>
                        <?php if (isset($request) && in_array(AuthComponent::user("role"), [1,2,3])): ?>
                          <a href="" id="editarCliente" class="btn btn-sm btn-warning">
                            <i class="fa fa-edit"></i>
                          </a>
                          <?php if (!is_null($customer["Customer"]["url_files"])): ?>
                            <a href="" id="descargaIMG" class="btn btn-sm btn-warning" data-id="<?php echo $customer["Customer"]["id"] ?>">
                              <i class="fa fa-download"></i>
                            </a>
                          <?php endif ?>
                        <?php endif ?>
                      </h5>
                    </div>
                  </div>
                <div class="row">
									<div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <label>ID Usuario </label>
                      <input
												disabled
												required
												type="text"
												name="data[Customer][user_id_commerce]"
												value="<?php echo $customer["Customer"]["user_id_commerce"] ?>"
												class="form-control camposFormulario">
                    </div>
                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <?php if (isset($request)): ?>
                        <input name="data[id]" required type="hidden" value="<?php echo $request["CreditsRequest"]["id"] ?>" class="form-control">
                      <?php endif ?>
                      <input name="data[Customer][id]" required type="hidden" value="<?php echo $customer["Customer"]["id"] ?>" class="form-control">
                      <label>Nit</label>
                      <input disabled required name="data[Customer][nit]" type="text" value="<?php echo $customer["Customer"]["nit"] ?>" class="form-control camposFormulario">
                    </div>
                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <label>Nombre de la empresa</label>
                      <input disabled required type="text" name="data[Customer][buss_name]" value="<?php echo $customer["Customer"]["buss_name"] ?>" class="form-control camposFormulario">
                    </div>
                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <label>Ciudad</label>
                      <?php echo $this->Form->input('CustomersAddress.address_city', array('class' => 'form-control camposFormulario','label'=>false,'div'=>false,'placeholder'=>"Ciudad","required", "value" => $customer["CustomersAddress"][0]["address_city"] , "disabled", "options" => Configure::read("CIUDADES"),"default" => "MEDELLIN" )); ?>
                      <!-- <input disabled required type="text" name="data[CustomersAddress][address_city]" value="<?php  //echo $customer["CustomersAddress"][0]["address_city"] ?>" class="form-control camposFormulario"> -->
                    </div>
                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <label>Dirección de la Empresa</label>
                      <input disabled required type="text" name="data[CustomersAddress][address_street]" value="<?php echo $customer["CustomersAddress"][0]["address_street"] ?>" class="form-control camposFormulario">
                    </div>
                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <label>Página We/Redes Sociales</label>
                      <input disabled  type="text" name="data[Customer][serv_name]" value="<?php echo $customer["Customer"]["serv_name"] ?>" class="form-control camposFormulario">
                    </div>
                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <label>Ingresos Mensuales</label>
                      <input disabled  name="data[Customer][monthly_income]" type="number" value="<?php echo $customer["Customer"]["monthly_income"] ?>" class="form-control camposFormulario">
                    </div>
                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <label>Egresos Mensuales</label>
                      <input disabled  name="data[Customer][monthly_expenses]" type="number" value="<?php echo $customer["Customer"]["monthly_expenses"] ?>" class="form-control camposFormulario">
                    </div>

                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <label>Registrado en Cámara de Comercio</label>
                      <input disabled required type="text" name="data[Customer][cci]" value="<?php echo $customer["Customer"]["cci"] ?>" class="form-control camposFormulario">
                    </div>

                </div>
                <hr>

                 <div class="row">
                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <?php if (isset($request)): ?>
                        <input name="data[id]" required type="hidden" value="<?php echo $request["CreditsRequest"]["id"] ?>" class="form-control">
                      <?php endif ?>
                      <input name="data[Customer][id]" required type="hidden" value="<?php echo $customer["Customer"]["id"] ?>" class="form-control">
                      <label>Nombres</label>
                      <input disabled required name="data[Customer][name]" type="text" value="<?php echo $customer["Customer"]["name"] ?>" class="form-control camposFormulario">
                    </div>
                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <label>Apellidos</label>
                      <input disabled required type="text" name="data[Customer][last_name]" value="<?php echo $customer["Customer"]["last_name"] ?>" class="form-control camposFormulario">
                    </div>
                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <label>Tipo de identificación</label>
                      <select name="data[Customer][identification_type]" disabled required class="form-control camposFormulario">
                        <?php foreach (Configure::read("Identification_TYPE") as $key => $value): ?>
                           <option value="<?php echo $key ?>" <?php echo $key == $customer["Customer"]["identification_type"] ? "selected" : "" ?> ><?php echo $value ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>
                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <label>Número de identificación</label>
                      <input disabled required type="text" name="data[Customer][identification]" value="<?php echo $customer["Customer"]["identification"] ?>" class="form-control camposFormulario">
                    </div>



                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <label>Número de Celular</label>
                      <input disabled required type="hidden" name="data[CustomersPhone][id]" value="<?php echo $customer["CustomersPhone"][0]["id"] ?>" class="form-control camposFormulario">
                      <input disabled required type="number" name="data[CustomersPhone][phone_number]" value="<?php echo $customer["CustomersPhone"][0]["phone_number"] ?>" class="form-control camposFormulario">
                    </div>
                     <div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <label>Email</label>
                      <input disabled <?php echo !empty($customer["Customer"]["email"]) ? "required" : "" ?> type="text" name="data[Customer][email]" value="<?php echo $customer["Customer"]["email"] ?>" class="form-control camposFormulario nocapt">
                    </div>

                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <label>Dirección</label>
                      <input disabled required type="text" name="data[CustomersAddress][address]" value="<?php echo $customer["CustomersAddress"][0]["address"] ?>" class="form-control camposFormulario">
                    </div>

					<?php if ( isset($request) && !is_null($request["CreditsRequest"]["pagare_inicial"])): ?>
                       <div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <label>Pagaré inicial enviado: </label>
                      <a href="<?php echo $this->Html->url("/files/pagares/initals/".$request["CreditsRequest"]["pagare_inicial"]) ?>" target="_blank" class="btn btn-info">
                        Ver archivo
                      </a>
                    </div>
                    <?php endif ?>
                    <?php if ( isset($request) && !is_null($request["CreditsRequest"]["pagare_final"])): ?>
                       <div class="form-group col-md-3 col-sm-12 col-xs-12">
                      <label>Pagaré inicial enviado: </label>
                      <a href="<?php echo $this->Html->url("/files/pagares/finals/".$request["CreditsRequest"]["pagare_final"]) ?>" target="_blank" class="btn btn-info">
                        Ver archivo
                      </a>
                    </div>
                    <?php endif ?>
                </div>
                <hr>

                <!--<div class="row">-->
                <!--  <div class="col-md-12 p-0">-->
                <!--    <h6 class="mb-0"><b>Referencia personal o familiar 1</b></h6>-->
                <!--  </div>-->
                <!--</div>-->
                <!--<div class="row">-->
                <!--  <div class="form-group col-md-4 col-sm-12 col-xs-12">-->
                <!--    <label>Nombre Completo</label>-->
                <!--    <input disabled required type="hidden" name="data[CustomersReference][0][id]" value="<?php echo $customer["CustomersReference"][0]["id"] ?>" class="form-control camposFormulario">-->
                <!--    <input disabled required type="text" name="data[CustomersReference][0][name]" value="<?php echo $customer["CustomersReference"][0]["name"] ?>" class="form-control camposFormulario">-->
                <!--  </div>-->
                <!--  <div class="form-group col-md-4 col-sm-12 col-xs-12">-->
                <!--    <label>Parentesco</label>-->
                <!--    <input disabled required type="text" name="data[CustomersReference][0][relationship]" value="<?php echo $customer["CustomersReference"][0]["relationship"] ?>" class="form-control camposFormulario">-->
                <!--  </div>-->
                <!--  <div class="form-group col-md-4 col-sm-12 col-xs-12">-->
                <!--    <label>Celular o Teléfono</label>-->
                <!--    <a href="https://2cs.co/mas/webcall.php?ext=101&telefono=<?php echo $customer["CustomersReference"][0]["phone"] ?>&cliente=mascreditos" target="_blank" class="card-link btn btn-outline-secondary btn-sm">-->
                <!--        <i class="fa fa-phone" data-toggle="tooltip" data-placement="top" title="" data-original-title="Llamar"></i>-->
                <!--    </a>-->
                <!--    <input disabled required type="text" name="data[CustomersReference][0][phone]" value="<?php echo $customer["CustomersReference"][0]["phone"] ?>" class="form-control camposFormulario">-->
                <!--  </div>                                                        -->
                <!--</div> -->

                <!--<div class="row">-->
                <!--  <div class="col-md-12 p-0">-->
                <!--    <h6 class="mb-0"><b>Referencia personal o familiar 2</b></h6>-->
                <!--  </div>-->
                <!--</div>-->
                <!--<div class="row">-->
                <!--  <div class="form-group col-md-4 col-sm-12 col-xs-12">-->
                <!--    <label>Nombre Completo</label>-->
                <!--    <input disabled required type="hidden" name="data[CustomersReference][1][id]" value="<?php echo $customer["CustomersReference"][1]["id"] ?>" class="form-control camposFormulario">-->
                <!--    <input disabled required type="text" name="data[CustomersReference][1][name]" value="<?php echo $customer["CustomersReference"][1]["name"] ?>" class="form-control camposFormulario">-->
                <!--  </div>-->
                <!--  <div class="form-group col-md-4 col-sm-12 col-xs-12">-->
                <!--    <label>Parentesco</label>-->
                <!--    <input disabled required type="text" name="data[CustomersReference][1][relationship]" value="<?php echo $customer["CustomersReference"][1]["relationship"] ?>" class="form-control camposFormulario">-->
                <!--  </div>-->
                <!--  <div class="form-group col-md-4 col-sm-12 col-xs-12">-->
                <!--    <label>Celular o Teléfono</label>-->
                <!--    <a href="https://2cs.co/mas/webcall.php?ext=101&telefono=<?php echo $customer["CustomersReference"][1]["phone"] ?>&cliente=mascreditos" target="_blank" class="card-link btn btn-outline-secondary btn-sm">-->
                <!--        <i class="fa fa-phone" data-toggle="tooltip" data-placement="top" title="" data-original-title="Llamar"></i>-->
                <!--    </a>-->
                <!--    <input disabled required type="text" name="data[CustomersReference][1][phone]" value="<?php echo $customer["CustomersReference"][1]["phone"] ?>" class="form-control camposFormulario">-->
                <!--  </div>                                                        -->
                <!--</div> -->

                <!--<div class="row">-->
                <!--  <div class="col-md-12 p-0">-->
                <!--    <h6 class="mb-0"><b>Referencia personal o familiar 3</b></h6>-->
                <!--  </div>-->
                <!--</div>-->
                <!--<div class="row">-->
                <!--  <div class="form-group col-md-4 col-sm-12 col-xs-12">-->
                <!--    <label>Nombre Completo</label>-->
                <!--    <input disabled required type="hidden" name="data[CustomersReference][2][id]" value="<?php echo $customer["CustomersReference"][2]["id"] ?>" class="form-control camposFormulario">-->
                <!--    <input disabled required type="text" name="data[CustomersReference][2][name]" value="<?php echo $customer["CustomersReference"][2]["name"] ?>" class="form-control camposFormulario">-->
                <!--  </div>-->
                <!--  <div class="form-group col-md-4 col-sm-12 col-xs-12">-->
                <!--    <label>Parentesco</label>-->
                <!--    <input disabled required type="text" name="data[CustomersReference][2][relationship]" value="<?php echo $customer["CustomersReference"][2]["relationship"] ?>" class="form-control camposFormulario">-->
                <!--  </div>-->
                <!--  <div class="form-group col-md-4 col-sm-12 col-xs-12">-->
                <!--    <label>Celular o Teléfono</label>-->
                <!--    <a href="https://2cs.co/mas/webcall.php?ext=101&telefono=<?php echo $customer["CustomersReference"][2]["phone"] ?>&cliente=mascreditos" target="_blank" class="card-link btn btn-outline-secondary btn-sm">-->
                <!--        <i class="fa fa-phone" data-toggle="tooltip" data-placement="top" title="" data-original-title="Llamar"></i>-->
                <!--    </a>                    -->
                <!--    <input disabled required type="text" name="data[CustomersReference][2][phone]" value="<?php echo $customer["CustomersReference"][2]["phone"] ?>" class="form-control camposFormulario">-->
                <!--  </div>                                                        -->
                <!--</div> -->

                 <?php if (isset($request) && in_array(AuthComponent::user("role"), [1,2,3])): ?>
                  <div class="col-md-12">
                    <input type="submit" disabled class="float-right btn btn-success camposFormulario" value="Guardar información">
                  </div>
                <?php endif; ?>

              </div>
           </div>
         </form>

				 <div class="title-tables">
          <h3 class="upper text-info d-inline">Fotos Cliente</h3>
              </div>
							<div class="py-4">

				 <a target="_blank" href="<?php echo Router::url("/",true) ?>files/customers/<?php echo $customer["Customer"]["document_file_up"] ?>" class="btn btn-sm btn-danger">
            Ver foto frontal <i class="fa fa-file"></i>
					</a>
					<a target="_blank" href="<?php echo Router::url("/",true) ?>files/customers/<?php echo $customer["Customer"]["document_file_down"] ?>" class="btn btn-sm btn-danger">
            Ver foto Reverso <i class="fa fa-file"></i>
					</a>
					<a target="_blank" href="<?php echo Router::url("/",true) ?>files/customers/<?php echo $customer["Customer"]["image_file"] ?>" class="btn btn-sm btn-danger">
            Ver selfie <i class="fa fa-file"></i>
					</a>
         <?php if (isset($request)): ?>
							</div>


               <div class="title-tables">
                  <h3 class="upper text-info d-inline">Notas</h3>
              </div>
              <?php echo $this->Form->create('NotesCustomer', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left')); ?>
                <?php echo $this->Form->input('credits_request_id', array('type' => 'hidden','label'=>false,'div'=>false,"value"=>$request["CreditsRequest"]["id"] )); ?>
                <?php echo $this->Form->input('user_id', array('type' => 'hidden','label'=>false,'div'=>false,"value"=> AuthComponent::user("id") )); ?>
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
                          Nota dejada el <b><?php echo date("d-m-Y h:i:A",strtotime($value["NotesCustomer"]["created"])) ?></b> por <?php echo $value["User"]["name"] ?>
                        </div>
                        <div class="card-body">
                          <p class="card-text"><?php echo $value["NotesCustomer"]["note"] ?></p>
                        </div>
                    </div>
                  <?php endforeach ?>
                <?php endif ?>
              </div>
            </div>

         <?php endif ?>


  </div>
  <?php if (isset($request)): ?>
    <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
        <div class="title-tables">
            <h3 class="upper text-info d-inline text-center mb-5">Documentos asociados</h3>
            <?php if (isset($request)): ?>
              <?php if (in_array(AuthComponent::user("role"), [2,3]) && empty($request["CreditsRequest"]["empresa_id"])): ?>

                  <?php echo $this->Form->create('Document', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal mt-5 form-label-left',"type" => "file")); ?>
                    <?php echo $this->Form->input('credits_request_id', array('type' => 'hidden','label'=>false,'div'=>false,"value"=>$request["CreditsRequest"]["id"] )); ?>
                    <?php echo $this->Form->input('state_request', array('type' => 'hidden','label'=>false,'div'=>false,"value"=>$request["CreditsRequest"]["state"] )); ?>
                    <?php echo $this->Form->input('user_id', array('type' => 'hidden','label'=>false,'div'=>false,"value"=> AuthComponent::user("id") )); ?>
                    <div class="form-group">
                        <?php echo $this->Form->input('file', array('class' => 'form-control border-input','label'=>false,'div'=>false,"label"=>"Selecciona el documento a subir para el estado actual de la solicitud de crédito","data-parsley-fileextension" => 1,"type" => "file","required")); ?>
                    </div>
                    <button type="submit" class="btn btn-success pull-right">Guardar documento</button>

                <?php echo $this->Form->end(); ?>
              <?php endif ?>

              <div class="mt-4">
                  <?php $estados = ["0" => "Recibidos","1" => "Estudio", "2" => "Pendientes","3" => "Aprobados","4" => "Negados", "5" => "Desembolso"] ?>
                  <?php if (AuthComponent::user("role") == 15): ?>
                    <h2><b>Documentos asociados</b></h2>
                  <?php else: ?>
                    <h2><b>Documentos previos</b></h2>
                  <?php endif ?>


                  <?php if (empty($documents)): ?>
                    <h3 class="text-center mt-3">
                      No hay documentos
                    </h3>
                  <?php else: ?>
                    <?php foreach ($documents as $key => $value): ?>
                      <div class="card note-list mb-3">
                          <div class="card-header">
                            <?php if (AuthComponent::user("role") == 15): ?>
                              <?php if ($value["Document"]["user_id"] == AuthComponent::user("id")): ?>
                                <b>Documentos iniciales</b> <br>
                              <?php else: ?>
                                <b>Documentos de la resolución</b> <br>
                              <?php endif ?>
                            <?php endif ?>
                            Documento subido el <b><?php echo date("d-m-Y h:i:A",strtotime($value["Document"]["created"])) ?></b> por <?php echo $value["User"]["name"] ?> <br>
                            Estado de la solicitud: <b><?php echo $estados[$value["Document"]["state_request"]] ?></b>
                          </div>
                          <div class="card-body">
                            <p class="card-text">
                              <a target="_blank" href="<?php echo Router::url("/",true) ?>files/documents/<?php echo $value["Document"]["file"] ?>" class="btn btn-sm btn-danger">
                                Ver documento <i class="fa fa-file"></i>
                              </a>
                              <?php if ($value["Document"]["type"] == 1): ?>

                                <a href="<?php echo $this->Html->url(array('controller' => 'customers', 'action' => 'delete_document',$this->Utilidades->encrypt($value['Document']['id']))); ?>" class="btn btn-sm btn-warning deleteDoc">
                                  Eliminar documento <i class="fa fa-trash"></i>
                                </a>
                              <?php endif ?>
                            </p>
                          </div>
                      </div>
                    <?php endforeach ?>
                  <?php endif ?>
                </div>

              <?php endif ?>
        </div>
    </div>
  <?php endif ?>
  <?php if (isset($request) && !empty($request["CreditsRequest"]["vars_score"]) && in_array(AuthComponent::user("role"), [1,2,3])): ?>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">


        <?php

          $total = $request["CreditsRequest"]["total_score"];
          $varsSelected = (array) json_decode($request["CreditsRequest"]["vars_score"]);

          $classColor = "";
          if($total >= 4.1){
            $classColor = "bg-green";
          }elseif ($total >= 3 && $total <= 4.09) {
            $classColor = "bg-warning";
          }else{
            $classColor = "bg-red";
          }

        ?>
         <h2 class="mt-4 d-block percetage bg-red p-2 text-center <?php echo $classColor ?>">
          Calificación <b> <?php echo $total ?> </b>
        </h2>
        <hr>

        <div class="form-inline content-parameters mb-5">
          <?php foreach ($varsSelected as $key => $value): ?>
            <div class="col-md-12">
              <div class="row">
                <div class="form-group col-md-2 col-sm-12 col-xs-12 mb-2">
                  <p><?php echo str_replace("_", " ", $key) ?></p>
                </div>
                <?php foreach ($value as $keyValue => $valueVar): ?>
                  <div class="form-group col-md-10 col-sm-12 col-xs-12 mb-2">
                    <input type="text" class="form-control" value="<?php echo $valueVar ?>" readonly>
                  </div>
                 <!--  <div class="form-group col-2"><button class="btn btn-secondary mb-2 btn-block"><?php echo $valueVar ?></button></div> -->
                <?php endforeach ?>

              </div>
            </div>
          <?php endforeach ?>
        </div>

    </div>
    <?php if ( isset($request) && !empty($request["CreditsRequest"]["response_score"])): ?>
      <div class="tab-pane fade" id="procredito" role="tabpanel" aria-labelledby="procredito-tab">
        <div class="table-responsive">
          <?php echo $this->Utilidades->jsonToHtml($request["CreditsRequest"]["response_score"]) ?>
        </div>
      </div>
      <div class="tab-pane fade" id="huella" role="tabpanel" aria-labelledby="huella-tab">
        <div class="table-responsive">
          <?php $data_score = json_decode( $request["CreditsRequest"]["response_score"]); ?>
          <?php if (isset($data_score->consultaresumenscore->huella) && !empty($data_score->consultaresumenscore->huella)): ?>
            <?php
              $totalMonth = 0;
              $totalMonthLast = 0;
              $lastMonths = [date("Ym",strtotime("-1 month")), date("Ym",strtotime("-2 month")), date("Ym",strtotime("-3 month")),date("Ym",strtotime("-4 month")), date("Ym",strtotime("-5 month")), date("Ym",strtotime("-6 month"))];

              foreach ($data_score->consultaresumenscore->huella as $key => $value) {
                 $mes = substr($value->FECHA, 4,2);
                 $mesData = substr($value->FECHA, 0,6);
                 if ($mes == date("m")) {
                   $totalMonth++;
                 }
                 if (in_array($mesData,$lastMonths)) {
                   $totalMonthLast++;
                 }
              }
             ?>
             <h2 class="mt-1 mb-1">
               Total veces consultadas este mes: <b><?php echo $totalMonth ?></b>
             </h2>
             <h2 class="mt-1 mb-1">
               Total veces consultadas en los últimos 6 meses: <b><?php echo $totalMonthLast ?></b>
             </h2>
            <?php foreach ($data_score->consultaresumenscore->huella as $key => $value): ?>
              <table class="table table-bordered mb-2 mt-2">
                  <tr>
                    <th>
                      ID Cliente
                    </th>
                    <td>
                      <?php echo $value->ID_CLIENTE ?>
                    </td>
                  </tr>
                  <tr>
                    <th>
                      Nombre Sucursal
                    </th>
                    <td>
                      <?php echo $value->NOMBRE_COMERCIAL ?>
                    </td>
                  </tr>
                  <tr>
                    <th>
                      Sucursal
                    </th>
                    <td>
                      <?php echo $value->SUCURSAL ?>
                    </td>
                  </tr>
                  <tr>
                    <th>
                      Fecha
                    </th>
                    <td>
                      <?php echo $value->FECHA ?>
                    </td>
                  </tr>
                  <tr>
                    <th>
                      Hora
                    </th>
                    <td>
                      <?php echo $value->HORA ?>
                    </td>
                  </tr>
              </table>
            <?php endforeach ?>
          <?php endif ?>
        </div>
      </div>
    <?php endif ?>
  <?php endif ?>
  <?php if (in_array(AuthComponent::user("role"), [1,2,3])): ?>
    <div class="tab-pane fade" id="historial" role="tabpanel" aria-labelledby="historial-tab">
      <div class="table-responsive">
            <table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
              <thead class="">
                <tr>
                  <!-- <th>Proveedor</th> -->
                  <th><?php echo __('ID'); ?></th>
                  <th><?php echo __('# Obligación'); ?></th>
                  <th><?php echo __('F. Aprobado'); ?></th>
                  <th><?php echo __('Aprobado'); ?></th>
                  <th><?php echo __('Retirado'); ?></th>
                  <th><?php echo __('Estado'); ?></th>
                  <th><?php echo __('Jurídico'); ?></th>
                  <th><?php echo __('Saldo'); ?></th>
                  <th><?php echo __('Cuotas pagadas mora'); ?></th>
                  <th><?php echo __('Valor mora'); ?></th>
                  <th><?php echo __('Cuotas en mora'); ?></th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($credits)): ?>
                  <tr>
                    <td colspan="10">
                      No hay información
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($credits as $key => $value): ?>
                    <tr>
                      <!-- <td>

                      </td> -->
                      <td>
                        <?php echo str_pad($value["Credit"]["credits_request_id"], 5, "0", STR_PAD_LEFT); ?>
                      </td>
                      <td>
                        <?php echo $value["Credit"]["code_pay"]; ?>
                      </td>
                      <td>
                        <?php echo date("d-m-Y",strtotime($value["Credit"]["created"])) ?>
                      </td>

                      <td>
                        $ <?php echo number_format($value["Credit"]["value_aprooved"]) ?>
                      </td>
                      <td>
                        $ <?php echo number_format($value["Credit"]["value_request"]) ?>
                      </td>

                      <td>
                        <?php if ($value["Credit"]["debt"] == 1): ?>
                          Mora
                        <?php else: ?>
                          <?php echo $value["Credit"]["state"] == 1 ? "Cancelado" : "No finalizado" ?>

                        <?php endif ?>
                      </td>
                      <td>
                          <?php echo $value["Credit"]["juridico"] == 1 ? "Si" : "No" ?>
                      </td>
                      <td>
                        $ <?php echo number_format($value["saldos"]["saldo"] <= 1 || $value["Credit"]["state"] == 1 ? 0 : $value["saldos"]["saldo"] ) ?>
                      </td>
                      <td>
                        <?php echo $value["debts"] ?>
                      </td>
                      <td>
                        $ <?php echo number_format($value["saldos"]["debt"]) ?>
                      </td>
                      <td>
                        <?php echo ($value["saldos"]["totalDebt"]) ?>
                      </td>
                      <td>
                            <a class="btn btn-info btn-sm" target="blank" href="<?php echo $this->Html->url(["controller"=>"credits","action"=>"payment_detail",$this->Utilidades->encrypt($value["CreditsRequest"]["id"])]) ?>"><i class="fa fa-check"></i> Ver </a>

                            <!-- <button data-toggle="modal" data-target="#editarValueCredit<?php echo $value["CreditsRequest"]["id"]; ?>" class="btn btn-success btn-sm">
                                <i class="fa fa-edit"></i> Editar valor crédito </a>
                            </button> -->
                      </td>
                    </tr>



                  <?php endforeach ?>
                <?php endif ?>
              </tbody>
            </table>
          </div>
   </div>
  <?php endif ?>
</div>

