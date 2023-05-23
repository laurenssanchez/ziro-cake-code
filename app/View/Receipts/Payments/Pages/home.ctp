<?php echo $this->element("/menu-landings"); ?>

  <div class="container-fluid">
    <div class="relativ">
    <div class="row">
      <div id="slide-home" class="carousel slide carousel-fade" data-ride="carousel">
              <ol class="carousel-indicators">
                <li data-target="#slide-home" data-slide-to="0" class="active"></li>
                <li data-target="#slide-home" data-slide-to="1"></li>
                <li data-target="#slide-home" data-slide-to="2"></li>
                <li data-target="#slide-home" data-slide-to="3"></li>
              </ol>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img src="img/imagen1.jpg" class="d-block w-145" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="img/imagen2.jpg" class="d-block w-145" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="img/imagen3.jpg" class="d-block w-145" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="img/imagen4.jpg" class="d-block w-145" alt="...">
                </div>
              </div>
              <a class="carousel-control-prev" href="#slide-home" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#slide-home" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
        </div>


            <div class="wsim p-5 box-calc">
              <div class="content-tittles ">
                <div class="line-tittles">|</div>
                <div>
                  <h1>Pide tu</h1>
                  <h2>CRÉDITO</h2>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">¿Cuánto dinero necesitas?</label>
                    <input id="moneyRange" type="range" min="50000" max="750000" step="10000" value="50000" class="range-borrow __range">
                    <p class="valuemin">$ 50.000</p>
                    <p class="valuemax pull-right">$ 750.000</p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Dinero Solicitado</label>
                    <input type="number" class="form-control value-actual d-none" value="0" id="valueNumberPrice" placeholder="$200.000">
                    <h2 class="value-actual form-control">$ <span id="totalSolicita2" ></span></h2>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">¿Con qué frecuencia de pagos necesitas?</label>
                    <select type="text" class="form-control frecuency-payments" id="frecuency">
                      <option value="1">Mensual</option>
											<option value="3">45 días</option>
											<option value="4">60 días</option>
                      <!-- <option value="2">Quincenal</option> -->
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Cuántas cuotas?</label>
                    <select class="form-control coutas-number">
                      <?php for($i = 1; $i <= 4; $i++ ):  ?>
                        <option data-mes="<?php echo $i ?>" data-quince="<?php echo $i*2 ?>" value="<?php echo $i ?>"><?php echo $i ?></option>
                      <?php endfor; ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="text-center mt-3">
                <button type="button" id="btnPlaPayment" class="btn btn-primary btn-lg">PLAN DE PAGOS</button>
              </div>
            </div>
      </div>
      </div>
  </div>
</div>


 <div class="container-fluid">
    <div class="row">
      <div class="container-fluid conntent-calculate ">
        <div class="col-md-12 bg-verde">
              <div class="row">
                <div class="col-md-8 p-5">
                  <div class="content-tittles mb-3">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>Estás solicitando </h1>
                      <h2>ESTE CRÉDITO</h2>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="row data-generate">
                        <div class="col-md-4">
                          <div class="solicitado">
                            <p>Solicitado</p>
                            <h2>$ <span id="totalSolicita"></span></h2>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="frecuencia">
                            <p>Frecuencia de pago</p>
                            <h2 id="frecuenciaText">Mensual</h2>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="cuota">
                            <p>Valor Cuota</p>
                            <h2>$ <span id="valueCalculated"></span></h2>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4 p-0">
                  <div class="inferior p-5">
                    <div class="text-center">
                      <?php if (AuthComponent::user("customer_complete") == 1): ?>
                        <a href="javascript::void(0)" class="btn btn-primary btn-lg requestCredit">SOLICITAR</a>
                      <?php else: ?>
                        <a href="<?php echo $this->Html->url(["controller"=>"pages","action" => "register_step_one"]) ?>" class="btn btn-primary btn-lg">SOLICITAR</a>
                      <?php endif ?>
                    </div>
                  </div>
                </div>
              </div>
        </div>
      </div>
      </div>
    </div>




<div class="container-fluid pt-5 pb-5 content-requisitos">
  <div class="container">
    <div class="container-fluid pt-5">
  <div class="container">
    <div class="col-md-12 mb-5">
      <div class="content-tittles justify-content">
        <div class="line-tittles">|</div>
        <div>
          <h1>SOLO DEBES CUMPLIR</h1>
          <h2>LOS SIGUIENTES REQUISITOS</h2>
        </div>
      </div>
    </div>
  </div>
</div>
    <div class="col-md-12 pt-4 pb-4">
      <div class="row">
        <div class="col-md-2 control-icon">
          <i class="fa fa-globe" aria-hidden="true"></i>
          <p>Residir en Colombia</p>
        </div>
        <div class="col-md-2 control-icon">
          <i class="fa fa-address-card-o" aria-hidden="true"></i>
          <p>Ser mayor de edad</p>
        </div>
        <div class="col-md-2 control-icon">
          <i class="fa fa-envelope-o" aria-hidden="true"></i>
          <p>Tener correo electrónico personal</p>
        </div>
        <div class="col-md-2 control-icon">
          <i class="fa fa-phone" aria-hidden="true"></i>
          <p>Tener número de celular personal</p>
        </div>
        <div class="col-md-2 control-icon">
          <i class="fa fa-list-alt" aria-hidden="true"></i>
          <p>Poseer historial crediticio</p>
        </div>
        <div class="col-md-2 control-icon">
          <i class="fa fa-ban" aria-hidden="true"></i>
          <p>No tener reporte crediticio negativo </p>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="container-fluid p-5 content-pasos">
  <div class="container">
    <div class="col-md-12">
      <div class="content-tittles justify-content">
        <div class="line-tittles">|</div>
        <div>
          <h1>ESTOS SON LOS PASOS</h1>
          <h2>QUE DEBES SEGUIR</h2>
        </div>
      </div>
      <div class="text-center copyp">
        <p>¡Lo necesitas, lo tienes! <b>Crédito fácil y rápido</b></p>
      </div>
    </div>
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-4 mb-3">
          <div class="content-tittles justify-content">
            <h1 class="numbersbig">1</h1>
            <div class="line-tittles">|</div>
            <div>
              <h1>ELIGE</h1>
              <h2>TU PRÉSTAMO</h2>
            </div>
          </div>
          <p>Elige el valor que vas a pedir prestado y la fecha en que nos vas a pagar.</p>
        </div>
        <div class="col-md-4 mb-3">
          <div class="content-tittles justify-content">
            <h1 class="numbersbig">2</h1>
            <div class="line-tittles">|</div>
            <div>
              <h1>LLENA</h1>
              <h2>EL FORMULARIO</h2>
            </div>
          </div>
          <p>En solo 5 minutos llena el formulario de cuatro sencillos pasos. Recuerda que debes otorgarnos autorización para el tratamiento de datos personales.</p>
        </div>
        <div class="col-md-4 mb-3">
          <div class="content-tittles justify-content">
            <h1 class="numbersbig">3</h1>
            <div class="line-tittles">|</div>
            <div>
              <h1>FIRMA</h1>
              <h2>EL CONTRATO</h2>
            </div>
          </div>
          <p>Revisa tu correo electrónico y tu celular que registraste y utiliza los códigos para firmar electrónicamente el contrato de préstamo, los términos y condiciones y la autorización para consultarte en centrales de riesgo.</p>
        </div>
      </div>
      <div class="row">
       <!--  <div class="col-md-4 mb-3">
          <div class="content-tittles justify-content">
            <h1 class="numbersbig">4</h1>
            <div class="line-tittles">|</div>
            <div>
              <h1>RECIBE</h1>
              <h2>TU DINERO</h2>
            </div>
          </div>
          <p>Si tu crédito es aprobado recibirás una notificación de aprobación y en menos de 1 día hábil el dinero en tu cuenta bancaria.</p>
        </div> -->
        <div class="col-md-4 mb-3">
          <div class="content-tittles justify-content">
            <h1 class="numbersbig">4</h1>
            <div class="line-tittles">|</div>
            <div>
              <h1>PAGA</h1>
              <h2>EN LA FECHA</h2>
            </div>
          </div>
          <p>Cuando llegue la fecha que acordamos nos pagas el dinero y recibes una calificación positiva en centrales de riesgo.</p>
        </div>
        <div class="col-md-4 mb-3">
          <div class="content-tittles justify-content">
            <h1 class="numbersbig">5</h1>
            <div class="line-tittles">|</div>
            <div>
              <h1>ACUMULA</h1>
              <h2>EXPERIENCIA</h2>
            </div>
          </div>
          <p>Pagando cumplidamente tu crédito podrás obtener mayor cupo de crédito para que puedas comprar todo lo que necesites.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid p-5 bg-success">
  <div class="container text-center">
    <div class="col-md-12 text-white p-3">
      <h2>¡Tu crédito fácil y rápido</h2>
      <p>Estamos contigo en todo momento</p>
      <div class="text-center p-3">
        <a href="<?php echo $this->Html->url(["controller"=>"pages","action" => "register_step_one"]) ?>" class="btn btn-primary btn-lg">SOLICITAR MI <b>CRÉDITO FÁCIL</b> </a>
      </div>
    </div>
  </div>
</div>


<div class="container-fluid pt-5 pb-3 bg-green text-white">
  <div class="container">
    <div class="col-md-12 pt-4 pb-5">
      <div class="row">
        <div class="col-md-4">
          <a class="" href="#">
            <img src="https://credishop.co/img/credishop.svg" class="img-fluid ">
          </a>
        </div>
         <div class="col-md-8">
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-6">
                  <ul>
										<p><b>ZÍRO</b></p>
                    <li>Av Santander 65 - 15 Local 115, Manizales, Colombia</li>
                    <li>Teléfono: (+57) 3209860583</li>
                    <li>Correo Electrónico: hola@somosziro.com</li>
                  </ul>
                </div>
                <div class="col-md-6">
                  <ul>
                    <p><b>DE SU INTERÉS</b></p>
                    <li class="list-inline-item"><a target="_blank" href="<?php echo $this->Html->url(["controller"=>"pages","action" => "politicas_uso_informacion"]) ?>">Políticas de Uso de Información</a></li>
                    <li class="list-inline-item"><a target="_blank" href="<?php echo $this->Html->url(["controller"=>"pages","action" => "tyc"]) ?>">Términos y Condiciones </a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="col-md-12 text-center border-top pt-4">
      <p>2020 somosziro.com Todos los derechos reservados
        <a href="" class="ml-3"><i class="fa-lg fa fa-facebook-square"></i></a>
        <a href="" class="ml-3"><i class="fa-lg fa fa-instagram"></i></a>
        <a href="" class="ml-3"><i class="fa-lg fa fa-linkedin"></i></a>
        <a href="" class="ml-3"><i class="fa-lg fa fa-twitter"></i></a>
        <a href="" class="ml-3"><i class="fa-lg fa fa-whatsapp"></i></a>
      </p>
    </div>
  </div>
</div>
<div class="container-fluid text-center">
  <div class="container">
    <img src="img/brands-footer.jpg" class="img-fluid p-4">
  </div>
</div>

<div class="modal fade " id="panelPayments" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">
          <div class="content-tittles">
            <div class="line-tittles">|</div>
            <div>
              <h1>PLAN</h1>
              <h2>DE PAGOS</h2>
            </div>
          </div>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="planPaymentBody">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php

echo $this->Html->script("home.js?".rand(),           array('block' => 'AppScript'));

?>
