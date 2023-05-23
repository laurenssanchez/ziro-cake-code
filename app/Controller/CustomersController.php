<?php

require_once '../Vendor/spreadsheet/vendor/autoload.php';

use Cake\Log\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

//use Cake\ORM\TableRegistry;
set_time_limit(0);

App::uses('AppController', 'Controller');
date_default_timezone_set('America/Bogota');

/**
 * Customers Controller
 *
 * @property Customer $Customer
 * @property PaginatorComponent $Paginator
 */
class CustomersController extends AppController {

	public $components = array('Paginator');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('validate_setp_one');
	}

	public function validate_setp_one(){
		$this->autoRender = false;
		var_dump($this->request->data);
		die;
	}

	public function download_img(){
		$this->autoRender = false;
		$this->Customer->recursive = -1;
		$customer = $this->Customer->findById($this->request->data["id"]);
		$customer = $customer["Customer"];

		if (!empty($customer["document_file_up"])) {
        	$fileData = file_get_contents($customer["url_files"].$customer["document_file_up"]);
        	file_put_contents(WWW_ROOT."files".DS."customers".DS.$customer["document_file_up"],$fileData);
        }
        if (!empty($customer["document_file_down"])) {
        	$fileData = file_get_contents($customer["url_files"].$customer["document_file_down"]);
        	file_put_contents(WWW_ROOT."files".DS."customers".DS.$customer["document_file_down"],$fileData);
        }
        if (!empty($customer["image_file"])) {
        	$fileData = file_get_contents($customer["url_files"].$customer["image_file"]);
        	file_put_contents(WWW_ROOT."files".DS."customers".DS.$customer["image_file"],$fileData);
        }
	}

	public function edit_info(){
		$this->autoRender = false;
		$this->loadModel("NotesCustomer");
		$this->loadModel("User");

		$this->Customer->save($this->request->data["Customer"]);

		$this->Customer->CustomersPhone->save($this->request->data["CustomersPhone"]);
		$customerId=$this->request->data["Customer"]["id"];
		$existsAdress = $this->Customer->CustomersAddress->field("id",["customer_id" => $customerId]);

		if($existsAdress){
			$this->request->data["CustomersAddress"]['id']=$existsAdress;
		} else {
			$this->request->data["CustomersAddress"]['customer_id']=$this->request->data["Customer"]["id"];
		}
		$this->Customer->CustomersAddress->save($this->request->data["CustomersAddress"]);

		// if (isset($this->request->data["CustomersReference"])) {
		// 	$this->Customer->CustomersReference->save($this->request->data["CustomersReference"][0]);
		// 	$this->Customer->CustomersReference->save($this->request->data["CustomersReference"][1]);
		// 	$this->Customer->CustomersReference->save($this->request->data["CustomersReference"][2]);
		// }

		$dataNote = [
			"NotesCustomer" => [
				"id" => null,
				"user_id" => AuthComponent::user("id"),
				"note" => "Se edito la información del usuario",
				"credits_request_id" => $this->request->data["id"]
			]
		];

		$this->NotesCustomer->create();
		$this->NotesCustomer->save($dataNote);

		if(!empty($this->request->data["Customer"]["email"])){
			$this->User->recursive = -1;
			$user = $this->User->findByCustomerId($this->request->data["Customer"]["id"]);
			if (!empty($user)) {
				$user["User"]["email"] = $this->request->data["Customer"]["email"];
				unset($user["User"]["password"]);
				$this->User->save($user);
			}
		}


	}

	public function index() {
		$conditions = $this->Customer->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->Customer->recursive = 1;
		$this->Paginator->settings = array('order'=>array('Customer.modified'=>'DESC'));
		$customers = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('customers'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->Customer->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->Customer->recursive = 0;
		$conditions = array('Customer.' . $this->Customer->primaryKey => $id);
		$this->set('customer', $this->Customer->find('first', compact('conditions')));
	}

	public function get_data_customers_old(){
		$this->layout = false;
		$this->Customer->recursive = 2;
		$customer = $this->Customer->findById($this->decrypt($this->request->data["customer"]));
		if(isset($this->request->data["request"])){
			$this->loadModel("CreditsRequest");
			$request = $this->CreditsRequest->findById($this->decrypt($this->request->data["request"]));
			$this->set("request",$request);

			$this->loadModel("NotesCustomer");

			$notes = $this->NotesCustomer->findAllByCreditsRequestId($this->decrypt($this->request->data["request"]));
			$this->set("notes",$notes);

		}
		$this->loadModel("Credit");
		$conditions 	= ["Credit.credits_request_id != " => 0,"Credit.customer_id" => $this->decrypt($this->request->data["customer"])];
		$credits 		= $this->Credit->find("all",["conditions"=>$conditions]);

		if(!empty($credits)){
			foreach ($credits as $key => $value) {
				$credits[$key]["saldos"] = $this->calculateTotales($value["Credit"],$value["CreditsPlan"]);
				$totalDebts 			 = 0;
				foreach ($value["CreditsPlan"] as $keyData => $valueData) {
					if(!is_null($valueData["date_debt"])){
						$totalDebts++;
					}
				}

				$credits[$key]["debts"] = $totalDebts;
			}
		}

		$this->set("customer",$customer);
		$this->set("credits",$credits);
	}

	public function get_data_customers(){
		$this->layout = false;
		$this->Customer->recursive = 2;
		$customer = $this->Customer->findById($this->decrypt($this->request->data["customer"]));
		if(isset($this->request->data["request"])){
			$this->loadModel("CreditsRequest");
			$request = $this->CreditsRequest->findById($this->decrypt($this->request->data["request"]));
			$this->set("request",$request);

			$this->loadModel("NotesCustomer");
			$this->loadModel("Document");

			$notes = $this->NotesCustomer->findAllByCreditsRequestId($this->decrypt($this->request->data["request"]));

			if (empty($request["CreditsRequest"]["empresa_id"])) {
				$documents = $this->Document->findAllByCreditsRequestIdAndState($this->decrypt($this->request->data["request"]),1);
			}else{
				$documents = $this->Document->findAllByCreditsRequestIdAndStateAndType($this->decrypt($this->request->data["request"]),1,[0,2]);
			}

			$validateDocumentForm = $this->Document->find("count",["conditions" => ["Document.state" => 1, "Document.credits_request_id" => $this->decrypt($this->request->data["request"]), "Document.state_request" => $request["CreditsRequest"]["state"] ] ]);

			$this->set("notes",$notes);
			$this->set("documents",$documents);
			$this->set("validateDocumentForm",$validateDocumentForm);

		}
		$this->loadModel("Credit");
		$conditions 	= ["Credit.credits_request_id != " => 0,"Credit.customer_id" => $this->decrypt($this->request->data["customer"])];
		$credits 		= $this->Credit->find("all",["conditions"=>$conditions]);

		if(!empty($credits)){
			foreach ($credits as $key => $value) {
				$credits[$key]["saldos"] =  $this->calculateTotales($value["Credit"],$value["CreditsPlan"]);
				$totalDebts 			 = 0;
				foreach ($value["CreditsPlan"] as $keyData => $valueData) {
					if(!is_null($valueData["date_debt"])){
						$totalDebts++;
					}
				}

				$credits[$key]["debts"] = $totalDebts;
			}
		}

		$this->set("customer",$customer);
		$this->set("credits",$credits);
	}

	private function calculateTotales($credit,$quotes){
		$this->loadModel("Credit");
		$quotes = $this->Credit->CreditsPlan->getDataQuotes($quotes, $credit["last_payment_date"], $credit["debt_rate"], $credit["id"]);

        $capitalTotal = $othersValue = $interesValue = 0;

        $totalDebt = 0;
        $totalCredit = 0;
        $totalQuoteDebt = 0;
        $totalCanceladas = 0;
        $dias = 0;

        foreach ($quotes as $keyQt => $valueQt) {

            $capitalTotal = floatval($valueQt["capital_value"] - $valueQt["capital_payment"]);
            $othersValue = floatval($valueQt["others_value"] - $valueQt["others_payment"]);
            $othersValue = floatval($valueQt["interest_value"] - $valueQt["interest_payment"]); //floatval($valueQt["interest_value"]-$valueQt["interest_payment"]);

            $totalCredit += floatVal($capitalTotal + $othersValue + $interesValue + $valueQt["debt_value"] + $valueQt["debt_honor"]);

            if ($valueQt["state"] == 0) {
            	$totalDebt += floatVal($valueQt["debt_value"] + $valueQt["debt_honor"]);
            }

            if ($valueQt["debt_value"] > 0 || $valueQt["debt_honor"] > 0) {
                if ($valueQt["state"] == 0) {
                    $totalQuoteDebt++;
                    $dias += $this->getDaysMoraCalculo($valueQt);
                }
            }

            if ($valueQt["state"] == 1) {
                $totalCanceladas++;
            }

        }

        return ["saldo" => $this->Credit->CreditsPlan->getCreditDeuda($credit["id"],null,null,true), "debt" => $totalDebt, "totalDebt" => $totalQuoteDebt, "totalCanceladas" => $totalCanceladas, "dias" => $dias];

	}

	public function add_note(){
		$this->autoRender = false;
		$this->loadModel("NotesCustomer");
		if(!empty($this->request->data["NotesCustomer"]["note"])){
			$this->NotesCustomer->create();
			$this->NotesCustomer->save($this->request->data);
		}
	}

	public function add_document(){
		$this->autoRender = false;
		$this->loadModel("Document");
		if(!empty($this->request->data["Document"]["file"])){
			$this->Document->create();
			$this->Document->save($this->request->data);
		}
	}

	public function delete_document($id){
		$this->autoRender = false;
		$id 						= 	$this->decrypt($id);
		$this->loadModel('Document');
		$this->Document->recursive 	= 	-1;
		$item 						=   $this->Document->findById($id);

		if(empty($item)){
		   $this->Session->setFlash(__('El borrado no fue realizado, el elemento seleccionado no existe.'), 'flash_error');
		}else{

			$this->Document->id = $id;
			if($this->Document->delete($id)){

				@$file = new File('{ROOT}{DS}webroot{DS}files{DS}documents{DS}'.$item['file']);
				@$file->delete();

				$this->Session->setFlash(__('El documento fue borrado fue realizado correctamente'), 'flash_success');
			}else{
				$this->Session->setFlash(__('El documento fue borrado no fue realizado'), 'flash_error');
			}
		}
		if (!$this->request->is("post")) {
			$this->redirect(array('action' => 'index_lista',"controller" => "credits_requests"));
		}
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Customer->create();
			if ($this->Customer->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$users = $this->Customer->User->find('list');

		$this->set(compact('users'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->Customer->id = $id;
		if (!$this->Customer->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Customer->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('Customer.' . $this->Customer->primaryKey => $id);
			$this->request->data = $this->Customer->find('first', compact('conditions'));
		}
		$users = $this->Customer->User->find('list');
		$this->set(compact('users'));
	}

	public function export() {
		// Obtener los datos de los clientes y sus teléfonos
		$customers = $this->Customer->find('all', [
			'contain' => ['CustomerPhone']
		]);

		// Crear el archivo Excel
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// Agregar encabezados de columna
		$sheet->setCellValue('A1', 'Nombre');
		$sheet->setCellValue('B1', 'Apellido');
		$sheet->setCellValue('C1', 'Identificación');
		$sheet->setCellValue('D1', 'Email');
		$sheet->setCellValue('E1', 'Nit');
		$sheet->setCellValue('F1', 'Nombre negocio');
		$sheet->setCellValue('G1', 'Teléfono');

		// Agregar datos de clientes y sus teléfonos
		$row = 2;
		foreach ($customers as $customer) {
			$sheet->setCellValue('A'.$row, $customer['Customer']['name']);
			$sheet->setCellValue('B'.$row, $customer['Customer']['last_name']);
			$sheet->setCellValue('C'.$row, $customer['Customer']['identification']);
			$sheet->setCellValue('D'.$row, $customer['Customer']['email']);
			$sheet->setCellValue('E'.$row, $customer['Customer']['nit']);
			$sheet->setCellValue('F'.$row, $customer['Customer']['buss_name']);


			foreach ($customer['CustomersPhone'] as $phone) {
				$sheet->setCellValue('G'.$row, $phone['phone_number']);
				$row++;
			}
		}

		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);



        $spreadsheet->getActiveSheet()->setTitle('Clientes Ziro');
        $spreadsheet->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $name = "files/clientes_ziro" . time() . ".xlsx";
        $writer->save($name);
		$url = Router::url("/", true) . $name;
		$this->redirect($url);
        die;
	}

}
