<div class="modal fade" id="transfer_credit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transferir Cupo Preaprobado </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="">
                <form id="formTransfer" action="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "request_credit_extra"]) ?>" method="post">
                    <div class="form-group">
                        <label for="shop_commerce_transfer">¿A qué proveedor enviarás tu dinero preaprobado? </label>
                        <select name="shop_commerce_transfer" id="shop_commerce_transfer" class="form-control" required="">
                            <option value="">Seleccionar</option>
                            <?php foreach ($list as $key => $value) : ?>
                                <option value="<?php echo $key ?>"><?php echo $value ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="creditTransverValue">Monto de Dinero </label>
                        <input name="creditTransverValue" id="creditTransverValue" placeholder="¿Cuánto dinero enviarás?" class="form-control" data-value="<?php echo $totalCustomerQuote ?>" max="<?php echo $totalCustomerQuote ?>" value="<?php echo $totalCustomerQuote ?>" min="50000" type="number" required="">
                    </div>

                    <div class="form-group">
                        <input type="hidden" id="id_request" name="id_request">
                        <input type="hidden" id="type" name="type" value="1">
                        <input type="hidden" id="customer" name="customer" value="<?php echo $this->Utilidades->encrypt(AuthComponent::user("customer_id")) ?>">
                        <label for="frecuencyValueTransfer">¿Con qué frecuencia de pagos necesitas?</label>
                        <select class="form-control" id="frecuencyValueTransfer">
                            <option value="1">Mensual</option>
                            <option value="3">45 días</option>
                            <option value="4">60 días</option>
                            <!-- <option value="2">Quincenal</option> -->
                        </select>
                    </div>
					<div class="form-group">
                        <label for="cuotasTransferValue">¿A cuántas cuotas?</label>
                        <select class="form-control" id="cuotasTransferValue">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <p class="text-center text-success" id="textCredit" data-actual="<?php echo $totalCustomerQuote ?>">
                            Tu cuota para este será de $<span id="valueDataTransfer"></span></p>
                    </div>
                    <div class="form-group text-center">
                        <a href="" class="btn btn-primary" id="initTransferData">
                            Iniciar proceso y firma digital.
                        </a>
                    </div>
                    <div class="form-group text-center envioCodigos">
                        <a href="" class="btn btn-warning" id="enviarCodigosCliente">
                            Envíar códigos para firma digital.
                        </a>
                    </div>
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
                    <div class="form-group">
                        <button type="button" class="btn btn-primary mx-auto" id="applyConfirmBtn" style="display: none">Confirmar Transferencia</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php $this->start("AppScript") ?>

<script type="text/javascript">
    var data2 = <?php echo $data ?>;
    var opciones = "";
    var valor = 0;
    var initial = 0;
    var final = 0;
    const solicitadoTransfer = document.querySelector("#creditTransverValue");

    solicitadoTransfer.addEventListener('onchage', (event) => {
        updateQuotesTransfer();
    });

    solicitadoTransfer.addEventListener('blur', (event) => {
        updateQuotesTransfer();
    });

    function updateQuotesTransfer() {
        data = "";
        var data2 = <?php echo $data ?>;
        //document.getElementById('coutas-number').innerHTML="";
        opciones = "";
        document.getElementById('cuotasTransferValue').innerHTML = "";
        //coutas-number
        //document.getElementById("creditTransverValue").value;
        valor = Number(document.getElementById("creditTransverValue").value)
        // alert(typeof(valor));
        if (valor == "") {
            valor = <?php echo $valorMini ?>
        }
        initial = 0;
        final = 0;
        for (x of data2) {
            //console.log(x.credits_lines_details.min_value);
            if ((valor >= x.credits_lines_details.min_value) && (valor <= x.credits_lines_details.max_value)) {
                if (initial == 0) {
                    initial = x.credits_lines_details.month;
                } else if (initial > x.credits_lines_details.month) {
                    initial = x.credits_lines_details.month;
                }
            }
        }
        for (x of data2) {
            //console.log(x.credits_lines_details.min_value);
            if ((valor >= x.credits_lines_details.min_value) && (valor <= x.credits_lines_details.max_value)) {
                if (final == 0) {
                    final = x.credits_lines_details.month;
                } else if (final < x.credits_lines_details.month) {
                    final = x.credits_lines_details.month;
                }
            }
        }
        if (initial == 0 || final == 0) {
            console.log("raro");
        }
        for (var i = initial; i <= final; i++) {
            // console.log(i);
            opciones += "<option data-mes=" + i + " data-quince=" + i * 2 + " value=" + i + ">" + i + "</option>" + "\n";
        }
        document.getElementById('cuotasTransferValue').innerHTML = opciones
        document.getElementById('cuotasTransferValue').value = initial
    }

    document.onload = updateQuotesTransfer();

</script>
<?php $this->end() ?>

<style type="text/css">
    .envioCodigos {
        display: none;
    }
</style>
