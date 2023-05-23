<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'home'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/display', array('controller' => 'pages', 'action' => 'display'));
	Router::connect('/general/calculate', array('controller' => 'pages', 'action' => 'calculate'));
	Router::connect('/general/plan_payments', array('controller' => 'pages', 'action' => 'plan_payments'));
	Router::connect('/general/fastpayment', array('controller' => 'pages', 'action' => 'fastpayment'));
	Router::connect('/general/generate_codes/*', array('controller' => 'pages', 'action' => 'generate_codes'));
	Router::connect('/general/validate_codes_crediventas/*', array('controller' => 'pages', 'action' => 'validate_codes_crediventas'));
	Router::connect('/general/commerce_payment', array('controller' => 'pages', 'action' => 'commerce_payment'));
	Router::connect('/payment_commerce_credishop', array('controller' => 'requests', 'action' => 'payment_commerce_credishop'));
	Router::connect('/payment_fast_search', array('controller' => 'credits', 'action' => 'search_user'));
	Router::connect('/payment_commerce_search', array('controller' => 'requests', 'action' => 'payment_commerce_search'));
	Router::connect('/payment_fast_select', array('controller' => 'credits', 'action' => 'get_credit_customer'));
	Router::connect('/get_data_payment', array('controller' => 'credits', 'action' => 'get_data_payment'));
	Router::connect('/payment_web_credishop', array('controller' => 'credits', 'action' => 'payment_web'));
	Router::connect('/payment_web_credishop_response', array('controller' => 'credits', 'action' => 'payment_web_response'));

	Router::connect('/crediventas/', array('controller' => 'pages', 'action' => 'crediventas'));
	Router::connect('/simulator/*', array('controller' => 'simulators', 'action' => 'simulate'));

	Router::connect('/pages/validar-codigo-proveedor', array('controller' => 'pages', 'action' => 'validarCodigoProveedor'));
	Router::connect('/pages/validar-cedula-cliente', array('controller' => 'pages', 'action' => 'validarCedulaCliente'));
	Router::connect('/pages/validar-correo-cliente', array('controller' => 'pages', 'action' => 'validarCorreoCliente'));
	Router::connect('/pages/validar-correo-usuario', array('controller' => 'pages', 'action' => 'validarCorreoUsuario'));
	Router::connect('/pages/registro-metamap', array('controller' => 'pages', 'action' => 'registroMetamap'));

	Router::connect('/tu-credito/*', array('controller' => 'pages', 'action' => 'register_step_unique'));

	Router::connect('/metamap/*', array('controller' => 'pages', 'action' => 'register_metamap'));

	Router::connect('/credits/actualizar-cupo/:customerId', array('controller' => 'Credits', 'action' => 'actualizarCupoCliente'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
