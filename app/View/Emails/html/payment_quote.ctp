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
    <title>PAGO DE CUOTAS DE CRÉDITOS</title>
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
              <td align="center">
                <a
                  href="https://creditos.somosziro.com/users/login"
                  target="_blank"
                >
                  <img
                    src="https://creditos.somosziro.com/img/email/mailPagoCredito/img/Header.png"
                    alt=""
                    width="600"
                    style="height: auto; display: block"
                  />
                </a>
              </td>
            </tr>
            <tr>
              <td align="center">
                <a
                  href="https://creditos.somosziro.com/users/login"
                  target="_blank"
                >
                  <img
                    src="https://creditos.somosziro.com/img/email/mailPagoCredito/img/Banner.png"
                    alt=""
                    width="600"
                    style="height: auto; display: block"
                  />
                </a>
              </td>
            </tr>
            <tr>
              <td>
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
                    <td style="color: #153643; width: 70px; height: 70px"></td>
                    <td style="color: #153643; height: 70px">
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
                          <td style="color: #153643; height: 70px">
                            <p
                              style="
                                margin: 0;
                                font-size: 16px;
                                line-height: 70px;
                                font-family: Arial, sans-serif;
                                text-align: center;
                                font-weight: 900;
                                color: #1e4752;
                              "
                            >
                              🎉 Yuhuuu! <?php echo $credit["Customer"]["name"]; ?>, recibimos tu
                              <span style="background-color: #c9fdc4">PAGO</span
                              > 🎉
                              <br />
                            </p>
                          </td>
                        </tr>
                        <tr>
                          <td style="color: #153643">
														<?php
															$totalData = 0;
															foreach ($quotes as $key => $value) {
																$totalData+=$value;
															}
														?>
                            <p
                              style="
                                margin: 0;
                                font-size: 16px;
                                line-height: 24px;
                                font-family: Arial, sans-serif;
                              "
                            >
                              ¡Genial! Hiciste un pago en <?php echo $shop_commerce["Shop"]["social_reason"]." - ". $shop_commerce["ShopCommerce"]["name"] ?> por un
                              valor de <br />
                              $<?php echo number_format($totalData) ?> y todo salió bien.
                            </p>
                            <p>
                              <strong> Estado de la Transacción: </strong>
                              <?php echo $credit["Credit"]["state"] == 1 ? "Pagado" : "En curso" ?>
                              <br />
                              <strong> Fecha de Transacción: </strong>
                              <?php echo $credit["Credit"]["created"] ?>

                              <br />
                              <strong> Estado de crédito: </strong> Activo
                              <br />
                              <strong> Empresa: </strong> <?php echo $shop_commerce["Shop"]["social_reason"]." - ". $shop_commerce["ShopCommerce"]["name"] ?> (a la
                              que se le hace el pago)
                              <br />
                              <strong> Valor de la Transacción: </strong> $<?php echo number_format($totalData) ?>
                              <br />
															<?php foreach ($quotes as $key => $value): ?>
																<strong> Número de cuota: </strong> <?php echo $key; ?><br>
															<?php endforeach ?>
                            </p>
                            <p
                              style="
                                margin: 0;
                                font-size: 16px;
                                line-height: 24px;
                                font-family: Arial, sans-serif;
                                text-align: center;
                              "
                            >
                              Recuerda seguirnos en nuestras redes sociales,
                              <br />
                              siempre tenemos cosas nuevas.
                            </p>
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
                              ¡Hasta pronto!
                              <br />
                            </p>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td style="color: #153643; width: 70px; height: 70px"></td>
                  </tr>
                </table>
              </td>
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
