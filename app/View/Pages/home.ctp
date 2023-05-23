<?php echo $this->element("/menu-landings"); ?>

  <div class="container-fluid">
    <div class="relativ">
    <div class="row">
<div class="container-fluid bg-green p-0">
      <div id="slide-home" class="carousel slide carousel-fade" data-ride="carousel" style="<?php echo AuthComponent::user("id") && in_array(AuthComponent::user("role"), [4,6]) ? "height: 600px !important" : "";?>">
              <!--<ol class="carousel-indicators">-->
              <!--  <li data-target="#slide-home" data-slide-to="0" class="active"></li>-->
              <!--  <li data-target="#slide-home" data-slide-to="1"></li>-->
              <!--  <li data-target="#slide-home" data-slide-to="2"></li>-->
              <!--  <li data-target="#slide-home" data-slide-to="3"></li>-->
              <!--</ol>-->
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img src="img/back2.png" class="d-block w-145" alt="...">
                </div>

                <!--<div class="carousel-item">-->
                <!--  <img src="img/imagen2.jpg" class="d-block w-145" alt="...">-->
                <!--</div>-->
                <!--<div class="carousel-item">-->
                <!--  <img src="img/imagen3.jpg" class="d-block w-145" alt="...">-->
                <!--</div>-->
                <!--<div class="carousel-item">-->
                <!--  <img src="img/imagen4.jpg" class="d-block w-145" alt="...">-->
                <!--</div>-->
              </div>
              <!--<a class="carousel-control-prev" href="#slide-home" role="button" data-slide="prev">-->
              <!--  <span class="carousel-control-prev-icon" aria-hidden="true"></span>-->
              <!--  <span class="sr-only">Previous</span>-->
              <!--</a>-->
              <!--<a class="carousel-control-next" href="#slide-home" role="button" data-slide="next">-->
              <!--  <span class="carousel-control-next-icon" aria-hidden="true"></span>-->
              <!--  <span class="sr-only">Next</span>-->
              <!--</a>-->
        </div>


            <div class="wsim p-5 box-calc" >

              <a href="https://creditos.somosziro.com/pages/register_step_unique" class="btn btn-secondary btn-lg home">SOLICITA TU CRÉDITO</a>
              <br> <br>

              <a href="https://creditos.somosziro.com/pages/comercios" class="btn btn-secondary btn-lg home">REGISTRA TU PROVEEDOR</a>

              <div style="display:none;">
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
                        <input id="moneyRange" type="range" min=<?php echo $valorMini ?>  max= <?php echo $Valormax ?> step="100" value=<?php echo $valorMini ?> class="range-borrow __range">
                        <p class="valuemin">$ <?php echo number_format($valorMini) ?> </p>
                        <p class="valuemax pull-right">$ <?php echo number_format($Valormax) ?></p>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="">Dinero Solicitado</label>
                        <input type="number" class="form-control value-actual d-none" value="0" id="valueNumberPrice" placeholder="$200.000">
                        <h2 class="value-actual form-control">$ <input type="number" id="totalSolicita2" step="100" min="50000" ></h2>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="">¿Con qué frecuencia de pagos necesitas?</label>
                        <select type="text" class="form-control frecuency-payments" id="frecuency">
                        <!--<option value="">Selecciona</option>-->
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
                        <select class="form-control coutas-number" id="coutas-number">


                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="text-center mt-3">
                    <button type="button" id="btnPlaPayment" class="btn btn-primary btn-lg">PLAN DE PAGOS</button>
                  </div>
              </div>

     <!---->
<div class="ziro-estres" style="position:fixed; right:10px; bottom:10px; text-align:center; padding:3px">
<a target="_BLANK" href="https://creditos.somosziro.com/">
<img src="https://creditos.somosziro.com/img/ziro-estres.png" height="50"></a>
</div>


            </div>
      </div>
      </div>
  </div>
</div>





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




