<?php if (isset($varsSelected)): ?>
  

  <?php 

    $classColor = "";
    if($total >= 4.1){
      $classColor = "bg-green";
    }elseif ($total >= 3 && $total <= 4.09) {
      $classColor = "bg-warning";
    }else{
      $classColor = "bg-red";
    }

  ?>
  <h2 class="mt-4 d-block percetage p-2 text-center <?php echo $classColor ?>"> 
    Calificación <b> <?php echo $total ?> </b>
  </h2>
  <hr>

  <div class="form-inline content-parameters mb-5">
    <?php foreach ($varsSelected as $key => $value): ?>
      <div class="col-md-12">
        <div class="row">    
          <div class="form-group col-2 mb-2">
            <p><?php echo str_replace("_", " ", $key) ?></p>
          </div>
          <?php foreach ($value as $keyValue => $valueVar): ?>
            <div class="form-group col-10 mb-2">
              <input type="text" class="form-control" value="<?php echo $valueVar ?>" readonly>
            </div>
           <!--  <div class="form-group col-2"><button class="btn btn-secondary mb-2 btn-block"><?php echo $valueVar ?></button></div> -->
          <?php endforeach ?>
          
        </div>
      </div>
    <?php endforeach ?>
  </div>

  <textarea name="Tapt" id="Tapt" cols="30" rows="10" style="display: none"><?php echo $this->Utilidades->encrypt(json_encode($varsSelected)) ?></textarea>
        <input type="hidden" id="Rdnb" value="<?php echo $this->Utilidades->encrypt($request["CreditsRequest"]["id"]); ?>">
        <input type="hidden" name="Dtapt" id="Dtapt" value="<?php echo $this->Utilidades->encrypt($total); ?>">
<?php else: ?>
  <hr>
  <h3 class="text-center mt-4">
    Error al consultar en datacrédito
  </h3>
<?php endif ?>

 <?php if (!empty($request["CreditsRequest"]["response_score"])): ?>    

    <h3 class="text-center">
      Huella de consulta
    </h3>  


    <div class="table-responsive">
      <?php $data_score = json_decode( $request["CreditsRequest"]["response_score"]); ?> 
      <?php if (isset($data_score->consultaresumenscore->huella) && !empty($data_score->consultaresumenscore->huella)): ?>
        <?php foreach ($data_score->consultaresumenscore->huella as $key => $value): ?>
          <table class="table table-bordered mb-2 mt-2">
              <tr>
                <th>
                  ID Cliente
                </th>
                <td>
                  <?php echo $value->ID_CLIENTE ?>
                </td>
              </tr>
              <tr>
                <th>
                  Nombre Sucursal
                </th>
                <td>
                  <?php echo $value->NOMBRE_COMERCIAL ?>
                </td>
              </tr>
              <tr>
                <th>
                  Sucursal
                </th>
                <td>
                  <?php echo $value->SUCURSAL ?>
                </td>
              </tr>
              <tr>
                <th>
                  Fecha
                </th>
                <td>
                  <?php echo $value->FECHA ?>
                </td>
              </tr>
              <tr>
                <th>
                  Hora
                </th>
                <td>
                  <?php echo $value->HORA ?>
                </td>
              </tr>
          </table>
        <?php endforeach ?>
      <?php else: ?>
        <h2 class="text-info">
          No se han registrado consultas.
        </h2>
      <?php endif ?>      
    </div>


<?php endif ?>