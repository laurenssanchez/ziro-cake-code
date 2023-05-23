<?php

class SmsPagoDiaPagoShell extends AppShell {

    public function main() {
		App::import('Controller', 'AppController');
		$AppController = new AppController();
		//do stuff...
		$data = [];
		$arrayCustomers=[];
		$this->loadModel("CreditsLine");
		$creditosToPay = $this->CreditsLine->query("SELECT  DATEDIFF(credits_plans.deadline,CURDATE()) as dias_pago, credits_plans.deadline, credits_plans.id,
		credits_plans.capital_value, credits_plans.credit_id,credits_plans.state,credits_plans.value_pending,
		credits.id, credits.code_pay, credits.quota_value,
		credits.customer_id, customers.id, customers.name, customers.email,
		customers.identification,
		customers_phones.id, customers_phones.customer_id, customers_phones.phone_number,
		shop_commerces.name
		FROM credits_plans
		INNER JOIN credits ON credits.id = credits_plans.credit_id
		INNER JOIN credits_requests ON credits_requests.id = credits.credits_request_id
		INNER JOIN shop_commerces ON shop_commerces.id = credits_requests.shop_commerce_id
		INNER JOIN customers ON customers.id = credits.customer_id
		INNER JOIN customers_phones ON customers.id = customers_phones.customer_id
		WHERE   credits_plans.deadline >= CURDATE()
		AND DATEDIFF(credits_plans.deadline, CURDATE()) =0
		AND credits_plans.state=0");

		// debug($creditosToPay);
		// die();

		//clientes que faltan 15 dias por pagar
		foreach($creditosToPay as $value) {
			$cliente=ucwords($value['customers']['name']);
			$commerce=ucfirst($value['shop_commerces']['name']);
			$fechaPago=$value['credits_plans']['deadline'];
			$valorPendiente=$value['credits_plans']['value_pending']==0 ? number_format($value['credits_plans']['capital_value']) : number_format($value['credits_plans']['value_pending']);
			$codePay=$value['credits']['code_pay'];

			$phone=$value['customers_phones']['phone_number'];
			$data['sms_body']='¡Hola '.$cliente.'! Tu factura de '.$commerce.' vence el día de hoy. Esperamos que estés disfrutando de todas las ventajas que ofrecemos en zíro. Por eso, te animamos a que realices el pago para evitar cargos adicionales. Tu fecha límite es: '.$fechaPago.' Valor: $'.$valorPendiente.' Para hacer el pago a través de corresponsal Bancolombia, por favor ten en cuenta estos datos: Convenio: 92379 Referencia: '.$codePay;
			// $phone='3023439045';
			$AppController->enviaSmsCellvoz($data, $phone, false);
			// debug($value);
			// die();
		}

		//correos a notificar
		$correos = [
			'juancacreativo@somosziro.com',
			'laurens@somosziro.com',
			'monica@somosziro.com',
		];

		//opciones para enviar el correo
		$options = [
			"subject"   => "Mensaje día de pago enviado a los clientes",
			"to"        => $correos,
			"vars"      => [
				'creditosToPay' => $creditosToPay,
				'dias'          => '0',
				'arrayCustomers' => $arrayCustomers
			],
			"template"  => "sms_recordatorio_pago",
		];


		$AppController->sendMail($options);
		$this->out('fin SmsPagoDiaPagoShell.');
    }


}
