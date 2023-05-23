<?php

class WspMsjPago3DiasShell extends AppShell {
    public function main() {
		App::import('Controller', 'AppController');
		$AppController = new AppController();
		$this->loadModel("CreditsLine");
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
		AND DATEDIFF(credits_plans.deadline, CURDATE()) =3
		AND credits_plans.state=0");

		//clientes que faltan 3 dias por pagar
		foreach($creditosToPay as $value) {
			$templateParams= [
				ucwords($value['customers']['name']),
				ucfirst($value['shop_commerces']['name']),
				ucfirst($value['shop_commerces']['name']),
				$value['credits_plans']['deadline'],
				$value['credits_plans']['value_pending']==0 ? number_format($value['credits_plans']['capital_value']) : number_format($value['credits_plans']['value_pending']),
				$value['credits']['code_pay'],
			];
			$phone=$value['customers_phones']['phone_number'];
			// $phone='3164093420';
			$templateMsj="⚠️ Recordatorio ⚠️\n\n¡Hola {{1}}! Espero que estés teniendo un maravilloso día. Queremos recordarte que tu factura de {{2}} 🤭 está por vencer 🥹. \n\nEsperamos que estés disfrutando de todas las ventajas que ofrecemos en {{3}} + *zíro*. Por eso, te animamos a que hagas el pago antes de la fecha límite para evitar cargos adicionales. 💚\n\nAquí están los detalles de tu factura:\n\n🗓️ Fecha límite de pago: {{4}}\n💰 Valor: {{5}}\n\nSi prefieres hacer el pago a través de corresponsal Bancolombia, por favor ten en cuenta estos datos:\n\nConvenio: 92379\nReferencia: {{6}}\n\nNo olvides enviarnos un comprobante de pago para poder hacer un seguimiento más fácil y agilizar el proceso en área de finanzas.\n\n📲  Si necesitas más información, por favor no dudes en contactarnos. Agradecemos mucho tu atención y compromiso con nosotros. 💚";
			$templateId="251385703910467";
			$AppController->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);
		}

		// correos a notificar
		$correos = [
			'victoria@somosziro.com',
			'juancacreativo@somosziro.com',
			'laurens@somosziro.com',
			'monica@somosziro.com',
		];

		//opciones para enviar el correo
		$options = [
			"subject"   => "Mensaje 3 días enviado a los clientes WhatsApp",
			"to"        => $correos,
			"vars"      => [
				'creditosToPay' => $creditosToPay,
				'dias'          => 3,
			],
			"template"  => "sms_recordatorio_pago",
		];

		$AppController->sendMail($options);
		$this->out('fin.');
    }


}
