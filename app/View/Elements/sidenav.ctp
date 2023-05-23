<div class="col-md-3 left_col">
  <div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
      <a href="" class="site_title p-0">
        <!-- <?php echo $this->Html->url("/") ?> -->
        <img src="hola.hpg" class="img-fluid px-2">
      </a>
    </div>

    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <div class="profile clearfix p-3">
      <div class="profile_info">
        <span>Hola</span>
        <h2 class="namesession">
          <?php echo AuthComponent::user("name") ?>
          <?php if (AuthComponent::user("role") == 4): ?>
            <?php echo "<br> <b>". AuthComponent::user("Shop.social_reason")."</b>" ?>
          <?php endif ?>
        </h2>
          <?php if (AuthComponent::user("role") == 5): ?>
            <hr class="borderwhite">
            <span class="cd">Cupo Disponible</span>
            <h2 class="bigh2">$  <?php echo number_format($totalCustomerQuote,2) ?></h2>
           <br>
              <a href="<?php echo $this->Html->url(["controller"=>"pages","action" => "dashboardcliente"]) ?>" class="btn btn-lg btn-primary home">

                  <!--btn btn-danger btn-sm btn-block mt-2-->
                Inicio
              </a>

            <?php if ($totalCustomerQuote < 750000): ?>
              <!--<a href="<?php echo $this->Html->url(["controller"=>"pages","action" => "newRequest"]) ?>" class="btn btn-danger btn-sm btn-block mt-2">
                Solicitar Crédito
              </a> -->

            <?php endif ?>

          <?php endif ?>
          <?php if (in_array(AuthComponent::user("role"), [1,2])): ?>
            <?php if (isset($debt_credishop)): ?>
              <hr class="borderwhite">
                <span>Solicitar Saldos a Favor</span>
              	<a href="<?php echo $this->Html->url(["controller"=>"payments","action" => "pendings"]) ?>" class="btn btn-primary btn-sm btn-block mt-2">
                    $ <?php echo number_format($debt_credishop,"2",".",",") ?>
              </a>
            <?php endif ?>
          <?php endif;?>
          <?php if (in_array(AuthComponent::user("role"), [4,7])): ?>
              <?php if (!empty($saldosCommercios)): ?>
                <hr class="borderwhite">
                <span>Solicitar Saldos a Favor</span>
                <?php foreach ($saldosCommercios as $key => $value): ?>
                  <?php if ($value["saldo"] == 0 ): ?>
                    <?php continue; ?>
                  <?php endif ?>
                  <a href="<?php echo $this->Html->url(["controller"=>"shop_payment_requests","action" => "add",$this->Utilidades->encrypt($key)]) ?>" class="btn btn-primary btn-saldo btn-sm btn-block mt-2">
                    <?php echo $value["name"]; ?> - <?php echo number_format($value["saldo"],"2",".",",") ?>
                	</a>
                <?php endforeach ?>
              <?php endif ?>
              <?php if (isset($debt_credishop)): ?>
                <hr class="borderwhite">
                <span class="mt-3 resetlineheight">Saldo por pagar a ZÍRO</span>
                <a href="<?php echo $this->Html->url(["controller"=>"payments","action" => "index"]) ?>">
                  <p class="bg-danger text-center text-white">
                    <?php echo number_format($debt_credishop,"2",".",",") ?>
                  </p>
                </a>
              <?php endif ?>
          <?php endif ?>
      </div>
      <div class="clearfix"></div>
    </div>
    <!-- /menu profile quick info -->

    <br />

    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <?php echo $this->element("roles/".AuthComponent::user("role")) ?>
     <!-- <div class="sidebar-footer hidden-small">
        <a data-toggle="tooltip" data-placement="top" title="Settings">
          <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
        </a>
        <a data-toggle="tooltip" data-placement="top" title="FullScreen" >
          <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
        </a>
        <a data-toggle="tooltip" data-placement="top" title="Lock">
          <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
        </a>
        <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
          <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
        </a>
      </div>-->
    </div>
    <!-- /menu footer buttons -->
  </div>
</div>
