<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Nuevo usuario administrador</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta charset="utf-8" />
	</head>

	<body style="background-color:#f8f8f8;  background-color: #ffffff;
    background-image: url(https://credishop.co/img/back.jpg);
    background-position: top center;
    background-repeat: no-repeat;">
    <br> <br> <br> <br>
		<table align="center" style="width: 600px; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;background-color:#ffffff;">
			<tr>
				<td>
					<table style="width: 600px;  display:block; padding: 50px 130px 0px 140px; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
						<tr>
							<td>
                                <center>
								    <img src="https://creditos.somosziro.com/img/logo-ziro.png" height="100">
								</center>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
						<tr>
							<td style="width:50px;"></td>
							<td style="width:500px;">
								<br>
								<h2 style="text-align: center;"><b style="color:#d20a11; text-transform: capitalize;">
									Hola, <?php echo $name_user ?>
								</b></h2>
								<h4 style="text-align: center;">Has sido invitado a gestionar los créditos del proveedor <b> <?php echo $name ?> en la sucursal  <?php echo $commerce ?></b>
								<br>
								 Tus datos de acceso son:</h4>
								<div style="padding: 15px 30px; background: #f8f8f8; text-align: center;">
									<h2 style="text-align: center;"><b style="color:#d20a11; text-transform: uppercase;">
									  Usuario: <b><?php echo $email ?></b>
								      Contraseña: <b><?php echo $dni ?></b>
									</h2>
								</div>
								<p style="text-align: center;"><a href="<?php echo Router::url("/",true) ?>users/login">
									IR A SOMOS ZÍRO
								</a></p>

							</td>
							<td style="width:25px;"></td>
						</tr>
					</table>

					<br>
					<br>
					<br>
				</td>
			</tr>
		</table>
		<br> <br> <br> <br>
	</body>
</html>
