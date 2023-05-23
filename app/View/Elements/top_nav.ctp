<!-- top navigation -->
<div class="top_nav">
  <div class="nav_menu">
      <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
      </div>
      <nav class="nav navbar-nav">
      <ul class=" navbar-right">
        <li class="nav-item dropdown open">

          <?php if (in_array(AuthComponent::user("role"), [4,6])): ?>
            <a href="#" class="btn btn-primary pull-sm " id="btnSearch">
             <span class="d-none d-md-inline-block"> Buscar cliente </span> <i class="fa fa-search vtc">	</i>
            </a>
          <?php elseif(AuthComponent::user("role") == 15): ?>
	        <a class="btn btn-danger btn-sm" href="<?php echo $this->Html->url(["controller" => "credits", "action" => "customer_request"]) ?>">
	          <i class="fa fa-plus"></i>
	          Crear nueva solicitud
	        </a>
          <?php endif ?>

          <span class="bl-timer"><i class="fa fa-clock-o" aria-hidden="true"></i><b> <span id="tiempoRestante"></span> minutos &nbsp; &nbsp;</b></span>
          <!-- <?php if (in_array(AuthComponent::user("role"), [4,6])): ?>
            <a href="<?php echo $this->Html->url(["controller"=>"pages","action"=>"commerce_payment"]) ?>" target="_blank" class="btn btn-secondary btn-sm" aria-haspopup="true" >
              Botón de pagos
            </a>
          <?php endif ?> -->




         <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
				  <span class="bl-textSaludo">Hola,</span> <?php echo AuthComponent::user("name") ?>
							<?php if (AuthComponent::user("role") == 4): ?>
								<?php echo " - <b>". AuthComponent::user("Shop.social_reason")."</b>" ?>
							<?php endif ?>
						</a>

          <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
          <!-- <a class="dropdown-item"  href="javascript:;"> Perfil</a>
            <a class="dropdown-item"  href="javascript:;">
              <span class="badge bg-red pull-right">50%</span>
              <span>Configuración</span>
            </a>
          <a class="dropdown-item"  href="javascript:;">Ayuda</a> -->
            <a class="dropdown-item"  href="<?php echo $this->Html->url(array("controller"=>"users","action" => "logout")) ?>"><i class="fa fa-sign-out pull-right"></i>
            Cerrar sesión</a>
          </div>
        </li>
      </ul>
    </nav>
  </div>
</div>

<?php if (in_array(AuthComponent::user("role"), [4,6,7])): ?>
	<div class="modal fade" id="searchCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
	  	<div class="modal-dialog  modal-dialog-scrollable modal-lg">
		    <div class="modal-content">
		      	<div class="modal-header">
			        <h5 class="modal-title" id="">
				          <div class="content-tittles">
					            <div class="line-tittles">|</div>
					            <div>
						            <h1>BUSCAR</h1>
						            <h2>CLIENTES EN EL SISTEMA</h2>
					            </div>
				          </div>
			        </h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
		      	</div>
		      	<div class="modal-body" id="searchCustomerBody">
					<div class="row">
						<div class="col-md-3 pt-2">
							<label for="#ccCustomer">Cédula del cliente</label>
						</div>
						<div class="col-9 col-md-6">
							<input type="number" id="ccCustomer" class="form-control">
						</div>
						<div class="col-3 col-md-3">
							<a href="" class="btn btn-search btn-primary" id="btnCustomerSearch">
								<i class="fa fa-search btc"></i>
							</a>
						</div>
					</div>
					<div class="row" id="dataCustomerDataPayment"></div>
		      	</div>
			    <div class="modal-footer">
			        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
			    </div>
		    </div>
	  	</div>
	</div>
<?php endif ?>
