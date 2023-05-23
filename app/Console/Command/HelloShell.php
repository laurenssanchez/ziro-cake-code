<?php

class HelloShell extends AppShell {

    public function main() {
		App::import('Controller', 'AppController');
		$AppController = new AppController();

		//opciones para enviar el correo
		$options = [
			"subject"   => "Mensaje día de pago enviado a los clientes",
			"to"        => 'laurens@somosziro.com',
			"vars"      => [
			],
			"template"  => "prueba_cron",
		];


		$AppController->sendMail($options);
		$this->out('fin SmsPagoDiaPagoShell.');
    }


}
