<!DOCTYPE html>
            <html lang="es">

            <head>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link href="https://creditos.somosziro.com/img/ico-ziro.jpg" type="image/x-icon" rel="icon"/><link href="https://creditos.somosziro.com/img/ico-ziro.jpg" type="image/x-icon" rel="shortcut icon"/>
                <title>Pagos ZÍRO</title>
                <!-- Bootstrap -->
                <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
                <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
                <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
                <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
                <!--[if lt IE 9]>
                      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
                    <![endif]-->
            </head>
            <style>
                .p-5{
                    padding: 30px 30px 0 30px;
                }
                .pb-5{
                    padding: 0px 30px 30px 30px;
                }
                .bg-white{
                    background-color: white;
                }
                @media (min-width: 1200px){
                    .container {
                        width: 870px;
                    }
                }
				.text__3unNc:before {
					content: "";
					width: 1em;
					height: 1em;
					margin-right: 0.3em;
					margin-top: -0.3em;
					background-repeat: no-repeat;
					background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 229.5 229.5'%3E%3Cpath fill='%238091a5' d='M214.419 32.12A7.502 7.502 0 0 0 209 25.927L116.76.275a7.496 7.496 0 0 0-4.02 0L20.5 25.927a7.5 7.5 0 0 0-5.419 6.193c-.535 3.847-12.74 94.743 18.565 139.961 31.268 45.164 77.395 56.738 79.343 57.209a7.484 7.484 0 0 0 3.522 0c1.949-.471 48.076-12.045 79.343-57.209 31.305-45.217 19.1-136.113 18.565-139.961zm-40.186 53.066l-62.917 62.917c-1.464 1.464-3.384 2.197-5.303 2.197s-3.839-.732-5.303-2.197l-38.901-38.901a7.497 7.497 0 0 1 0-10.606l7.724-7.724a7.5 7.5 0 0 1 10.606 0l25.874 25.874 49.89-49.891a7.497 7.497 0 0 1 10.606 0l7.724 7.724a7.5 7.5 0 0 1 0 10.607z'/%3E%3C/svg%3E");
					background-size: contain;
				}
            </style>
            <body class="fastpayment">
                <header id="main-header" style="margin-top:20px">
                    <div class="row">
                        <div class="col-lg-12 franja">
                            <img class="center-block" src="https://creditos.somosziro.com/img/logo-ziro.png" style="width: 400px">
                        </div>
                    </div>
                </header>
                <div class="container bg-white p-5">
                    <div class="row" style="margin-top:20px">
                        <div class="col-lg-12">
                            <h4 style="text-align:left"> Respuesta de la Transacción </h4>
                            <hr>
                        </div>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>Referencia</td>
                                            <td id="referencia"></td>
                                        </tr>
                                        <tr>
                                            <td class="bold">Fecha</td>
                                            <td id="fecha" class=""></td>
                                        </tr>
                                        <tr>
                                            <td>Respuesta</td>
                                            <td id="respuesta"></td>
                                        </tr>
                                        <tr>
                                            <td>Motivo</td>
                                            <td id="motivo"></td>
                                        </tr>
                                        <tr>
                                            <td class="bold">Banco</td>
                                            <td class="" id="banco">
                                        </tr>
                                        <tr>
                                            <td class="bold">Recibo</td>
                                            <td id="recibo"></td>
                                        </tr>
                                        <tr>
                                            <td class="bold">Total</td>
                                            <td class="" id="total">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <footer>
                    <div class="row">
                        <div class="container bg-white pb-5">
                            <div class="col-lg-12">
								<div class="text__3unNc" style="
									display: block;
									vertical-align: middle;
									margin-bottom: 0;
									color: #8091a5;
									font-size: .65rem;
									margin-bottom: 0.2rem;
								">Pagos seguros por</div>
                                <img src="https://transaction-redirect.wompi.co/d939c20b901669b66c7fca8d38986872.png" style="margin-top:10px; float:left;width: 107px;">
								<img src="https://transaction-redirect.wompi.co/c3c9236f3161b4466882150259703ecf.png" height="40px" style="margin-top:10px; float:right;height:18px;">
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
                <!-- Include all compiled plugins (below), or include individual files as needed -->
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                <script>
                function getQueryParam(param) {
                    location.search.substr(1)
                        .split("&")
                        .some(function(item) { // returns first occurence and stops
                            return item.split("=")[0] == param && (param = item.split("=")[1])
                        })
                    return param
                }
                $(document).ready(function() {
                    //llave publica del proveedor

                    //Referencia de payco que viene por url
                    var ref_payco = getQueryParam('id');
                    //Url Rest Metodo get, se pasa la llave y la ref_payco como paremetro
                    var urlapp = "https://production.wompi.co/v1/transactions/" + ref_payco;

                    $.get(urlapp, function(response) {
						console.log(response)

                        if (response.data.status) {

                            $('#fecha').html(response.data.created_at);
                            $('#respuesta').html(response.data.status);
                            $('#referencia').text(response.data.reference);
                            $('#motivo').text(response.data.payment_method.payment_description);
                            $('#recibo').text(response.data.id);
                            $('#banco').text(response.data.payment_method_type);
                            $('#autorizacion').text(response.data.x_approval_code);
                            $('#total').text(' $ ' + response.data.payment_method.extra.transaction_value);
							console.log('response.data' +response.data);


                        } else {
                            alert("Error consultando la información");
                        }
                    }).fail(function() {
						alert("Error consultando la información");
					});

                });
                </script>
            </body>

            </html>
