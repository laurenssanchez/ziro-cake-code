<?php

class SmsClientesMoraShell extends AppShell {

    public function main() {
		App::import('Controller', 'AppController');
		$AppController = new AppController();
		//do stuff...
		$data = [];
		$arrayCustomers=[];
		$AppController->loadModel("CreditsLine");
		$creditosToPay = $AppController->CreditsLine->query("SELECT  DATEDIFF(CURDATE(),credits_plans.deadline) as dias_pago,
		credits_plans.deadline, credits_plans.id,
		credits_plans.capital_value, credits_plans.credit_id,credits_plans.state,credits_plans.value_pending,
		credits_plans.capital_value, credits_plans.interest_value, credits_plans.others_value,
		credits.id, credits.code_pay, credits.quota_value,
		credits.customer_id, customers.id, customers.name, customers.email,
		customers.identification,
		customers_phones.id, customers_phones.customer_id, customers_phones.phone_number,
		shop_commerces.name,
		credits_requests.id, credits_requests.date_disbursed, credits_requests.value_disbursed
		FROM credits_plans
		INNER JOIN credits ON credits.id = credits_plans.credit_id
		INNER JOIN credits_requests ON credits_requests.id = credits.credits_request_id
		INNER JOIN shop_commerces ON shop_commerces.id = credits_requests.shop_commerce_id
		INNER JOIN customers ON customers.id = credits.customer_id
		INNER JOIN customers_phones ON customers.id = customers_phones.customer_id
		WHERE   credits_plans.deadline <= CURDATE()
		AND DATEDIFF(credits_plans.deadline, CURDATE()) <0
		AND credits_plans.state=0
		ORDER BY credits_plans.deadline DESC");

		$AppController->loadModel('CreditsPlan');
        $AppController->CreditsPlan->setCuotasValue();
        $AppController->CreditsPlan->update_cuotes_days();
		$hoy = date("Y-m-d"); // obtenemos la fecha actual en formato yyyy-mm-dd

		foreach($creditosToPay as $key => $value) {
			$phone=$value['customers_phones']['phone_number'];
			// $phone='3023439045';
			$fechaLimte=$value['credits_plans']['deadline'];
			$valorCompra=$value['credits_requests']['value_disbursed'];
			$codePay=$value['credits']['code_pay'];
			$comercio=$value['shop_commerces']['name'];
			$diasMora=abs($value[0]['dias_pago']);
			$resultQuote = $AppController->payment_total($value['credits_requests']['id'], $value['credits_plans']['id']);
			$capital = $resultQuote['quote']["CreditsPlan"]["capital_value"];
			$interes = $resultQuote['quote']["CreditsPlan"]["interest_value"];
			$others  = $resultQuote['quote']["CreditsPlan"]["others_value"];
			$TotalAbonado = $resultQuote['quote']["CreditsPlan"]["capital_payment"] + $resultQuote['quote']["CreditsPlan"]["interest_payment"] + $resultQuote['quote']["CreditsPlan"]["others_payment"];
			$interesMora=number_format($resultQuote['quote']["CreditsPlan"]["debt_value"]+$resultQuote['quote']["CreditsPlan"]["debt_honor"]);
			$totalAbonado= is_null($resultQuote['quote']["CreditsPlan"]["TotalAbo"])?0:number_format($resultQuote['quote']["CreditsPlan"]["TotalAbo"]);
			$cuotaNormal = $capital;
			$totalCuota = ($cuotaNormal+$others+$interes+$resultQuote['quote']["CreditsPlan"]["debt_value"]+$resultQuote['quote']["CreditsPlan"]["debt_honor"]) - (is_null($resultQuote['quote']["CreditsPlan"]["TotalAbo"])?0:$resultQuote['quote']["CreditsPlan"]["TotalAbo"]);
			if ($totalCuota <= 0) {
				$totalCuota = 0;
			}
			$totalPagar=$resultQuote['quote']["CreditsPlan"]["state"] == 1 ? 0 :number_format($totalCuota);

			//validar si tiene compromiso de pago
			$AppController->loadModel('Commitment');
			$lastCommitment = $AppController->Commitment->find('first', array(
				'conditions' => array(
					'Commitment.credits_plan_id' => $value['credits_plans']['id'],
					'Commitment.state >=' => 0
				),
				'order' => array('Commitment.created DESC')
			));

			$flagEnvioMsjMora=true;

			if ($lastCommitment) {
				$fechaCompromiso = $lastCommitment['Commitment']['deadline'];
				//si la fecha del compromiso es hoy
				if ($fechaCompromiso == $hoy) {
					$flagEnvioMsjMora=false;
					$templateId="785543039804418";
					//parametros
					$templateParams= [
						ucwords($value['customers']['name']),
						$totalPagar,
					];
					//id de la plantilla
					$templateMsj='Buen día {{1}},\n¿Cómo estás? Espero que muy bien. 👍🏼\nTe escribo porque, salvo error de ZIRO 💚, que no nos haya notificado tu pago, tal como me indicaste en las conversaciones durante esta semana, no hemos recibido el pago de la factura por {{2}}. 🫣\nTe agradezco enviarme el comprobante del pago realizado para poderlo enviar a finanzas y saldar tu deuda. ✅\nEn caso de no haber cancelado, ingresa al botón de pago y cancela antes de que el día termine, 🆘 no permitas que tu compromiso quede incumplido ⚠';
					$app=$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);
				}  else if($fechaCompromiso > $hoy) {
					$flagEnvioMsjMora=false;
				}
			}

			if ($flagEnvioMsjMora && $diasMora<=29 && $value['customers']['identification'] !=='16079406') {
				$customerId=$value['customers']['id'];
				$AppController->loadModel("Credits");
				array_push($arrayCustomers,$customerId);
				//***********************
				/////mora 1 a 3 dias//
				//***********************
				if ($diasMora==1) {
					$templateId="1393779924691255";
					//parametros
					$templateParams= [
						ucwords($value['customers']['name']),
					];
					//id de la plantilla
					$templateMsj='¡¡¡ Hola {{1}},\n⚠Solo queremos recordarte que el pago de tu deuda es importante para nosotros y para ti 💚. Al cumplir con tu pago a tiempo, estarás fortaleciendo tu historial crediticio y abriendo más oportunidades para tu negocio.\ningresa a este botón de pago donde digitando tu cedula, observaras el valor a pagar. Cancela lo más pronto posible.\n⭐ Si ya realizaste el pago, envianos el soporte de pago y por favor haga caso omiso a este mensaje.';
					$app=$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);

				} else if($diasMora==2) {
					$templateId="508522064625032";
					//parametros
					$templateParams= [
						ucwords($value['customers']['name']),
						$fechaLimte,
						$interesMora,
						$totalPagar,
						$diasMora,
					];

					//id de la plantilla
					$templateMsj="Hola {{1}}, ¡Buen día! ☀️ Esperamos que hoy tengas un día maravilloso. Queríamos recordarte que tú factura está vencida 😭.  ingresa a este botón de pago y cancela lo más pronto posible.\n🗓 Fecha límite de pago: {{2}} \n\nIntereses $ {{3}}\n💰 Valor a pagar: $ {{4}}\nDias en mora: {{5}}\n⭐ Si ya realizaste el pago, envianos el soporte de pago y haz caso omiso de este mensaje";
					$app=$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);

				} else if($diasMora==3) {
					$templateId="900996841136310";
					//parametros
					$templateParams= [
						ucwords($value['customers']['name']),
						$totalPagar,
						$fechaLimte,
						$valorCompra,
						$interesMora,
						$totalPagar,
						$diasMora,
						$codePay
					];
					//id de la plantilla
					$templateMsj='¡¡¡ Hola {{1}}, 💚 Zíro le hace más fácil ponerse al día con su pago de la factura por un monto total de {{2}}, la cual aún no registra su pago!!! ⚠ Le invitamos cordialmente a ponerse al día con sus obligaciones. Ingrese al botón de pago al final del mensaje. En caso de que ya haya efectuado el pago, le pedimos disculpas, envianos el soporte de pago y por favor haga caso omiso a este mensaje.\n🗓 Fecha límite de pago: {{3}}\n\n💰 Valor de la compra: ${{4}}\nIntereses $ {{5}}\n💰 Valor a pagar: $ {{6}}\nDias en mora: {{7}}\nReferencia de pago: {{8}}';
					$app=$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);
				}
				//***********************
				/////mora 4 a 6 dias//
				//***********************
				else if($diasMora==4) {
					$templateId="186062584210568";
					//parametros
					$templateParams= [
						ucwords($value['customers']['name']),
						$comercio,
						$fechaLimte,
						$diasMora,
						$valorCompra,
						$interesMora,
						$totalPagar,
						$comercio
					];
					//id de la plantilla
					$templateMsj='Hola!!! {{1}} 🙌 Espero que estés teniendo un buen día! Solo queremos recordarte que tu factura de {{2}} 💜 pagada con crédito Zíro 💚 está vencida, ⚠️ Recuerda que es importante que pagues tu crédito a tiempo para evitar que te cobren intereses.\u000b\u000b\u000b los cuales actualmente ya están corriendo\n🗓️ Fecha límite de pago: {{3}} \u000b\n📰 Dias en mora: {{4}}\n💰 Valor credito: ${{5}}\nIntereses $ {{6}}\nSaldo Actual: {{7}}\nIngresa a este *Link directo en la plataforma de pagos de Zíro* – Solo digitando tu número de cedula podrás visualizar el valor a pagar, creditos.somosziro.com/general/fastpayment   Allí podrás elegir pagar desde tu cuenta Bancolombia, por PSE desde cualquier banco o Nequi. Cancela hoy y aprovecha todas las promociones disponibles con {{8}}. 💪 Mi nombre es Mónica y estoy para servirte 💚';
					$app=$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);

				} else if($diasMora==5) {
					$templateId="1388871221890882";
					//parametros
					$templateParams= [
						ucwords($value['customers']['name']),
						$diasMora,
						$valorCompra,
						$interesMora,
						$totalPagar,
					];
					//id de la plantilla
					$templateMsj='¡¡¡ Hola {{1}}, ☀️ Zíro le informa que para este momento su factura presenta {{2}} días de vencimiento!!! Puede ponerse al día con su factura haciendo clic en el botón de pago. En caso de que ya haya efectuado el pago, le pedimos disculpas, y haga caso omiso a este mensaje, de lo contrario le informamos que los intereses actualmente ya estan corriendo por lo que, 🆘 al no cancelar, su deuda seguirá creciendo. ⚠\n💰 Valor de la compra: ${{3}}\nIntereses $ {{4}}\n💰 Valor a pagar: $ {{5}}\n⭐ Si ya realizaste el pago, envianos el soporte de pago';
					$app=$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);
				}
				//***********************
				/////mora 6 a 8 dias//
				//***********************
				else if($diasMora==6 || $diasMora==8) {
					$templateId="251430837311851";
					//parametros
					$templateParams= [
						ucwords($value['customers']['name']),
						$fechaLimte,
						$valorCompra,
						$interesMora,
						$totalPagar,
						$diasMora,
					];
					//id de la plantilla
					$templateMsj='Hola {{1}} 💚 Recuerda que siempre puedes contar con nosotros para ayudarte a alcanzar todas tus metas y objetivos. ⚠ Mi nombre es Mónica y te invito a que trabajemos juntos para encontrar una solución que te ayude a cumplir con tus obligaciones pendientes con Zíro. 💪 ingresa a este botón de pago y cancela lo más pronto posible 🆘\n🗓 Fecha límite de pago: {{2}}\n\n💰 Valor de la compra: ${{3}}\nIntereses $ {{4}}\n💰 Valor a pagar: $ {{5}}\nDias en mora: {{6}}\n⭐ Si ya realizaste el pago, envianos el soporte de pago y por favor haga caso omiso a este mensaje';
					$app=$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);

				} else if($diasMora==7) {
					$templateId="180747201454194";
					//parametros
					$templateParams= [
						ucwords($value['customers']['name']),
						$fechaLimte,
						$valorCompra,
						$interesMora,
						$totalPagar,
						$diasMora,
					];
					//id de la plantilla
					$templateMsj='¡¡¡ Hola {{1}}, ☀️ Tu éxito es nuestro éxito!!!! Así que queremos seguir apoyándote en tu crecimiento, por eso es importante cumplir con nuestros acuerdos de pago, tal como se indicó en el pagaré enviado a tu correo electrónico el día que retiraste el pedido 💚.\nTe recordamos la información más importante de tu crédito:\n🗓 Fecha límite de pago: {{2}}\n\n💰 Valor de la compra: ${{3}}\nIntereses $ {{4}}\n💰 Valor a pagar: $ {{5}}\nDias en mora: {{6}}\n⭐ Si ya realizaste el pago, envianos el soporte de pago y por favor haga caso omiso a este mensaje';
					$app=$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);
				}
				//***********************
				/////Mora 9-11 días//
				//***********************
				else if($diasMora==9 || $diasMora==11) {
					$templateId="923488115658389";
					//parametros
					$templateParams= [
						ucwords($value['customers']['name']),
						$fechaLimte,
						$valorCompra,
						$interesMora,
						$totalPagar,
						$diasMora,
					];
					//id de la plantilla
					$templateMsj='Hola {{1}} 💚 \nNos complace saber que nuestro préstamo ha sido de gran ayuda para tu negocio 🚀Ahora es importante cumplir con nuestros acuerdos para seguir ayudándote a crecer📈. Comunícate con nosotros 💚 Estamos para ayudarte y hacer crecer tu negocio 🚀 Mi nombre es Mónica y recuerda 👏👏👏 ¡¡¡¡ Cada pago que haces es un paso hacia tu crecimiento y éxito como emprendedor!!!! 💚 ingresa a este botón de pago y cancela lo más pronto posible  \n🗓 Fecha límite de pago: {{2}}\n\n💰 Valor de la compra: ${{3}}\nIntereses $ {{4}}\n💰 Valor a pagar: $ {{5}}\nDias en mora: {{6}}\n⭐ Si ya realizaste el pago, envianos el soporte de pago y por favor haga caso omiso a este mensaje';
					$app=$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);
				} else if($diasMora==10) {
					$templateId="930586738362410";
					//parametros
					$templateParams= [
						ucwords($value['customers']['name']),
						$fechaLimte,
						$valorCompra,
						$interesMora,
						$totalPagar,
						$diasMora,
					];
					//id de la plantilla
					$templateMsj='Hola {{1}} 💚 \n\nQuiero aprovechar esta oportunidad para agradecerte por tu confianza al elegir nuestros servicios. Sin embargo, hemos notado que tu cuenta presenta un atraso en el pago y es importante que se solvente para mantener nuestros compromisos de servicio. \n🗓 Fecha límite de pago: {{2}}\n\n💰 Valor de la compra: ${{3}}\nIntereses $ {{4}}\n💰 Valor a pagar: $ {{5}}\nDias en mora: {{6}}\n⭐ Si ya realizaste el pago, envianos el soporte de pago y por favor haga caso omiso a este mensaje';
					$app=$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);
				}
				//***********************
				/////Mora 11-15 días//
				//***********************
				else if($diasMora==11 || $diasMora==13 || $diasMora==15) {
					$templateId="1396989334453317";
					//parametros
					$templateParams= [
						ucwords($value['customers']['name']),
						$fechaLimte,
						$valorCompra,
						$interesMora,
						$totalPagar,
						$diasMora,
					];
					//id de la plantilla
					$templateMsj='Hola {{1}}💚 \nQuiero recordarte que aún tenemos pendiente el pago de la deuda que adquiriste con nosotros para tu negocio. \n\n🗓 Fecha límite de pago: {{2}} \n\n💰 Valor de la compra: ${{3}}\nIntereses $ {{4}}\n💰 Valor a pagar: $ {{5}}\nDias en mora: {{6}}\n⭐ Si ya realizaste el pago, envianos el soporte de pago y por favor haga caso omiso a este mensaje';
					$app=$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);

				} else if($diasMora==12 || $diasMora==14) {
					$templateId="1627955794370431";
					//parametros
					$templateParams= [
						ucwords($value['customers']['name']),
						$fechaLimte,
						$valorCompra,
						$interesMora,
						$totalPagar,
						$diasMora,
					];
					//id de la plantilla
					$templateMsj='Hola {{1}} 💚 \nTu pago a tiempo es importante para nosotros y para tu vida crediticia. ¡¡¡ Sabemos que tu negocio está en constante evolución y queremos seguir siendo una parte de tu crecimiento 🚀!!! Te agradecemos por cumplir con tus obligaciones financieras con nosotros.\n🆘 ingresa a este botón de pago y cancela lo más pronto posible  \n🗓 Fecha límite de pago: {{2}}\n\n💰 Valor de la compra: ${{3}}\nIntereses $ {{4}}\n💰 Valor a pagar: $ {{5}}\nDias en mora: {{6}}\n⭐ Si ya realizaste el pago, envianos el soporte de pago y por favor haga caso omiso a este mensaje';
					$app=$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);
				}
				//***********************
				/////Mora 16 a 17 dias s//
				//***********************
				else if($diasMora==16) {
					$templateId="765811418273259";
					//parametros
					$templateParams= [
						ucwords($value['customers']['name']),
						$valorCompra,
						$interesMora,
						$totalPagar,
						$diasMora,
					];
					//id de la plantilla
					$templateMsj='Hola {{1}}, ¡Buen día! ☀️ \n\n🆘 Entendemos tu situación actual la cual te ha llevado al no pago de tu compromiso con Zíro, también sabemos que a veces puede ser difícil manejar las finanzas, sin embargo, al dejar pasar más los días te expones al riesgo de no poder acceder a productos financieros en el futuro. ⚠️ Por favor no dudes en contactarnos lo antes posible, mi nombre es Mónica y estoy para servirte a ti y a tu negocio 👏👏 \n💰 Valor de la compra: ${{2}}\nIntereses $ {{3}}\n💰 Valor a pagar: $ {{4}}\nDias en mora: {{5}}\ningresa a este botón de pago y cancela lo más pronto posible  \n⭐ Si ya realizaste el pago, envianos el soporte de pago y por favor haga caso omiso a este mensaje';
					$app=$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);

				} else if($diasMora==17) {
					$templateId="734360238319450";
					//parametros
					$templateParams= [
						ucwords($value['customers']['name']),
						$valorCompra,
						$interesMora,
						$totalPagar,
						$diasMora,
					];
					//id de la plantilla
					$templateMsj='Hola {{1}}, ¡Buen día! ☀️\n\nEspero que te encuentres bien. ☺️ Me pongo en contacto contigo para recordarte que tienes una deuda pendiente que manifestaste pago esta semana. 💚\nPuedes realizar el pago a través de transferencia bancaria o incluso Nequi. Si necesitas ayuda estamos aquí para ayudarte en lo que podamos. 💪🏼\n💰 Valor de la compra: ${{2}}\nIntereses $ {{3}}\n💰 Valor a pagar: $ {{4}}\nDias en mora: {{5}}\ningresa a este botón de pago y cancela lo más pronto posible  \n⭐ Si ya realizaste el pago, envianos el soporte de pago y por favor haga caso omiso a este mensaje';
					$app=$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);
				}
				//***********************
				/////Mora 18 a 19 dias//
				//***********************
				else if($diasMora==18 || $diasMora==19) {
					$templateId="191507970318916";
					//parametros
					$templateParams= [
						ucwords($value['customers']['name']),
					];
					//id de la plantilla
					$templateMsj='Hola {{1}}, ¡Buen día! ☀️\nNos ponemos en contacto con usted en relación a la deuda que adquirió para su local. Lamentablemente, hemos observado que el pago de esta deuda se encuentra vencido y aún no hemos recibido el pago correspondiente.\ningresa a este botón de pago y cancela lo más pronto posible  \n⭐ Si ya realizaste el pago, envianos el soporte de pago y por favor haga caso omiso a este mensaje';
					$app=$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);
				}
				//***********************
				/////Mora 20 a 25 dias//
				//***********************
				if ($diasMora >= 20 && $diasMora <= 25) {
					$templateId="1275994233313199";
					//parametros
					$templateParams= [
						ucwords($value['customers']['name']),
						$valorCompra,
						$interesMora,
						$totalPagar,
						$diasMora,
					];
					//id de la plantilla
					$templateMsj='Hola {{1}}, como se ha notificado en diversas ocasiones se han vencido los plazos para el pago de la factura pendiente con Zíro 💚. Por tanto, le notificamos que, de no ponerse al día en el menor tiempo posible, 🆘 traslademos el caso a estamentos administrativos/judiciales, una vez el crédito cumpla 30 días de mora.\n💰 Valor de la compra: ${{2}}\nIntereses $ {{3}}\n💰 Valor a pagar: $ {{4}}\nDias en mora: {{5}}\n⭐ Si ya realizaste el pago, envianos el soporte de pago y por favor haga caso omiso a este mensaje\nRecuerda que el único medio virtual habilitado para el pago es el siguiente:';
					$app=$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);
				}
				//***********************
				/////Mora 26 a 29 dias //
				//***********************
				if ($diasMora >= 26 && $diasMora <= 29) {
					$templateId="727568772431036";
					//parametros
					$templateParams= [
						ucwords($value['customers']['name']),
						$valorCompra,
						$interesMora,
						$totalPagar,
						$diasMora,
					];
					//id de la plantilla
					$templateMsj='Hola {{1}}, sabemos que a veces pueden surgir imprevistos, sin embargo, en esta oportunidad, le escribimos para informarle que su deuda con nosotros sigue pendiente. ⚠ Apreciamos su negocio y los esfuerzos que haces cada día para que crezca. Informamos que de no recibir el pago y al cumplirse 30 días en mora tomaremos medidas legales para recuperar el pago.\n💰 Valor de la compra: ${{2}}\nIntereses $ {{3}}\n💰 Valor a pagar: $ {{4}}\nDias en mora: {{5}}\n⭐ Si ya realizaste el pago, envianos el soporte de pago y por favor haga caso omiso a este mensaje\nRecuerda que el único medio virtual habilitado para el pago es el siguiente:';
					$app=$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);
				}
			}

		}

		//correos a notificar
		$correos = [
			'victoria@somosziro.com',
			'laurens@somosziro.com',
			'monica@somosziro.com',
		];

		//opciones para enviar el correo
		$options = [
			"subject"   => "Mensaje enviado a los clientes en mora.",
			"to"        => $correos,
			"vars"      => [
				'creditosToPay' => $creditosToPay,
				'arrayCustomers' => $arrayCustomers
			],
			"template"  => "ws_clientes_mora",
		];

		$AppController->sendMail($options);
		$this->out('fin mensajes mora.');
    }
}
