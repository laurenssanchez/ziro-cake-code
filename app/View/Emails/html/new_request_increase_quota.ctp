<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <meta name="x-apple-disable-message-reformatting" />
        <title></title>
        <style>
            table,
            td,
            div,
            h1,
            p {
                font-family: Arial, sans-serif;
            }
            #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            }

            #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
            }

            #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #BBFFBF;
            color: #004651;
            }
        </style>
    </head>
    <body style="margin: 0; padding: 0;">
        <table role="presentation" style="width: 100%; border-collapse: collapse; border: 0; border-spacing: 0; background: #ffffff;">
            <tr>
                <td align="center" style="padding: 0;">
                    <table role="presentation" style="width: 600px; border-collapse: collapse; border-spacing: 0; text-align: left;">
                        <tr>
                            <td align="center" style="padding: 0;">
                                <a href="https://creditos.somosziro.com/users/login" target="_blank">
                                    <img src="https://creditos.somosziro.com/img/email/mailNuevoCliente/Header.png" alt="" width="600" style="height: auto; display: block;" />
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td style="color: #153643; height: 70px; padding: 0;">
                                <p style="margin: 0; font-size: 15px; line-height: 70px; font-family: Arial, sans-serif; text-align: center; font-weight: 900; color: #1e4752;">
                                    Alerta, nueva solicitud de aumento de cupo
                                    <span style="background-color: #c9fdc4;">ZÍRO</span>
                                    <br />
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 0;">
                                <table role="presentation" style="width: 100%; border-collapse: collapse; border: 0; border-spacing: 0;">
                                    <tr>
                                        <td style="color: #153643; width: 60px; height: 70px;"></td>
                                        <td style="color: #153643; height: 70px;">
                                            <p style="margin: 0; font-size: 16px; line-height: 24px; font-family: Arial, sans-serif; text-align: center;">
											 	Un cliente ha realizado una solicitud de aumento de cupo, a continuación los datos del cliente:
                                            </p>

                                            <table style="margin-top: 2rem;" id="customers">
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Identificación</th>
                                                    <th>Email</th>
                                                    <th>Telefono</th>
                                                </tr>
                                                <tr>
												<td><?php echo $customerNombre ?></td>
                                                    <td><?php echo $customerIdentificacion ?></td>
                                                    <td><?php echo $customerEmail ?></td>
                                                    <td><?php echo $customerTelefono ?></td>
                                                </tr>
                                            </table>
                                            <br />
                                        </td>
                                        <td style="color: #153643; width: 60px; height: 70px;"></td>
                                    </tr>
                                </table>
                            </td>
                            <td style="color: #153643; width: 70px; height: 70px;"></td>
                        </tr>
                        <tr>
                            <td align="center" style="padding: 0;">
                                <a href="https://creditos.somosziro.com/users/login" target="_blank">
                                    <img src="https://creditos.somosziro.com/img/email/mailNuevoCliente/Footer_01.jpg" alt="" width="600" style="height: auto; display: block;" />
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="padding: 0; background: #144755;">
                                <p style="height: 60px; color: #fff; font-size: 12.2px; margin: 0;">
                                    <br />
                                    Somos Zíro, Av Santander 65 - 15 Local 115, Manizales, Colombia, (+57) 3209860583
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
