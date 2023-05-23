<div class="menu_section">
  <h3>General</h3>
  <ul class="nav side-menu">
    <li>
      <a href="<?php echo $this->Html->url(["controller" => "customers_codes", "action" => "index","?"=>["tab" => 1]]) ?>">
        <i class="fa fa-phone"></i> 
        Códigos enviados <span class="fa fa-phone"></span>
      </a>
    </li>
    <li>
      <a href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "index_support","?"=>["tab" => 1]]) ?>">
        <i class="fa fa-phone"></i> 
        Creditos clientes <span class="fa fa-money"></span>
      </a>
    </li>
    <li>
      <a href="<?php echo $this->Html->url(["controller" => "receipts", "action" => "index" ]) ?>">
        <i class="fa fa-phone"></i> 
        Pagos clientes <span class="fa fa-money"></span>
      </a>
    </li>
  </ul>
</div>