<?php echo $this->element("/menu-landings"); ?>

<div class="container-fluid p-0 bg-img-blue">
  <div class="control-absolute">
  <div class="content-steps container">

    <div class="relative">
        <div class="circle-content circle-content-1 active">
          <h2>1</h2>
        </div>
        <div class="steps step1 active">
          <p>Capturas</p>
        </div>
    </div>

    <div class="relative">
        <div class="circle-content circle-content-2">
          <h2>2</h2>
        </div>
        <div class="steps step2">
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

  <div class="container pb-5">
     <div class="row">
      <?php echo $this->Form->create('Customer', array('role' => 'form','data-parsley-validate=""')); ?>
        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-6 pt-5">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>INFORMACIÓN DE</h1>
                      <h2>TU CUENTA</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-6 pt-5">
                <p>En este Email recibirás tus códigos de seguridad</p>

                  <?php if (isset($customer)): ?>
                    <?php echo $this->Form->input('id', array('class' => 'form-control','label'=>'Tipo de identificación','div'=>false,"type"=>"hidden")); ?>

                     <div class="form-group">
                    <?php echo $this->Form->input('email', array('class' => 'form-control','label'=>'Tu Correo Electrónico','div'=>false,'placeholder'=>'Escribe tu correo Electrónico','required' => true,"readonly")); ?>
                  </div>
                  <?php else: ?>
                  <div class="form-group">
                    <?php echo $this->Form->input('email', array('class' => 'form-control','label'=>'Tu Correo Electrónico','div'=>false,'placeholder'=>'Escribe tu correo Electrónico','required' => true)); ?>

                    <a href="<?php echo $this->Html->url(["controller"=>"pages","action"=>"normal_request"]) ?>" class="btn btn-info mt-2 p-0 px-1">
                      No tengo correo eléctronico (X)</i>
                    </a>

                  </div>

                  <?php endif ?>

                  <?php if (!isset($customer)): ?>


                  <p>Con esta contraseña tendrás acceso a tu cuenta!</p>

                  <div class="form-group">
                    <?php echo $this->Form->input('password', array('class' => 'form-control','label'=>'Tu Contraseña','div'=>false,'placeholder'=>'Escribe tu contraseña',"data-parsley-type"=>"alphanum","data-parsley-minlength"=>"4","data-parsley-maxlength"=>"4",'required' => true)); ?>
                  </div>

                  <?php endif ?>
                <div class="form-group">
                  <?php echo $this->Form->input('code', array('class' => 'form-control','label'=>'Código del proveedor','div'=>false,'placeholder'=>'Código de la tienda donde solicitas el crédito','required' => true)); ?>
                </div>
              </div>
          </div>
        </div>
      <?php if(!isset($customer)): ?>
        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-6 pt-5">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>INFORMACIÓN</h1>
                      <h2>DE IDENTIFICACIÓN</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-6 pt-5">
                <p>Con esta información validamos tu identidad</p>
                  <div class="form-group">
                    <?php echo $this->Form->input('identification_type', array('class' => 'form-control','label'=>'Tipo de identificación','div'=>false,"value" => "CC","type" => "hidden")); ?>
                  </div>

                  <div class="form-group">
                    <?php echo $this->Form->input('identification', array('class' => 'form-control','label'=>'Tu número de identificación','div'=>false,'placeholder'=>'Escribe tu Número de identificación','data-parsley-type'=>"number","data-parsley-minlength"=>"5",'required' => true)); ?>
                  </div>

              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-6">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>FOTO</h1>
                      <h2>DOCUMENTO FRONTAL</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-6 text-center">
                <select id="select" style="display:none;">
                  <option></option>
                </select>
                <canvas id="canvasFotoUp" style="display: none;" class="w-100"></canvas>
                <img src="" id="imgUpFile" class="img-fluid">
                 <?php echo $this->Form->input('document_file_up2', array('class' => 'form-control','label'=>false,'div'=>false,"required" => true,"type"=>"hidden")); ?>
                <button type="button" class="btn btn-primary btn-lg mt-2 fotoBtn" data-canvas="canvasFotoUp" id="fotoUpFile" data-input="CustomerDocumentFileUp2" data-img="imgUpFile">Tomar foto</button>
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-6">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>FOTO</h1>
                      <h2>DOCUMENTO RESPALDO</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-6 text-center">
                  <canvas id="canvasFotoDown" style="display: none;" class="w-100"></canvas>
                  <img src="" class="img-fluid" id="imgDownFile">
                   <?php echo $this->Form->input('document_file_down2', array('class' => 'form-control','label'=>false,'div'=>false,"required" => true,"type"=>"hidden")); ?>
                  <button type="button" class="btn btn-primary btn-lg mt-2 fotoBtn" data-canvas="canvasFotoDown" id="fotoDownFile" data-input="CustomerDocumentFileDown2" data-img="imgDownFile">Tomar foto</button>
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-6">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>FOTO SELFIE CON</h1>
                      <h2>DOCUMENTO FRONTAL</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-6 text-center">
                  <canvas id="canvasFotoUser" style="display: none;" class="w-100"></canvas>
                  <img src="" class="img-fluid" id="imgFotoUser">
                  <?php echo $this->Form->input('image_file2', array('class' => 'form-control','label'=>false,'div'=>false,"required" => true,"type"=>"hidden")); ?>
                  <button type="button" class="btn btn-primary btn-lg mt-2 fotoBtn" data-canvas="canvasFotoUser" id="fotoUserFile" data-input="CustomerImageFile2" data-img="imgFotoUser">Tomar foto</button>
              </div>
          </div>
        </div>

      <?php endif;?>

        <div class="col-md-12 bg-white pd-registerblock">
          <div class="row">
              <div class="col-md-7">
                  <div class="content-tittles">
                    <div class="line-tittles">|</div>
                    <div>
                      <h1>AUTORIZACIÓN DE </h1>
                      <h2>TRATAMIENTO DE DATOS</h2>
                    </div>
                  </div>
              </div>
              <div class="col-md-5">
                    <div class="form-check p-2">
                      <?php echo $this->Form->input('tyc', array('class' => 'form-check-input','label'=>false,'div'=>false,"required" => true,"type"=>"checkbox")); ?>
                      <label class="form-check-label big-label" for="">Si, acepto la Autorización de Tratamiento de Datos</label>
                    </div>
              </div>
          </div>
        </div>

        <div class="col-md-12 bg-white text-center pt-3 pb-5">
          <div class="pb-5">
              <a href="<?php echo $this->Html->url(AuthComponent::user("id") ? ["controller"=>"credits_requests","action"=>"index"] : ["controller"=>"pages","action"=>"home"] ) ?>" class="btn btn-outline-primary btn-lg mt-2">Volver</a>
              <input type="submit" class="btn btn-primary btn-lg mt-2" value="Guardar">
          </div>
        </div>
      </form>
     </div>
  </div>
</div>

<?php echo $this->element("take_photo"); ?>
<?php
  echo $this->Html->script("ctrl/customers/add.js?".rand(),             array('block' => 'AppScript'));
  echo $this->Html->script("ctrl/customers/forms.js?".rand(),             array('block' => 'AppScript'));
?>

<?php echo $this->element("/modals/tyc"); ?>
