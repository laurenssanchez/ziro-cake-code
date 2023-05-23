<?php

App::uses('Dispatcher', 'Routing');

define('CRON_DISPATCHER',true);

if($argc == 2) {
	$Dispatcher = new Dispatcher();
	$Dispatcher->dispatch(new CakeRequest($argv[1]),
		new CakeResponse(array('charset' => Configure::read('App.encoding'))));
}
