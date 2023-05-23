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
      <a>
        <i class="fa fa-shopping-basket"></i>
        Cupos por gestionar <span class="fa fa-connectdevelop"></span>
      </a>
      <ul class="nav child_menu">
        <li>
          <a href="<?php echo $this->Html->url(["controller" => "shop_commerces", "action" => "index"]) ?>">Listar sucursales
          </a>
        </li>
        <li>
          <a href="<?php echo $this->Html->url(["controller" => "shop_commerces", "action" => "add"]) ?>">
            Crear sucursal
          </a>
        </li>
      </ul>
    </li>
    <li>
      <a href="<?php echo $this->Html->url(["controller" => "shop_payment_requests", "action" => "index"]) ?>">
        <i class="fa fa-money"></i>
        Saldos y desembolsos <span class="fa fa-money"></span>
      </a>
    </li>
  </ul>
</div>
