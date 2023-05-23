<?php

class SmsPago2DiasShell extends AppShell {
    public function main() {
		App::import('Controller', 'AppController');
		$AppController = new AppController();
		//do stuff...
		$data = [];
		$data['sms_body'] = 'Estar al día con tu crédito Ziro es súper fácil, tan fácil como fue pedirlo. Puedes pagar en línea o en uno de los miles de corresponsales en todo el país. Ingresa a este link https://bit.ly/3VIG84v y entérate de los medios de pago que tenemos para ti
		';
		$this->loadModel("CreditsLine");
		$this->loadModel("CreditsLine");
		$creditosToPay = $this->CreditsLine->query("SELECT  DATEDIFF(credits_plans.deadline,CURDATE()) as dias_pago, credits_plans.deadline, credits_plans.id,
		credits_plans.capital_value, credits_plans.credit_id,
		credits_plans.state,credits.id, credits.code_pay, credits.quota_value,
		credits.customer_id, customers.id, customers.name, customers.email,
		customers.identification,
		customers_phones.id, customers_phones.customer_id, customers_phones.phone_number
		FROM credits_plans
		INNER JOIN credits ON credits.id = credits_plans.credit_id
		INNER JOIN customers ON customers.id = credits.customer_id
		INNER JOIN customers_phones ON customers.id = customers_phones.customer_id
		WHERE   credits_plans.deadline >= CURDATE()
		AND DATEDIFF(credits_plans.deadline, CURDATE()) =2
		AND credits_plans.state=0");

		//clientes que faltan 15 dias por pagar
		foreach($creditosToPay as $value) {
			// ¡Hola ((Nombre))! Tu factura de ((Proveedor)) vence el día de hoy. Esperamos que estés disfrutando de todas las ventajas que ofrecemos en zíro. Por eso, te animamos a que realices el pago para evitar cargos adicionales. Tu fecha límite es: ((Fecha)) Valor: ((Valor de crédito)) Para hacer el pago a través de corresponsal Bancolombia, por favor ten en cuenta estos datos: Convenio: 92379 Referencia:((Número de referencia)) Agradecemos mucho tu atención y compromiso con nosotros. Ingresa a este link https://bit.ly/3VIG84v y entérate de los otros medios de pago que tenemos para ti
			// $data['sms_body'] = 'Hola, '.strtoupper($value['customers']['name']).' tu crédito Zíro vence el día de hoy, tienes un pago pendiente de tu factura por un valor de $'.number_format($value['credits_plans']['capital_value']).', tu fecha límite es el '.$value['credits_plans']['deadline'] .'. Ingresa a este link https://bit.ly/3VIG84v y entérate de los medios de pago que tenemos para ti. Si necesitas más información, por favor no dudes en ponerte en contacto con nosotros. Apreciamos mucho tu apoyo y tu compromiso.';
			$data['sms_body'] = '¡Hola  '.strtoupper($value['customers']['name']).'! Tu factura de  '.strtoupper($value['customers']['name']).' vence el día de hoy. Esperamos que estés disfrutando de todas las ventajas que ofrecemos en zíro. Por eso, te animamos a que realices el pago para evitar cargos adicionales.  Valor: '.number_format($value['credits_plans']['capital_value']).' Para hacer el pago a través de corresponsal Bancolombia, por favor ten en cuenta estos datos: Convenio: 92379 Referencia:'.number_format($value['credits_plans']['capital_value']).' Agradecemos mucho tu atención y compromiso con nosotros. Ingresa a este link https://bit.ly/3VIG84v y entérate de los otros medios de pago que tenemos para ti';
			$phone=$value['customers_phones']['phone_number'];
			$AppController->enviaSmsCellvoz($data, $phone, false);
			// $AppController->enviaSmsTwillio($data, $phone, false);
		}

		//correos a notificar
		$correos = [
			'juancacreativo@somosziro.com',
			'laurens@somosziro.com',
			'monica@somosziro.com',
		];

		//opciones para enviar el correo
		$options = [
			"subject"   => "Mensaje 2 días enviado a los clientes",
			"to"        => $correos,
			"vars"      => [
				'creditosToPay' => $creditosToPay,
				'dias'          => 2,
			],
			"template"  => "sms_recordatorio_pago",
		];


		$AppController->sendMail($options);
		$this->out('fin.');
    }


}
