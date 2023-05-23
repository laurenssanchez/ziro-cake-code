<div class="contentdash">
	<div class="">
		<img src="hola.hpg" class="img-fluid px-5 py-0">
	  <div class="my-1 py-2 text-white">
	  	<a class=" pull-right iconcolor"  href="<?php echo $this->Html->url(array("controller"=>"users","action" => "logout")) ?>">
	  		<i class="fa fa-power-off"></i>
      </a>
	    <h5 class="titleg mb-0">Hola,</h5>
	    <h3 class="titlename m-0"><b class="capt"><?php echo AuthComponent::user("name") ?></b></h3>
	    <hr class="linewhite">
	  </div>
	  <div class="bg-light text-center px-3 py-4">
	    <h1 class="titleg m-0"><b>$  <?php echo number_format($totalCustomerQuote,2) ?></b></h1>
	    <p class="copys m-0">TU CUPO TOTAL DISPONIBLE</p>
	    <a  class="btn btn-primary  mt-2" href="<?php echo $this->Html->url(["controller" => "credits", "action" => "index"]) ?>" >MIS CRÉDITOS</a>
	  </div>

	  <div class="bg-light text-center px-3 py-4 mt-3">
	    <h1 class="titleg m-0"><b>$ <?php echo number_format($total, 0,",",".") ?></b></h1>
	    <p class="copys m-0">SALDO EN CRÉDITOS ACTIVOS</p>
	    <?php if ($total > 0): ?>
	    	<a  class="btn btn-primary fastsearchingData mt-2" data-idt="<?php echo $customer["Customer"]["identification"] ?>" href="">PAGAR CUOTAS</a>
	    <?php endif ?>
	  </div>

		<div id="cardsbtn" class="bg-light text-center px-3 py-3 mt-3">
		  <!-- <div class="groupcard">
					<?php if (isset($totalCustomerQuote) && $totalCustomerQuote > 10000): ?>
						<a href="javascript:void(0)" class="" id="transferBtn">
						  	<img src="<?php echo Router::url("/",true) ?>img/png/1.png" alt="" class="img-fluid">
						  	<span>Transferir Cupo</span>
						</a>
					<?php endif ?>
		  </div> -->
		  <div class="groupcard">
		  	<?php if (isset($totalCustomerQuote) && $totalCustomerQuote > 0): ?>
			  	<?php if ($actualNoTrue): ?>
			  		<h3 class="text-danger">
			  			<?php if ($actualNoTrue): ?>
			  			<b>Tu solicitud de aumento de cupo fue negada</b>

			  			<?php endif ?>



			  		</h3>
			  	<?php else: ?>
			  		<a href="javascript:void(0)" class="" id="solicitaNew" >
					  	<img src="<?php echo Router::url("/",true) ?>img/png/2.png" alt="" class="img-fluid">
					  	<span>Solicitar Aumento </span>
					</a>
			  	<?php endif ?>
		  	<?php endif ?>
			  	<?php if ($actualNoTrueNormal): ?>

			  		<h6 class="text-danger">
			  			<b>Tu última solicitud de cupo por: $<?php echo number_format($creditoNormal["CreditsRequest"]["request_value"]) ?> fue negada.</b>
			  		</h6>
			  	<?php endif ?>
		  </div>
		  <div class="groupcard">
			  <a href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "index"]) ?>" class="">
			  	<img src="<?php echo Router::url("/",true) ?>img/png/4.png" alt="" class="img-fluid">
			  	<span>Mis Solicitudes</span>
			  </a>
		  </div>
		  <div class="groupcard">
			  <a href="<?php echo $this->Html->url(["controller" => "payments", "action" => "index"]) ?>" class="">
			  	<img src="<?php echo Router::url("/",true) ?>img/png/5.png" alt="" class="img-fluid">
			  	<span>Pagos Realizados</span>
			  </a>
		  </div>
		</div>



</div>

<div class="modal fade" id="modalDeuda" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pagar deudas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modal-body-pagarCuota" id="bodyDeuda">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<style>
	.select2{
		width: 100% !important;
	}
</style>

<?php echo $this->Html->css("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css",["block"=>"styleApp"]) ?>
<?php echo $this->Html->css("https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css",["block"=>"styleApp"]) ?>

<?php echo $this->Html->script("https://checkout.epayco.co/checkout.js?".rand(),           array('block' => 'AppScript')); ?>
<?php echo $this->Html->script("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js",           array('block' => 'AppScript')); ?>
<?php echo $this->Html->script("customers/admin.js?".rand(),           array('block' => 'AppScript')); ?>


<?php echo $this->Html->script("https://checkout.wompi.co/widget.js?".rand(),           array('block' => 'AppScript')); ?>
<?php echo $this->element("/modals/transfer_credit"); ?>
<?php echo $this->element("/modals/request_credit_extra",["actual"=>$totalCustomerQuote]); ?>
