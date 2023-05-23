<?php echo $this->element("/menu-landings"); ?>

<div class="container-fluid bg-img-blue p-5 block4">
  <div class="container p-5 mt-5 mb-5 bg-white">
     <div class="row">
		<div class="content-tittles">
			<div class="line-tittles">|</div>
			<div>
			  <h1>CONFIRMACIÓN DE APROBACIÓN DE </h1>
			  <h2>SOLICITUD DE CRÉDITO</h2>
			</div>
		</div>
		<div class="col-md-12 mt-4 mb-3 p-0">
			<h2> <span class="upper"><?php echo AuthComponent::user("name") ?></span>, estás a un paso de finalizar el proceso.</h2>
			<h5>Ahora debes confirmar la cantidad de dinero que solicitas e ingresar los códigos para validar tu identidad</h5>
		<hr>
		</div>
		<div class="col-md-12">
		<div class="row">
			<?php echo $this->Form->create('Customer', array('role' => 'form','data-parsley-validate=""')); ?>
		     <div class="row">
		     <div class="col-md-5 confirmcredit">
		              <div class="row">
		                <div class="col-md-12">
		                  <div class="form-group">
		                    <label for="">¿Cuánto dinero necesitas?</label>
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
		                    <select class="form-control coutas-number" name="couteValue" id="coutas-number">

		                    </select>
		                  </div>
		                </div>
		                <div class="col-md-6">
                          <div class="solicitado">
                            <p>Solicitado</p>
                            <input type="number" class="form-control value-actual d-none" value="0" id="valueNumberPrice" placeholder="$200.000" name="priceValue">
                            <h4>$ <input type="number" id="totalSolicita2" class="w-75" step="100" min=<?php echo $valorMini ?> max= <?php echo $Valormax ?>></h4>
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
		              </div>

		     </div>
			<div class="col-md-7 px-4">
				<!--	<div class="form-group">
					    <?php echo $this->Form->input('code_email', array('class' => 'form-control','label'=>'Código enviado al email','div'=>false,'placeholder'=>'Escribe el código que enviamos a tu email','required' => true,'type'=>'number')); ?>
					</div>
						<?php echo $this->Form->input('code_email_verify', array('class' => 'form-control','label'=>'Código enviado al email','div'=>false,'required' => true,"type" => "hidden","value"=>$this->Utilidades->encrypt($codeEmail))); ?>

					<div class="form-group">
						 <?php echo $this->Form->input('code_phone', array('class' => 'form-control','label'=>'Código enviado al celular','div'=>false,'placeholder'=>'Escribe el código que enviamos a tu celular','required' => true,'type'=>'number')); ?>
					</div>
					<?php echo $this->Form->input('code_phone_verify', array('class' => 'form-control','div'=>false,'required' => true,"type" => "hidden","value"=>$this->Utilidades->encrypt($codePhone))); ?>
				 -->
				 <input type="submit" class="btn btn-lg btn-primary mt-2" id="here" value="Finalizar Solicitud" >

				<!--	<a type="submit" class="btn btn-lg btn-secondary mt-2 " href="/"> Reenviar códigos </a>  -->
			</div>
			</div>
				</form>
		</div>
		</div>

     </div>



  </div>
</div>

	       <script>
		       document.getElementById("here").addEventListener("click", myFunction);
		       function myFunction() {
		         window.location.href="https://credishop.co/pages/dashboardcliente";
		       }
		      </script>

<div class="modal fade " id="panelPayments" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">
          <div class="content-tittles">
            <div>
              <h1>PLAN DE PAGOS</h1>
            </div>
          </div>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="planPaymentBody">

      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>



<script type="text/javascript">
    $(function () {
       $('#panelPayments').modal('toggle');
    });
</script>

<?php

echo $this->Html->script("home.js?".rand(),           array('block' => 'AppScript'));

?>

<script type="text/javascript">

 var data = <?php echo $data ?>;

 console.log(data);

 var opciones = "";
 var valor = 0;
 var initial = 0;
 var final = 0;



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





document.onload = updateQuotes();



</script>
