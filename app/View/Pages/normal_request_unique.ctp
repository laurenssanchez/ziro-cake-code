<?php echo $this->element("/menu-landings"); ?>

<div class="container-fluid p-0 bg-img-blue">
  <div class="container namelinecredit">
      <h2 class="upper"><b>Crédito tradicional</b></h2>
  </div>

      <?php echo $this->Form->create('Customer', array('role' => 'form','data-parsley-validate=""')); ?>
  <div class="container pb-3">
     <div class="row">

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-5 pt-3">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>INFORMACIÓN DEL</h1>
                      <h2>PROVEEDOR</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-7 pt-3">
                <div class="form-group">
                    <label for="">Tipo de crédito a solicitar</label><br>
                    <?php echo $this->Form->input('online', array('class' => 'form-control','label'=>false,'div'=>false,"data-toggle"=>"toggle", "data-on"=>"Crédito online", "data-off"=>"Compra en tienda", "data-onstyle"=>"outline-success", "data-offstyle"=>"outline-danger",'required' => false,"type" => "checkbox","data-size"=>"sm","data-width"=>"100%",'value' => '1',
                      'hiddenField' => '2',"checked")); ?>
                  </div>
                <div class="form-group" id="formCodeData">
                  <?php echo $this->Form->input('code', array('class' => 'form-control','label'=>'Código del proveedor','div'=>false,'placeholder'=>'Código de la tienda donde solicitas el crédito','required' => true)); ?>
                </div>

                  <!--<div class="form-group">-->
                  <!--  <?php echo $this->Form->input('code', array('class' => 'form-control','label'=>'Código del proveedor','div'=>false,'placeholder'=>'Código de la tienda donde solicitas el crédito','required' => true)); ?>-->
                  <!--</div>-->
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-5 pt-2">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>INFORMACIÓN</h1>
                      <h2>DE TU EMPRESA</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-7 pt-2">

                <div class="form-group">
                      <?php echo $this->Form->input('nit', array('class' => 'form-control','label'=>'NIT','div'=>false,'placeholder'=>'Ingresa NIT','required' => true)); ?>
                  </div>
                  <div class="form-group">
                      <?php echo $this->Form->input('buss_name', array('class' => 'form-control','label'=>'Nombre de tu Empresa','div'=>false,'placeholder'=>'Ingresa la razón social','required' => true)); ?>
                  </div>

                    <div class="form-group">
                  <?php echo $this->Form->input('CustomersAddress.address_city', array('class' => 'form-control border-input','label'=>'Ingresa la Ciudad','div'=>false,'placeholder'=>"Ciudad","required", "value" => isset($customer) && !empty($customer["CustomersAddress"]) ? $customer["CustomersAddress"]["0"]["address_city"] : "", "options" => Configure::read("CIUDADES"),"default" => "MEDELLIN" )); ?>
	              </div>
                <div class="form-group">
                  <?php echo $this->Form->input('CustomersAddress.address_street', array('class' => 'form-control border-input','label'=>'Ingresa la dirección','div'=>false,'placeholder'=>"Dirección","required", "value" => isset($customer) && !empty($customer["CustomersAddress"]) ? $customer["CustomersAddress"]["0"]["address_street"] : "" )); ?>

                  <?php echo $this->Form->input('CustomersAddress.address_type', array('class' => 'form-control border-input','label'=>'Ciudad','div'=>false,"required", "value" => 1,"type"=>"hidden" )); ?>
                </div>

                   <div class="form-group">
                      <?php echo $this->Form->input('serv_name', array('class' => 'form-control','label'=>'Página Web/Red Social','div'=>false,'placeholder'=>'Ingresa página web o red social','required' => true)); ?>
                  </div>

                    <div class="form-group">
                    <?php echo $this->Form->input('monthly_income', array('class' => 'form-control','label'=>'Ingresos Mensuales','div'=>false,'placeholder'=>'¿Cuánto dinero ganas al mes?','required' => true,"data-parsley-validate" => "number","min"=>0,"max"=>1000000000)); ?>
                  </div>

                    <div class="form-group">
                    <?php echo $this->Form->input('monthly_expenses', array('class' => 'form-control','label'=>'Egresos Mensuales','div'=>false,'placeholder'=>'¿Cuánto dinero gastas al mes?','required' => true,"data-parsley-validate" => "number","min"=>0,"max"=>1000000000)); ?>
                  </div>

                  <!---->
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="">¿Cuánto cupo necesitas?</label>
                        <!--<input id="moneyRange" type="range" min="50000" max="750000" step="100" value="50000" class="range-borrow __range">
                        <p class="valuemin">$ 50.000</p>
                        <p class="valuemax pull-right">$ 750.000</p>-->

						<input id="moneyRange" type="range" min=<?php echo $valorMini ?>  max= <?php echo $Valormax ?> step="100" value=<?php echo $valorMini ?> class="range-borrow __range">
                        <p class="valuemin">$ <?php echo number_format($valorMini) ?> </p>
                        <p class="valuemax pull-right">$ <?php echo  number_format($Valormax) ?></p>

                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Frecuencia de pago</label>
                        <select type="text" class="form-control frecuency-payments" name="frecuency" id="frecuency">
                          <option value="1">Mensual</option>
                          <option value="2">Quincenal</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Cuotas</label>
                        <select class="form-control coutas-number" id="coutas-number" name="couteValue">

                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="solicitado">
                        <p>Solicitado</p>
                        <input type="number" class="form-control value-actual d-none" value="0" id="valueNumberPrice" placeholder="$200.000" name="priceValue">
                        <h4>$ <input type="number" id="totalSolicita2" step="100" min=<?php echo $valorMini ?> ></h4>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="cuota">
                        <p>Valor Cuota</p>
                        <h4>$ <span id="valueCalculated"></span></h4>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="text-center mt-3">
                      <button type="button" id="btnPlaPayment" class="btn btn-primary btn-lg">PLAN DE PAGOS</button>
                  </div>
                </div><br><br>


                  <!---->
                   <div class="form-group">
                    <?php echo $this->Form->input('cci', array('class' => 'form-control','label'=>'¿Estás registrado en la cámara de comercio?','div'=>false,"options" => Configure::read("CCI_TYPE"),'required' => true)); ?>
                  </div>

              </div>
          </div>
        </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
            <div class="col-md-5 pt-3">
                <div class="content-tittles">
                  <div class="line-tittles">|</div>
                  <div>
                    <h1>INFORMACIÓN</h1>
                    <h2>PERSONAL</h2>
                  </div>
                </div>
            </div>
              <div class="col-md-7 pt-3">
                <p>Diligencia tus datos como aparecen en tu cédula</p>
                   <div class="form-group">
                      <?php echo $this->Form->input('name', array('class' => 'form-control','label'=>'Nombres','div'=>false,'placeholder'=>'Nombres como están en tu cédula','required' => true)); ?>
                  </div>
                  <div class="form-group">
                      <?php echo $this->Form->input('last_name', array('class' => 'form-control','label'=>'Apellidos','div'=>false,'placeholder'=>'Apellidos como están en tu cédula','required' => true)); ?>
                  </div>

                  <p>Con esta información validamos tu identidad</p>
                  <div class="form-group">
                    <?php echo $this->Form->input('identification_type', array('class' => 'form-control','label'=>'Tipo de identificación','div'=>false,"options" => Configure::read("Identification_TYPE"),'required' => true)); ?>
                  </div>

                  <div class="form-group">
                    <?php echo $this->Form->input('identification', array('class' => 'form-control','label'=>'Tu número de identificación','div'=>false,'placeholder'=>'Escribe tu Número de identificación','data-parsley-type'=>"number","data-parsley-minlength"=>"5",'required' => true)); ?>
                  </div>

              </div>
            </div>
          </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-5 pt-3">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>DATOS DE</h1>
                      <h2>CONTACTO</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-7 pt-3">

                	  <div class="form-group">
                    <?php if (isset($customer) && !empty($customer["CustomersPhone"])): ?>
                      <?php echo $this->Form->input('CustomersPhone.1.id', array('class' => 'form-control border-input',"value" => $customer["CustomersPhone"][0]["id"],"type"=>"hidden")); ?>
                    <?php endif ?>
                    <?php echo $this->Form->input('CustomersPhone.1.phone_number', array('class' => 'form-control border-input','label'=>'Número de celular','div'=>false,"placeholder"=>"Ingresa tu número de celular","required","value" => isset($customer) && !empty($customer["CustomersPhone"]) ? $customer["CustomersPhone"][0]["phone_number"] : "","data-parsley-type"=>"number" )); ?>
                    <?php echo $this->Form->input('CustomersPhone.1.phone_type', array('class' => 'form-control border-input',"value" => isset($customer) && !empty($customer["CustomersPhone"]) ? $customer["CustomersPhone"][0]["phone_type"] : "1","type"=>"hidden")); ?>
	                </div>

	                 <!---->

                  <?php if (isset($customer) && !empty($customer["CustomersAddress"]) ): ?>
                  <?php echo $this->Form->input('CustomersAddress.id', array('class' => 'form-control border-input','label'=>false,'div'=>false,'placeholder'=>"Como aparece en tu cuenta de servicios","required", "value" => $customer["CustomersAddress"]["0"]["id"],"type" => "hidden" )); ?>
                <?php endif ?>

      			<div class="form-group">
                  <?php echo $this->Form->input('CustomersAddress.address', array('class' => 'form-control border-input','label'=>'Tu dirección personal','div'=>false,'placeholder'=>"Como aparece en tu cuenta de servicios","required", "value" => isset($customer) && !empty($customer["CustomersAddress"]) ? $customer["CustomersAddress"]["0"]["address"] : "" )); ?>
      		    </div>

              </div>
          </div>
        </div>


        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-6">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>FOTO</h1>
                      <h2>CÉDULA FRONTAL</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-6 text-center">
                <select id="select" style="display:none;">
                  <option></option>
                </select>
                <canvas id="canvasFotoUp" style="display: none;" class="w-100"></canvas>
                <img src="" id="imgUpFile" class="img-fluid">
                 <?php echo $this->Form->input('document_file_up2', array('class' => 'form-control','label'=>false,'div'=>false,"required" => true,"type"=>"hidden")); ?>
                <button type="button" class="btn btn-primary btn-lg mt-2 fotoBtn" data-canvas="canvasFotoUp" id="fotoUpFile" data-input="CustomerDocumentFileUp2" data-img="imgUpFile">Tomar foto</button>
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-6">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>FOTO</h1>
                      <h2>CÉDULA REVERSO</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-6 text-center">
                  <canvas id="canvasFotoDown" style="display: none;" class="w-100"></canvas>
                  <img src="" class="img-fluid" id="imgDownFile">
                   <?php echo $this->Form->input('document_file_down2', array('class' => 'form-control','label'=>false,'div'=>false,"required" => true,"type"=>"hidden")); ?>
                  <button type="button" class="btn btn-primary btn-lg mt-2 fotoBtn" data-canvas="canvasFotoDown" id="fotoDownFile" data-input="CustomerDocumentFileDown2" data-img="imgDownFile">Tomar foto</button>
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-6">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>FOTO SELFIE CON</h1>
                      <h2>CÉDULA FRONTAL</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-6 text-center">
                  <canvas id="canvasFotoUser" style="display: none;" class="w-100"></canvas>
                  <img src="" class="img-fluid" id="imgFotoUser">
                  <?php echo $this->Form->input('image_file2', array('class' => 'form-control','label'=>false,'div'=>false,"required" => true,"type"=>"hidden")); ?>
                  <button type="button" class="btn btn-primary btn-lg mt-2 fotoBtn" data-canvas="canvasFotoUser" id="fotoUserFile" data-input="CustomerImageFile2" data-img="imgFotoUser">Tomar foto</button>
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-4">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>AUTORIZACIÓN DE </h1>
                      <h2>TRATAMIENTO DE DATOS</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-8">
                    <div class="form-check p-2">
                      <?php echo $this->Form->input('tyc', array('class' => 'form-check-input','label'=>false,'div'=>false,"required" => true,"type"=>"checkbox")); ?>
                      <label class="form-check-label big-label" for="">Autorizo a Somos Ziro S.A.S a consultar mi información en centrales de riesgo y a enviarme información sobre su producto. También autorizo el tratamiento de mis datos personales, según la Política de Tratamiento de Datos Personales.</label>
                    </div>
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white text-center pt-3 pb-3">
          <div class="pb-3">
              <a href="<?php echo $this->Html->url(AuthComponent::user("id") ? ["controller"=>"credits_requests","action"=>"index"] : ["controller"=>"pages","action"=>"home"] ) ?>" class="btn btn-outline-primary btn-lg mt-2">Volver</a>
              <input type="submit" class="btn btn-primary btn-lg mt-2" value="Guardar">
          </div>
        </div>

     </div>
  </div>
  </form>
</div>

<div class="modal fade " id="panelPayments" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">
          <div class="content-tittles">
            <div class="line-tittles">|</div>
            <div>
              <h1>PLAnN</h1>
              <h2>DE PAGOS</h2>
            </div>
          </div>
        </h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="planPaymentBody">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php echo $this->element("take_photo"); ?>
<?php
  echo $this->Html->script("ctrl/customers/add.js?".rand(),             array('block' => 'AppScript'));
  echo $this->Html->script("ctrl/customers/forms.js?".rand(),             array('block' => 'AppScript'));
?>


<script>

  var COMMERCE_CODE = "<?php echo Configure::read("CODE_COMMERCE") ?>";

</script>



<?php echo $this->Html->css("https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"); ?>

<?php echo $this->Html->script("https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js?".rand(),             array('block' => 'AppScript')); ?>

<style>
  .btn-sm{
    height: 35px;
  }
</style>

<?php echo $this->element("/modals/tyc"); ?>


<?php

echo $this->Html->script("home.js?".rand(),           array('block' => 'AppScript'));

?>


<script type="text/javascript">

 var data = <?php echo $data ?>;


 var opciones = "";
 var valor = 0;
 var initial = 0;
 var final = 0;



const moneyRange = document.querySelector("#moneyRange");

moneyRange.addEventListener('blur', (event) => {
	updateQuotes();
});

const solicitado = document.querySelector("#totalSolicita2");

solicitado.addEventListener('onchage', (event) => {
	updateQuotes();
});


solicitado.addEventListener('blur', (event) => {
	updateQuotes();
});




const valueprice = document.querySelector("#valueNumberPrice");

valueprice.addEventListener('onchage', (event) => {
	updateQuotes();
});

valueprice.addEventListener('blur', (event) => {
	updateQuotes();
});




function  updateQuotes(){

  data = "";
  var data = <?php echo $data ?>;
	//document.getElementById('coutas-number').innerHTML="";
	opciones = "";
	document.getElementById('coutas-number').innerHTML="";
	//coutas-number
	//document.getElementById("totalSolicita2").value;
	valor = Number(document.getElementById("totalSolicita2").value)
 // alert(typeof(valor));
    if (valor==""){
	    valor =	<?php echo $valorMini ?>
	  }

	initial = 0;

	final = 0;

	for (x of data) {
		//console.log(x.credits_lines_details.min_value);



   if ((valor>=Number(x.credits_lines_details.min_value)) && (valor <= Number(x.credits_lines_details.max_value)   )) {


			if (initial==0){
				  initial=Number(x.credits_lines_details.month);

			}else if(initial>Number(x.credits_lines_details.month)){
				  initial=Number(x.credits_lines_details.month);

			}




   }
	}


  for (x of data) {
		//console.log(x.credits_lines_details.min_value);



   if ((valor>=Number(x.credits_lines_details.min_value)) && (valor <= Number(x.credits_lines_details.max_value)   )) {


			if (final==0){
        final=Number(x.credits_lines_details.month);

			}else if(final<Number(x.credits_lines_details.month)){
        final=Number(x.credits_lines_details.month);

			}




   }
	}





  if(initial == 0 || final == 0){
      console.log("raro");
  }




	for (var i = initial; i <= final;i++){
    // console.log(i);
		opciones += "<option data-mes="+ i +" data-quince="+i*2 +" value="+ i +">"+ i +"</option>" + "\n";

	}





	document.getElementById('coutas-number').innerHTML=opciones
	$("#coutas-number").val(initial)
}

document.onload = updateQuotes();



</script>
