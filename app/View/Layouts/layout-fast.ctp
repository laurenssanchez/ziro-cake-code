<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="https://creditos.somosziro.com/img/ico-ziro.jpg" type="image/x-icon" rel="icon"/><link href="https://creditos.somosziro.com/img/ico-ziro.jpg" type="image/x-icon" rel="shortcut icon"/>
    <link href="https://fonts.googleapis.com/css2?family=Muli:wght@300;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@1,300;1,700&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css" />
    <title>ZÍRO</title>

    <?php echo $this->Html->css(['/vendors/bootstrap/dist/css/bootstrap.min.css','/vendors/font-awesome/css/font-awesome.min.css','/css/home.css','/css/responsive.css']); ?>

    <?php

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');

     ?>

     <?php echo $this->element("js_vars"); ?>

      <script type="text/javascript">

        var root        = "<?php echo Router::url("/",true); ?>"
        var controller  = "<?php echo $this->params->params["controller"]; ?>"
        var action      = "<?php echo $this->params->params["action"]; ?>"
      ///  var data_full   = <?php echo json_encode($this->params); ?>
      </script>

       <style>

      #preloader {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          z-index: 9999;
          background: #000000b5;
      }
      #loader {
          display: block;
          position: relative;
          left: 50%;
          top: 50%;
          width: 150px;
          height: 150px;
          margin: -75px 0 0 -75px;
          border-radius: 50%;
          border: 3px solid transparent;
          border-top-color: #1FE679;
          -webkit-animation: spin 1.5s linear infinite;
          animation: spin 1.5s linear infinite;
      }
      #loader:before {
          content: "";
          position: absolute;
          top: 5px;
          left: 5px;
          right: 5px;
          bottom: 5px;
          border-radius: 50%;
          border: 3px solid transparent;
          border-top-color: #299359;
          -webkit-animation: spin 2s linear infinite;
          animation: spin 2s linear infinite;
      }
      #loader:after {
          content: "";
          position: absolute;
          top: 15px;
          left: 15px;
          right: 15px;
          bottom: 15px;
          border-radius: 50%;
          border: 3px solid transparent;
          border-top-color: #fff;
          -webkit-animation: spin 1s linear infinite;
          animation: spin 1s linear infinite;
      }
      @-webkit-keyframes spin {
          0%   {
              -webkit-transform: rotate(0deg);
              -ms-transform: rotate(0deg);
              transform: rotate(0deg);
          }
          100% {
              -webkit-transform: rotate(360deg);
              -ms-transform: rotate(360deg);
              transform: rotate(360deg);
          }
      }
      @keyframes spin {
          0%   {
              -webkit-transform: rotate(0deg);
              -ms-transform: rotate(0deg);
              transform: rotate(0deg);
          }
          100% {
              -webkit-transform: rotate(360deg);
              -ms-transform: rotate(360deg);
              transform: rotate(360deg);
          }
      }
      </style>


  </head>
  <body class="fastpayment">
    <div id="preloader">
    <div id="loader"></div>
  </div>
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->fetch('content'); ?>

        <?php
          echo $this->Html->script(array('/vendors/jquery/dist/jquery.min.js','/vendors/bootstrap/dist/js/bootstrap.bundle.min.js','/vendors/parsleyjs/dist/parsley.min.js','/vendors/parsleyjs/dist/i18n/es.js','bootstrap-notify.min.js','https://maps.googleapis.com/maps/api/js?key=AIzaSyA5kX4Hd4DKakL_l0kyKj_dASdKZ7Cah_M&libraries=places','general.js','/js/custom.js'));
        ?>

        <?php
          echo $this->fetch('AppScript');
        ?>
  </body>
</html>
