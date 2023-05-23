<div class="menu_section">
  <h3>General</h3>
  <ul class="nav side-menu">
    <!--<li>
    <a href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "index","?"=>["usoFecha" => "1","ccCustomer" => "","commerce" => "","ini" => date("Y-m-d",strtotime("-1 day")),"end" => date("Y-m-d") ]]) ?>">
        <i class="fa fa-money"></i>
        Solicitudes de cupos <span class="fa fa-money"></span>
      </a>

    </li>-->
    <li>
      <a>
        <i class="fa fa-shopping-basket"></i>
        Solicitudes de cupos <span class="fa fa-money"></span>
      </a>
      <ul class="nav child_menu">
        <li>
          <a href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "index", "?"=>["usoFecha" => "1","ccCustomer" => "","commerce" => "","ini" => date("Y-m-d",strtotime("-1 day")),"end" => date("Y-m-d") ]]) ?>">
            Por Tarjetas
          </a>
        </li>
        <li>
          <a href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "index_lista" ]) ?>">
            Por Lista
          </a>
        </li>
      </ul>
    </li>
    <li>
      <a href="<?php echo $this->Html->url(["controller" => "payments", "action" => "index"]) ?>">
        <i class="fa fa-money"></i>
        Recaudos <span class="fa fa-money"></span>
      </a>
    </li>
  </ul>
</div>
