<?php echo $this->element("menu-landings"); ?>

<div class="container-fluid pr-0 pl-0 pt-5 bg-img">
  <div class="container-login">
    <div class="login-box-body">
        <center>
            <a class="navbar-brand" href="<?php echo Router::url("/",true) ?>"><img src="hola.hpg" class="img-fluid"></a>
        </center>

              <?php echo $this->Form->create('User', array('role' => 'form','data-parsley-validate=""'));?>
            <?php if (!empty($hash)) : ?>
              <?php echo $this->Form->input('hash',array('value' => $hash,'type' => 'hidden')); ?>
            <?php endif;?>
            <?php echo $this->Form->input('password',array('label' => "",'class' => "form-control mb-2",'placeholder' => __('Nueva contraseña'),"required"=>true));?>
            <?php echo $this->Form->input('re_password',array('label' => "",'class' => "form-control mb-2",'placeholder' => __('Confirmar nueva contraseña'),"type"=>"password","required"=>true,"data-parsley-equalto"=>"#UserPassword","data-parsley-error-message"=>"Esta información debe coincidir con el campo Nueva contraseña"));?>

        <div class="home-rest">
          <?php echo $this->Form->end(__('Restablecer'),array('class' => 'btn btn-login btn-primary home'));?>
         </div>

        </div>
      </div>

      <!---->
<div class="ziro-estres" style="position:fixed; right:10px; bottom:10px; text-align:center; padding:3px">
<a target="_BLANK" href="https://creditos.somosziro.com/">
<img src="https://creditos.somosziro.com/img/ziro-estres.png" height="50"></a>
</div>
</div>
