<?php echo $this->element("/menu-landings"); ?>

<div class="container-fluid p-0 bg-img-blue">
	<div class="control-absolute">
		<div class="content-steps container">

			<div class="relative" style="width: 100%;">
				<div class="circle-content circle-content-1 active" style="left: 47%;">
					<h2 class="js-iconStep">
						<i class="fa fa-building-o" aria-hidden="true"></i>
					</h2>
				</div>
				<div class="steps step1 active">
					<p class="bl-tituloStep">Datos para Registro</p>
				</div>
			</div>

		</div>
	</div>

	<div class="container pb-5">
		<div class="row">
			<?php
				echo $this->Form->create('Customer', [
					'role' => 'form',
					'data-parsley-validate=""',
   				]);
			?>

			<!-- FOTO CÉDULA FRONTAL  -->
			<div class="col-md-12 bg-white pd-registerblock" id="step0Paso1" >
				<?php if (!isset($customer)): ?>

					<!--  -->
					<div class="row">
						<div class="col-12 mt-5">
							<p class="font-weight-bold mb-5">
								Recuerda tener a mano el código del proveedor
							</p>
						</div>
						<div class="col-md-12 bg-white ">
							<div class="row">
								<div class="col-12">
									<div class="content-tittles justify-content-center">
										<h1 class="text-center">FOTO CÉDULA FRONTAL </h1>
									</div>
								</div>
								<div class="col-12 text-center">
									<select id="select" style="display:none;">
									<option></option>
									</select>
									<div class="fotoSelfie">
										<div class="blContenedorFoto">

											<img src="https://creditos.somosziro.com/img/caraFrontal.jpg" id="imgUpFile" class="img-fluid d-block mx-auto">
											<canvas id="canvasFotoUp" style="display: none;" class="w-100"></canvas>

											<?php echo $this->Form->input('document_file_up2', [
												'class' => 'form-control',
												'label' => false,
												'div' => false,
												"required" => true,
												"type" => "hidden",
											]); ?>


											<div class="file-select d-xl-none" id="src-file1" >
												<input type="file"
														name="data[Customer][document_file_up2]"
														id="fotoFrontal"
														accept="image/*"
														capture="user">
											</div>
											<button type="button" class="btn btn-primary btn-lg mt-2 fotoBtn d-none d-xl-inline-block" data-canvas="canvasFotoUp" id="fotoUpFile" data-input="CustomerDocumentFileUp2" data-img="imgUpFile">Tomar foto</button>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
				<?php endif; ?>
			</div>

			<!-- FOTO REVERSO DE LA CÉDULA  -->
			<div class="col-md-12 bg-white pd-registerblock" id="step0Paso2" style="display:none">

				<div class="row">
					<div class="col-12 mt-5">
						<p class="font-weight-bold mb-5">
							Recuerda tener a mano el código del proveedor
						</p>
					</div>
					<div class="col-md-12 bg-white  mt-1">
							<div class="row">
								<div class="col-12">
									<div class="content-tittles justify-content-center">
										<h1 class="text-center">FOTO CÉDULA REVERSO </h1>
									</div>
								</div>
								<div class="col-12 text-center">
									<div class="fotoSelfie">
										<div class="blContenedorFoto">
											<img src="https://creditos.somosziro.com/img/caraTrasera.jpg" id="imgDownFile" class="img-fluid img-fluid d-block mx-auto">
											<canvas id="canvasFotoDown" style="display: none;" class="w-100"></canvas>

											<?php echo $this->Form->input('document_file_down2', [
												'class' => 'form-control',
												'label' => false,
												'div' => false,
												"required" => true,
												"type" => "hidden",
											]); ?>
											<button type="button" class="btn btn-primary btn-lg mt-2 fotoBtn d-none d-xl-inline-block " data-canvas="canvasFotoDown" id="fotoDownFile" data-input="CustomerDocumentFileDown2" data-img="imgDownFile">Tomar foto</button>
											<div class="file-select d-xl-none" id="src-file2" >
												<input type="file"
														name="data[Customer][document_file_down2]"
														id="fotoReverso"
														accept="image/*"
														capture="user">
											</div>

										</div>
									</div>
								</div>
							</div>
						</div>
				</div>
			</div>

			<!-- FOTO SELFIE -->
			<div class="col-md-12 bg-white pd-registerblock" id="step0Paso3" style="display:none">
				<div class="row">
					<div class="col-12 mt-5">
						<p class="font-weight-bold mb-5">
							Recuerda tener a mano el código del proveedor
						</p>
					</div>
					<div class="col-md-12 bg-white mt-1">
						<div class="row">
							<div class="col-12">
								<div class="content-tittles justify-content-center">
									<h1 class="text-center">FOTO SELFIE </h1>
								</div>
							</div>
							<div class="col-12 text-center">
								<div class="fotoSelfie">
									<div class="blContenedorFoto">
										<canvas id="canvasFotoUser" style="display: none;" class="w-100"></canvas>
										<img src="https://creditos.somosziro.com/img/caraSelfie.jpg" id="imgFotoUser" class="img-fluid img-fluid d-block mx-auto">
										<?php echo $this->Form->input('image_file2', [
											'class' => 'form-control',
											'label' => false,
											'div' => false,
											"required" => true,
											"type" => "hidden",
										]); ?>
										<button type="button" class="btn btn-primary btn-lg mt-2 fotoBtn d-none d-xl-block mx-auto" data-canvas="canvasFotoUser" id="fotoUserFile" data-input="CustomerImageFile2" data-img="imgFotoUser">Tomar foto</button>
										<div class="file-select d-xl-none" id="src-file3" >
											<input type="file"
													name="data[Customer][image_file2]"
													id="fotoSelfie"
													accept="image/*"
													capture="user">
										</div>
									</div>
								</div>
							</div>

							<div class="js-response2"></div>
							<div class="js-response3"></div>
						</div>
					</div>

				</div>
			</div>

			<div class="col-md-12 bg-white pd-registerblock" id="step1" style="display:none">
				<div class="row">
					<div class="col-12 mt-5">
						<p>
							Generar tu crédito con Zíro es muy fácil!
						</p>
						<p>
							Para empezar debes tener a la mano tu cédula física y un celular con cámara donde puedas recibir mensajes de texto.
							Ten en cuenta que la financiación se realizara únicamente a persona natural (si eres empresa la solicitud la debe realizar el Representante Legal o el dueño de la empresa).
							El correo que registres en nuestro sistema es donde recibirás toda la información de aprobación y seguimiento de tu crédito. Guarda muy bien la contraseña que crees para que puedas tener fácil acceso a tu cuenta de Zíro. Allí podrás revisar tu estado, fecha de pago y realizar de manera ágil tus pagos.
						</p>
						<p>
							Cuando hayas llenado la información, el equipo de Zíro te enviará un link para realizar tu verificación. Una vez se realicé tu validación te estaremos confirmado la aprobación de tu cupo.
						</p>

						<p>
							El equipo de Zíro estará apoyándote en cualquier inquietud que tengas en el proceso. ¿Qué esperas para comunicarte con nosotros a través de Whatsapp?
							Es muy fácil, ¿No?
						</p>
					</div>


					<div class="col-md-5 pt-3">
						<div class="content-tittles">
							<div class="line-tittles">|</div>
							<div>
								<h1>CÓDIGO DE </h1>
								<h2>TU PROVEEDOR
									<a class="popAyuda icon-ayuda"
										tabindex="0"
										role="button"
										data-toggle="popover"
										data-trigger="focus"
										title="CÓDIGO DE TU PROVEEDOR"
										data-content="Revisa con tu proveedor donde estas realizando la compra para que te suministren este código">
										<i class="fa fa-question-circle"></i>
									</a>
								</h2>

							</div>
						</div>
					</div>

					<div class="6Lc530QjAAAAAF2eRBC29bZbI2Vs2MmaGzyCz25v" data-sitekey="6Lc530QjAAAAAGcX2b3CCHSa_vKuPq24LeINoevi"></div>

					<div class="col-md-7 pt-3 js-required">
						<div class="form-group">
							<?php
								echo $this->Form->input('code', [
									'class' => 'form-control',
									'label' => '',
									'value' => $codigo,
									'required' => true,
								]);
							?>
						</div>
					</div>
				</div>
			</div>

			<?php if (!isset($customer["Customer"]["city_birth"])): ?>
				<div class="col-md-12 bg-white pd-registerblock" id="step2" style="display:none" >
					<div class="row">
						<div class="col-12 pt-3">
							<div class="content-tittles">
								<div class="line-tittles">|</div>
								<div>
									<h1>INFORMACIÓN</h1>
									<h2>PERSONAL</h2>
								</div>
							</div>
						</div>
						<div class="col-12 pt-3">
							<p>Diligencia tus datos como aparecen en tu cédula</p>
							<div class="row">
								<div class="col-md-6  js-required">
									<div class="form-group">
										<?php
											echo $this->Form->input('name', [
												'class' => 'form-control',
												'label' => 'Nombre',
												'div' => false,
												'placeholder' => 'Nombres como están en tu cédula',
												'required' => true,
											]);
										?>
									</div>
								</div>
								<div class="col-md-6  js-required">
									<div class="form-group ">
										<?php echo $this->Form->input('last_name', [
												'class' => 'form-control',
												'label' => 'Apellidos',
												'div' => false,
												'placeholder' => 'Apellidos como están en tu cédula',
												'required' => true,
											]);
										?>
									</div>
								</div>
								<div class="col-12">
									<p>Con esta información validamos tu identidad</p>
								</div>
								<div class="col-md-6">
									<div class="form-group  ">
										<?php
											echo $this->Form->input('identification_type', [
												'class' => 'form-control',
												'label' => 'Tipo de identificación',
												'div' => false,
												"options" => Configure::read("Identification_TYPE"),
												'required' => true,
											]);
										?>
									</div>
								</div>
								<div class="col-md-6 js-required">
									<div class="form-group ">
										<?php
											echo $this->Form->input('identification', [
												'class' => 'form-control',
												'label' => 'Tu número de identificación',
												'div' => false,
												'placeholder' => 'Escribe tu Número de identificación',
												'data-parsley-type' => "number",
												"data-parsley-minlength" => "5",
												'required' => true,
											]);
										?>
									</div>
								</div>
							</div>
						</div>
					</div>



				</div>
			<?php endif; ?>

			<div class="col-md-12 bg-white pd-registerblock" id="step3" style="display:none">
				<div class="row">
					<div class="col-12 pt-3">
						<div class="content-tittles">
							<div class="line-tittles">|</div>
							<div>
								<h1>DATOS DE</h1>
								<h2>CONTACTO</h2>
							</div>
						</div>
					</div>
					<div class="col-12 pt-3">
						<div class="row">
							<div class="col-md-6  js-required">
								<p>En este número de celular recibirás tus código de seguridad</p>
								<div class="form-group">
									<?php if (isset($customer) && !empty($customer["CustomersPhone"])): ?>
										<?php
											echo $this->Form->input('CustomersPhone.1.id', [
												'class' => 'form-control border-input',
												"value" => $customer["CustomersPhone"][0]["id"],
												"type" => "hidden",
											]);
										?>
									<?php endif; ?>

									<?php
										echo $this->Form->input('CustomersPhone.1.phone_number', [
											'class' => 'form-control border-input',
											'label' => 'Número de celular',
											'div' => false,
											"placeholder" => "Ingresa tu número de celular",
											"required",
											"value" => isset($customer) && !empty($customer["CustomersPhone"]) ? $customer["CustomersPhone"][0]["phone_number"] : "",
											"data-parsley-type" => "number",
										]);
									?>

									<?php
										echo $this->Form->input('CustomersPhone.1.phone_type', [
											'class' => 'form-control border-input',
											"value" => isset($customer) && !empty($customer["CustomersPhone"]) ? $customer["CustomersPhone"][0]["phone_type"] : "1",
											"type" => "hidden",
										]);
									?>
								</div>
							</div>

							<div class="col-md-6  js-required">
								<p>En este Email recibirás tus códigos de seguridad</p>
								<?php if (isset($customer)): ?>
									<?php
										echo $this->Form->input('id', [
											'class' => 'form-control',
											'label' => 'Tipo de identificación',
											'div' => false,
											"type" => "hidden",
										]);
									?>

									<div class="form-group">
										<?php
											echo $this->Form->input('email', [
												'class' => 'form-control',
												'label' => 'Tu Correo Electrónico',
												'div' => false,
												'placeholder' => 'Escribe tu correo Electrónico',
												'required' => true,
												"readonly",
											]);
										?>
									</div>
								<?php else: ?>
									<div class="form-group">
										<?php
											echo $this->Form->input('email', [
												'class' => 'form-control',
												'label' => 'Tu Correo Electrónico',
												'div' => false,
												'placeholder' => 'Escribe tu correo Electrónico',
												'required' => true,
											]);
										?>

										<!--<a href="<?php echo $this->Html->url(["controller" => "pages", "action" => "normal_request_unique"]); ?>" class="btn btn-info mt-2 p-0 px-1">-->
										<!--  No tengo correo eléctronico (X)</i>-->
										<!--</a>-->
									</div>
								<?php endif; ?>
							</div>

							<div class="col-12">
								<p>Con esta contraseña tendrás acceso a tu cuenta!</p>
							</div>

							<div class="col-md-6">
								<?php if (!isset($customer)): ?>

									<div class="form-group">
										<?php
											echo $this->Form->input('password', [
												'class' => 'form-control',
												'label' => 'Tu Contraseña',
												'div' => false,
												'placeholder' => 'Escribe tu contraseña',
												"data-parsley-type" => "alphanum",
												"data-parsley-minlength" => "4",
												"data-parsley-maxlength" => "4",
												'required' => true,
											]);
										?>
										<span>Digita una contraseña de 4 digitos</span>
									</div>

								<?php endif; ?>
							</div>

							<div class="col-md-6">
								<?php if (isset($customer) && !empty($customer["CustomersAddress"])): ?>
									<?php
										echo $this->Form->input('CustomersAddress.id', [
											'class' => 'form-control border-input',
											'label' => false,
											'div' => false,
											'placeholder' => "Dirección de residencia",
											"required",
											"value" => $customer["CustomersAddress"]["0"]["id"],
											"type" => "hidden",
										]);
									?>
								<?php endif; ?>

								<div class="form-group">
									<?php
										echo $this->Form->input('CustomersAddress.address', [
											'class' => 'form-control border-input',
											'label' => 'Tu dirección personal',
											'div' => false,
											'placeholder' => "Dirección de residencia",
											"required",
											"value" => isset($customer) && !empty($customer["CustomersAddress"]) ? $customer["CustomersAddress"]["0"]["address"] : "",
										]);
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php if (!isset($customer["Customer"]["city_birth"])): ?>

				<div class="col-md-12 bg-white pd-registerblock" id="step4" style="display:none">
					<div class="row">
						<div class="col-12 pt-3">
							<div class="content-tittles">
								<div class="line-tittles">|</div>
								<div>
									<h1>INFORMACIÓN DE</h1>
									<h2>TU NEGOCIO</h2>
								</div>
							</div>
						</div>
						<div class="col-12 pt-3">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('nit', [
												'class' => 'form-control',
												'label' => 'Cédula o NIT de tu negocio <a class="popAyuda icon-ayuda"
												tabindex="0"
												role="button"
												data-toggle="popover"
												data-trigger="focus"
												title="NIT"
												data-content="Debes ingresar el número de cédula o NIT al cual tu proveedor te realizará tu factura de compra ">
												<i class="fa fa-question-circle"></i>
												</a>',
												'div' => false,
												'placeholder' => 'Ingresa el número de cédula o NIT',
												'required' => true,
											]);
										?>
									</div>

									<div class="form-group">
										<?php
											echo $this->Form->input('buss_name', [
												'class' => 'form-control',
												'label' => 'Nombre de tu Negocio
												<a class="popAyuda icon-ayuda"
												tabindex="0"
												role="button"
												data-toggle="popover"
												data-trigger="focus"
												title="Nombre de tu Negocio"
												data-content="Debes ingresar la Razón Social de tu negocio al cual tu proveedor te realizará tu factura de compra ">
												<i class="fa fa-question-circle"></i>
												</a>',
												'div' => false,
												'placeholder' => 'Ingresa la razón social',
												'required' => true,
											]);
										?>
									</div>

									<div class="form-group">
										<?php
											echo $this->Form->input('CustomersAddress.address_city', [
												'class' => 'form-control border-input select2',
												'label' => 'Ingresa la Ciudad',
												'div' => false,
												'placeholder' => "Ciudad",
												"required",
												"value" => isset($customer) && !empty($customer["CustomersAddress"]) ? $customer["CustomersAddress"]["0"]["address_city"] : "",
												"options" => Configure::read("CIUDADES"),
												"default" => "MEDELLIN",
											]);
										?>
									</div>


									<div class="form-group">
											<?php
											echo $this->Form->input('user_id_commerce', [
												'class' => 'form-control',
												'label' => 'Digita el user_id de tu cuenta que aparece en el comercio que vas a comprar <a class="popAyuda icon-ayuda" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="user_id" data-content="El user_id es un número de cuenta que aparece en el perfil de tu cuenta del comercio en el que deseas comprar">
												<i class="fa fa-question-circle"></i>
											</a>',
												'div' => false,
												'placeholder' => 'user_id de tu cuenta',
												'data-parsley-type' => "number",
												"data-parsley-minlength" => "0",
											]);
										?>
									</div>



									<!-- <div class="form-group">
									<?php
										echo $this->Form->input('serv_name', [
											'class' => 'form-control',
											'label' => 'Página Web/Red Social',
											'div' => false,
											'placeholder' => 'Ingresa página web o red social',
											'required' => true,
										]);
									?>
									</div>

									<div class="form-group">
									<?php
										echo $this->Form->input('monthly_income', [
											'class' => 'form-control',
											'label' => 'Ingresos Mensuales',
											'div' => false,
											'placeholder' => '¿Cuáles son tus ventas mensuales?',
											'required' => true,
											"data-parsley-validate" => "number",
											"min" => 0,
											"max" => 1000000000,
										]);
									?>
									</div>

									<div class="form-group">
									<?php
										echo $this->Form->input('monthly_expenses', [
											'class' => 'form-control',
											'label' => 'Egresos Mensuales',
											'div' => false,
											'placeholder' => '¿Cuánto dinero gastas al mes?',
											'required' => true,
											"data-parsley-validate" => "number",
											"min" => 0,
											"max" => 1000000000,
										]);
									?>
									</div> -->


								</div>

								<div class="col-md-6">
									<div class="row">
										<div class="col-md-12">
										<div class="form-group">
										<?php
											echo $this->Form->input('cci', [
												'class' => 'form-control',
												'label' => '¿Estás registrado en la cámara de comercio?',
												'div' => false,
												"options" => Configure::read("CCI_TYPE"),
												'required' => true,
											]);
										?>
									</div>
											<div class="form-group">
												<label for="">¿Cuánto le compras a tu empresa aliada?
													<a class="popAyuda icon-ayuda" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="¡Este es el valor de las compras de inventario que esperas realizar mensualmente!">
														<i class="fa fa-question-circle"></i>
													</a>
												</label>
												<input type="text"
													class="form-control"
													value="0"
													id="numerDecimalRequest"
													onkeyup="formatoCosto(this)">

												<input type="hidden"
													class="form-control"
													value="0"
													id="priceValue"
													name="priceValue">
											</div>
											<div class="form-group">
												<?php
													echo $this->Form->input('CustomersAddress.address_street', [
														'class' => 'form-control border-input',
														'label' => 'Ingresa la dirección',
														'div' => false,
														'placeholder' => "Dirección",
														"required",
														"value" => isset($customer) && !empty($customer["CustomersAddress"]) ? $customer["CustomersAddress"]["0"]["address_street"] : "",
														]);
													?>

												<?php
													echo $this->Form->input('CustomersAddress.address_type', [
														'class' => 'form-control border-input',
														'label' => 'Ciudad',
														'div' => false,
														"required",
														"value" => 1,
														"type" => "hidden",
													]);
												?>
											</div>
										</div>
									</div>
								</div>


							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<div class="col-md-12 bg-white pd-registerblock" id="step5" style="display:none">
				<div class="row ">
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
							<?php
								echo $this->Form->input('politics', [
									'class' => 'form-check-input',
									'label' => false,
									'div' => false,
									"required" => true,
									"type" => "checkbox",
								]);
							?>
							<label class="form-check-label big-label pt-2" for="">
								Autorizo a Somos Zíro S.A.S a consultar mi información en centrales de riesgo y a enviarme información sobre su producto. También autorizo el tratamiento de mis datos personales, según la Política de Tratamiento de Datos Personales, la cual también puedes consultar en nuestra página web www.somosziro.com.
							</label>
						</div>
					</div>
				</div>

			</div>

			<div class="col-md-12 bg-white text-right pt-3 pb-5 pd-registerblock">


				<div class="pb-5">
					<!-- <a href="<?php echo $this->Html->url('/pages/validar-codigo-proveedor'); ?>" class="btn btn-outline-primary btn-lg mt-2">Volver</a> -->
					<div class="btn btn-outline-primary btn-lg mt-2 jsVolverAtras" onclick="funcionVolverAtras()" style="display:none">Atras</div>
					<div class="btn btn-outline-primary btn-lg mt-2 jsSiguiente" onclick="funcionSiguiente()" >Siguiente</div>
					<div class="mx-auto text-center jsRefreshPage" style="display:none">
						<ul>
							<li>* Activa la ubicación en la barra superior y presiona el botón recargar página</li>
						</ul>
						<div class=" btn btn-outline-primary btn-lg mt-2 " onclick="funcionRecargarPage()" >Recargar Pagina</div>
					</div>
					<input type="submit" class="btn btn-primary btn-lg mt-2" id="guardarFormulario" value="Enviar" style="display:none">
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
<div class="bl-linkWP">
	<a class="icono-wp" href="https://wa.me/+573209860583/?text=Hola%20Zíro,%20quiero%20saber%20un%20poco%20más!" target="_blank">
		<i class="fa fa-whatsapp" aria-hidden="true"></i>
	</a>
</div>

<div class="modal fade " id="panelPayments" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="">
					<div class="content-tittles">
						<div class="line-tittles">|</div>
						<div>
							<h1>PLAN</h1>
							<h2>DE PAGOS</h2>
						</div>
					</div>
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="planPaymentBody">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalInformacion" tabindex="-1" aria-labelledby="modalInformacionLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalInformacionLabel"><strong>Generar tu crédito con Zíro es muy fácil!</strong></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<p>
					Para empezar debes tener a la mano tu cédula física y un celular con cámara donde puedas recibir mensajes de texto.
					Ten en cuenta que la financiación se realizara únicamente a persona natural (si eres empresa la solicitud la debe realizar el Representante Legal o el dueño de la empresa).
					El correo que registres en nuestro sistema es donde recibirás toda la información de aprobación y seguimiento de tu crédito. Guarda muy bien la contraseña que crees para que puedas tener fácil acceso a tu cuenta de Zíro. Allí podrás revisar tu estado, fecha de pago y realizar de manera ágil tus pagos.
				</p>
				<p>
					Cuando hayas llenado la información, el equipo de Zíro te enviará un link para realizar tu verificación. Una vez se realicé tu validación te estaremos confirmado la aprobación de tu cupo.
				</p>

				<p>
					El equipo de Zíro estará apoyándote en cualquier inquietud que tengas en el proceso. ¿Qué esperas para comunicarte con nosotros a través de Whatsapp?
					Es muy fácil, ¿No?
				</p>
			</div>
			<div class="cho-container"></div>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary">Continuar</button>
			</div>
		</div>
	</div>
</div>


<!-- ocultos -->
<input value="<?php echo $this->Html->url(array('controller' => $this->request->controller, 'action' => 'validarCodigoProveedor')); ?>" id="rutaValidarCodigo" type="hidden">

<?php echo $this->element("take_photo"); ?>

<!-- css -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
	.btn-sm {
		height: 35px;
	}
</style>

<!-- javascript -->
<?php
	echo $this->Html->script("ctrl/customers/add.js?" . rand(),             array('block' => 'AppScript'));
	echo $this->Html->script("ctrl/customers/forms.js?" . rand(),             array('block' => 'AppScript'));
	echo $this->Html->script("https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js?" . rand(),             array('block' => 'AppScript'));

?>

<?php echo $this->element("/modals/tyc"); ?>
<?php echo $this->Html->css("https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"); ?>
<?php echo $this->Html->script("home.js?" . rand(),           array('block' => 'AppScript'));?>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
	var COMMERCE_CODE = "<?php echo Configure::read("CODE_COMMERCE") ?>";
	var data = <?php echo $data ?>;
	var opciones = "";
	var valor = 0;
	var initial = 0;
	var final = 0;

	const moneyRange = document.querySelector("#moneyRange");
	moneyRange.addEventListener('blur', (event) => {
		updateQuotes();
	});

	const solicitado = document.querySelector("#totalSolicita2");
	solicitado.addEventListener('onchage', (event) => {
		updateQuotes();
	});


	solicitado.addEventListener('blur', (event) => {
		updateQuotes();
	});

	const valueprice = document.querySelector("#valueNumberPrice");

	valueprice.addEventListener('onchage', (event) => {
		updateQuotes();
	});

	valueprice.addEventListener('blur', (event) => {
		updateQuotes();
	});

	function updateQuotes() {
		data = "";
		var data = <?php echo $data ?>;
		//document.getElementById('coutas-number').innerHTML="";
		opciones = "";
		document.getElementById('coutas-number').innerHTML = "";
		//coutas-number
		//document.getElementById("totalSolicita2").value;
		valor = Number(document.getElementById("totalSolicita2").value)
		// alert(typeof(valor));
		if (valor == "") {
			valor = <?php echo $valorMini ?>
		}

		initial = 0;
		final = 0;

		for (x of data) {
			//console.log(x.credits_lines_details.min_value);
			if ((valor >= Number(x.credits_lines_details.min_value)) && (valor <= Number(x.credits_lines_details.max_value))) {
				if (initial == 0) {
					initial = Number(x.credits_lines_details.month);
				} else if (initial > Number(x.credits_lines_details.month)) {
					initial = Number(x.credits_lines_details.month);
				}
			}
		}


		for (x of data) {
			//console.log(x.credits_lines_details.min_value);
			if ((valor >= Number(x.credits_lines_details.min_value)) && (valor <= Number(x.credits_lines_details.max_value))) {
				if (final == 0) {
					final = Number(x.credits_lines_details.month);
				} else if (final < Number(x.credits_lines_details.month)) {
					final = Number(x.credits_lines_details.month);
				}
			}
		}

		if (initial == 0 || final == 0) {
			console.log("raro");
		}

		for (var i = initial; i <= final; i++) {
			// console.log(i);
			opciones += "<option data-mes=" + i + " data-quince=" + i * 2 + " value=" + i + ">" + i + "</option>" + "\n";
		}

		document.getElementById('coutas-number').innerHTML = opciones
		$("#coutas-number").val(initial)
	}

	document.onload = updateQuotes();
</script>


<?php $this->start("AppScript") ?>
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			// Activa el funcionamiento de los poppover
			$('.popAyuda').popover('enable')
			//select 2
			$('.select2').select2({
				width: '100%'
			});
			if(navigator.geolocation){
				var success = function(position){
				var latitud = position.coords.latitude,
						longitud = position.coords.longitude;
				}
				navigator.geolocation.getCurrentPosition(success, function(msg){
					console.error( msg );
					if(msg.message === 'User denied Geolocation'){
						Swal.fire({
							icon: "error",
							title: "Los servicios de ubicación no estan habilitados",
							text: 'Para obtener la mejor experiencia de verificación posible, debes habilitar el seguimiento de ubicación para este sitio web.',
						});
						$('.jsSiguiente').css('display','none');
						$('.jsRefreshPage').css('display','block');
					} else{
						$('.jsRefreshPage').css('display','none');
						$('.jsSiguiente').css('display','inline-block');
					}
				});
			}

			previsualizarDocumentoFrontal()
			previsualizarDocumentoReverso()
			previsualizarDocumentoFotoSelfie()

		});


		function funcionRecargarPage(){
			location.reload()
		}


		/**
		 * @param String name
		 * @return String
		 */
		function getParameterByName(name) {
			name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
			var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
				results = regex.exec(location.search);
			return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}
		function previsualizarDocumentoFrontal(){

			const $seleccionArchivoFotoFrontal = document.querySelector("#fotoFrontal"),
			$imagenPrevisualFrontal = document.querySelector("#imgUpFile");

			// Escuchar cuando cambie
			$seleccionArchivoFotoFrontal.addEventListener("change", () => {
				// Los archivos seleccionados, pueden ser muchos o uno
				const archivos = $seleccionArchivoFotoFrontal.files;
				// Si no hay archivos salimos de la función y quitamos la imagen
				if (!archivos || !archivos.length) {
					$imagenPrevisualizacion.src = "";
					return;
				}
				// Ahora tomamos el primer archivo, el cual vamos a previsualizar
				const primerArchivo = archivos[0];
				// Lo convertimos a un objeto de tipo objectURL
				const objectURL = URL.createObjectURL(primerArchivo);
				// Y a la fuente de la imagen le ponemos el objectURL
				$imagenPrevisualFrontal.src = objectURL;
			});
		}
		function previsualizarDocumentoReverso(){
			const $seleccionArchivoFotoFrontal = document.querySelector("#fotoReverso"),
			$imagenPrevisualFrontal = document.querySelector("#imgDownFile");

			// Escuchar cuando cambie
			$seleccionArchivoFotoFrontal.addEventListener("change", () => {
				// Los archivos seleccionados, pueden ser muchos o uno
				const archivos = $seleccionArchivoFotoFrontal.files;
				// Si no hay archivos salimos de la función y quitamos la imagen
				if (!archivos || !archivos.length) {
					$imagenPrevisualizacion.src = "";
					return;
				}
				// Ahora tomamos el primer archivo, el cual vamos a previsualizar
				const primerArchivo = archivos[0];
				// Lo convertimos a un objeto de tipo objectURL
				const objectURL = URL.createObjectURL(primerArchivo);
				// Y a la fuente de la imagen le ponemos el objectURL
				$imagenPrevisualFrontal.src = objectURL;
			});
		}
		function previsualizarDocumentoFotoSelfie(){
			const $imgFotoUser = document.querySelector("#fotoSelfie"),
			$imagenPrevisualFrontal = document.querySelector("#imgFotoUser");

			// Escuchar cuando cambie
			$imgFotoUser.addEventListener("change", () => {
				// Los archivos seleccionados, pueden ser muchos o uno
				const archivos = $imgFotoUser.files;
				// Si no hay archivos salimos de la función y quitamos la imagen
				if (!archivos || !archivos.length) {
					$imagenPrevisualizacion.src = "";
					return;
				}
				// Ahora tomamos el primer archivo, el cual vamos a previsualizar
				const primerArchivo = archivos[0];
				// Lo convertimos a un objeto de tipo objectURL
				const objectURL = URL.createObjectURL(primerArchivo);
				// Y a la fuente de la imagen le ponemos el objectURL
				$imagenPrevisualFrontal.src = objectURL;
			});
		}
		function formatoCosto(el) {
			el.value = el.value.replace(/[^0-9]/g,'');
			valorIngresado=el.value.replace(/,/g, "");
			var v = el.value;
			v = v.replace(/[^0-9]/g,"");
			$(el).val(v);
			var rgx = /(\d+)(\d{3})/;
			while (rgx.test(v)) { v = v.replace(rgx, '$1,$2'); }
			$(el).val(v);

			$('#priceValue').val(valorIngresado);
		}
	</script>
	<script src="https://www.google.com/recaptcha/api.js?render=6LdXokojAAAAAJr-iUDvQ1NZQSWnwMcgFHGDJBzd"></script>
<?php $this->end() ?>
