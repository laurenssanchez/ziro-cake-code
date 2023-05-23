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
    <title>Recuperar Contraseña</title>
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
									Hola, <?php echo $nombre; ?>
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
												<?php echo __('Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en ')?><strong><?php echo __('SOMOS ZÍRO') ?></strong><?php echo  __(', si no fuiste tú, por favor ignora este mensaje, de lo contrario haz clic en el siguiente enlace para continuar el proceso.') ?>
											</p>

											<p style="margin-top: 30px;">
												<a href="<?php echo Router::url("/",true).'users/remember_password_step_2/'.$hash ?>"
												style="text-decoration: none;width: 300px;height: 40px !important;display: block;margin: 0 auto;border-radius: 8px;background-color: #0DD0C7;color: #fff;line-height: 3.3; text-align: center;">
												<?php echo __("RESTABLECER CONTRASEÑA")?>
											</a>
										</p>
                      <p style="
                          margin: 0;
                          font-size: 16px;
                          font-family: Arial, sans-serif;
                          text-align: center;
                          color: #1e4752;
                        "
                      >
												Si tienes algún problema con este proceso de verificación puedes comunicarte con soporte a la línea  590 4603

                        <br /> <br />
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
