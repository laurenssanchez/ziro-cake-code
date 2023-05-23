<div class="menu_section">
  <h3>General</h3>
  <ul class="nav side-menu">
    <li>
      <a href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "index"]) ?>">
        <i class="fa fa-money"></i>
        Solicitudes de cupos <span class="fa fa-money"></span>
      </a>
    </li>
    <li>
      <a href="<?php echo $this->Html->url(["controller" => "credits", "action" => "index"]) ?>">
        <i class="fa fa-money"></i>
        Historial de Créditos <span class="fa fa-money"></span>
      </a>
    </li>
    <!-- <li>
      <a>
        <i class="fa fa-shopping-basket"></i>
        Historial de solicitudes <span class="fa fa-connectdevelop"></span>
      </a>
      <ul class="nav child_menu">
        <li>
          <a href="<?php echo $this->Html->url(["controller" => "shop_commerces", "action" => "add"]) ?>">
             Historial completo
          </a>
        </li>
      </ul>
    </li> -->
    <li>
      <a href="<?php echo $this->Html->url(["controller" => "payments", "action" => "reports"]) ?>">
        <i class="fa fa-file"></i>
        Informes <span class="fa fa-file"></span>
      </a>
    </li>

		<li>
      <a href="<?php echo $this->Html->url(["controller" => "credits", "action" => "index"]) ?>">
        <i class="fa fa-money"></i>
        Creditos <span class="fa fa-money"></span>
      </a>
    </li>

    <!-- <li>
      <a href="<?php echo $this->Html->url(["controller" => "payments", "action" => "index"]) ?>">
        <i class="fa fa-money"></i>
        Recaudos <span class="fa fa-money"></span>
      </a>
    </li> -->

    <!--<li>-->
    <!--  <a href="<?php echo $this->Html->url(["controller" => "requests", "action" => "index"]) ?>">-->
    <!--    <i class="fa fa-money"></i>-->
    <!--    Pagos online <span class="fa fa-money"></span>-->
    <!--  </a>-->
    <!--</li>-->
  </ul>
</div>
