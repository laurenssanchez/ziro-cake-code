<!DOCTYPE html>
<html lang="es">
	<head>
		<title>solicitud de pago a tienda</title>
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
					<h1>
						Hola, <br>
						La tienda <?php echo $name ?>, ha solicitado un pago por: <?php echo number_format($total,2,".",","); ?>
					</h1>

					<br>
					<br>
					<br>
				</td>
			</tr>
		</table>
		<br> <br> <br> <br>
	</body>
</html>

