<nav class="navbar navbar-expand-md navbar-light">
    <a class="navbar-brand logo" href="<?php echo Router::url("/",true) ?>"><img src="" class="img-fluid"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
<!--             <li class="nav-item active">
                <a class="nav-link" href="<?php echo Router::url("/",true) ?>">Inicio <span class="sr-only">(current)</span></a>
            </li>
             <li class="nav-item">
                <a class="nav-link" href="<?php echo $this->Html->url(["controller"=>"pages","action"=>"fastpayment"]) ?>">Pago rápido</a>
            </li> -->
<!--             <li class="nav-item">
                <a class="nav-link" href="#">Proveedor Afiliados</a>
            </li>  -->
        </ul>
        <ul class="navbar-nav">
            <?php if (!AuthComponent::user("id")): ?>
              <!--  <li class="nav-item">
                  <a class="btn btn-lg btn-outline-secondary mr-2" href="<?php echo $this->Html->url(["controller"=>"pages","action" => "shops"]) ?>"><i class="fa fa-building" aria-hidden="true"></i> Empresas</a>
              </li>     -->
              <!--<li class="nav-item">-->
              <!--    <a class="btn btn-lg btn-outline-secondary mr-2" href="<?php echo $this->Html->url(["controller"=>"pages","action"=>"commerce_payment"]) ?>"><i class="fa fa-dollar" aria-hidden="true"></i> Transacciones</a>-->
              <!--</li>  -->
              <!--<li class="nav-item">-->
              <!--    <a class="btn btn-lg btn-outline-secondary mr-2" href="<?php echo $this->Html->url(["controller"=>"pages","action"=>"fastpayment"]) ?>"><i class="fa fa-dollar" aria-hidden="true"></i> Pago Cuotas</a>-->
              <!--</li>              -->
              <li class="nav-item">
                  <a class="btn btn-lg btn-secondary mr-2" href="<?php echo $this->Html->url(["controller"=>"users","action"=>"login"]) ?>"><i class="fa fa-sign-in" aria-hidden="true"></i> Ingresar</a>
              </li>
              <li class="nav-item">
                  <a class="btn btn-lg btn-primary mr-2" href="<?php echo $this->Html->url(["controller"=>"pages","action"=>"register_step_unique"]) ?>"><i class="fa fa-user" aria-hidden="true"></i> Registrarme</a>
              </li>
              <!--<li class="nav-item">-->
              <!--    <a class="btn btn-lg btn-warning" href="<?php echo $this->Html->url(["controller"=>"users","action"=>"login"]) ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Transferir Cupo</a>-->
              <!--</li>              -->
            <?php else: ?>
              <li class="nav-item">
                  <p class="d-inline mr-3">Hola, <b><a href="<?php echo $this->Html->url(["controller"=>"payments","action"=>"index"]) ?>"><?php echo AuthComponent::user("name") ?></a></b></p>
                  <a class="btn btn-lg btn-primary" href="<?php echo $this->Html->url(["controller"=>"users","action"=>"logout"]) ?>"><i class="fa fa-times" aria-hidden="true"></i> Salir</a>
              </li>
            <?php endif ?>
        </ul>
    </div>
</nav>
