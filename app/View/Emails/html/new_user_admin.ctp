<!DOCTYPE html>
<html
  lang="en"
  xmlns="http://www.w3.org/1999/xhtml"
  xmlns:o="urn:schemas-microsoft-com:office:office"
>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="x-apple-disable-message-reformatting" />
    <title>Nuevo usuario administrador</title>
    <!--[if mso]>
      <noscript>
        <xml>
          <o:OfficeDocumentSettings>
            <o:PixelsPerInch>96</o:PixelsPerInch>
          </o:OfficeDocumentSettings>
        </xml>
      </noscript>
    <![endif]-->
    <style>
      table,
      td,
      div,
      h1,
      p {
        font-family: Arial, sans-serif;
      }
    </style>
  </head>
  <body style="margin: 0; padding: 0">
    <table
      role="presentation"
      style="
        width: 100%;
        border-collapse: collapse;
        border: 0;
        border-spacing: 0;
        background: #ffffff;
      "
    >
      <tr>
        <td align="center" style="padding: 0">
          <table
            role="presentation"
            style="
              width: 600px;
              border-collapse: collapse;
              border-spacing: 0;
              text-align: left;
            "
          >
            <tr>
              <td align="center" style="padding: 0">
                <a
                  href="https://creditos.somosziro.com/users/login"
                  target="_blank"
                >
                  <img
                    src="https://creditos.somosziro.com/img/email/mailBienvenida/img/Header.png"
                    alt=""
                    width="600"
                    style="height: auto; display: block"
                  />
                </a>
              </td>
            </tr>
            <tr>
              <td align="center" style="padding: 0">
                <a
                  href="https://creditos.somosziro.com/users/login"
                  target="_blank"
                >
                  <img
                    src="https://creditos.somosziro.com/img/email/mailBienvenida/img/Banner.png"
                    alt=""
                    width="600"
                    style="height: auto; display: block"
                  />
                </a>
              </td>
            </tr>
            <tr>
              <td style="color: #153643; height: 70px; padding: 0">
                <p
                  style="
                    margin: 0;
                    font-size: 15px;
                    line-height: 70px;
                    font-family: Arial, sans-serif;
                    text-align: center;
                    font-weight: 900;
                    color: #1e4752;
                  "
                >
                  🎉 Hola <?php echo $name_user ?> 🎉
                  <br />
                </p>
              </td>
            </tr>
            <tr>
              <td style="padding: 0">
                <table
                  role="presentation"
                  style="
                    width: 100%;
                    border-collapse: collapse;
                    border: 0;
                    border-spacing: 0;
                  "
                >
                  <tr>
                    <td style="color: #153643; width: 60px; height: 70px"></td>
                    <td style="color: #153643; height: 70px">
                      <p
                        style="
                          margin: 0;
                          font-size: 16px;
                          line-height: 24px;
                          font-family: Arial, sans-serif;
                          text-align: center;
                        "
                      >
												Felicidades, el proveedor <b> <?php echo $name ?> </b> ha sido registrado correctamente en SOMOS ZÍRO
                      </p>
											<br>
											<?php if (!isset($empresa)): ?>
												<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #ccc;">
													<tr style="border:1px solid #ccc;">
														<th style="border:1px solid #ccc;">Plan de pago: </th>
														<td style="border:1px solid #ccc;"><?php echo $plan ?></td>
													</tr>
													<tr style="border:1px solid #ccc;">
														<th style="border:1px solid #ccc;">Sucursales inscritas:</th>
														<td style="border:1px solid #ccc;">
															<?php echo $commerces ?>
														</td>
													</tr>
													<tr style="border: 1px solid #ccc">
														<th style="border: 1px solid #ccc">total a pagar: </th>
														<td style="border: 1px solid #ccc">$ <?php echo number_format($total); ?></td>
													</tr>
												</table>
												<br>
												<p
													style="
														margin: 0;
														font-size: 16px;
														line-height: 24px;
														font-family: Arial, sans-serif;
														text-align: center;
													"
												>
													Recuerda que primero debes realizar el pago total de $ <?php echo number_format($total); ?> y enviar el soporte a SOMOS ZÍRO para activar tu cuenta, luego podrás ingresar con los siguientes datos.
												</p>
											<?php endif ?>
											<br>
											<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #ccc;">
												<tr style="border:1px solid #ccc;">
													<th style="border:1px solid #ccc;">Usuario: </th>
													<td style="border:1px solid #ccc;"><?php echo $email ?></td>
												</tr>
												<tr style="border:1px solid #ccc;">
													<th style="border:1px solid #ccc;">Contraseña:</th>
													<td style="border:1px solid #ccc;">
														<?php echo $dni ?>
													</td>
												</tr>
											</table>
                      <p
                        style="
                          margin: 0;
                          font-size: 16px;
                          font-family: Arial, sans-serif;
                          text-align: center;
                          line-height: 60px;
                          font-weight: 900;
                          color: #1e4752;
                        "
                      >
												<a href="<?php echo Router::url("/",true) ?>users/login">
													IR A SOMOS ZÍRO
												</a>
                        <br />
                      </p>
                    </td>
                    <td style="color: #153643; width: 60px; height: 70px"></td>
                  </tr>
                </table>
              </td>
              <td style="color: #153643; width: 70px; height: 70px"></td>
            </tr>
            <tr>
              <td align="center" style="padding: 0">
                <a
                  href="https://creditos.somosziro.com/users/login"
                  target="_blank"
                >
                  <img
                    src="https://creditos.somosziro.com/img/email/mailBienvenida/img/Footer_01.jpg"
                    alt=""
                    width="600"
                    style="height: auto; display: block"
                  />
                </a>
              </td>
            </tr>
            <tr>
              <td style="padding: 0">
                <table
                  role="presentation"
                  style="
                    width: 600px;
                    border-collapse: collapse;
                    border: 0;
                    border-spacing: 0;
                    font-family: Arial, sans-serif;
                  "
                >
                  <tr>
                    <td align="center" style="padding: 0">
                      <a
                        href="https://creditos.somosziro.com/users/login"
                        target="_blank"
                      >
                        <img
                          src="https://creditos.somosziro.com/img/email/mailBienvenida/img/Footer_02.jpg"
                          alt=""
                          width="250"
                          height="38"
                          style="height: auto; display: block"
                        />
                      </a>
                    </td>
                    <td align="center" style="padding: 0">
                      <a
                        href="https://www.linkedin.com/company/somosziro/?viewAsMember=true"
                        target="_blank"
                      >
                        <img
                          src="https://creditos.somosziro.com/img/email/mailBienvenida/img/Footer_03.jpg"
                          alt=""
                          width="28"
                          height="38"
                          style="height: auto; display: block"
                        />
                      </a>
                    </td>
                    <td align="center" style="padding: 0">
                      <a
                        href="https://www.instagram.com/somosziro/"
                        target="_blank"
                      >
                        <img
                          src="https://creditos.somosziro.com/img/email/mailBienvenida/img/Footer_04.jpg"
                          alt=""
                          width="23"
                          height="38"
                          style="height: auto; display: block"
                        />
                      </a>
                    </td>
                    <td align="center" style="padding: 0">
                      <a
                        href="https://www.facebook.com/somosziro"
                        target="_blank"
                      >
                        <img
                          src="https://creditos.somosziro.com/img/email/mailBienvenida/img/Footer_05.jpg"
                          alt=""
                          width="22"
                          height="38"
                          style="height: auto; display: block"
                        />
                      </a>
                    </td>
                    <td align="center" style="padding: 0">
                      <a
                        href="https://www.youtube.com/channel/UC25K7GS2swbkmf4gVTdmNkg"
                        target="_blank"
                      >
                        <img
                          src="https://creditos.somosziro.com/img/email/mailBienvenida/img/Footer_06.jpg"
                          alt=""
                          width="25"
                          height="38"
                          style="height: auto; display: block"
                        />
                      </a>
                    </td>
                    <td align="center" style="padding: 0">
                      <a
                        href="https://creditos.somosziro.com/users/login"
                        target="_blank"
                      >
                        <img
                          src="https://creditos.somosziro.com/img/email/mailBienvenida/img/Footer_07.jpg"
                          alt=""
                          width="252"
                          height="38"
                          style="height: auto; display: block"
                        />
                      </a>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td align="center" style="padding: 0; background: #144755">
                <p
                  style="
                    height: 60px;
                    color: #fff;
                    font-size: 12.2px;
                    margin: 0;
                  "
                >
                  <br />
                  Somos Zíro, Av Santander 65 - 15 Local 115, Manizales,
                  Colombia, (+57) 3209860583
                </p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
