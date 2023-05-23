<?php echo $this->element("/menu-landings"); ?>

<div class="container-fluid p-0 bg-img-blue">
  <div class="control-absolute">
  <div class="content-steps container">

    <div class="relative">
        <div class="circle-content circle-content-1">
          <h2>1</h2>
        </div>
        <div class="steps step1">
          <p>Capturas</p>
        </div>
    </div>

    <div class="relative">
        <div class="circle-content circle-content-2 active">
          <h2>2</h2>
        </div>
        <div class="steps step2 active">
          <p>Personal</p>
        </div>
    </div>

    <div class="relative">
        <div class="circle-content circle-content-3">
          <h2>3</h2>
        </div>
        <div class="steps step3">
          <p>Contactos</p>
        </div>
    </div>

  </div>
</div>

  <div class="container pb-3">
     <div class="row">
      <?php echo $this->Form->create('Customer', array('role' => 'form','data-parsley-validate=""',"class" => "w-100")); ?>
        <?php if (!isset($customer["Customer"]["city_birth"])): ?>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-5 pt-3">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>INFORMACIÓN</h1>
                      <h2>PERSONAL</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-7 pt-3">
                <p>Diligencia tus datos como aparecen en tu cédula</p>
					         <div class="form-group">
	                    <?php echo $this->Form->input('name', array('class' => 'form-control','label'=>'Nombres','div'=>false,'placeholder'=>'Nombres como están en tu cédula','required' => true)); ?>
	                </div>
                  <div class="form-group">
                      <?php echo $this->Form->input('last_name', array('class' => 'form-control','label'=>'Apellidos','div'=>false,'placeholder'=>'Apellidos como están en tu cédula','required' => true)); ?>
                  </div>
        				  <div class="form-group">
                    <label for="">Tu fecha de nacimiento</label>
        					  <?php echo $this->Form->text('date_birth', array('class' => 'form-control','label'=>'Tu fecha de nacimiento','div'=>false,"type" => "date",'required' => true)); ?>
        				  </div>
              </div>
          </div>
        </div>
        <?php endif ?>


        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-5 pt-3">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>INFORMACIÓN</h1>
                      <h2>LABORAL</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-7 pt-3">
                <form>
                  <div class="form-group">
                    <?php echo $this->Form->input('occupation', array('class' => 'form-control','label'=>'Tu ocupación','div'=>false,'placeholder'=>'Selecciona una ocupación...','required' => true,"empty"=>"Selecciona tu ocupación...","options" => array_combine(Configure::read("OCUPACIONES"), Configure::read("OCUPACIONES")))); ?>
                  </div>

	              <div class="form-group">
                   <?php echo $this->Form->input('monthly_income', array('class' => 'form-control','label'=>'Ingresos mensuales','div'=>false,'placeholder'=>'¿Cuánto dinero ganas al mes?','required' => true,"data-parsley-validate" => "number","min"=>0,"max"=>1000000000)); ?>
	              </div>
                <div class="form-group">
                   <?php echo $this->Form->input('type_contract', array('class' => 'form-control','label'=>'Tipo de contrato','div'=>false,'required' => true,"empty"=>"Selecciona tu profesión...","options" => Configure::read("TIPO_DE_CONTRATO") )); ?>
                </div>

              </div>
          </div>
        </div>


        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-4">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>AUTORIZACIÓN </h1>
                      <h2>CUPO ENDEUDAMIENTO</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-8">
                    <div class="form-check">
                      <?php echo $this->Form->input('politics', array('class' => 'form-check-input','label'=>false,'div'=>false,"required" => true,"type"=>"checkbox")); ?>
                      <label class="form-check-label big-label pt-2" for="">Si, Acepto el Contrato para Cupo de Endeudamiento Rotativo y Acceso a la Plataforma</label>
                    </div>
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white text-center pt-3 pb-3">
              <input type="submit" class="btn btn-primary btn-lg mt-2" value="Guardar">
          </div>
        </div>

    </form>
     </div>
  </div>
</div>

<?php

   echo $this->Html->script("ctrl/customers/forms.js?".rand(),             array('block' => 'AppScript'));

 ?>
<?php echo $this->element("/modals/tyc"); ?>
<?php
  echo $this->Html->script("ctrl/customers/tyc.js?".rand(),             array('block' => 'AppScript'));
?>
