<?php
require_once '../Vendor/spreadsheet/vendor/autoload.php';

use Cake\Log\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

set_time_limit(0);

App::uses('AppController', 'Controller');
date_default_timezone_set('America/Bogota');

class ShopsController extends AppController {


	public $components = array('Paginator');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow( 'verifyNit');
	}

	public function excel_commerces()
	{
		$this->autoRender = false;
		$commerces = $this->Shop->ShopCommerce->find("all",["recursive"=>-1]);

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$spreadsheet->getProperties()->setCreator('CREDISHOP')
            ->setLastModifiedBy('CREDISHOP')
            ->setTitle('Sucursales')
            ->setSubject('Sucursales')
            ->setDescription('Sucursales ZÍRO')
            ->setKeywords('Sucursales')
            ->setCategory('Sucursales');

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'proveedor admón')
            ->setCellValue('B1', 'Sucursal')
            ->setCellValue('C1', 'Direción')
            ->setCellValue('D1', 'Ciudad')
            ->setCellValue('E1', 'Teléfono')
            ->setCellValue('F1', 'Código');

        if (!empty($commerces)) {
            $i = 2;
            foreach ($commerces as $key => $value) {

                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, $value["ShopCommerce"]["shop_name"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $value["ShopCommerce"]["name"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $value["ShopCommerce"]["address"] );
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $value["ShopCommerce"]["shop_city"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, $value["ShopCommerce"]["phone"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, $value["ShopCommerce"]["code"]);
                $i++;
            }
        }

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

        $spreadsheet->getActiveSheet()->setTitle('Sucursales');
        $spreadsheet->getActiveSheet()->getStyle('A1:W1')->getFont()->setBold(true);
        //$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
        $name = "files/sucursales_" . time() . ".xls";
        $writer->save($name);

        $url = Router::url("/", true) . $name;
        $this->redirect($url);

		var_dump($commerces);
		die;
	}


	public function excel_shops() {
		$this->autoRender = false;
		$this->Shop->unBindModel(["hasMany"=>["ShopReference","ShopPayment"],"belongsTo"=> array_keys($this->Shop->belongsTo) ]);
		$shops = $this->Shop->find("all");

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $spreadsheet->getProperties()->setCreator('CREDISHOP')
            ->setLastModifiedBy('CREDISHOP')
            ->setTitle('Comercios')
            ->setSubject('Comercios')
            ->setDescription('Comercios ZÍRO')
            ->setKeywords('Comercios')
            ->setCategory('Comercios');

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Nit')
            ->setCellValue('B1', 'Razón social')
            ->setCellValue('C1', 'Ciudad')
            ->setCellValue('D1', 'Dirección')
            ->setCellValue('E1', 'ID admin')
            ->setCellValue('F1', 'Administrador')
            ->setCellValue('G1', 'Correo')
            ->setCellValue('H1', '# Cédula')
            ->setCellValue('I1', 'Id # cédula banco')
            ->setCellValue('J1', 'Banco')
            ->setCellValue('K1', '# de cuenta')
            ->setCellValue('L1', 'Tipo de cuenta')
            ->setCellValue('M1', '% pago')
            // ->setCellValue('N1', '% pago 2')
            ->setCellValue('N1', '# Sucursales');

        if (!empty($shops)) {
            $i = 2;
            foreach ($shops as $key => $value) {

                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, $value["Shop"]["nit"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $value["Shop"]["social_reason"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $value["Shop"]["city"] );
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $value["Shop"]["address"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, $value["Shop"]["identification_admin"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, $value["Shop"]["name_admin"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, $value["Shop"]["email"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $value["Shop"]["identification_admin"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $value["Shop"]["identification_account"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, $value["Shop"]["account_bank"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, $value["Shop"]["account_number"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, $value["Shop"]["account_type"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, $value["Shop"]["cost_min"]);
                // $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, $value["Shop"]["cost_min"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, count($value["ShopCommerce"]));
                $i++;
            }
        }

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);

        $spreadsheet->getActiveSheet()->setTitle('Comercios');
        $spreadsheet->getActiveSheet()->getStyle('A1:W1')->getFont()->setBold(true);
        //$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
        $name = "files/comercios_" . time() . ".xls";
        $writer->save($name);

        $url = Router::url("/", true) . $name;
        $this->redirect($url);

	}


	public function index() {
		$conditions = $this->Shop->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->Shop->unBindModel([
			"belongsTo" => ["Adviser"]
		]);
		$this->Shop->recursive = 2;
		$this->Paginator->settings = array('order'=>array('Shop.modified'=>'DESC'));
		$shops = $this->Paginator->paginate(null, $conditions);

		foreach ($shops as $keyShop => $valueShop) {
			$commerces = [];
			foreach ($valueShop["ShopCommerce"] as $keyCom => $valueCommerce) {
				$commerces[] = $valueCommerce["id"];
			}
			$shops[$keyShop]["Shop"]["debt"] = 0;
			if(!empty($commerces)){
				$this->loadModel("ShopsDebt");
				$shops[$keyShop]["Shop"]["debt"] = $this->ShopsDebt->field("SUM(value)",["shop_commerce_id"=>$commerces,"state"=>0]);
			}
		}

		$this->set(compact('shops'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->Shop->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->Shop->recursive = 1;
		$conditions = array('Shop.' . $this->Shop->primaryKey => $id);
		$this->set('shop', $this->Shop->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->autoRender = false;

			if (!isset($this->request->data["Shop"]["id"])) {
				$this->Shop->create();
				$this->request->data["Shop"]["state"] 			= 0;
			}

			// $this->request->data["Shop"]["products_lists"] 	= implode(",", $this->request->data["Shop"]["products_lists"]);


			if ($this->Shop->save($this->request->data)) {
				$shop_id = $this->Shop->id;
				$this->createUserAdmin($this->request->data,$shop_id);
				// if(!isset($this->request->data["Shop"]["id"])){
				// 	foreach ($this->request->data["ShopReference"] as $key => $value) {
				// 		$value["shop_id"] = $shop_id;
				// 		$this->Shop->ShopReference->create();
				// 		$this->Shop->ShopReference->save($value);
				// 	}
				// }
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}

		}
		$users = $this->Shop->User->find('list',["conditions" => ["User.role" => [8]]]);
		$credits_line = $this->Shop->CreditsLine->find('list');
		$this->set(compact('users','credits_line'));
	}

	private function createUserAdmin($data,$shop_id){
		$userInfo = ["User" => [
			"email" => $data["Shop"]["email"],
			"name"  => $data["Shop"]["name_admin"],
			"password" => $data["Shop"]["identification_admin"],
			"shop_id"  => $shop_id,
			"role" 	   => 4,
			"state"    => 0
		]];

		$this->Shop->User->create();
		if($this->Shop->User->save($userInfo)){

			$user_id   = $this->Shop->User->id;
			$varsEmail = [
				"plan" 		=> Configure::read("PLANES.".$data["Shop"]["plan"]),
				"total" 	=> $data["Shop"]["payment_total"],
				"commerces" => $data["Shop"]["number_commerces"],
				"name" 		=> $data["Shop"]["social_reason"],
				"name_user" => $data["Shop"]["name_admin"],
				"email"     => $userInfo["User"]["email"],
				"dni"  		=> $data["Shop"]["identification_admin"],
 			];

			$shop = $this->Shop->find("first",["conditions" => ["Shop.id" => $shop_id], "recursive" => -1,"fields" => ["Shop.id", "Shop.user_id"] ]);

			$shop["Shop"]["user_id"] = $user_id;
			$this->Shop->save($shop);

			$options = [
				"subject" 	=> "Bienvenido a ZÍRO",
				"to"   		=> $userInfo["User"]["email"],
				"vars" 	    => $varsEmail,
				"template"	=> "new_user_admin",
			];

			$this->sendMail($options);
		}
	}

	public function delete_data($id){
		$this->autoRender = false;
		$idShop = $this->decrypt($id);
		if (!$this->Shop->exists($idShop)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$shop = $this->Shop->findById($idShop);

		if ($shop["Shop"]["state"] == 1) {
			$this->Shop->User->updateAll(["User.state" => 0 ], ["User.shop_id" =>$idShop]);
			$idsCommerces = $this->Shop->ShopCommerce->find("list",["fields"=>["id","id"],"conditions" => ["ShopCommerce.shop_id" => $idShop] ]);
			$this->Shop->User->updateAll(["User.state" => 0 ], ["User.shop_commerce_id" =>$idShop]);
			$shop["Shop"]["state"] = 0;
		}else{
			$this->Shop->User->updateAll(["User.state" => 1 ], ["User.shop_id" =>$idShop]);
			$shop["Shop"]["state"] = 1;
		}

		$this->Shop->save($shop["Shop"]);
		$this->Session->setFlash(__('Cambio de estado correctamente'), 'flash_success');
		$this->redirect(["action"=>"index"]);
	}

	public function change_state($id){
		$this->autoRender = false;
		$idShop = $this->decrypt($id);
		if (!$this->Shop->exists($idShop)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$shop = $this->Shop->findById($idShop);

		$data = array(
			"ShopPayment" => [
				"shop_id"			  => $shop["Shop"]["id"],
				"date" 				  => $shop["Shop"]["created"],
				"outstanding_balance" => 0,
				"state" 			  => 1,
				"payment_value"		  => $shop["Shop"]["payment_total"],
				"payment_date"		  => date("Y-m-d H:i:s")
			]
		);

		$this->Shop->ShopPayment->create();
		$this->Shop->ShopPayment->save($data);
		$shop["Shop"]["state"] = 1;
		$shop["User"]["state"] = 1;
		unset($shop["User"]["password"]);

		if(!$this->Shop->save($shop["Shop"])){
			$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
		}

		$this->Shop->User->save($shop["User"]);

		$options = [
			"subject" 	=> "Usuario activado correctamente",
			"to"   		=> $shop["User"]["email"],
			"vars" 	    => [],
			"template"	=> "user_active_shop",
		];

		$this->sendMail($options);
		$this->Session->setFlash(__('El proveedor se activo correctamente'), 'flash_success');
		$this->redirect(["action"=>"index"]);
	}

	public function verifyNit(){
		$this->autoRender = false;
		if($this->request->is("ajax")){
			$nit 	= $this->request->query["data"]["Shop"]["nit"];
			$allNit = $this->Shop->find("count",["conditions"=>["nit"=>$nit]]);
			if($allNit == 0 ){
				header("HTTP/1.1 200 Ok");
			}else{
				throw new NotFoundException(__('Página no encontrada'));
			}
		}
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->Shop->id = $id;
		if (!$this->Shop->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			/*if ($this->Shop->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}*/

			$this->request->data["Shop"]["products_lists"] 	= implode(",", $this->request->data["Shop"]["products_lists"]);

			if ($this->request->data["Shop"]["id"]) {

				$data = array(
					'id'=> $this->request->data["Shop"]["id"],
					'nit'=> $this->request->data["Shop"]["nit"],
					'social_reason'=> $this->request->data["Shop"]["social_reason"],
					'guild'=> $this->request->data["Shop"]["guild"],
					'department'=> $this->request->data["Shop"]["department"],
					'city'=> $this->request->data["Shop"]["city"],
					'address'=> $this->request->data["Shop"]["address"],
					'phone'=> $this->request->data["Shop"]["phone"],
					'identification_admin'=> $this->request->data["Shop"]["identification_admin"],
					'name_admin'=> $this->request->data["Shop"]["name_admin"],
					'email'=> $this->request->data["Shop"]["email"],
					'cellpone_admin'=> $this->request->data["Shop"]["cellpone_admin"],
					'identification_account'=> $this->request->data["Shop"]["identification_account"],
					'account_type'=> $this->request->data["Shop"]["account_type"],
					'account_bank'=> $this->request->data["Shop"]["account_bank"],
					'account_number'=> $this->request->data["Shop"]["account_number"],
					'services_list'=> $this->request->data["Shop"]["services_list"],
					'products_lists'=> $this->request->data["Shop"]["products_lists"],
					'adviser'=> $this->request->data["Shop"]["adviser"],
					'plan'=> $this->request->data["Shop"]["plan"],
					'payment_type'=> $this->request->data["Shop"]["payment_type"],
					'number_commerces'=> $this->request->data["Shop"]["number_commerces"],
					'cost_min'=> $this->request->data["Shop"]["cost_min"],
					'cost_max'=> $this->request->data["Shop"]["cost_max"],
					//'payment_total'=> $this->request->data["Shop"]["payment_total"],
					//'payment_total'=> 0,
					'chamber_commerce_file'=> $this->request->data["Shop"]["chamber_commerce_file"],
					'rut_file'=> $this->request->data["Shop"]["rut_file"],
					'image_admin'=> $this->request->data["Shop"]["image_admin"],
					'identification_up_file'=> $this->request->data["Shop"]["identification_up_file"],
					'identification_down_file'=> $this->request->data["Shop"]["identification_down_file"],
					'credits_line_id'=> $this->request->data["Shop"]["credits_line_id"],
				);

				$this->Shop->id = $this->request->data["Shop"]["id"];
				//$this->Shop->set($this->request->data["Shop"]);
				$this->Shop->set($data);
				$this->Shop->save();

				$shop_id = $this->Shop->id;
				if(!isset($this->request->data["Shop"]["id"])){
					foreach ($this->request->data["ShopReference"] as $key => $value) {
						$value["shop_id"] = $shop_id;
						$this->Shop->ShopReference->create();
						$this->Shop->ShopReference->save($value);
					}
					$this->createUserAdmin($this->request->data,$shop_id);
				}

				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));

			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}

		} else {
			$conditions = array('Shop.' . $this->Shop->primaryKey => $id);
			$this->request->data = $this->Shop->find('first', compact('conditions'));
		}
		$users = $this->Shop->User->find('list',["conditions" => ["User.role" => [8]]]);
		$credits_line = $this->Shop->CreditsLine->find('list');
		$this->set(compact('users','credits_line'));
	}
}
