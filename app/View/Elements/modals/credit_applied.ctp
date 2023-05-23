<div class="modal fade" id="credit_applied" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar retiro del crédito </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5><b>¿El cliente ya realizó el pedido?</b></h5>
                <form id="valueCreditApplied">
                    <div class="form-group">
                        <label for="">Valor total del crédito a utilizar</label>
                        <input type="number" required=""
							class="form-control"
							min="50000"
							step="100"
							id="valueCredit"
							placeholder="Ingresa el valor que retiró el cliente en productos">
                        <input type="hidden" class="form-control" id="id_request">
                        <input type="hidden" class="form-control" id="shop_commerce_data" value="<?php echo AuthComponent::user("role") == 4 ? "" : AuthComponent::user("shop_commerce_id") ?>">
                    </div>

                    <div class="form-group">
                        <label for="">¿Con qué frecuencia de pagos necesitas?</label>
                        <select type="text" class="form-control frecuency-payments" id="frecuency">
                            <option value="1">Mensual</option>
                            <option value="3">45 días</option>
                            <option value="4">60 días</option>
							<?php  if( AuthComponent::user("id") == 16418): ?>
                            	<option value="2">Quincenal</option>
							<?php  endif; ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="">A cuántas cuotas</label>
                        <select class="form-control" id="valueNumberQ">
                        </select>
                    </div>

                    <div class="form-group d-none" id="dates-retires">
                        <label for="">¿Fecha de retiro?</label>
                        <input type="date" class="form-control date-retired" id="dateRetired" value="<?php echo date("Y-m-d") ?>">
                    </div>
                    <div class="form-group text-center">
                        <label for="" class="text-center text-info textCuote"></label>
                    </div>
                    <div class="form-group text-center envioCodigos">
                        <a href="" class="btn btn-warning" id="enviarCodigosCliente">
                            Envíar códigos al cliente.
                        </a>
                    </div>
                    <hr>
                    <div class="preloadbox" style="display: none;"><img src="<?php echo $this->Html->url('/img/loading.gif'); ?>"></div>
                    <div class="form-group codigosCliente2" style="display: none !important;">
                        <label for="codeMail">Código enviado al correo</label>
                        <input type="number" class="form-control" id="codeMail" placeholder="Ingresa el código enviado al correo">
                        <input type="hidden" class="form-control" id="codeMailRequest">
                    </div>
                    <div class="form-group codigosCliente">
                        <label for="codePhone">Código enviado al celular</label>
                        <input type="number" class="form-control" id="codePhone" placeholder="Ingresa el código enviado al celular">
                        <input type="hidden" class="form-control" id="codePhoneRequest">
                    </div>
                    <div class="form-group text-center codigosCliente">
                        <a href="" class="btn btn-success" id="validarCodigos">
                            Validar códigos.
                        </a>
                        <a href="" class="btn btn-danger" id="reenviarCodigos">
                            Reenviar códigos.
                        </a>
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="applyConfirmBtn" style="display: none">Confirmar Retiro</button>
            </div>
        </div>
    </div>
</div>




<script type="text/javascript">
    var data = <?php echo $data ?>;

    $('#credit_applied').on('show', function() {
        document.getElementById("valueCredit").focus();
    });

    var opciones = "";
    var valor = 0;
    var initial = 0;
    var final = 0;

    const solicitado = document.querySelector("#valueCredit");

    solicitado.addEventListener('onchage', (event) => {
        updateQuotes();
    });

    solicitado.addEventListener('blur', (event) => {
        updateQuotes();
    });

    const frecuency = document.querySelector("#frecuency");


	$(frecuency).change(function() {
		updateQuotes();
	});

    frecuency.addEventListener('blur', (event) => {
        updateQuotes();
    });

    function updateQuotes() {
        data = "";
        var data = <?php echo $data ?>;

        opciones = "";
        opciones_dos = "";
        document.getElementById('valueNumberQ').innerHTML = "";
        //coutas-number

        valor = Number(document.getElementById("valueCredit").value)
        // alert(typeof(valor));
        if (valor == "") {
            valor = <?php echo $valorMini ?>
        }

        initial = 0;
        final = 0;

        for (x of data) {
            if ((valor >= Number(x.credits_lines_details.min_value)) && (valor <= Number(x.credits_lines_details.max_value))) {
                if (initial == 0) {
                    initial = Number(x.credits_lines_details.month);
                } else if (initial > Number(x.credits_lines_details.month)) {
                    initial = Number(x.credits_lines_details.month);
                }
            }
        }

        for (x of data) {
            //console.log(x.credits_lines_details.min_value);
            if ((valor >= Number(x.credits_lines_details.min_value)) && (valor <= Number(x.credits_lines_details.max_value))) {
                if (final == 0) {
                    final = Number(x.credits_lines_details.month);
                } else if (final < Number(x.credits_lines_details.month)) {
                    final = Number(x.credits_lines_details.month);
                }
            }
        }

        if (initial == 0 || final == 0) {
            console.log("raro");
        }


        for (var i = initial; i <= final; i++) {
            // console.log(i);
            opciones += "<option data-mes=" + i + " data-quince=" + i * 2 + " value=" + i + ">" + i + "</option>" + "\n";
            opciones_dos += "<option data-mes=" + i + " data-quince=" + i * 2 + " value=" + i * 2 + ">" + i * 2 + "</option>" + "\n";
        }

        //document.getElementById('valueNumberQ').innerHTML=opciones
        $("#valueNumberQ").val(initial)

        //const frecuency = document.querySelector("#frecuency");
        if (frecuency.value == null || frecuency.value == 'NaN' || typeof frecuency.value === "undefined") {
            frecuency.value = "1";
        }

		if(frecuency.value  == "1"){
			document.getElementById('valueNumberQ').innerHTML = opciones;
			$(".coutas-number > option, #valueNumberQ > option").each(function(index, el) {
				$(this).addClass("d-block").removeClass('d-none');
				var valor = $(this).data("mes");
				$(this).val(valor);
				$(this).text(valor);
			});

		} else if(frecuency.value  == "3" || frecuency.value  == "4"){

			document.getElementById('valueNumberQ').innerHTML = opciones;
			$(".coutas-number > option, #valueNumberQ > option").each(function(index, el) {
				var valor = $(this).data("mes");
				$(this).val('1');
				$(this).text('1');
				if (valor!='1') {
					$(this).addClass("d-none").removeClass('d-block');
				}
			});
		}
		else{
			document.getElementById('valueNumberQ').innerHTML = opciones_dos;
			$(".coutas-number > option, #valueNumberQ > option").each(function(index, el) {
				$(this).addClass("d-block").removeClass('d-none');
				var valor = $(this).data("quince");
				$(this).val(valor);
				$(this).text(valor);
			});
		}
    }

    document.onload = updateQuotes();
</script>
