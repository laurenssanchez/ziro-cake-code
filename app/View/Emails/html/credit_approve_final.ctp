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
    <title>Desembolso de crédito</title>
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
                  🎉 Hola <?php echo $credit["Customer"]["name"]; ?> 🎉
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
												Queremos informarte que tu crédito en la tienda <b><?php echo $shop_commerce["Shop"]["social_reason"]." - ". $shop_commerce["ShopCommerce"]["name"] ?></b>, ha sido desembolsado y quedó con las siguientes condiciones
                      </p>
											<br>
											<table align="center" width="500" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #ccc;">
												<tr style="border:1px solid #ccc;">
													<th style="border:1px solid #ccc;">Número de obligación: </th>
													<td style="border:1px solid #ccc;"><?php echo h($credit["CreditsRequest"]["code_pay"]); ?></td>
												</tr>
												<tr style="border:1px solid #ccc;">
													<th style="border:1px solid #ccc;">Tipo de pago:</th>

													<td style="border:1px solid #ccc;">
														<?php
															if ($credit["Credit"]["type"] == 1)
																$tipoCredito= "Mensual";
															else if($credit["Credit"]["type"] == 3)
																$tipoCredito= "45 días";
															else if($credit["Credit"]["type"] == 4)
																$tipoCredito= "60 días";
															else
																$tipoCredito= "Quincenal";

															echo $tipoCredito;
														?>
														<!-- <?php echo $credit["Credit"]["type"] == "2" ? "Quincenal" : "Mensual" ?> -->
													</td>

												</tr>
												<tr style="border: 1px solid #ccc">
													<th style="border: 1px solid #ccc">Tasa de interés: </th>
													<td style="border: 1px solid #ccc">0 %</td>
												</tr>
												<tr style="border: 1px solid #ccc">
													<th style="border: 1px solid #ccc">Tasa de otros cargos:</th>
													<td style="border: 1px solid #ccc"><?php echo $credit["Credit"]["others_rate"] ?> %</td>
												</tr>
												<tr style="border: 1px solid #ccc">
													<th style="border: 1px solid #ccc">Tasa de mora: </th>
													<td style="border: 1px solid #ccc"><?php echo $credit["Credit"]["debt_rate"] ?> %</td>
												</tr>
												<tr style="border: 1px solid #ccc">
													<th style="border: 1px solid #ccc">Inicio del crédito: </th>
													<td style="border: 1px solid #ccc"><?php echo date("d-m-Y",strtotime($credit["Credit"]["created"])) ?></td>
												</tr>
												<tr style="border: 1px solid #ccc">
													<th style="border: 1px solid #ccc">Fin del crédito</th>
													<td style="border: 1px solid #ccc"><?php echo date("d-m-Y",strtotime($credit["Credit"]["deadline"])) ?></td>
												</tr>
												<tr style="border: 1px solid #ccc">
													<th style="border: 1px solid #ccc">Valor cuota</th>
													<td style="border: 1px solid #ccc">$ <?php echo number_format($credit["Credit"]["quota_value"]); ?></td>
												</tr>
											</table>
											<br>
											<br>
											<br>
											<h3>Plan de pagos</h3>
											<table align="center" width="500" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #ccc;">
												<thead>
													<tr style="border: 1px solid #ccc">
														<th style="border: 1px solid #ccc">Número</th>
														<th style="border: 1px solid #ccc">Capital</th>
														<th style="border: 1px solid #ccc">Otros cobros</th>
														<th style="border: 1px solid #ccc">Interés</th>
														<th style="border: 1px solid #ccc">Fecha límite</th>
														<th style="border: 1px solid #ccc">Deuda</th>
													</tr>
												</thead>
												<?php foreach ($credit["CreditsPlan"] as $key => $value): ?>
													<tr style="border: 1px solid #ccc">
														<td style="border: 1px solid #ccc"><?php echo $value["number"] ?></td>
														<td style="border: 1px solid #ccc">$ <?php echo number_format($value["capital_value"]); ?></td>
														<td style="border: 1px solid #ccc">$ <?php echo number_format($value["others_value"]); ?></td>
														<td style="border: 1px solid #ccc">$ <?php echo number_format($value["interest_value"]); ?></td>
														<td style="border: 1px solid #ccc"><?php echo date("d-m-Y",strtotime($value["deadline"])); ?></td>
														<td style="border: 1px solid #ccc">$ <?php echo number_format($value["value_pending"]); ?></td>
													</tr>
												<?php endforeach ?>
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
                        ¡Hasta pronto!
                        <br />
                      </p>


												<h4  style="
                          margin: 0;
                          font-size: 16px;
                          line-height: 24px;
                          font-family: Arial, sans-serif;
                          text-align: center;
                        ">
													<a href="https://somosziro.com/politica-de-privacidad/" target="_blank">Políticas de Uso de Información</a>
												</h4>
												<h4  style="
                          margin: 0;
                          font-size: 16px;
                          line-height: 24px;
                          font-family: Arial, sans-serif;
                          text-align: center;
                        ">
													<a href="https://somosziro.com/terminos-y-condiciones/"  target="_blank">Términos y Condiciones</a>
												</h4>
												<br />
												<br />

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
