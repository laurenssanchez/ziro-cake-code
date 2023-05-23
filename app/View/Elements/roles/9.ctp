<div class="menu_section">
  <h3>General</h3>
  <ul class="nav side-menu">
    <li>
      <a href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "cobranza","?" => ["tab" => 1] ]) ?>">
        <i class="fa fa-money"></i>
        Gestión de Cobranza <span class="fa fa-money"></span>
      </a>
    </li>

		<li>
      <a href="<?php echo $this->Html->url(["controller" => "payments", "action" => "index"]) ?>">
        <i class="fa fa-money"></i>
        Recaudos <span class="fa fa-money"></span>
      </a>
    </li>

  </ul>
</div>
