<h5><b>Registra un saldo adeudado de este proveedor</b></h5>
<?php echo $this->Form->create('ShopsDebt', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left')); ?>
    <?php echo $this->Form->input('user_id', array('class' => 'form-control border-input','label'=>false,'div'=>false,"value"=>AuthComponent::user("id"),"type"=>"hidden")); ?>
    <?php echo $this->Form->input('shop_id', array('class' => 'form-control border-input','label'=>false,'div'=>false,"value"=>$id,"type"=>"hidden")); ?>
    <div class="form-row">
    	  <div class="form-group col-md-12">
              <?php echo $this->Form->label('ShopsDebt.shop_commerce_id',__('Sucursal'), array());?>
            <?php echo $this->Form->input('shop_commerce_id', array('class' => 'form-control border-input','label'=>false,'div'=>false,"options"=>$commercesList,"empty"=>"Seleccionar","required")); ?>
          </div>
          <div class="form-group col-md-4">
              <?php echo $this->Form->label('ShopsDebt.value',__('Valor adeudado'), array());?>
            <?php echo $this->Form->input('value', array('class' => 'form-control border-input','label'=>false,'div'=>false,"placeholder" => "Ingresa el valor","min"=>1000,"step"=> 1000,"required")); ?>
          </div>
          <div class="form-group col-md-8">
              <?php echo $this->Form->label('ShopsDebt.reason',__('Concepto'), array());?>
            <?php echo $this->Form->input('reason', array('class' => 'form-control border-input','label'=>false,'div'=>false,"placeholder"=>"Detalla la razón de este cobro","required")); ?>
        </div>
        <div class="form-group col-md-12">
            <p>Recuerda que este valor se liquidará cuando el proveedor solicite un pago de un crédito</p>
            <div class="mt-4 mb-4 text-right">
              <button type="submit" class="btn btn-primary">Registrar Deuda</button>
            </div>
        </div>
    </div>
</form>
<hr>
<h5><b>Deudas previas</b></h5>
<table class="table">
  <thead class="thead-light">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Fecha</th>
      <th scope="col">Proveedor</th>
      <th scope="col">Valor</th>
      <th scope="col">Concepto</th>
      <th scope="col">Registró</th>
      <th scope="col">Estado</th>
    </tr>
  </thead>
  <tbody>
      <?php if (!empty($debts)): ?>
        <?php $num = 1; ?>
        <?php foreach ($debts as $key => $value): ?>
            <tr class="<?php echo in_array($value["ShopsDebt"]["state"], [0,1]) ? "pending-row" : "" ?>">
                <td scope="row"><?php echo $num ?></td>
                <td><?php echo $value["ShopsDebt"]["created"] ?></td>
                <td><?php echo $value["ShopCommerce"]["name"] ?></td>
                <td><?php echo number_format($value["ShopsDebt"]["value"]) ?></td>
                <td><?php echo $value["ShopsDebt"]["reason"] ?></td>
                <td><?php echo $value["ShopsDebt"]["user_id"] == "0" ? "Sistema" : $value["User"]["name"] ?></td>
                <td><?php

                    switch ($value["ShopsDebt"]["state"]) {
                      case '0':
                        $state = "Pendiente de cobro";
                        break;
                      case '1':
                        $state = "En proceso de cobro";
                        break;
                      case '2':
                        $state = 'Pagado';
                        break;
                    }

                    echo $state;

                 ?></td>
            </tr>
            <?php $num++; ?>
        <?php endforeach ?>
      <?php else: ?>
          <tr>
              <td colspan="6">No hay deudas registradas</td>
          </tr>
      <?php endif ?>
  </tbody>
</table>
