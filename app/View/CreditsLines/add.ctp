<div class="page-title">
	<div class="row">
		<div class="col-md-6">
			<h3><?php echo __('Creación de nueva línea de Crédito'); ?></h3>
		</div>
		<div class="col-md-6 text-right">
			<a class="btn btn-secondary"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
            	<i class="glyphicon glyphicon-th-list"></i>
            	<?php echo __('Ver líneas de crédito'); ?>
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
						<input type="text" class="form-control" name="name" id="name" placeholder="Ingrese nombre de linea" required>
					</div>
				</div>
				<div class="form-group">
        			<label for="validate-text">Descripcion</label>
					<div class="input-group">
						<input type="text" class="form-control" name="description" id="description" placeholder="Ingrese una descripcion" required>
						<!--<span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>-->
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
								<label for="validate-text">Cantidad de meses</label>
								<div class="input-group">
									<input type="number" class="form-control" name="month" id="month" >
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
						<th>monto minimo</th>
						<th>monto maximo</th>
						<th style='display:none;'>Count</th>
						<th>Tiempo (Mes)</th>
						<th style='display:none;'>Tiempo minimo</th>
						<th style='display:none;'>Tiempo maximo</th>
						<th>Tasa de interes</th>
						<th>Otros cargos</th>
						<th>Tasa de Mora</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
					<!--	<tr>
							<td id="">2</td>
							<td>1</td>
							<td>1000000</td>
							<td>1.0</td>
							<td>1.9</td>
							<td>2.0</td>
							<td>4.0</td>

							<td class="text-center"><a class='btn btn-info btn-xs' href="#"><span class="glyphicon glyphicon-edit"></span> Edit</a> <a href="#" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Del</a></td>
						</tr>
						<tr>
							<td>2</td>
							<td>1000001</td>
							<td>4000000</td>
							<td>2.0</td>
							<td>2.9</td>
							<td>4.0</td>
							<td>12.0</td>
							<td class="text-center"><a class='btn btn-info btn-xs' href="#"><span class="glyphicon glyphicon-edit"></span> Edit</a> <a href="#" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Del</a></td>
						</tr> 
					-->
				</table>

				<div class="item form-group">
					<div class="col-md-6 col-sm-6 offset-md-4">
						<button type="submit" class="btn btn-success guardar">Guardar</button>
					</div>
				</div>

				</div>
			</div>
		</div>
	</div>
</div>

<style>

	.custab{
		border: 1px solid #ccc;
		padding: 5px;
		margin: 5% 0;
		box-shadow: 3px 3px 2px #ccc;
		transition: 0.5s;
		}
	.custab:hover{
		box-shadow: 3px 3px 0px transparent;
		transition: 0.5s;
		}

		.input-group-addon.primary {
		color: rgb(255, 255, 255);
		background-color: rgb(50, 118, 177);
		border-color: rgb(40, 94, 142);
	}
	.input-group-addon.success {
		color: rgb(255, 255, 255);
		background-color: rgb(92, 184, 92);
		border-color: rgb(76, 174, 76);
	}
	.input-group-addon.info {
		color: rgb(255, 255, 255);
		background-color: rgb(57, 179, 215);
		border-color: rgb(38, 154, 188);
	}
	.input-group-addon.warning {
		color: rgb(255, 255, 255);
		background-color: rgb(240, 173, 78);
		border-color: rgb(238, 162, 54);
	}
	.input-group-addon.danger {
		color: rgb(255, 255, 255);
		background-color: rgb(217, 83, 79);
		border-color: rgb(212, 63, 58);
	}
</style>

<script type="text/javascript">

$(document).on("click", ".btn-mas", function(){
	//var min_month =  document.getElementById("min_month").value;
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
			fila="<tr><td rowspan='1'>" + min_value + "</td><td  rowspan='1'>" + max_value + "</td><td style='display:none;'>" + cont + "</td><td><input type='number' class='form-control' id="+ 'month3'+(resume_table.rows.length-1) +"  style='width: 50%;' value=" + cont + "></td><td style='display:none;'>1</td><td style='display:none;'>" + month + "</td><td><input type='number' class='form-control' id="+ 'interest_rate6'+(resume_table.rows.length-1) +"  style='width: 50%;' value=" + interest_rate + "></td><td class='text-center' style='color:#000000;'> <input type='number' class='form-control' id="+ 'others_rate7'+(resume_table.rows.length-1) +"  style='width: 50%;' value=" + others_rate + "></td><td class='text-center' style='color:#000000;'> <input type='number' class='form-control' id="+ 'debt_rate8'+(resume_table.rows.length-1) +"  style='width: 50%;' value=" + debt_rate + "><td class='text-center'><button class='btn btn-danger btn-xs borrar'><span class='glyphicon glyphicon-remove'></span> Del</button></td> </tr>";
		} else {
			fila= "<tr><td rowspan='1' style='border-top: 1px solid #ffffff; color:#ffffff;'>" + min_value + "</td><td style='border-top: 1px solid #ffffff; color:#ffffff;'>" + max_value + "</td><td style='display:none;'>" + cont + "</td><td><input type='number' class='form-control' id="+ 'month3'+(resume_table.rows.length-1) +"  style='width: 50%;' value=" + cont + "></td><td style='display:none;'>1</td><td style='display:none;'>" + month + "</td><td><input type='number' class='form-control' id="+ 'interest_rate6'+(resume_table.rows.length-1) +"  style='width: 50%;' value=" + interest_rate + "></td><td class='text-center' style='color:#000000;'> <input type='number' class='form-control' id="+ 'others_rate7'+(resume_table.rows.length-1) +"  style='width: 50%;' value=" + others_rate + "></td><td class='text-center' style='color:#000000;'> <input type='number' class='form-control' id="+ 'debt_rate8'+(resume_table.rows.length-1) +"  style='width: 50%;' value=" + debt_rate + "><td class='text-center'><button class='btn btn-danger btn-xs borrar'><span class='glyphicon glyphicon-remove'></span> Del</button></td> </tr>";
		}

		var btn = document.createElement("TR");
		btn.innerHTML=fila;
		document.getElementById("table1").appendChild(btn);
	}
	
	//document.getElementById("min_month").value = 0;
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
		url: root+"credits_lines/add",
		type: "post",
		data: {
			name: name,
			description: description,
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

/*$("body").on('submit', '.guardar', function(event) {

	$.post(root+"credits_lines/add", {name,description,array_detail}, function(data, textStatus, xhr) {
		//BTN_CLICK.trigger("click");
		//preloader(false)
		showMessage("Llamada realizada");
	});
});
*/

/*
$(document).ready(function() {
    $('.input-group input[required], .input-group textarea[required], .input-group select[required]').on('keyup change', function() {
		var $form = $(this).closest('form'),
            $group = $(this).closest('.input-group'),
			$addon = $group.find('.input-group-addon'),
			$icon = $addon.find('span'),
			state = false;

    	if (!$group.data('validate')) {
			state = $(this).val() ? true : false;
		}else if ($group.data('validate') == "email") {
			state = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test($(this).val())
		}else if($group.data('validate') == 'phone') {
			state = /^[(]{0,1}[0-9]{3}[)]{0,1}[-\s\.]{0,1}[0-9]{3}[-\s\.]{0,1}[0-9]{4}$/.test($(this).val())
		}else if ($group.data('validate') == "length") {
			state = $(this).val().length >= $group.data('length') ? true : false;
		}else if ($group.data('validate') == "number") {
			state = !isNaN(parseFloat($(this).val())) && isFinite($(this).val());
		}

		if (state) {
				$addon.removeClass('danger');
				$addon.addClass('success');
				$icon.attr('class', 'glyphicon glyphicon-ok');
		}else{
				$addon.removeClass('success');
				$addon.addClass('danger');
				$icon.attr('class', 'glyphicon glyphicon-remove');
		}

        if ($form.find('.input-group-addon.danger').length == 0) {
            $form.find('[type="submit"]').prop('disabled', false);
        }else{
            $form.find('[type="submit"]').prop('disabled', true);
        }
	});

    $('.input-group input[required], .input-group textarea[required], .input-group select[required]').trigger('change');


});
*/

</script>



