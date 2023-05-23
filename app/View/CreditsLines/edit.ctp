<div class="page-title">
	<div class="row">
		<div class="col-md-6">
			<h3><?php echo __('Líneas de credito'); ?></h3>
		</div>
		<div class="col-md-6 text-right">
			<a class="btn btn-secondary"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
		        <i class="fa fa-list-alt"></i>
		        <?php echo __('Ver líneas de crédito'); ?>
		    </a>
		    <a class="btn btn-success"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
		        <i class="fa fa-plus-circle"></i>
		        <?php echo __('Crear otra línea'); ?>
		    </a>
		    <a class="btn btn-info" href="<?php echo $this->Html->url(array('action'=>'view',$this->Utilidades->encrypt($creditsLine['CreditsLine']['id'])));?>">
		        <i class="fa fa-eye"></i>
		        <?php echo __('Ver detalle'); ?>
		    </a>
		</div>
	</div>
</div>


<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 ">
		<div class="x_panel">
			<div class="x_content">
				<br />
				
				<div class="form-group">
        			<label for="validate-text">Nombre de linea</label>
					<div class="input-group">
						<!--<input type="text" class="form-control" name="name" id="name" placeholder="Ingrese nombre de linea" value=<?php echo $creditsLine['CreditsLine']['name'] ?>  >
						<input type="hidden" class="form-control" name="id" id="id" value=<?php echo $this->Utilidades->encrypt($creditsLine['CreditsLine']['id']) ?> >
-->
						<?php echo $this->Form->input('name', array('placeholder'=>__('Ingrese nombre de linea'),'name'=>'name','id'=>'name', 'class'=>'form-control','label'=>false,'div'=>false,'value'=> h($creditsLine['CreditsLine']['name']) )) ?>
						<?php echo $this->Form->input('id', array('name'=>'id','id'=>'id', 'class'=>'form-control','label'=>false,'div'=>false, 'hidden' => true,'value'=> $this->Utilidades->encrypt($creditsLine['CreditsLine']['id']) )) ?>

					</div>
				</div>
				<div class="form-group">
        			<label for="validate-text">Descripcion</label>
					<div class="input-group">
						<?php echo $this->Form->input('description', array('placeholder'=>__('Ingrese una descripcion'),'name'=>'description','id'=>'description', 'class'=>'form-control','label'=>false,'div'=>false,'value'=> h($creditsLine['CreditsLine']['description']) )) ?>
					</div>
				</div>
				<div class="form-group">
        			<label for="validate-text">Estado</label>
					<div class="input-group">
						<?php echo $creditsLine['CreditsLine']['state'] == 1 ? __('Activo') : __('Inactivo') ;?> 
						<?php echo $this->Form->input('state', array('name'=>'state','id'=>'state', 'class'=>'form-control','label'=>false,'div'=>false,'hidden' => true,'value'=> $creditsLine['CreditsLine']['state'] )) ?>
					</div>
				</div>
				<br />				
				<table>
					<thead>
						<tr>
							
							<th>
								<label for="validate-text">monto minimo</label>
								<div class="input-group">
									<input type="number" class="form-control" name="min_value" id="min_value" >
									<!--<span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>-->
								</div>
							</th>
							<th>
								<label for="validate-text">monto maximo</label>
								<div class="input-group">
									<input type="number" class="form-control" name="max_value" id="max_value" >
									<!--<span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>-->
								</div>
							</th>
							<th>
								<label for="validate-text">Tiempo (Mes)</label>
								<div class="input-group">
									<input type="number" class="form-control" name="min_month" id="month" >
									<!--<span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>-->
								</div>
							</th>
							<th>
								<label for="validate-text">Tasa de interes</label>
								<div class="input-group">
									<input type="number" class="form-control" name="interest_rate" id="interest_rate" >
									<!--<span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>-->
								</div>
							</th>
							<th>
								<label for="validate-text">Otros cargos</label>
								<div class="input-group">
									<input type="number" class="form-control" name="others_rate" id="others_rate" >
									<!--<span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>-->
								</div>
							</th>
							<th>
								<label for="validate-text">Tasa de Mora</label>
								<div class="input-group">
									<input type="number" class="form-control" name="debt_rate" id="debt_rate" >
									<!--<span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>-->
								</div>
							</th>
							<th>
								<!--<a class="btn btn-primary btn-mas pull-right"><b>+</b> Add detalle</a>-->
								<button class="btn btn-primary btn-mas pull-right"><b>+</b> Add detalle</button>
							</th>
						</tr>
					</thead>
				</table>

				<div class="row col-md-12 col-md-offset-2 custyle">
				<table class="table table-striped custab" id="table1">
				<thead>
					<tr>
						<th class="text-center">monto minimo</th>
						<th class="text-center">monto maximo</th>
						<th style='display:none;'>Count</th>
						<th >Tiempo (Mes)</th>
						<th style='display:none;'>Tiempo minimo</th>
						<th style='display:none;'>Tiempo maximo</th>
						<th >Tasa de interes</th>
						<th >Otros cargos</th>
						<th >Tasa de Mora</th>
						<th >Action</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($creditLineDetail)): ?>
						<!--<td class="text-center">
							No hay registro
						</td>-->
					<?php else:
							?>
						<?php foreach ($creditLineDetail as $key => $value): ?>
							<tr>
								<?php if ($value["credits_lines_details"]["count"] == '1'): ?>
									<td class="text-center" >
										<?php echo $value["credits_lines_details"]["min_value"] ?>
									</td>
									<td class="text-center">
										<?php echo $value["credits_lines_details"]["max_value"] ?>
									</td>
								<?php else:?>
									<?php if ($key%2 == 0): ?>
										<td class="text-center" style='border-top: 1px solid #ffffff; color:#F9F9F9;' >
											<?php echo $value["credits_lines_details"]["min_value"] ?>
										</td>
										<td class="text-center" style='border-top: 1px solid #ffffff; color:#F9F9F9;' >
											<?php echo $value["credits_lines_details"]["max_value"] ?>
										</td>
									<?php else:?>
										<td class="text-center" style='border-top: 1px solid #ffffff; color:#ffffff;' >
											<?php echo $value["credits_lines_details"]["min_value"] ?>
										</td>
										<td class="text-center" style='border-top: 1px solid #ffffff; color:#ffffff;' >
											<?php echo $value["credits_lines_details"]["max_value"] ?>
										</td>
									<?php endif ?>
								<?php endif ?>	
								<td class="text-center" style='display:none;'>
									<?php echo $value["credits_lines_details"]["count"] ?>
								</td>						
								<td >
									<input type="number" class="form-control" id=<?php echo "month3".$key ?>  style='width: 50%;' value=<?php echo $value["credits_lines_details"]["month"] ?> >
								</td>
								<td class="text-center" style='display:none;'>
									<?php echo $value["credits_lines_details"]["min_month"] ?>
								</td>
								<td class="text-center" style='display:none;'>
									<?php echo $value["credits_lines_details"]["max_month"] ?>
								</td>
								<td >
									<input type="number" class="form-control" id=<?php echo "interest_rate6".$key ?>  style='width: 50%;' value=<?php echo $value["credits_lines_details"]["interest_rate"]?> >
								</td>
								<td >
									<input type="number" class="form-control" id=<?php echo "others_rate7".$key ?> style='width: 50%;'  value=<?php echo $value["credits_lines_details"]["others_rate"]?> > 
								</td>
								<td >
									<input type="number" class="form-control"  id=<?php echo "debt_rate8".$key ?> style='width: 50%;'  value= <?php echo $value["credits_lines_details"]["debt_rate"]?> >
								</td>
								<td class='text-center'>
									<button class='btn btn-danger btn-xs borrar'>
										<span class='glyphicon glyphicon-remove'></span> Del
									</button>
								</td>
							</tr>
						<?php endforeach ?>
					<?php endif ?>																				
				</tbody>	
				</table>
				<div class="ln_solid"></div>
				<div class="item form-group">
					<div class="col-md-6 col-sm-6 offset-md-4">
						<button type="submit" class="btn btn-success guardar">Guardar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

$(document).on("click", ".btn-mas", function(){
	var month =  document.getElementById("month").value;
	var min_value =  document.getElementById("min_value").value;
	var max_value =  document.getElementById("max_value").value;
	var interest_rate =  document.getElementById("interest_rate").value;
	var others_rate =  document.getElementById("others_rate").value;
	var debt_rate =  document.getElementById("debt_rate").value;

	var fila="";
	var cont = 0;

	var resume_table = document.getElementById("table1");

	for (let index = 0; index < month; index++) {
		cont++;
		if (index == 0) {
			fila="<tr><td class='text-center' rowspan='1' style='color:#000000;'>" + min_value + "</td><td class='text-center' rowspan='1' style='color:#000000;'>" + max_value + "</td><td style='display:none;'>" + cont + "</td><td class='text-center' style='color:#000000;'><input type='number' class='form-control' id="+ 'month3'+(resume_table.rows.length-1) +"  style='width: 50%;' value=" + cont + "></td><td style='display:none;'>1</td><td style='display:none;'>" + month + "</td><td class='text-center' style='color:#000000;'> <input type='number' class='form-control' id="+ 'interest_rate6'+(resume_table.rows.length-1) +"  style='width: 50%;' value=" + interest_rate + "></td><td class='text-center' style='color:#000000;'> <input type='number' class='form-control' id="+ 'others_rate7'+(resume_table.rows.length-1) +"  style='width: 50%;' value=" + others_rate + "></td><td class='text-center' style='color:#000000;'> <input type='number' class='form-control' id="+ 'debt_rate8'+(resume_table.rows.length-1) +"  style='width: 50%;' value=" + debt_rate + "><td class='text-center'><button class='btn btn-danger btn-xs borrar'><span class='glyphicon glyphicon-remove'></span> Del</button></td> </tr>";
		} else {
			fila= "<tr><td class='text-center' rowspan='1' style='border-top: 1px solid #FFFFFF; color:#FFFFFF;'>" + min_value + "</td><td class='text-center' style='border-top: 1px solid #FFFFFF; color:#FFFFFF;'>" + max_value + "</td><td style='display:none;'>" + cont + "</td><td class='text-center' style='color:#000000;'><input type='number' class='form-control' id="+ 'month3'+(resume_table.rows.length-1) +"  style='width: 50%;' value=" + cont + "></td><td style='display:none;'>1</td><td style='display:none;'>" + month + "</td><td class='text-center' style='color:#000000;'>  <input type='number' class='form-control' id="+ 'interest_rate6'+(resume_table.rows.length-1) +"  style='width: 50%;' value=" + interest_rate + "></td><td class='text-center' style='color:#000000;'> <input type='number' class='form-control' id="+ 'others_rate7'+(resume_table.rows.length-1) +"  style='width: 50%;' value=" + others_rate + "></td><td class='text-center' style='color:#000000;'> <input type='number' class='form-control' id="+ 'debt_rate8'+(resume_table.rows.length-1) +"  style='width: 50%;' value=" + debt_rate + "><td class='text-center'><button class='btn btn-danger btn-xs borrar'><span class='glyphicon glyphicon-remove'></span> Del</button></td> </tr>";
		}

		var btn = document.createElement("TR");
		btn.innerHTML=fila;
		document.getElementById("table1").appendChild(btn);
	}
	
	document.getElementById("month").value = 0;
	document.getElementById("min_value").value = 0;
	document.getElementById("max_value").value = 0;
	document.getElementById("interest_rate").value = 0;
	document.getElementById("others_rate").value = 0;
	document.getElementById("debt_rate").value = 0;
		
});

$(document).on('click', '.borrar', function (event) {
	event.preventDefault();
	$(this).closest('tr').remove();
});

$(document).on("click", ".guardar", function(){
	$("#preloader").show();
	var name =  document.getElementById("name").value;
	var description =  document.getElementById("description").value;
	var id = document.getElementById("id").value;
	var state = document.getElementById("state").value;

	var resume_table = document.getElementById("table1");

	var arrayTotal = "";

	for (var i = 1, row; row = resume_table.rows[i]; i++) {
		var array = "{";
		for (var j = 0, col; col = row.cells[j]; j++) {

			switch(j) {
				case 0:
					array = array +'"min_value" :' + col.innerText + ",";
					break;
				case 1:
					array = array + '"max_value" :' + col.innerText + ",";
					break;
				case 2:
					array = array + '"count" :'+ col.innerText +",";
					break;
				case 3:
					array = array + '"month" :'+ document.getElementById("month3"+(i-1)).value  +",";
					break;		
				case 4:
					array = array + '"min_month" :' + col.innerText + ",";
					break;
				case 5:
					array = array + '"max_month" :'+ col.innerText +",";
					break;		
				case 6:
					array = array + '"interest_rate" :' + document.getElementById("interest_rate6"+(i-1)).value + ",";
					//array = array + '"interest_rate" :' + col.children[0].defaultValue + ",";
					break;
				case 7:
					array = array + '"others_rate" :' +  document.getElementById("others_rate7"+(i-1)).value + ",";
					break;
				case 8:
					array = array + '"debt_rate" :' +  document.getElementById("debt_rate8"+(i-1)).value + "}";
					break;
			}		
		}
		if (i == 1) {
			arrayTotal = '{' + '"0":' + array ;
		}else{
			var d = i-1;
			arrayTotal = arrayTotal  + "," + '"'+d+'":' + array ;
		}
	}
	arrayTotal = arrayTotal + "}";

	$.ajax({ 
		url: root+"credits_lines/edit/"+id,
		type: "post",
		data: {
			id: id,
			name: name,
			description: description,
			state: state,
			arrayTotal: arrayTotal,
		}
	}).done(function(respuesta){
		$("#preloader").hide();
		if (respuesta.status == "ok") {
			window.location= root + "credits_lines";
			showMessage("Datos Guardados Correctamente");
		}else{
			showMessage(respuesta.error);
		}
	});	
});

</script>