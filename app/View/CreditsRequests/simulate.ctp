<h5> <?php echo $request["Customer"]["name"] ?> <!--<span class="history-credits">5 créditos previos</span> -->

</h5>
<hr>
<div class="">
  <p class="card-text d-inline mr-2"><b>CC:</b> <?php echo $request["Customer"]["identification"] ?></p>
  <p class="card-text d-inline mr-2"><b>Solicita:</b>  $<?php echo number_format($request["CreditsRequest"]["request_value"]) ?>  x <?php echo $request["CreditsRequest"]["request_number"] ?> cuotas</p>
  <p class="card-text d-inline mr-2"><b>Celular:</b> <?php echo $request["Customer"]["CustomersPhone"][0]["phone_number"] ?></p>
  <p class="card-text d-inline mr-2"><b>Proveedor:</b> <?php echo $request["ShopCommerce"]["Shop"]["social_reason"] ?> - <?php echo $request["ShopCommerce"]["name"] ?></p>

  <?php if (empty($request["CreditsRequest"]["vars_score"])): ?>


  <a href="javascript:void()" id="searchCentral" class="btn btn-success" data-request="<?php echo $this->Utilidades->encrypt($request["CreditsRequest"]["id"]); ?>">
    Consultar centrales <i class="fa fa-eye"></i>
  </a>
  <?php else: ?>
    <a href="javascript:void()" id="viewCentral" class="btn btn-success" data-request="<?php echo $this->Utilidades->encrypt($request["CreditsRequest"]["id"]); ?>" style="display:none">
    Ver respuesta de centrales <i class="fa fa-eye"></i>
  </a>
  <?php endif ?>
</div>
<div class="result-consult">
  <input type="hidden" id="Rdnb" value="<?php echo $this->Utilidades->encrypt($request["CreditsRequest"]["id"]); ?>">

</div>
<button type="button" class="btn btn-success pull-right approveRequest" data-id="<?php echo $this->Utilidades->encrypt($request["ShopCommerce"]["id"]) ?>">Aprobar</button>
<button type="button" class="btn btn-secondary pull-right rejectRequest" data-id="<?php echo $this->Utilidades->encrypt($request["CreditsRequest"]["id"]); ?>">Rechazar</button>

