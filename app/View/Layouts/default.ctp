<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="https://creditos.somosziro.com/img/ico-ziro.jpg" type="image/x-icon" rel="icon"/><link href="https://creditos.somosziro.com/img/ico-ziro.jpg" type="image/x-icon" rel="shortcut icon"/>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Muli:wght@300;700&display=swap" rel="stylesheet">
    <title>ZÍRO</title>

    <?php echo $this->Html->css(['/vendors/bootstrap/dist/css/bootstrap.min.css','/vendors/font-awesome/css/font-awesome.min.css','/builds/css/custom.css?v=1.1.0']); ?>



    <?php
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
     ?>
    <?php echo $this->Html->script(['/vendors/jquery/dist/jquery.min.js',]) ?>
     <?php
          echo $this->fetch('styleApp');
          echo $this->fetch('jqueryApp');
        ?>
    <?php echo $this->element("js_vars"); ?>
        <script type="text/javascript">
            var root        = "<?php echo Router::url("/",true); ?>"
            var controller  = "<?php echo $this->params->params["controller"]; ?>"
            var action      = "<?php echo $this->params->params["action"]; ?>"
            var datenow      = "<?php echo date("Y-m-d"); ?>"
          ///  var data_full   = <?php echo json_encode($this->params); ?>
          ///
            var isRol = <?php echo AuthComponent::user("role") == 6 && AuthComponent::user("shop_commerce_id") == Configure::read("COMMERCE") ? "true" : "false"; ?>;

            var idDataRol = "<?php echo $this->Utilidades->encrypt(Configure::read("COMMERCE")) ?>"

        </script>

    <?php
     $whitelist = array(
              '127.0.0.1',
              '::1'
          );

   ?>

  </head>
  <body class="nav-md aaa" id="dashboardcliente">

<div id="preloader">
  <div id="loader"></div>
</div>
        <div class="container body <?php echo $this->params->params['controller']; echo $this->params->params['action']; ?>">
            <div class="main_container">

                <?php if (AuthComponent::user("id")): ?>
                  <?php echo $this->element("sidenav"); ?>
                  <?php echo $this->element("top_nav"); ?>
                <?php endif ?>
                <div class="right_col" role="main">
                    <?php echo $this->Session->flash(); ?>
                  <div class="">
                    <?php echo $this->fetch('content'); ?>
                  </div>
                </div>
            </div>
        </div>
        <?php
          echo $this->Html->script(array('/vendors/bootstrap/dist/js/bootstrap.bundle.min.js','/vendors/fastclick/lib/fastclick.js','/builds/js/custom.min.js','https://cdn.jsdelivr.net/npm/sweetalert2@9','/vendors/parsleyjs/dist/parsley.min.js','/vendors/parsleyjs/dist/i18n/es.js','bootstrap-notify.min.js','https://www.chartjs.org/dist/2.9.4/Chart.min.js','general.js','sesion.js'));
        ?>
				<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/PrintArea/2.4.1/jquery.PrintArea.min.js" integrity="sha512-mPA/BA22QPGx1iuaMpZdSsXVsHUTr9OisxHDtdsYj73eDGWG2bTSTLTUOb4TG40JvUyjoTcLF+2srfRchwbodg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- 'https://maps.googleapis.com/maps/api/js?key=AIzaSyA5kX4Hd4DKakL_l0kyKj_dASdKZ7Cah_M&libraries=places' -->
        <?php
          echo $this->fetch('AppScript');
        ?>

  </body>
</html>
