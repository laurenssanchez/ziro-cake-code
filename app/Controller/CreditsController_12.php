<?php

require_once '../Vendor/spreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;


set_time_limit(0);

App::uses('AppController', 'Controller');
// date_default_timezone_set('America/Bogota');

class CreditsController extends AppController {


	public $components = array('Paginator');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow( 'search_user','set_debts','get_credit_customer','get_data_payment',"payment_web","payment_web_response","reorganice" );
	}

	public function reorganice(){
		$this->autoRender = false;
		$credits = $this->Credit->find("list",["conditions" => ["Credit.credits_request_id != "=>0,"Credit.debt" => 1,],"fields" => ["id", "id"] ]);
		if (!empty($credits)) {
			foreach ($credits as $key => $value) {
				$cuotas = $this->Credit->CreditsPlan->getCuotesInformation($value,null,0);
				$this->validate_cuotes_data($cuotas);
			}
		}else{
			die;
		}
	}

	private function validate_cuotes_data($cuotas){
		$this->loadModel("CreditsRequest");

		foreach ($cuotas as $keyCuota => $valueCuota) {
			if (intVal($valueCuota["CreditsPlan"]["debt_value"]) > 0 ) {
				$customer = $this->Credit->field("customer_id",["id"=>$valueCuota["CreditsPlan"]["credit_id"]]);
				$this->CreditsRequest->CreditLimit->updateAll(
					["CreditLimit.active" => 0],
					[
						"CreditLimit.state"  => [1,3,4,5],
						"CreditLimit.customer_id" => $customer
					]
				);

				return true;
			}
		}
	}

	public function centrales() {
		if(!isset($this->request->query["ini"])){
			$fechaInicioReporte = date("Y-m-d");
		}else{
			$fechaInicioReporte = $this->request->query["ini"];
		}

		if(!isset($this->request->query["end"])){
			$fechaFinReporte = date("Y-m-d");
		}else{
			$fechaFinReporte = $this->request->query["end"];
		}


		if($this->request->is("post")){
			$conditions 	= ["Credit.credits_request_id != " => 0];
			$data 			= $this->request->data;

			if (isset($this->request->data["ini"]) && isset($this->request->data["end"])) {
				$conditions["DATE(Credit.created) >=" ] = $this->request->data["ini"];
				$conditions["DATE(Credit.created) <=" ] = $this->request->data["end"];
				$this->set("fechas",true);
			}

			if ($data["Credit"]["type"] == 1) {
				$this->datacredito($data,$conditions);
			}else{
				$this->procredito($data,$conditions);
			}
		}

		$this->set(compact("fechaInicioReporte","fechaFinReporte"));
	}

	private function procredito($data,$conditions){

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$spreadsheet->getProperties()->setCreator('CREDISHOP')
			        ->setLastModifiedBy('CREDISHOP')
			        ->setTitle('PROCREDITO')
			        ->setSubject('PROCREDITO')
			        ->setDescription('PROCREDITO ZÍRO')
			        ->setKeywords('PROCREDITO')
			        ->setCategory('PROCREDITO');


		$spreadsheet->setActiveSheetIndex(0)
			        ->setCellValue('A1', 'Tipo Documento Cliente')
			        ->setCellValue('B1', 'Número Documento Cliente')
			        ->setCellValue('C1', 'Nombre Completo')
			        ->setCellValue('D1', 'Tipo Garante')
			        ->setCellValue('E1', 'Sucursal Obligación')
			        ->setCellValue('F1', 'Tipo Obligación')
			        ->setCellValue('G1', 'Número Obligación')
			        ->setCellValue('H1', 'Tipo Contrato')
			        ->setCellValue('I1', 'Refinanciacion / Restructuración (Campo Opcional)')
			        ->setCellValue('J1', 'Fecha Obligación')
			        ->setCellValue('K1', 'Periodicidad Pago (Dias)')
			        ->setCellValue('L1', 'Valor Obligación')
			        ->setCellValue('M1', 'Cargo Fijo  (Campo solo para Contratos)')
			        ->setCellValue('N1', 'Saldo a Fecha de Corte')
			        ->setCellValue('O1', 'Saldo en Mora a Fecha de Corte')
			        ->setCellValue('P1', 'Cuotas Pactadas')
			        ->setCellValue('Q1', 'Cuotas Pagadas')
			        ->setCellValue('R1', 'Cuotas en Mora')
			        ->setCellValue('S1', 'Valor Cuota')
			        ->setCellValue('T1', 'Valor Pagado a Fecha de Corte')
			        ->setCellValue('U1', 'Dias en Mora (Calculado por la Plantilla)')
			        ->setCellValue('V1', 'Fecha de Ultimo Pago o Abono')
			        ->setCellValue('W1', 'Fecha de Vencimiento')
			        ->setCellValue('X1', 'Cupo Total Aprobado / Cupo Crédito')
			        ->setCellValue('Y1', 'Cupo Utilizado')
			        ->setCellValue('Z1', 'Termino del Contrato')
			        ->setCellValue('AA1', 'Meses Celebrados (Vigencia), Aplica solo para contractos')
			        ->setCellValue('AB1', 'Meses Clausula Permanencia,  Aplica solo para contractos')
			        ->setCellValue('AC1', 'Motivo Pago')
			        ->setCellValue('AD1', 'Situación o Estado del Titular(Campo Obligatorio, cuando Tipo de Contrato sea 4)')
			        ->setCellValue('AE1', 'País')
			        ->setCellValue('AF1', 'Departamento')
			        ->setCellValue('AG1', 'Ciudad')
			        ->setCellValue('AH1', 'Tipo Dirección')
			        ->setCellValue('AI1', 'Dirección')
			        ->setCellValue('AJ1', 'Tipo Teléfono')
			        ->setCellValue('AK1', 'Teléfono')
			        ->setCellValue('AL1', 'Extensión (Campo Opcional)')
			        ->setCellValue('AM1', 'Correo Electrónico (Campo Opcional)')
			        ->setCellValue('AN1', 'Tipo Documento Soporte de la Obligación Referenciada (Campo Opcional)')
			        ->setCellValue('AO1', 'Número Obligación Referenciada (Campo Opcional)');


		try {
			$credits =  $this->Credit->find("all",["conditions" => $conditions, ]);

		} catch (Exception $e) {
			$credits = [];
		}

		if (!empty($credits)) {
			$i = 2;
			foreach ($credits as $key => $value) {
				$this->Credit->Customer->unBindModel(["hasMany"=>["Credit","CustomersReference","CreditsRequest","User"]]);
				$value["Customer"] = $this->Credit->Customer->findById($value["Customer"]["id"]);
				$value["saldos"] = $this->calculateTotales($value["Credit"],$value["CreditsPlan"]);

				$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, "1" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $value["Customer"]["Customer"]["identification"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $value["Customer"]["Customer"]["name"]." ".$value["Customer"]["Customer"]["last_name"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, "1" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, "00" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, "15" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, $value["Credit"]["credits_request_id"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, "3" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, "" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$i, date("d/m/Y",strtotime($value["Credit"]["created"])));
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$i, $value["Credit"]["type"] == "1" ? "30" : "15");
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$i, $value["CreditsRequest"]["value_disbursed"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('M'.$i, "" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('N'.$i, $value["Credit"]["value_pending"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, $value["saldos"]["debt"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, $value["Credit"]["number_fee"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('Q'.$i, $value["saldos"]["totalCanceladas"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('R'.$i, $value["saldos"]["totalDebt"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('S'.$i, $value["Credit"]["quota_value"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('T'.$i, $value["CreditsRequest"]["value_disbursed"]-$value["Credit"]["value_pending"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('U'.$i, $value["saldos"]["dias"]);
				$ultimoPago = is_null($value["Credit"]["last_payment_date"]) ? null : date("d/m/Y",strtotime($value["Credit"]["last_payment_date"]));
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('V'.$i, $ultimoPago);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('W'.$i, date("d/m/Y",strtotime($value["Credit"]["deadline"])));
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('X'.$i, $value["CreditsRequest"]["value_approve"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('Y'.$i, $value["CreditsRequest"]["value_disbursed"]);

				$spreadsheet->setActiveSheetIndex(0)->setCellValue('Z'.$i, $value["Customer"]["Customer"]["type_contract"] == "Indefinido" ? "1" : "0" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('AA'.$i, "" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('AB'.$i, "" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('AC'.$i, $value["Credit"]["state"] == 1 ? "0" : "" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('AD'.$i, "" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('AE'.$i, "COLOMBIA" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('AF'.$i, $this->getDepartMent($value["Customer"]["CustomersAddress"]["0"]["address_city"]) );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('AG'.$i, strtoupper($value["Customer"]["CustomersAddress"]["0"]["address_city"]));
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('AH'.$i, "1" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('AI'.$i, strtoupper($value["Customer"]["CustomersAddress"]["0"]["address"]));
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('AJ'.$i, "2" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('AK'.$i, strtoupper($value["Customer"]["CustomersPhone"]["0"]["phone_number"]));
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('AL'.$i, "2" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('AM'.$i, $value["Customer"]["Customer"]["email"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('AN'.$i, "" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('AO'.$i, "" );

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
		$spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AJ')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AL')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AM')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AN')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AO')->setAutoSize(true);

		$spreadsheet->getActiveSheet()->setTitle('Procredito');
		$spreadsheet->getActiveSheet()->getStyle('A1:AO1')->getFont()->setBold(true);
		//$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

		$writer = 	IOFactory::createWriter($spreadsheet, 'Xlsx');
		$name 	=	"files/procredito_".time().".xlsx";
		$writer->save($name);

		$url =  Router::url("/",true).$name;
		$this->redirect($url);
	}

	private function getDepartMent($ciudad){
		$datos = Configure::read("CIUDADES");

		foreach ($datos as $departamento => $ciudades) {
			foreach ($ciudades as $key => $value) {
				if(strtolower($ciudad) == strtolower($value)){
					return $departamento;
				}
			}
		}
	}

	private function datacredito($data,$conditions){

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$spreadsheet->getProperties()->setCreator('CREDISHOP')
			        ->setLastModifiedBy('CREDISHOP')
			        ->setTitle('DATACREDITO')
			        ->setSubject('DATACREDITO')
			        ->setDescription('DATACREDITO ZÍRO')
			        ->setKeywords('DATACREDITO')
			        ->setCategory('DATACREDITO');


		$spreadsheet->setActiveSheetIndex(0)
			        ->setCellValue('A1', 'TIPO DE IDENTIFICACION')
			        ->setCellValue('B1', 'NUMERO DE IDENTIFICACION')
			        ->setCellValue('C1', 'NOMBRE COMPLETO')
			        ->setCellValue('D1', 'NUMERO DE LA CUENTA U OBLIGACION')
			        ->setCellValue('E1', 'FECHA APERTURA')
			        ->setCellValue('F1', 'FECHA VENCIMIENTO')
			        ->setCellValue('G1', 'RESPONSABLE')
			        ->setCellValue('H1', 'NOVEDAD')
			        ->setCellValue('I1', 'ESTADO ORIGEN DE LA CUENTA')
			        ->setCellValue('J1', 'VALOR INICIAL')
			        ->setCellValue('K1', 'VALOR SALDO DEUDA')
			        ->setCellValue('L1', 'VALOR DISPONIBLE')
			        ->setCellValue('M1', 'V. CUOTA MENSUAL')
			        ->setCellValue('N1', 'VALOR SALDO MORA')
			        ->setCellValue('O1', 'TOTAL CUOTAS')
			        ->setCellValue('P1', 'CUOTAS CANCELADAS')
			        ->setCellValue('Q1', 'CUOTAS EN MORA')
			        ->setCellValue('R1', 'FECHA LIMITE DE PAGO')
			        ->setCellValue('S1', 'FECHA DE PAGO')
			        ->setCellValue('T1', 'CIUDAD CORRESPONDENCIA')
			        ->setCellValue('U1', 'DIRECCION DE CORRESPONDENCIA')
			        ->setCellValue('V1', 'CORREO ELECTRONICO')
			        ->setCellValue('W1', 'CELULAR');


		try {
			$credits =  $this->Credit->find("all",["conditions" => $conditions, ]);

		} catch (Exception $e) {
			$credits = [];
		}

		if (!empty($credits)) {
			$i = 2;
			foreach ($credits as $key => $value) {
				$this->Credit->Customer->unBindModel(["hasMany"=>["Credit","CustomersReference","CreditsRequest","User"]]);
				$value["Customer"] = $this->Credit->Customer->findById($value["Customer"]["id"]);
				$value["saldos"] = $this->calculateTotales($value["Credit"],$value["CreditsPlan"]);

				$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, "1" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $value["Customer"]["Customer"]["identification"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $value["Customer"]["Customer"]["name"]." ".$value["Customer"]["Customer"]["last_name"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $value["Credit"]["credits_request_id"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, date("Ymd",strtotime($value["Credit"]["created"])));
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, date("Ymd",strtotime($value["Credit"]["deadline"])));
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, "00" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, $this->getNovedadAndData($value["Credit"],$value["saldos"]) );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, "0" );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$i, $value["CreditsRequest"]["value_disbursed"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$i, $value["Credit"]["value_pending"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$i, $value["CreditsRequest"]["value_disbursed"]-$value["Credit"]["value_pending"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('M'.$i, $value["Credit"]["quota_value"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('N'.$i, $value["saldos"]["debt"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, $value["Credit"]["number_fee"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, $value["saldos"]["totalCanceladas"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('Q'.$i, $value["saldos"]["totalDebt"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('R'.$i, date("Ymd",strtotime($value["Credit"]["deadline"])));
				$lastDate = is_null($value["Credit"]["last_payment_date"]) ? null : date("Ymd",strtotime($value["Credit"]["last_payment_date"]));
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('S'.$i, $lastDate);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('T'.$i, strtoupper($value["Customer"]["CustomersAddress"]["0"]["address_city"]));
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('U'.$i, strtoupper($value["Customer"]["CustomersAddress"]["0"]["address"]));
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('V'.$i, $value["Customer"]["Customer"]["email"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('W'.$i, strtoupper($value["Customer"]["CustomersPhone"]["0"]["phone_number"]));

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
		$spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);

		$spreadsheet->getActiveSheet()->setTitle('Datacredito');
		$spreadsheet->getActiveSheet()->getStyle('A1:W1')->getFont()->setBold(true);
		//$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

		$writer = 	IOFactory::createWriter($spreadsheet, 'Xlsx');
		$name 	=	"files/datacredito_".time().".xlsx";
		$writer->save($name);

		$url =  Router::url("/",true).$name;
		$this->redirect($url);
	}

	private function getNovedadAndData($credit,$saldos){

		$code = "01";

		if ($credit["juridico"] == 1) {
			$code = "13";
		}elseif($credit["state"] == 1){
			$code = "05";
		}elseif($saldos["debt"] > 0 && $saldos["dias"] > 0 && $saldos["dias"] <= 30){
			$code = "06";
		}elseif($saldos["debt"] > 0 && $saldos["dias"] > 31 && $saldos["dias"] <= 60){
			$code = "07";
		}elseif($saldos["debt"] > 0 && $saldos["dias"] > 61 && $saldos["dias"] <= 90){
			$code = "08";
		}elseif($saldos["debt"] > 0 && $saldos["dias"] > 91){
			$code = "09";
		}elseif($credit["state"] == 0){
			$code = "01";
		}

		return $code;
	}

	public function set_debts(){
		$this->autoRender = false;
		$this->Credit->CreditsPlan->validateSaldo();
	}

	public function cartera(){
		if(!isset($this->request->query["tab"])){
			$this->redirect(["action"=>"intereses","?" => ["tab"=>1]]);
		}
		switch ($this->request->query["tab"]) {
			case '1':
				$this->credOtorgados();
				break;
			case '2':
				$this->credVigentes();
				break;
			case '3':
				$this->credCancelados();
				break;
			case '4':
				$this->credVigMora();
				break;
		}
		$this->set("tab",$this->request->query["tab"]);
	}

	public function cartera_export() {
		$this->autoRender = false;
		if(!isset($this->request->query["tab"])){
			$this->redirect(["action"=>"intereses","?" => ["tab"=>1]]);
		}

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$spreadsheet->getProperties()->setCreator('CREDISHOP')
			        ->setLastModifiedBy('CREDISHOP')
			        ->setTitle('CARTERA')
			        ->setSubject('CARTERA')
			        ->setDescription('CARTERA Credishop')
			        ->setKeywords('CARTERA')
			        ->setCategory('CARTERA');

		switch ($this->request->query["tab"]) {
			case '1':
				$this->credOtorgadosExport($spreadsheet);
				break;
			case '2':
				$this->credVigentesExport($spreadsheet);
				break;
			case '3':
				$this->credCanceladosExport($spreadsheet);
				break;
			case '4':
				$this->credVigMoraExport($spreadsheet);
				break;
		}
	}

	private function credOtorgados(){

		$conditions 	= ["Credit.credits_request_id != " => 0];
		$totalCartera	= 0;

		if(!isset($this->request->query["ini"])){
			$fechaInicioReporte = date("Y-m-d");
		}else{
			$fechaInicioReporte = $this->request->query["ini"];
		}

		if(!isset($this->request->query["end"])){
			$fechaFinReporte = date("Y-m-d");
		}else{
			$fechaFinReporte = $this->request->query["end"];
		}

		if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
			$conditions["DATE(Credit.created) >=" ] = $this->request->query["ini"];
			$conditions["DATE(Credit.created) <=" ] = $this->request->query["end"];
			$this->set("fechas",true);
		}

		$query = $this->request->query;
		if(isset($query["range"]) && !empty($query["range"])){
			$valuesRange = explode(";", $query["range"]);
			if(count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])){

				$conditions["Credit.value_request >= "] = $valuesRange[0];
				$conditions["Credit.value_request <= "] = $valuesRange[1];

				$min = $valuesRange[0];
				$max = $valuesRange[1];

			}else{
				$conditions["Credit.id"] = null;
			}
		}else{
			$min = 1;
			$max = 1000000;
		}

		if(isset($query['commerce']) && !empty($query['commerce']) ){
			$this->loadModel("ShopCommerce");
			$shopCommerce 	= $this->ShopCommerce->findByCode($query['commerce']);
			if(!empty($shopCommerce)){
				$creditsRequests = Set::extract($shopCommerce["CreditsRequest"],"{n}.credit_id");
				foreach($creditsRequests as $clave=>$valor){
					if(empty($valor)) unset($creditsRequests[$clave]);
				}
				$conditions["Credit.id"] = $creditsRequests;
			}else{
				$conditions["Credit.id"] = null;
			}
			$this->Set("commerce",$query['commerce']);
		}


		try {
			$this->Paginator->settings = ["conditions" => $conditions, ];
			$credits = $this->Paginator->paginate();

			if (!empty($credits)) {
				$this->loadModel("ShopCommerce");

				foreach ($credits as $key => $value) {
					try {
						$credits[$key]["Comercio"] 	= $this->ShopCommerce->findById($value["CreditsRequest"]['shop_commerce_id']);

					} catch (Exception $e) {
						$credits[$key]["Comercio"] 	= [];
					}
					$credits[$key]["Customer"] = $this->Credit->Customer->findById($value["Customer"]["id"]);
				}
			}

			$totalCartera = $this->Credit->find("first",["conditions" => $conditions,"fields" => ["SUM(value_request) as total"] ]);

			if(!empty($totalCartera)){
				$totalCartera = $totalCartera["0"]["total"];
			}

		} catch (Exception $e) {
			$credits = [];
		}


		$this->set(compact("fechaInicioReporte","fechaFinReporte","min","max","credits","totalCartera"));
	}

	private function credOtorgadosExport($spreadsheet){

		$spreadsheet->setActiveSheetIndex(0)
			        ->setCellValue('A1', 'FECHA APROBADO')
			        ->setCellValue('B1', 'NÚMERO DE OBLIGACIÓN')
			        ->setCellValue('C1', 'CÉDULA')
			        ->setCellValue('D1', 'NOMBRE COMPLETO')
			        ->setCellValue('E1', 'TELÉFONO')
			        ->setCellValue('F1', 'DIRECCIÓN')
			        ->setCellValue('G1', 'VALOR APROBADO')
			        ->setCellValue('H1', 'ESTADO DEL CRÉDITO')
			        ->setCellValue('I1', 'PROVEEDOR');

		$conditions 	= ["Credit.credits_request_id != " => 0];

		if(!isset($this->request->query["ini"])){
			$fechaInicioReporte = date("Y-m-d");
		}else{
			$fechaInicioReporte = $this->request->query["ini"];
		}

		if(!isset($this->request->query["end"])){
			$fechaFinReporte = date("Y-m-d");
		}else{
			$fechaFinReporte = $this->request->query["end"];
		}

		if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
			$conditions["DATE(Credit.created) >=" ] = $this->request->query["ini"];
			$conditions["DATE(Credit.created) <=" ] = $this->request->query["end"];
			$this->set("fechas",true);
		}
		$query = $this->request->query;

		if(isset($query["range"]) && !empty($query["range"])){
			$valuesRange = explode(";", $query["range"]);
			if(count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])){

				$conditions["Credit.value_request >= "] = $valuesRange[0];
				$conditions["Credit.value_request <= "] = $valuesRange[1];

				$min = $valuesRange[0];
				$max = $valuesRange[1];

			}else{
				$conditions["Credit.id"] = null;
			}
		}else{
			$min = 0;
			$max = 1000000;
		}

		if(isset($query['commerce']) && !empty($query['commerce']) ){
			$this->loadModel("ShopCommerce");
			$shopCommerce 	= $this->ShopCommerce->findByCode($query['commerce']);
			if(!empty($shopCommerce)){
				$creditsRequests = Set::extract($shopCommerce["CreditsRequest"],"{n}.credit_id");
				foreach($creditsRequests as $clave=>$valor){
					if(empty($valor)) unset($creditsRequests[$clave]);
				}
				$conditions["Credit.id"] = $creditsRequests;
			}else{
				$conditions["Credit.id"] = null;
			}
			$this->Set("commerce",$query['commerce']);
		}


		try {
			$credits =  $this->Credit->find("all",["conditions" => $conditions, ]);

		} catch (Exception $e) {
			$credits = [];
		}

		if (!empty($credits)) {
			$i = 2;
			$this->loadModel("ShopCommerce");
			foreach ($credits as $key => $value) {
				$customer = $this->Credit->Customer->findById($value["Customer"]["id"]);

				$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, date("d-m-Y",strtotime($value["Credit"]["created"])) );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $value["Credit"]["code_pay"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $customer["Customer"]["identification"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $customer["Customer"]["name"]." ".$customer["Customer"]["last_name"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $customer["CustomersPhone"]["0"]["phone_number"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $customer["CustomersAddress"]["0"]["address"]. " ".$customer["CustomersAddress"]["0"]["address_city"]." ".$customer["CustomersAddress"]["0"]["address_street"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, ($value["Credit"]["value_request"] ));

				if ($value["Credit"]["debt"]) {
					$state = "Mora";
				}else{
					$state = $value["Credit"]["state"] == 1 ? "Cancelado" : "No finalizado";
				}
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, $state );
				$comercio 	= ["Comercio" => $this->ShopCommerce->findById($value["CreditsRequest"]['shop_commerce_id'])];
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, $comercio["Comercio"]["Shop"]["social_reason"]." - ".$comercio["Comercio"]["ShopCommerce"]["name"]. " - ".$comercio["Comercio"]["ShopCommerce"]["code"] );
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

		$spreadsheet->getActiveSheet()->setTitle('Cartera otorgada');
		$spreadsheet->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

		$writer = 	IOFactory::createWriter($spreadsheet, 'Xlsx');
		$name 	=	"files/cartera_otorgados_".time().".xlsx";
		$writer->save($name);

		echo Router::url("/",true).$name;
		die;
	}

	private function credVigentes(){

		$conditions 	= ["Credit.credits_request_id != " => 0, "Credit.state" => 0];
		$totalCartera	= 0;

		if(!isset($this->request->query["ini"])){
			$fechaInicioReporte = date("Y-m-d");
		}else{
			$fechaInicioReporte = $this->request->query["ini"];
		}

		if(!isset($this->request->query["end"])){
			$fechaFinReporte = date("Y-m-d");
		}else{
			$fechaFinReporte = $this->request->query["end"];
		}

		if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
			$conditions["DATE(Credit.created) >=" ] = $this->request->query["ini"];
			$conditions["DATE(Credit.created) <=" ] = $this->request->query["end"];
			$this->set("fechas",true);
		}
		$query = $this->request->query;

		if(isset($query["range"]) && !empty($query["range"])){
			$valuesRange = explode(";", $query["range"]);
			if(count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])){

				$conditions["Credit.value_request >= "] = $valuesRange[0];
				$conditions["Credit.value_request <= "] = $valuesRange[1];

				$min = $valuesRange[0];
				$max = $valuesRange[1];

			}else{
				$conditions["Credit.id"] = null;
			}
		}else{
			$min = 1;
			$max = 1000000;
		}

		if(isset($query['commerce']) && !empty($query['commerce']) ){
			$this->loadModel("ShopCommerce");
			$shopCommerce 	= $this->ShopCommerce->findByCode($query['commerce']);
			if(!empty($shopCommerce)){
				$creditsRequests = Set::extract($shopCommerce["CreditsRequest"],"{n}.credit_id");
				foreach($creditsRequests as $clave=>$valor){
					if(empty($valor)) unset($creditsRequests[$clave]);
				}
				$conditions["Credit.id"] = $creditsRequests;
			}else{
				$conditions["Credit.id"] = null;
			}
			$this->Set("commerce",$query['commerce']);
		}


		try {
			$this->Paginator->settings = ["conditions" => $conditions, ];
			$credits = $this->Paginator->paginate();

			if (!empty($credits)) {
				$this->loadModel("ShopCommerce");
				foreach ($credits as $key => $value) {
					$this->Credit->Customer->unBindModel(["hasMany"=>["Credit","CreditLimit","CreditsRequest","User"]]);
					$credits[$key]["Customer"] = $this->Credit->Customer->findById($value["Customer"]["id"]);
					$credits[$key]["saldos"] = $this->calculateTotales($value["Credit"],$value["CreditsPlan"]);
					try {
						$credits[$key]["Comercio"] 	= $this->ShopCommerce->findById($value["CreditsRequest"]['shop_commerce_id']);

					} catch (Exception $e) {
						$credits[$key]["Comercio"] 	= [];
					}
				}
			}

			$totalCartera = $this->Credit->find("first",["conditions" => $conditions,"fields" => ["SUM(value_request) as total"] ]);

			if(!empty($totalCartera)){
				$totalCartera = $totalCartera["0"]["total"];
			}

		} catch (Exception $e) {
			$credits = [];
		}

		$this->set(compact("fechaInicioReporte","fechaFinReporte","min","max","credits","totalCartera"));
	}

	private function credVigentesExport($spreadsheet){
		$spreadsheet->setActiveSheetIndex(0)
			        ->setCellValue('A1', 'FECHA APROBADO')
			        ->setCellValue('B1', 'NÚMERO DE OBLIGACIÓN')
			        ->setCellValue('C1', 'CÉDULA')
			        ->setCellValue('D1', 'NOMBRE COMPLETO')
			        ->setCellValue('E1', 'TELÉFONO')
			        ->setCellValue('F1', 'DIRECCIÓN')
			        ->setCellValue('G1', 'VALOR APROBADO')
			        ->setCellValue('H1', 'SALDO')
			        ->setCellValue('I1', 'PROVEEDOR');

		$conditions 	= ["Credit.credits_request_id != " => 0, "Credit.state" => 0];
		$totalCartera	= 0;

		if(!isset($this->request->query["ini"])){
			$fechaInicioReporte = date("Y-m-d");
		}else{
			$fechaInicioReporte = $this->request->query["ini"];
		}

		if(!isset($this->request->query["end"])){
			$fechaFinReporte = date("Y-m-d");
		}else{
			$fechaFinReporte = $this->request->query["end"];
		}

		if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
			$conditions["DATE(Credit.created) >=" ] = $this->request->query["ini"];
			$conditions["DATE(Credit.created) <=" ] = $this->request->query["end"];
			$this->set("fechas",true);
		}
		$query = $this->request->query;

		if(isset($query["range"]) && !empty($query["range"])){
			$valuesRange = explode(";", $query["range"]);
			if(count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])){

				$conditions["Credit.value_request >= "] = $valuesRange[0];
				$conditions["Credit.value_request <= "] = $valuesRange[1];

				$min = $valuesRange[0];
				$max = $valuesRange[1];

			}else{
				$conditions["Credit.id"] = null;
			}
		}else{
			$min = 1;
			$max = 1000000;
		}

		if(isset($query['commerce']) && !empty($query['commerce']) ){
			$this->loadModel("ShopCommerce");
			$shopCommerce 	= $this->ShopCommerce->findByCode($query['commerce']);
			if(!empty($shopCommerce)){
				$creditsRequests = Set::extract($shopCommerce["CreditsRequest"],"{n}.credit_id");
				foreach($creditsRequests as $clave=>$valor){
					if(empty($valor)) unset($creditsRequests[$clave]);
				}
				$conditions["Credit.id"] = $creditsRequests;
			}else{
				$conditions["Credit.id"] = null;
			}
			$this->Set("commerce",$query['commerce']);
		}


		try {
			$credits = $this->Credit->find("all",["conditions" => $conditions, ]);
		} catch (Exception $e) {
			$credits = [];
		}

		if (!empty($credits)) {
			$i = 2;
			$this->loadModel("ShopCommerce");
			foreach ($credits as $key => $value) {
				$this->Credit->Customer->unBindModel(["hasMany"=>["Credit","CreditLimit","CreditsRequest","User"]]);
				$customer 	= $this->Credit->Customer->findById($value["Customer"]["id"]);
				$saldos 	= $this->calculateTotales($value["Credit"],$value["CreditsPlan"]);

				$customer = $this->Credit->Customer->findById($value["Customer"]["id"]);

				$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, date("d-m-Y",strtotime($value["Credit"]["created"])) );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $value["Credit"]["code_pay"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $customer["Customer"]["identification"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $customer["Customer"]["name"]." ".$customer["Customer"]["last_name"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $customer["CustomersPhone"]["0"]["phone_number"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $customer["CustomersAddress"]["0"]["address"]. " ".$customer["CustomersAddress"]["0"]["address_city"]." ".$customer["CustomersAddress"]["0"]["address_street"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, ($value["Credit"]["value_request"] ));

				$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, $saldos["saldo"] );

				$comercio 	= ["Comercio" => $this->ShopCommerce->findById($value["CreditsRequest"]['shop_commerce_id'])];
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, $comercio["Comercio"]["Shop"]["social_reason"]." - ".$comercio["Comercio"]["ShopCommerce"]["name"]. " - ".$comercio["Comercio"]["ShopCommerce"]["code"] );
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

		$spreadsheet->getActiveSheet()->setTitle('Cartera vigentes');
		$spreadsheet->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

		$writer = 	IOFactory::createWriter($spreadsheet, 'Xlsx');
		$name 	=	"files/cartera_vigentes_".time().".xlsx";
		$writer->save($name);

		echo Router::url("/",true).$name;
	}

	private function credVigMora(){

		$conditions 	= ["Credit.credits_request_id != " => 0, "Credit.state" => 0,"Credit.debt" => 1];
		$totalCartera	= 0;

		if(!isset($this->request->query["ini"])){
			$fechaInicioReporte = date("Y-m-d");
		}else{
			$fechaInicioReporte = $this->request->query["ini"];
		}

		if(!isset($this->request->query["end"])){
			$fechaFinReporte = date("Y-m-d");
		}else{
			$fechaFinReporte = $this->request->query["end"];
		}

		if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
			$conditions["DATE(Credit.created) >=" ] = $this->request->query["ini"];
			$conditions["DATE(Credit.created) <=" ] = $this->request->query["end"];
			$this->set("fechas",true);
		}
		$query = $this->request->query;

		if(isset($query["range"]) && !empty($query["range"])){
			$valuesRange = explode(";", $query["range"]);
			if(count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])){

				$conditions["Credit.value_request >= "] = $valuesRange[0];
				$conditions["Credit.value_request <= "] = $valuesRange[1];

				$min = $valuesRange[0];
				$max = $valuesRange[1];

			}else{
				$conditions["Credit.id"] = null;
			}
		}else{
			$min = 1;
			$max = 1000000;
		}

		if(isset($query['commerce']) && !empty($query['commerce']) ){
			$this->loadModel("ShopCommerce");
			$shopCommerce 	= $this->ShopCommerce->findByCode($query['commerce']);
			if(!empty($shopCommerce)){
				$creditsRequests = Set::extract($shopCommerce["CreditsRequest"],"{n}.credit_id");
				foreach($creditsRequests as $clave=>$valor){
					if(empty($valor)) unset($creditsRequests[$clave]);
				}
				$conditions["Credit.id"] = $creditsRequests;
			}else{
				$conditions["Credit.id"] = null;
			}
			$this->Set("commerce",$query['commerce']);
		}


		try {
			$this->Paginator->settings = ["conditions" => $conditions, ];
			$credits = $this->Paginator->paginate();

			if (!empty($credits)) {
				$this->loadModel("ShopCommerce");
				foreach ($credits as $key => $value) {
					$this->Credit->Customer->unBindModel(["hasMany"=>["Credit","CreditLimit","CreditsRequest","User"]]);
					$credits[$key]["Customer"] = $this->Credit->Customer->findById($value["Customer"]["id"]);
					$credits[$key]["saldos"] = $this->calculateTotales($value["Credit"],$value["CreditsPlan"]);
					try {
						$credits[$key]["Comercio"] 	= $this->ShopCommerce->findById($value["CreditsRequest"]['shop_commerce_id']);

					} catch (Exception $e) {
						$credits[$key]["Comercio"] 	= [];
					}
				}
			}

			$totalCartera = $this->Credit->find("first",["conditions" => $conditions,"fields" => ["SUM(value_request) as total"] ]);

			if(!empty($totalCartera)){
				$totalCartera = $totalCartera["0"]["total"];
			}

		} catch (Exception $e) {
			$credits = [];
		}

		$this->set(compact("fechaInicioReporte","fechaFinReporte","min","max","credits","totalCartera"));
	}

	private function credVigMoraExport($spreadsheet){
		$spreadsheet->setActiveSheetIndex(0)
			        ->setCellValue('A1', 'FECHA APROBADO')
			        ->setCellValue('B1', 'NÚMERO DE OBLIGACIÓN')
			        ->setCellValue('C1', 'CÉDULA')
			        ->setCellValue('D1', 'NOMBRE COMPLETO')
			        ->setCellValue('E1', 'TELÉFONO')
			        ->setCellValue('F1', 'DIRECCIÓN')
			        ->setCellValue('G1', 'VALOR APROBADO')
			        ->setCellValue('H1', 'SALDO VIGENTE')
			        ->setCellValue('I1', 'SALDO EN MORA')
			        ->setCellValue('J1', 'SALDO VIGENTE')
			        ->setCellValue('K1', 'CANTIDAD CUOTAS EN MORA')
			        ->setCellValue('L1', 'PROVEEDOR');


		$conditions 	= ["Credit.credits_request_id != " => 0, "Credit.state" => 0,"Credit.debt" => 1];
		$totalCartera	= 0;

		if(!isset($this->request->query["ini"])){
			$fechaInicioReporte = date("Y-m-d");
		}else{
			$fechaInicioReporte = $this->request->query["ini"];
		}

		if(!isset($this->request->query["end"])){
			$fechaFinReporte = date("Y-m-d");
		}else{
			$fechaFinReporte = $this->request->query["end"];
		}

		if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
			$conditions["DATE(Credit.created) >=" ] = $this->request->query["ini"];
			$conditions["DATE(Credit.created) <=" ] = $this->request->query["end"];
			$this->set("fechas",true);
		}
		$query = $this->request->query;

		if(isset($query["range"]) && !empty($query["range"])){
			$valuesRange = explode(";", $query["range"]);
			if(count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])){

				$conditions["Credit.value_request >= "] = $valuesRange[0];
				$conditions["Credit.value_request <= "] = $valuesRange[1];

				$min = $valuesRange[0];
				$max = $valuesRange[1];

			}else{
				$conditions["Credit.id"] = null;
			}
		}else{
			$min = 0;
			$max = 1000000;
		}

		if(isset($query['commerce']) && !empty($query['commerce']) ){
			$this->loadModel("ShopCommerce");
			$shopCommerce 	= $this->ShopCommerce->findByCode($query['commerce']);
			if(!empty($shopCommerce)){
				$creditsRequests = Set::extract($shopCommerce["CreditsRequest"],"{n}.credit_id");
				foreach($creditsRequests as $clave=>$valor){
					if(empty($valor)) unset($creditsRequests[$clave]);
				}
				$conditions["Credit.id"] = $creditsRequests;
			}else{
				$conditions["Credit.id"] = null;
			}
			$this->Set("commerce",$query['commerce']);
		}


		try {
			$credits = $this->Credit->find("all",["conditions" => $conditions, ]);

		} catch (Exception $e) {
			$credits = [];
		}

		if (!empty($credits)) {
			$i = 2;
			$this->loadModel("ShopCommerce");
			foreach ($credits as $key => $value) {
				$this->Credit->Customer->unBindModel(["hasMany"=>["Credit","CreditLimit","CreditsRequest","User"]]);
				$customer = $this->Credit->Customer->findById($value["Customer"]["id"]);
				$saldos = $this->calculateTotales($value["Credit"],$value["CreditsPlan"]);

				$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, date("d-m-Y",strtotime($value["Credit"]["created"])) );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $value["Credit"]["code_pay"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $customer["Customer"]["identification"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $customer["Customer"]["name"]." ".$customer["Customer"]["last_name"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $customer["CustomersPhone"]["0"]["phone_number"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $customer["CustomersAddress"]["0"]["address"]. " ".$customer["CustomersAddress"]["0"]["address_city"]." ".$customer["CustomersAddress"]["0"]["address_street"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, ($value["Credit"]["value_request"] ));

				$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, $saldos["saldo"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, $saldos["debt"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$i, $value["Credit"]["quota_value"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$i, $saldos["totalDebt"] );

				$comercio 	= ["Comercio" => $this->ShopCommerce->findById($value["CreditsRequest"]['shop_commerce_id'])];
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$i, $comercio["Comercio"]["Shop"]["social_reason"]." - ".$comercio["Comercio"]["ShopCommerce"]["name"]. " - ".$comercio["Comercio"]["ShopCommerce"]["code"] );

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

		$spreadsheet->getActiveSheet()->setTitle('Cartera vigentes EN MORA');
		$spreadsheet->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

		$writer = 	IOFactory::createWriter($spreadsheet, 'Xlsx');
		$name 	=	"files/cartera_vigentes_mora_".time().".xlsx";
		$writer->save($name);

		echo Router::url("/",true).$name;
	}

	private function credCancelados(){

		$conditions 	= ["Credit.credits_request_id != " => 0, "Credit.state" => 1];
		$totalCartera	= 0;

		if(!isset($this->request->query["ini"])){
			$fechaInicioReporte = date("Y-m-d");
		}else{
			$fechaInicioReporte = $this->request->query["ini"];
		}

		if(!isset($this->request->query["end"])){
			$fechaFinReporte = date("Y-m-d");
		}else{
			$fechaFinReporte = $this->request->query["end"];
		}

		if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
			$conditions["DATE(Credit.created) >=" ] = $this->request->query["ini"];
			$conditions["DATE(Credit.created) <=" ] = $this->request->query["end"];
			$this->set("fechas",true);
		}
		$query = $this->request->query;

		if(isset($query["range"]) && !empty($query["range"])){
			$valuesRange = explode(";", $query["range"]);
			if(count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])){

				$conditions["Credit.value_request >= "] = $valuesRange[0];
				$conditions["Credit.value_request <= "] = $valuesRange[1];

				$min = $valuesRange[0];
				$max = $valuesRange[1];

			}else{
				$conditions["Credit.id"] = null;
			}
		}else{
			$min = 1;
			$max = 1000000;
		}

		if(isset($query['commerce']) && !empty($query['commerce']) ){
			$this->loadModel("ShopCommerce");
			$shopCommerce 	= $this->ShopCommerce->findByCode($query['commerce']);
			if(!empty($shopCommerce)){
				$creditsRequests = Set::extract($shopCommerce["CreditsRequest"],"{n}.credit_id");
				foreach($creditsRequests as $clave=>$valor){
					if(empty($valor)) unset($creditsRequests[$clave]);
				}
				$conditions["Credit.id"] = $creditsRequests;
			}else{
				$conditions["Credit.id"] = null;
			}
			$this->Set("commerce",$query['commerce']);
		}


		try {
			$this->Paginator->settings = ["conditions" => $conditions, ];
			$credits = $this->Paginator->paginate();

			if (!empty($credits)) {
				$this->loadModel("ShopCommerce");
				foreach ($credits as $key => $value) {
					$this->Credit->Customer->unBindModel(["hasMany"=>["Credit","CreditLimit","CreditsRequest","User"]]);
					$credits[$key]["Customer"] = $this->Credit->Customer->findById($value["Customer"]["id"]);

					$totalDebts = 0;

					foreach ($value["CreditsPlan"] as $keyData => $valueData) {
						if(!is_null($valueData["date_debt"])){
							$totalDebts++;
						}
					}

					$credits[$key]["debts"] = $totalDebts;
					try {
						$credits[$key]["Comercio"] 	= $this->ShopCommerce->findById($value["CreditsRequest"]['shop_commerce_id']);

					} catch (Exception $e) {
						$credits[$key]["Comercio"] 	= [];
					}
				}
			}

			$totalCartera = $this->Credit->find("first",["conditions" => $conditions,"fields" => ["SUM(value_request) as total"] ]);

			if(!empty($totalCartera)){
				$totalCartera = $totalCartera["0"]["total"];
			}

		} catch (Exception $e) {
			$credits = [];
		}

		$this->set(compact("fechaInicioReporte","fechaFinReporte","min","max","credits","totalCartera"));
	}

	private function credCanceladosExport($spreadsheet){
		$spreadsheet->setActiveSheetIndex(0)
			        ->setCellValue('A1', 'FECHA APROBADO')
			        ->setCellValue('B1', 'NÚMERO DE OBLIGACIÓN')
			        ->setCellValue('C1', 'CÉDULA')
			        ->setCellValue('D1', 'NOMBRE COMPLETO')
			        ->setCellValue('E1', 'TELÉFONO')
			        ->setCellValue('F1', 'DIRECCIÓN')
			        ->setCellValue('G1', 'VALOR APROBADO')
			        ->setCellValue('H1', 'CUOTAS PAGADAS EN MORA')
			        ->setCellValue('I1', 'PROVEEDOR');

		$conditions 	= ["Credit.credits_request_id != " => 0, "Credit.state" => 1];
		$totalCartera	= 0;

		if(!isset($this->request->query["ini"])){
			$fechaInicioReporte = date("Y-m-d");
		}else{
			$fechaInicioReporte = $this->request->query["ini"];
		}

		if(!isset($this->request->query["end"])){
			$fechaFinReporte = date("Y-m-d");
		}else{
			$fechaFinReporte = $this->request->query["end"];
		}

		if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
			$conditions["DATE(Credit.created) >=" ] = $this->request->query["ini"];
			$conditions["DATE(Credit.created) <=" ] = $this->request->query["end"];
			$this->set("fechas",true);
		}
		$query = $this->request->query;

		if(isset($query["range"]) && !empty($query["range"])){
			$valuesRange = explode(";", $query["range"]);
			if(count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])){

				$conditions["Credit.value_request >= "] = $valuesRange[0];
				$conditions["Credit.value_request <= "] = $valuesRange[1];

				$min = $valuesRange[0];
				$max = $valuesRange[1];

			}else{
				$conditions["Credit.id"] = null;
			}
		}else{
			$min = 0;
			$max = 1000000;
		}

		if(isset($query['commerce']) && !empty($query['commerce']) ){
			$this->loadModel("ShopCommerce");
			$shopCommerce 	= $this->ShopCommerce->findByCode($query['commerce']);
			if(!empty($shopCommerce)){
				$creditsRequests = Set::extract($shopCommerce["CreditsRequest"],"{n}.credit_id");
				foreach($creditsRequests as $clave=>$valor){
					if(empty($valor)) unset($creditsRequests[$clave]);
				}
				$conditions["Credit.id"] = $creditsRequests;
			}else{
				$conditions["Credit.id"] = null;
			}
			$this->Set("commerce",$query['commerce']);
		}


		try {
			$credits = $this->Credit->find("all",["conditions" => $conditions, ]);
		} catch (Exception $e) {
			$credits = [];
		}

		if (!empty($credits)) {
			$i = 2;
			foreach ($credits as $key => $value) {
				$this->Credit->Customer->unBindModel(["hasMany"=>["Credit","CreditLimit","CreditsRequest","User"]]);
				$customer 	= $this->Credit->Customer->findById($value["Customer"]["id"]);
				$totalDebts = 0;

				foreach ($value["CreditsPlan"] as $keyData => $valueData) {
					if(!is_null($valueData["date_debt"])){
						$totalDebts++;
					}
				}

				$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, date("d-m-Y",strtotime($value["Credit"]["created"])) );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $value["Credit"]["code_pay"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $customer["Customer"]["identification"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $customer["Customer"]["name"]." ".$customer["Customer"]["last_name"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $customer["CustomersPhone"]["0"]["phone_number"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $customer["CustomersAddress"]["0"]["address"]. " ".$customer["CustomersAddress"]["0"]["address_city"]." ".$customer["CustomersAddress"]["0"]["address_street"] );
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, ($value["Credit"]["value_request"] ));

				$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, $totalDebts );

				$comercio 	= ["Comercio" => $this->ShopCommerce->findById($value["CreditsRequest"]['shop_commerce_id'])];
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, $comercio["Comercio"]["Shop"]["social_reason"]." - ".$comercio["Comercio"]["ShopCommerce"]["name"]. " - ".$comercio["Comercio"]["ShopCommerce"]["code"] );
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

		$spreadsheet->getActiveSheet()->setTitle('Cartera cancelados');
		$spreadsheet->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

		$writer = 	IOFactory::createWriter($spreadsheet, 'Xlsx');
		$name 	=	"files/cartera_cancelados_".time().".xlsx";
		$writer->save($name);

		echo Router::url("/",true).$name;

	}

	private function calculateTotales($credit,$quotes){
		$quotes 		= $this->Credit->CreditsPlan->getDataQuotes($quotes,$credit["last_payment_date"],$credit["debt_rate"],$credit["id"]);

		$capitalTotal 	= $othersValue = $interesValue = 0;

		$totalDebt 		= 0;
		$totalCredit 	= 0;
		$totalQuoteDebt	= 0;
		$totalCanceladas	= 0;
		$dias = 0;

		foreach ($quotes as $keyQt => $valueQt) {

			$capitalTotal = floatval($valueQt["capital_value"]-$valueQt["capital_payment"]);
			$othersValue  = floatval($valueQt["others_value"]-$valueQt["others_payment"]);
			$othersValue  = floatval($valueQt["interest_value"]-$valueQt["interest_payment"]);

			$totalCredit+=floatVal($capitalTotal+$othersValue+$interesValue+$valueQt["debt_value"]+$valueQt["debt_honor"]);

			$totalDebt += floatVal($valueQt["debt_value"]+$valueQt["debt_honor"]);

			if($valueQt["debt_value"] > 0 || $valueQt["debt_honor"] > 0){
				$totalQuoteDebt++;
				$dias+=$valueQt["days"];
			}

			if($valueQt["state"] == 1){
				$totalCanceladas++;
			}
		}

		return ["saldo" => $totalCredit,"debt" => $totalDebt,"totalDebt" => $totalQuoteDebt,"totalCanceladas" => $totalCanceladas,"dias" => $dias ];

	}

	public function recaudos(){
		$this->loadModel("Receipt");

		$query 			= $this->request->query;
		$conditions 	= [];
		$creditsCero 	= $this->Credit->find("list",["fields"=>["id","id"],"conditions" => ["Credit.credits_request_id" => 0]]);

		if(!empty($creditsCero)){
			$conditions["CreditsPlan.credit_id <>"] = $creditsCero;
		}

		if(!isset($this->request->query["ini"])){
			$fechaInicioReporte = date("Y-m-d");
		}else{
			$fechaInicioReporte = $this->request->query["ini"];
		}

		if(!isset($this->request->query["end"])){
			$fechaFinReporte = date("Y-m-d");
		}else{
			$fechaFinReporte = $this->request->query["end"];
		}

		if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
			$conditionsReceipt["DATE(Payment.created) >=" ] = $this->request->query["ini"];
			$conditionsReceipt["DATE(Payment.created) <=" ] = $this->request->query["end"];

			$receiptsIds = $this->Receipt->Payment->find("list",["fields" => ["id","receipt_id"],"conditions" => $conditionsReceipt ]);
			if (!empty($receiptsIds)) {
				$conditions["Receipt.id"] = $receiptsIds;
			}

			$this->set("fechas",true);
		}

		if(isset($query['commerce']) && !empty($query['commerce']) ){
			$conditions["ShopCommerce.code"] = $query["commerce"];
			$this->Set("commerce",$query['commerce']);
		}

		$totalReceipt = 0;

		try {
			$this->Paginator->settings = [ "conditions" => $conditions, ];
			$receipts = $this->Paginator->paginate($this->Receipt);

			$dataReceipt = $this->Receipt->find("first",["conditions"=>$conditions,"fields" => ["SUM(Receipt.value) as total"]]);

			if (!empty($dataReceipt)) {
				$totalReceipt = $dataReceipt["0"]["total"];
			}

			if (!empty($receipts)) {
				foreach ($receipts as $key => $value) {
					$this->Receipt->ShopCommerce->Shop->recursive = -1;
					$this->Credit->Customer->unBindModel(["hasMany"=>["Credit"]]);
					$customer = $this->Credit->Customer->findById( $this->Credit->field("customer_id",["id" => $value["CreditsPlan"]["credit_id"] ]) );
					$shop 	  = $this->Receipt->ShopCommerce->Shop->field("social_reason",["id" => $value["ShopCommerce"]["shop_id"]]);
					$receipts[$key]["customer"] = $customer;
					$receipts[$key]["ShopCommerce"]["shop"] = $shop;
					$receipts[$key]["Receipt"]["obligacion"] = $this->Credit->field("credits_request_id",["id" => $value["CreditsPlan"]["credit_id"]]);
				}
			}

		} catch (Exception $e) {
			$receipts = [];
		}

		$this->set(compact("fechaInicioReporte","fechaFinReporte","receipts","totalReceipt"));
	}

	public function intereses_export($time = null) {
		$this->autoRender = false;
		$this->loadModel("CreditsPlan");
		$conditions 	= ["Credit.credits_request_id != " => 0];
		$group 			= ["CreditsPlan.credit_id"];
		$having 		= [];
		$totalInteres 	= 0;

		if(!isset($this->request->query["ini"])){
			$fechaInicioReporte = date("Y-m-d");
		}else{
			$fechaInicioReporte = $this->request->query["ini"];
		}

		if(!isset($this->request->query["end"])){
			$fechaFinReporte = date("Y-m-d");
		}else{
			$fechaFinReporte = $this->request->query["end"];
		}

		$query = $this->request->query;

		if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
			$conditions["DATE(Credit.created) >=" ] = $this->request->query["ini"];
			$conditions["DATE(Credit.created) <=" ] = $this->request->query["end"];
			$this->set("fechas",true);
		}

		if(isset($query['commerce']) && !empty($query['commerce']) ){
			$this->loadModel("ShopCommerce");
			$shopCommerce 	= $this->ShopCommerce->findByCode($query['commerce']);
			if(!empty($shopCommerce)){
				$creditsRequests = Set::extract($shopCommerce["CreditsRequest"],"{n}.credit_id");
				foreach($creditsRequests as $clave=>$valor){
					if(empty($valor)) unset($creditsRequests[$clave]);
				}
				$conditions["Credit.id"] = $creditsRequests;
			}else{
				$conditions["Credit.id"] = null;
			}
			$this->Set("commerce",$query['commerce']);
		}
		$query = $this->request->query;

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$spreadsheet->getProperties()->setCreator('CREDISHOP')
			        ->setLastModifiedBy('CREDISHOP')
			        ->setTitle('INTERESES')
			        ->setSubject('INTERESES')
			        ->setDescription('INTERESES Credishop')
			        ->setKeywords('INTERESES')
			        ->setCategory('INTERESES');

		if($query["tab"] == 1){
			if(isset($query["range"]) && !empty($query["range"])){
				$valuesRange = explode(";", $query["range"]);
				if(count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])){

					$having["OR"][] = ["SUM(CreditsPlan.interest_value) >= " => $valuesRange[0],
					"SUM(CreditsPlan.interest_value) <= " => $valuesRange[1] ];

					$having["OR"][] = ["SUM(CreditsPlan.others_value) >= " => $valuesRange[0],
					"SUM(CreditsPlan.others_value) <= " => $valuesRange[1] ];

				}else{
					$conditions["Credit.id"] = null;
				}
			}

			// Add some data
			$spreadsheet->setActiveSheetIndex(0)
				        ->setCellValue('A1', 'OBLIGACIÓN')
				        ->setCellValue('B1', 'FECHA CRÉDITO')
				        ->setCellValue('C1', 'ESTADO CRÉDITO')
				        ->setCellValue('D1', 'PROVEEDOR')
				        ->setCellValue('E1', 'SALDO CRÉDITO')
				        ->setCellValue('F1', 'INTERESES CRÉDITO')
				        ->setCellValue('G1', 'OTROS CREDITO');

			try {
				$valuesQuotes = $this->CreditsPlan->find("all",["fields" => ["Credit.*","CreditsPlan.*","SUM(CreditsPlan.interest_value) as INTERES","SUM(CreditsPlan.others_value) as OTROS"],"group" => $group, "conditions" => $conditions, "having" => $having  ]);
			} catch (Exception $e) {
				$valuesQuotes = [];
			}

			$i = 2;

			if(!empty($valuesQuotes)){
				foreach ($valuesQuotes as $key => $value) {
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, $value["Credit"]["code_pay"] );
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, date("d-m-Y",strtotime($value["Credit"]["created"])) );

					$estado = $value["Credit"]["state"] == 1 ? "Pagado" : "No finalizado";

					$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $estado );

					$shopCommerce = $this->Credit->CreditsRequest->field("shop_commerce_id",["id"=>$value["Credit"]["credits_request_id"]]);

					$shopCommerce = $this->Credit->CreditsRequest->ShopCommerce->findById($shopCommerce);

					$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $shopCommerce["ShopCommerce"]["name"]." - ".$shopCommerce["Shop"]["social_reason"]." - ".$shopCommerce["ShopCommerce"]["code"]  );

					$totalByCredit = 0;

					if($value["Credit"]["state"] == 0){
						$quotes 		= $this->Credit->CreditsPlan->getCuotesInformation($value["Credit"]["id"],null,0);
						$capitalTotal 	= $othersValue = $interesValue = 0;
						$totalCredit 	= 0;

						foreach ($quotes as $keyQt => $valueQt) {

							$capitalTotal = floatval($valueQt["CreditsPlan"]["capital_value"]-$valueQt["CreditsPlan"]["capital_payment"]);
							$othersValue  = floatval($valueQt["CreditsPlan"]["others_value"]-$valueQt["CreditsPlan"]["others_payment"]);
							$othersValue  = floatval($valueQt["CreditsPlan"]["interest_value"]-$valueQt["CreditsPlan"]["interest_payment"]);

							$totalCredit+=floatVal($capitalTotal+$othersValue+$interesValue+$valueQt["CreditsPlan"]["debt_value"]+$valueQt["CreditsPlan"]["debt_honor"]);
						}
						$totalByCredit+=$totalCredit;
					}

					$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $totalByCredit );
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $value["0"]["INTERES"] );
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, $value["0"]["OTROS"] );
					$i++;

				}
			}
		}else{

			// Add some data
			$spreadsheet->setActiveSheetIndex(0)
				        ->setCellValue('A1', 'OBLIGACIÓN')
				        ->setCellValue('B1', 'FECHA CRÉDITO')
				        ->setCellValue('C1', 'ESTADO CRÉDITO')
				        ->setCellValue('D1', 'PROVEEDOR')
				        ->setCellValue('E1', 'CUOTAS PAGADAS')
				        ->setCellValue('F1', 'INTERESES CRÉDITO')
				        ->setCellValue('G1', 'OTROS CREDITO');

			if(isset($query["range"]) && !empty($query["range"])){
				$valuesRange = explode(";", $query["range"]);
				if(count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])){

					$having["OR"][] = ["SUM(CreditsPlan.interest_payment) >= " => $valuesRange[0],
					"SUM(CreditsPlan.interest_payment) <= " => $valuesRange[1] ];

					$having["OR"][] = ["SUM(CreditsPlan.others_payment) >= " => $valuesRange[0],
					"SUM(CreditsPlan.others_payment) <= " => $valuesRange[1] ];

				}else{
					$conditions["Credit.id"] = null;
				}
			}

			try {
				$valuesQuotes = $this->CreditsPlan->find("all", ["fields" => ["Credit.*","CreditsPlan.*","SUM(CreditsPlan.interest_payment) as INTERES","SUM(CreditsPlan.others_payment) as OTROS"],"group" => $group, "conditions" => $conditions, "having" => $having  ] );
			} catch (Exception $e) {
				$valuesQuotes = [];
			}

			$i = 2;

			if(!empty($valuesQuotes)){
				foreach ($valuesQuotes as $key => $value) {
					$totalByCredit = 0;

					$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, $value["Credit"]["code_pay"] );
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, date("d-m-Y",strtotime($value["Credit"]["created"])) );

					$estado = $value["Credit"]["state"] == 1 ? "Pagado" : "No finalizado";

					$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $estado );

					$shopCommerce = $this->Credit->CreditsRequest->field("shop_commerce_id",["id"=>$value["Credit"]["credits_request_id"]]);

					$shopCommerce = $this->Credit->CreditsRequest->ShopCommerce->findById($shopCommerce);

					$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $shopCommerce["ShopCommerce"]["name"]." - ".$shopCommerce["Shop"]["social_reason"]." - ".$shopCommerce["ShopCommerce"]["code"]  );

					$this->Credit->CreditsPlan->recursive = -1;
					$quotes 		= $this->Credit->CreditsPlan->findAllByCreditId($value["Credit"]["id"]);

					foreach ($quotes as $keyQt => $valueQt) {
						if ($valueQt["CreditsPlan"]["state"] == 1) {
							$totalByCredit++;
						}
					}

					$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $totalByCredit );
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $value["0"]["INTERES"] );
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, $value["0"]["OTROS"] );
					$i++;

				}
			}
		}

		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

		$spreadsheet->getActiveSheet()->setTitle('Intereses');
		$spreadsheet->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

		$title = $query["tab"] == 1 ? "causados" : "obtenidos";
		// $writer->save('php://output');
		$name =	"files/intereses_".$title."_".time().".xlsx";
		$writer->save($name);

		return Router::url("/",true).$name;



	}

	public function recaudos_export($time = null){
		$this->autoRender = false;
		$this->loadModel("Receipt");

		$query 			= $this->request->query;
		$conditions 	= [];
		$creditsCero 	= $this->Credit->find("list",["fields"=>["id","id"],"conditions" => ["Credit.credits_request_id" => 0]]);

		if(!empty($creditsCero)){
			$conditions["CreditsPlan.credit_id <>"] = $creditsCero;
		}

		if(!isset($this->request->query["ini"])){
			$fechaInicioReporte = date("Y-m-d");
		}else{
			$fechaInicioReporte = $this->request->query["ini"];
		}

		if(!isset($this->request->query["end"])){
			$fechaFinReporte = date("Y-m-d");
		}else{
			$fechaFinReporte = $this->request->query["end"];
		}

		if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
			$conditionsReceipt["DATE(Payment.created) >=" ] = $this->request->query["ini"];
			$conditionsReceipt["DATE(Payment.created) <=" ] = $this->request->query["end"];

			$receiptsIds = $this->Receipt->Payment->find("list",["fields" => ["id","receipt_id"],"conditions" => $conditionsReceipt ]);
			if (!empty($receiptsIds)) {
				$conditions["Receipt.id"] = $receiptsIds;
			}
		}

		if(isset($query['commerce']) && !empty($query['commerce']) ){
			$conditions["ShopCommerce.code"] = $query["commerce"];
			$this->Set("commerce",$query['commerce']);
		}

		$totalReceipt = 0;

			$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

			$spreadsheet->getProperties()->setCreator('CREDISHOP')
				        ->setLastModifiedBy('CREDISHOP')
				        ->setTitle('RECAUDOS')
				        ->setSubject('RECAUDOS')
				        ->setDescription('RECAUDOS ZÍRO')
				        ->setKeywords('RECAUDOS')
				        ->setCategory('RECAUDOS');

			// Add some data
			$spreadsheet->setActiveSheetIndex(0)
				        ->setCellValue('A1', 'FECHA RECAUDO')
				        ->setCellValue('B1', 'NuMERO DE OBLIGACIÓN')
				        ->setCellValue('C1', 'CÉDULA')
				        ->setCellValue('D1', 'NOMBRE COMPLETO')
				        ->setCellValue('E1', 'TELÉFONO')
				        ->setCellValue('F1', 'DIRECCIÓN')
				        ->setCellValue('G1', 'VALOR RECAUDADO')
				        ->setCellValue('H1', 'PROVEEDOR');


		try {
			$receipts = $this->Receipt->find("all", [ "conditions" => $conditions, ]);



			$i = 2;

			if (!empty($receipts)) {
				foreach ($receipts as $key => $value) {

					$this->Credit->Customer->unBindModel(["hasMany"=>["Credit"]]);
					$customer = $this->Credit->Customer->findById( $this->Credit->field("customer_id",["id" => $value["CreditsPlan"]["credit_id"] ]) );
					$fecha 	  = !empty($value["Payment"]) ? $value["Payment"][0]["created"] : $value["CreditsPlan"]["date_payment"];
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, date("d-m-Y",strtotime($fecha)) );
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $this->Credit->field("code_pay",["id" => $value["CreditsPlan"]["credit_id"]]));
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $customer["Customer"]["identification"] );
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $customer["Customer"]["name"]." ".$customer["Customer"]["last_name"] );
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $customer["CustomersPhone"]["0"]["phone_number"] );
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $customer["CustomersAddress"]["0"]["address"]. " ".$customer["CustomersAddress"]["0"]["address_city"]." ".$customer["CustomersAddress"]["0"]["address_street"] );
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, $value["Receipt"]["value"] );

					 $this->Receipt->ShopCommerce->Shop->recursive = -1;

					$shop 	  = $this->Receipt->ShopCommerce->Shop->field("social_reason",["id" => $value["ShopCommerce"]["shop_id"]]);
					$comercioData = empty($value["ShopCommerce"]["code"]) ? "PAGO WEB" : $value["ShopCommerce"]["code"]." - ".$shop." - ".$value["ShopCommerce"]["name"];
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i,$comercioData);
					$i++;
				}
			}


		} catch (Exception $e) {
			$receipts = [];
		}

		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

		$spreadsheet->getActiveSheet()->setTitle('Recaudos');
		$spreadsheet->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		// $writer->save('php://output');
		$name =	"files/recaudos_".time().".xlsx";
		$writer->save($name);

		return Router::url("/",true).$name;

	}


	public function intereses(){
		if(!isset($this->request->query["tab"])){
			$this->redirect(["action"=>"intereses","?" => ["tab"=>1]]);
		}

		$this->loadModel("CreditsPlan");
		$conditions 	= ["Credit.credits_request_id != " => 0];
		$group 			= ["CreditsPlan.credit_id"];
		$having 		= [];
		$totalInteres 	= 0;

		if(!isset($this->request->query["ini"])){
			$fechaInicioReporte = date("Y-m-d");
		}else{
			$fechaInicioReporte = $this->request->query["ini"];
		}

		if(!isset($this->request->query["end"])){
			$fechaFinReporte = date("Y-m-d");
		}else{
			$fechaFinReporte = $this->request->query["end"];
		}

		$query = $this->request->query;

		if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
			$conditions["DATE(Credit.created) >=" ] = $this->request->query["ini"];
			$conditions["DATE(Credit.created) <=" ] = $this->request->query["end"];
			$this->set("fechas",true);
		}

		if(isset($query['commerce']) && !empty($query['commerce']) ){
			$this->loadModel("ShopCommerce");
			$shopCommerce 	= $this->ShopCommerce->findByCode($query['commerce']);
			if(!empty($shopCommerce)){
				$creditsRequests = Set::extract($shopCommerce["CreditsRequest"],"{n}.credit_id");
				foreach($creditsRequests as $clave=>$valor){
					if(empty($valor)) unset($creditsRequests[$clave]);
				}
				$conditions["Credit.id"] = $creditsRequests;
			}else{
				$conditions["Credit.id"] = null;
			}
			$this->Set("commerce",$query['commerce']);
		}
		$query = $this->request->query;

		if($query["tab"] == 1){
			if(isset($query["range"]) && !empty($query["range"])){
				$valuesRange = explode(";", $query["range"]);
				if(count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])){

					$having["OR"][] = ["SUM(CreditsPlan.interest_value) >= " => $valuesRange[0],
					"SUM(CreditsPlan.interest_value) <= " => $valuesRange[1] ];

					$having["OR"][] = ["SUM(CreditsPlan.others_value) >= " => $valuesRange[0],
					"SUM(CreditsPlan.others_value) <= " => $valuesRange[1] ];

					$min = $valuesRange[0];
					$max = $valuesRange[1];

				}else{
					$conditions["Credit.id"] = null;
				}
			}else{
				$min = 1;
				$max = 1000000;
			}

			$this->Paginator->settings = ["fields" => ["Credit.*","CreditsPlan.*","SUM(CreditsPlan.interest_value) as INTERES","SUM(CreditsPlan.others_value) as OTROS"],"group" => $group, "conditions" => $conditions, "having" => $having  ];
			$valuesQuotes = $this->Paginator->paginate($this->CreditsPlan);

			$total 			= 0;
			if(!empty($valuesQuotes)){
				$totalInteres = $this->CreditsPlan->find("first",["conditions"=>$conditions,"fields"=>["SUM(CreditsPlan.interest_value + CreditsPlan.others_value) as total"]]);
				if (!empty($totalInteres)) {
					$totalInteres = $totalInteres["0"]["total"];
				}
				foreach ($valuesQuotes as $key => $value) {
					$totalByCredit = 0;

					if($value["Credit"]["state"] == 0){
						$quotes 		= $this->Credit->CreditsPlan->getCuotesInformation($value["Credit"]["id"],null,0);

						$capitalTotal 	= $othersValue = $interesValue = 0;

						$totalCredit = 0;

						foreach ($quotes as $keyQt => $valueQt) {

							$capitalTotal = floatval($valueQt["CreditsPlan"]["capital_value"]-$valueQt["CreditsPlan"]["capital_payment"]);
							$othersValue  = floatval($valueQt["CreditsPlan"]["others_value"]-$valueQt["CreditsPlan"]["others_payment"]);
							$othersValue  = floatval($valueQt["CreditsPlan"]["interest_value"]-$valueQt["CreditsPlan"]["interest_payment"]);

							$totalCredit+=floatVal($capitalTotal+$othersValue+$interesValue+$valueQt["CreditsPlan"]["debt_value"]+$valueQt["CreditsPlan"]["debt_honor"]);
						}
						$totalByCredit+=$totalCredit;
					}

					$valuesQuotes[$key]["Credit"]["saldo"] = $totalByCredit;


					$shopCommerce = $this->Credit->CreditsRequest->field("shop_commerce_id",["id"=>$value["Credit"]["credits_request_id"]]);

					$valuesQuotes[$key]["Credit"]["comercio"] = $this->Credit->CreditsRequest->ShopCommerce->findById($shopCommerce);

				}
			}
			$this->set("valuesQuotes",$valuesQuotes);
		}else{
			if(isset($query["range"]) && !empty($query["range"])){
				$valuesRange = explode(";", $query["range"]);
				if(count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])){

					$having["OR"][] = ["SUM(CreditsPlan.interest_payment) >= " => $valuesRange[0],
					"SUM(CreditsPlan.interest_payment) <= " => $valuesRange[1] ];

					$having["OR"][] = ["SUM(CreditsPlan.others_payment) >= " => $valuesRange[0],
					"SUM(CreditsPlan.others_payment) <= " => $valuesRange[1] ];

					$min = $valuesRange[0];
					$max = $valuesRange[1];

				}else{
					$conditions["Credit.id"] = null;
				}
			}else{
				$min = 1;
				$max = 1000000;
			}

			$this->Paginator->settings = ["fields" => ["Credit.*","CreditsPlan.*","SUM(CreditsPlan.interest_payment) as INTERES","SUM(CreditsPlan.others_payment) as OTROS"],"group" => $group, "conditions" => $conditions, "having" => $having  ];
			$valuesQuotes = $this->Paginator->paginate($this->CreditsPlan);

			if(!empty($valuesQuotes)){
				$totalInteres = $this->CreditsPlan->find("first",["conditions"=>$conditions,"fields"=>["SUM(CreditsPlan.interest_payment + CreditsPlan.others_payment) as total"]]);
				if (!empty($totalInteres)) {
					$totalInteres = $totalInteres["0"]["total"];
				}
				foreach ($valuesQuotes as $key => $value) {
					$totalByCredit = 0;
					$this->Credit->CreditsPlan->recursive = -1;
					$quotes 		= $this->Credit->CreditsPlan->findAllByCreditId($value["Credit"]["id"]);

					foreach ($quotes as $keyQt => $valueQt) {
						if ($valueQt["CreditsPlan"]["state"] == 1) {
							$totalByCredit++;
						}
					}

					$valuesQuotes[$key]["Credit"]["totales"] = $totalByCredit;

					$shopCommerce = $this->Credit->CreditsRequest->field("shop_commerce_id",["id"=>$value["Credit"]["credits_request_id"]]);
					$valuesQuotes[$key]["Credit"]["comercio"] = $this->Credit->CreditsRequest->ShopCommerce->findById($shopCommerce);


				}
			}
			$this->set("valuesQuotes",$valuesQuotes);

		}



		$this->set("tab",$this->request->query["tab"]);
		$this->set(compact("fechaInicioReporte","fechaFinReporte","min","max","totalInteres"));
	}

	public function index() {
		$conditions = $this->Credit->buildConditions($this->request->query);

		if(AuthComponent::user("role") == 5){
			$conditions["Credit.customer_id"] = AuthComponent::user("customer_id");
		}elseif(AuthComponent::user("role") == 4 || AuthComponent::user("role") == 7 ){
			$this->loadModel("ShopCommerce");
			$conditions2 = ["ShopCommerce.shop_id"=>AuthComponent::user("shop_id")];
          	$commerces  = $this->ShopCommerce->find("all",["fields"=>["id"],"recursive"=>-1, "conditions"=> $conditions2 ]);
          	if(!empty($commerces)){
	            $commerces      = Set::extract($commerces,"{n}.ShopCommerce.id");
	            $requests  		= $this->Credit->CreditsRequest->find("list",["conditions"=>["shop_commerce_id" => $commerces]]);
	            if(!empty($requests)){
	            	$conditions["Credit.credits_request_id"] = $requests;
	            }else{
	            	$conditions["Credit.credits_request_id"] = 0;
	            }
	      	}else{
            	$conditions["Credit.credits_request_id"] = 0;
            }
		}elseif(AuthComponent::user("role") == 6 ) {
			$requests  		= $this->Credit->CreditsRequest->find("list",["conditions"=>["shop_commerce_id" => AuthComponent::user("shop_commerce_id") ]]);
            if(!empty($requests)){
            	$conditions["Credit.credits_request_id"] = $requests;
            }else{
            	$conditions["Credit.credits_request_id"] = 0;
            }
		}

		$conditions["Credit.credits_request_id !="] = 0;

		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->Credit->recursive = 0;
		$this->Paginator->settings = array('order'=>array('Credit.modified'=>'DESC'),"group" => ["Credit.credits_request_id"]);
		$credits = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('credits'));
	}

	public function search_user(){
		$this->layout = false;

		$this->Credit->Customer->recursive = -1;
		$customer 		= $this->Credit->Customer->findByIdentification($this->request->data["identification"]);
		$creditsCliente = [];

		if(!empty($customer)){
			$this->Session->write("customer_id",$customer["Customer"]["id"]);
			$creditsCliente = $this->Credit->find("all",["recursive"=>-1,"conditions" => ["Credit.state" => 0,"Credit.customer_id" => $customer["Customer"]["id"],"Credit.credits_request_id != " => 0, "Credit.juridico" => 0 ]]);

			if(!empty($creditsCliente)){
				$creditsCliente = $this->getSaldosByCredit($creditsCliente);
			}
		}

		$this->set(compact("customer","creditsCliente"));

	}

	public function payment_web(){
		$this->autoRender = false;
		$requestData = $this->request->data;
		if(isset($requestData["x_signature"])){

			$p_cust_id_cliente	=	Configure::read("PAYMENT.id");
            $p_key				= 	Configure::read("PAYMENT.p_key");

            $x_ref_payco		=	$requestData['x_ref_payco'];
            $x_transaction_id	=	$requestData['x_transaction_id'];
            $x_amount			=	$requestData['x_amount'];
            $x_currency_code	=	$requestData['x_currency_code'];
            $x_signature		=	$requestData['x_signature'];
            $continue 			= 	true;

            if(!Configure::read("PAYMENT.test")){
            	$signature=hash('sha256',
	               $p_cust_id_cliente.'^'
	              .$p_key.'^'
	              .$x_ref_payco.'^'
	              .$x_transaction_id.'^'
	              .$x_amount.'^'
	              .$x_currency_code
	            );

	            $this->log($signature,"debug");
	            $this->log($x_signature,"debug");

            	if($signature != $x_signature){
            		$continue = false;
            	}
            }

            if($requestData["x_cod_response"] == 1 && $continue){
            	$data = ["type" => 2, "value" => $requestData["x_amount_ok"],"credit_id" => $requestData["x_extra1"]];

            	$this->request->data = $data;

            	$this->log($this->request->data, "debug");

            	$this->payment_quotes(json_encode($requestData));
            }
		}else{
			$this->log(json_encode($this->request->data),"debug");
		}
		$this->log("final", "debug");
	}

	public function payment_web_response(){
		$this->layout = false;
		$this->log($this->request,"debug");
	}

	public function get_data_payment(){
		$this->autoRender = false;

		$credit = $this->Credit->find("first",["recursive" => 2, "conditions" => ["Credit.id" => $this->decrypt($this->request->data["credit"]) ] ]);

		$datos = [
			"name" => "Pago Crédito",
			"description" => "Pago ZÍRO, obligación #". $credit["Credit"]["code_pay"] ,
			"invoice" => date("YmdHis"),
			"currency" => "cop",
			"amount" => $this->request->data["value"],
			"tax_base" => "0",
	        "tax" => "0",
	        "country" => "co",
	        "lang" => "es",
	        "external" => false,
	        "extra1" => $this->request->data["credit"],
	        "confirmation" => Router::url("/",true)."payment_web_credishop",
          	"response" => Router::url("/",true)."payment_web_credishop_response",
			"name_billing" => $credit["Customer"]["name"]." ".$credit["Customer"]["last_name"],
			"number_doc_billing" => $credit["Customer"]["identification"]
		];

		return json_encode(["configuration" => Configure::read("PAYMENT"),"datos" => $datos]);
	}

	public function get_credit_customer(){
		$this->layout = false;

		if(isset($this->request->data["creditPayment"])){
			$creditInfo = $this->Credit->find("first",["recursive" => -1, "conditions" => ["id" => $this->decrypt($this->request->data["creditPayment"]) ] ]);
			$creditsCliente = $this->getSaldosByCredit([$creditInfo])[$this->request->data["creditPayment"]];
			$cuotesPayment 	= $this->Credit->CreditsPlan->find("count",["conditions" => ["CreditsPlan.state" => 1,"CreditsPlan.credit_id" =>$creditInfo["Credit"]["id"] ] ]);
		}
		$this->set(compact("creditInfo","creditsCliente","cuotesPayment"));

	}

	private function getSaldosByCredit($credits){
		$totalByCredit = [];
		foreach ($credits as $key => $value) {

			$commerceData = $this->Credit->CreditsRequest->ShopCommerce->findById($this->Credit->CreditsRequest->field("shop_commerce_id",["id"=>$value["Credit"]["credits_request_id"] ]));

			$totalByCredit[$this->encrypt($value["Credit"]["id"])] = [
				"values" => [
					"total" => $this->Credit->CreditsPlan->getTotalDeudaCredit($value["Credit"]["id"]),
					"min_value" => $this->Credit->CreditsPlan->getMinValue($value["Credit"]["id"])
				],"fecha" => date("Y-m-d",strtotime($value["Credit"]["created"])),"numero" => $value["Credit"]["code_pay"],"commerce" => $commerceData["Shop"]["social_reason"] . " - " . $commerceData["ShopCommerce"]["name"]
			];
		}
		return $totalByCredit;
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->Credit->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->Credit->recursive = 0;
		$conditions = array('Credit.' . $this->Credit->primaryKey => $id);
		$this->set('credit', $this->Credit->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Credit->create();
			if ($this->Credit->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$creditsLines = $this->Credit->CreditsLine->find('list');
		$customers = $this->Credit->Customer->find('list');
		$this->set(compact('creditsLines', 'customers'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->Credit->id = $id;
		if (!$this->Credit->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Credit->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('Credit.' . $this->Credit->primaryKey => $id);
			$this->request->data = $this->Credit->find('first', compact('conditions'));
		}
		$creditsLines = $this->Credit->CreditsLine->find('list');
		$customers = $this->Credit->Customer->find('list');
		$this->set(compact('creditsLines', 'customers'));
	}

	public function payment_detail($creditId){

		$this->Credit->CreditsRequest->recursive = in_array(AuthComponent::user("role"), [1,2,3,5,4,6,7]) ? 2 : 1;

		$this->Credit->CreditsRequest->unBindModel(
			["belongsTo" => ["CreditsLine"] ]
		);
		$creditRequest 	= $this->Credit->CreditsRequest->findById($this->decrypt($creditId));
		$creditInfo 	= $this->Credit->findById($creditRequest["CreditsRequest"]["credit_id"]);

		$quotes 		= $this->Credit->CreditsPlan->getCuotesInformation($creditRequest["CreditsRequest"]["credit_id"]);

		$totalCredit 	= $this->Credit->CreditsPlan->getCreditDeuda($creditInfo["Credit"]["id"]);

		$this->set(compact("creditRequest","layout","creditInfo","quotes","totalCredit"));
	}

	private function getTotalFinal($creditId){

		$total 			= 0;
		$credit 		= $this->Credit->find("first",["conditions"=>["id"=>$creditId],"recursive" => -1]);
		$allCuotes 		= $this->Credit->CreditsPlan->find("count",["conditions"=> ["CreditsPlan.credit_id" => $creditId]]);
		$quotes 		= $this->Credit->CreditsPlan->getCuotesInformation($creditId,null,0);

		$capitalTotal 	= 0;
		$first 			= null;

		foreach ($quotes as $key => $value) {
			if (is_null($first)) {
				$first = $value;
			}else{
				if($value["CreditsPlan"]["debt_value"] > 0 || $value["CreditsPlan"]["debt_honor"] > 0){
					$total += $value["CreditsPlan"]["interest_value"];
					$total += $value["CreditsPlan"]["others_value"];
				}
			}
			$total 		  += $value["CreditsPlan"]["debt_value"];
			$total 		  += $value["CreditsPlan"]["debt_honor"];
			$capitalTotal += ($value["CreditsPlan"]["capital_value"]-$value["CreditsPlan"]["capital_payment"]);
		}

		$fechaRefIni = is_null($credit["Credit"]["last_payment_date"]) ? date("Y-m-d",strtotime($credit["Credit"]["created"])) : $credit["Credit"]["last_payment_date"];

		$deadline 		= new DateTime($fechaRefIni);
		$nowDate 		= new DateTime(date("Y-m-d"));
		$difference 	= $deadline->diff($nowDate);
		$days			= $difference->days;

		if ($allCuotes == count($quotes) && $days <= 30  ) {
			$interesesPasados = $first["CreditsPlan"]["interest_value"];
			$interesesOther   = $first["CreditsPlan"]["others_value"];
		}else{
			$interesesPasados = $first["CreditsPlan"]["interest_value"];
			$interesesOther   = $first["CreditsPlan"]["others_value"];
		}
		$total		  	  += round($capitalTotal + $interesesPasados + $interesesOther);

		return $total;
	}

	public function plan_payemts_pdf($requestId,$type = "view",$return = null) {



		$this->Credit->CreditsRequest->recursive = 2;

		$this->Credit->CreditsRequest->unBindModel(["belongsTo" => ["CreditsLine"] ]);
		$creditRequest 	= $this->Credit->CreditsRequest->findById($this->decrypt($requestId));
		$creditInfo 	= $this->Credit->findById($creditRequest["CreditsRequest"]["credit_id"]);

		$quotes 		= $this->Credit->CreditsPlan->getCuotesInformation($creditRequest["CreditsRequest"]["credit_id"]);

		if($type == "pdf"){
			$this->autoRender 	= false;
				$options = array(
				'template'	=> 'plans_payments',
				'ruta'		=> APP . 'webroot'.DS.'files'.DS.'plans_payment'.DS.md5($requestId).".pdf",
				'vars'		=> compact("creditRequest","creditInfo","quotes"),
			);
			$this->generatePdf($options);

			$urlPdf = Router::url("/",true).'files'.DS.'plans_payment'.DS.md5($requestId).".pdf";

			if (!is_null($return)) {
				return $urlPdf;
			}else{
				$this->redirect($urlPdf);
			}
		}elseif($type == "view"){
			$this->set(compact("creditRequest","creditInfo","quotes"));
		}

	}

	public function quotes_value(){
		$this->autoRender = false;
		$creditId 	= $this->decrypt($this->request->data["credit_id"]);
		$quotes 	= $this->request->data["quotes"];

		$total      = 0;

		foreach ($quotes as $key => $value) {
			$quotes[$key] = $this->decrypt($value);
		}

		$totalCuotesNoPayment = $this->Credit->CreditsPlan->find("count",["conditions"=>["CreditsPlan.credit_id"=>$creditId,"CreditsPlan.state"=>'0']]);

		if ($totalCuotesNoPayment ==  count($quotes) && $totalCuotesNoPayment != 0 && $totalCuotesNoPayment > 1) {
			$total 			  = $this->getTotalFinal($creditId);
		}else{
			foreach ($quotes as $key => $value) {
				$dataQuote 	= $this->Credit->CreditsPlan->getCuotesInformation($creditId,$value);
				$capital 	= $dataQuote["CreditsPlan"]["capital_value"]-$dataQuote["CreditsPlan"]["capital_payment"];
                $interes 	= $dataQuote["CreditsPlan"]["interest_value"]-$dataQuote["CreditsPlan"]["interest_payment"];
                $others  	= $dataQuote["CreditsPlan"]["others_value"]-$dataQuote["CreditsPlan"]["others_payment"];

                $total 		+= $capital+$interes+$others+$dataQuote["CreditsPlan"]["debt_value"]+$dataQuote["CreditsPlan"]["debt_honor"]+$dataQuote["CreditsPlan"]["others_add"]+$dataQuote["CreditsPlan"]["interest_add"]+$dataQuote["CreditsPlan"]["debt_add"];
			}
		}

		return $total;
	}

	private function payment_cuotes_normal($valorPago,$value,$capital = null, $dataWeb = null){

		$paymentValue = 0;

		$credit 	  = $this->Credit->find("first",["conditions"=>["Credit.id"=>$value["CreditsPlan"]["credit_id"]],"recursive" => 1]);

		$this->loadModel("CreditsRequest");
		$this->loadModel("Payment");

		// if( $value["CreditsPlan"]["others_add"] > 0 && $valorPago > 0 ){
		// 	$paymentDebt = [
		// 		"Payment" => [
		// 			"credits_plan_id" => $value["CreditsPlan"]["id"],
		// 			"value"			  => $valorPago > $value["CreditsPlan"]["others_add"] ? $value["CreditsPlan"]["others_add"] : $valorPago,
		// 			"user_id"		   => AuthComponent::user("id"),
		// 			"shop_commerce_id" => AuthComponent::user("role") == 11 ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
		// 			"type"			   => 6,
		// 			"web"		   	   => $dataWeb,
		// 			"juridic"		   => AuthComponent::user("role") == 11 ? 1 : 0
		// 		]
		// 	];
		// 	$this->Payment->create();
		// 	if($this->Payment->save($paymentDebt)){
		// 		$this->Session->write("CUOTAID",$this->Payment->id);
		// 		$valorPago -= $paymentDebt["Payment"]["value"];
		// 		$paymentValue += $paymentDebt["Payment"]["value"];
		// 		$value["CreditsPlan"]["date_debt"] 	= date("Y-m-d");
		// 	}else{
		// 		$this->log($this->Payment->validationErrors,"debug");
		// 	}
		// }

		// if( $value["CreditsPlan"]["interest_add"] > 0 && $valorPago > 0 ){
		// 	$paymentDebt = [
		// 		"Payment" => [
		// 			"credits_plan_id" => $value["CreditsPlan"]["id"],
		// 			"value"			  => $valorPago > $value["CreditsPlan"]["interest_add"] ? $value["CreditsPlan"]["interest_add"] : $valorPago,
		// 			"user_id"		   => AuthComponent::user("id"),
		// 			"shop_commerce_id" => AuthComponent::user("role") == 11 ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
		// 			"type"			   => 7,
		// 			"web"		   	   => $dataWeb,
		// 			"juridic"		   => AuthComponent::user("role") == 11 ? 1 : 0
		// 		]
		// 	];
		// 	$this->Payment->create();
		// 	if($this->Payment->save($paymentDebt)){
		// 		$this->Session->write("CUOTAID",$this->Payment->id);
		// 		$valorPago -= $paymentDebt["Payment"]["value"];
		// 		$paymentValue += $paymentDebt["Payment"]["value"];
		// 		$value["CreditsPlan"]["date_debt"] 	= date("Y-m-d");
		// 	}else{
		// 		$this->log($this->Payment->validationErrors,"debug");
		// 	}
		// }

		// if( $value["CreditsPlan"]["debt_add"] > 0 && $valorPago > 0 ){
		// 	$paymentDebt = [
		// 		"Payment" => [
		// 			"credits_plan_id" => $value["CreditsPlan"]["id"],
		// 			"value"			  => $valorPago > $value["CreditsPlan"]["debt_add"] ? $value["CreditsPlan"]["debt_add"] : $valorPago,
		// 			"user_id"		   => AuthComponent::user("id"),
		// 			"shop_commerce_id" => AuthComponent::user("role") == 11 ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
		// 			"type"			   => 8,
		// 			"web"		   	   => $dataWeb,
		// 			"juridic"		   => AuthComponent::user("role") == 11 ? 1 : 0
		// 		]
		// 	];
		// 	$this->Payment->create();
		// 	if($this->Payment->save($paymentDebt)){
		// 		$this->Session->write("CUOTAID",$this->Payment->id);
		// 		$valorPago -= $paymentDebt["Payment"]["value"];
		// 		$paymentValue += $paymentDebt["Payment"]["value"];
		// 		$value["CreditsPlan"]["date_debt"] 	= date("Y-m-d");
		// 	}else{
		// 		$this->log($this->Payment->validationErrors,"debug");
		// 	}
		// }

		if( $value["CreditsPlan"]["debt_value"] > 0 && $valorPago > 0 ){
			$paymentDebt = [
				"Payment" => [
					"credits_plan_id" => $value["CreditsPlan"]["id"],
					"value"			  => $valorPago > $value["CreditsPlan"]["debt_value"] ? $value["CreditsPlan"]["debt_value"] : $valorPago,
					"user_id"		   => AuthComponent::user("id"),
					"shop_commerce_id" => AuthComponent::user("role") == 11 ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
					"type"			   => 4,
					"web"		   	   => $dataWeb,
					"juridic"		   => AuthComponent::user("role") == 11 ? 1 : 0
				]
			];
			$this->Payment->create();
			if($this->Payment->save($paymentDebt)){
				$this->Session->write("CUOTAID",$this->Payment->id);
				$valorPago -= $paymentDebt["Payment"]["value"];
				$paymentValue += $paymentDebt["Payment"]["value"];
				$value["CreditsPlan"]["date_debt"] 	= date("Y-m-d");
			}else{
				$this->log($this->Payment->validationErrors,"debug");
			}
		}

		if($value["CreditsPlan"]["debt_honor"] > 0 && $valorPago > 0 ){
			$paymentHonor = [
				"Payment" => [
					"credits_plan_id" => $value["CreditsPlan"]["id"],
					"value"			  => $valorPago > $value["CreditsPlan"]["debt_honor"] ? $value["CreditsPlan"]["debt_honor"] : $valorPago,
					"user_id"		   => AuthComponent::user("id"),
					"shop_commerce_id" => AuthComponent::user("role") == 11 ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
					"type"			   => 5,
					"web"		   	   => $dataWeb,
					"juridic"		   => AuthComponent::user("role") == 11 ? 1 : 0
				]
			];
			$this->Payment->create();
			if($this->Payment->save($paymentHonor)){
				$this->Session->write("CUOTAID",$this->Payment->id);
				$valorPago -= $paymentHonor["Payment"]["value"];
				$paymentValue += $paymentHonor["Payment"]["value"];
				$value["CreditsPlan"]["date_debt"] 		= date("Y-m-d");
			}else{
				$this->log($this->Payment->validationErrors,"debug");
			}
		}

		if( ( $value["CreditsPlan"]["others_value"] > $value["CreditsPlan"]["others_payment"] && $valorPago > 0 && is_null($capital ) ) || ( ( $value["CreditsPlan"]["debt_value"] > 0 || $value["CreditsPlan"]["debt_honor"] > 0 ) && $valorPago > 0 )  ){

			$valorPagoOthers = $value["CreditsPlan"]["others_value"]-$value["CreditsPlan"]["others_payment"];

			$paymentOthers = [
				"Payment" => [
					"credits_plan_id" => $value["CreditsPlan"]["id"],
					"value"			  => $valorPago > $valorPagoOthers ? $valorPagoOthers : $valorPago,
					"user_id"		   => AuthComponent::user("id"),
					"shop_commerce_id" => AuthComponent::user("role") == 11 ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
					"type"			   => 3,
					"web"		   	   => $dataWeb,
					"juridic"		   => AuthComponent::user("role") == 11 ? 1 : 0
				]
			];
			$this->Payment->create();
			if($this->Payment->save($paymentOthers)){
				$this->Session->write("CUOTAID",$this->Payment->id);
				$valorPago -= $paymentOthers["Payment"]["value"];
				$paymentValue += $paymentOthers["Payment"]["value"];
				$value["CreditsPlan"]["others_payment"] += $paymentOthers["Payment"]["value"];
			}else{
				$this->log($this->Payment->validationErrors,"debug");
			}
		}

		if( ( $value["CreditsPlan"]["interest_value"] > $value["CreditsPlan"]["interest_payment"] && $valorPago > 0 && is_null($capital) ) || ( ( $value["CreditsPlan"]["debt_value"] > 0 || $value["CreditsPlan"]["debt_honor"] > 0 ) && $valorPago > 0 )  ){

			$valorPagoInteres = $value["CreditsPlan"]["interest_value"]-$value["CreditsPlan"]["interest_payment"];

			$paymentInteres = [
				"Payment" => [
					"credits_plan_id" => $value["CreditsPlan"]["id"],
					"value"			  => $valorPago > $valorPagoInteres ? $valorPagoInteres : $valorPago,
					"user_id"		   => AuthComponent::user("id"),
					"shop_commerce_id" => AuthComponent::user("role") == 11 ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
					"type"			   => 2,
					"web"		   	   => $dataWeb,
					"juridic"		   => AuthComponent::user("role") == 11 ? 1 : 0
				]
			];
			$this->Payment->create();
			if($this->Payment->save($paymentInteres)){
				$this->Session->write("CUOTAID",$this->Payment->id);
				$valorPago -= $paymentInteres["Payment"]["value"];
				$paymentValue += $paymentInteres["Payment"]["value"];
				$value["CreditsPlan"]["interest_payment"] 	+= $paymentInteres["Payment"]["value"];
			}else{
				$this->log($this->Payment->validationErrors,"debug");
			}
		}

		if($value["CreditsPlan"]["capital_value"] > $value["CreditsPlan"]["capital_payment"] && $valorPago > 0){

			$valorPagoCapital = $value["CreditsPlan"]["capital_value"]-$value["CreditsPlan"]["capital_payment"];

			$paymentCapital = [
				"Payment" => [
					"credits_plan_id" => $value["CreditsPlan"]["id"],
					"value"			  => $valorPago > $valorPagoCapital ? $valorPagoCapital : $valorPago,
					"user_id"		   => AuthComponent::user("id"),
					"shop_commerce_id" => AuthComponent::user("role") == 11 ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
					"type"			   => 1,
					"web"		   	   => $dataWeb,
					"juridic"		   => AuthComponent::user("role") == 11 ? 1 : 0
				]
			];
			$this->Payment->create();
			if($this->Payment->save($paymentCapital)){

				$paymentID = $this->Payment->id;

				$this->Session->write("CUOTAID",$this->Payment->id);
				$valorPago -= $paymentCapital["Payment"]["value"];
				$paymentValue += $paymentCapital["Payment"]["value"];
				$value["CreditsPlan"]["capital_payment"] 	+= $paymentCapital["Payment"]["value"];

				$credito = $this->Credit->find("first",["recursive" => -1, "conditions" => ["Credit.id" => $value["CreditsPlan"]["credit_id"] ] ]);

				$credito["Credit"]["value_pending"]-=$paymentCapital["Payment"]["value"];

				$this->Credit->save($credito);

				$this->log($this->Payment->validationErrors,"debug");

				if($value["CreditsPlan"]["debt_value"] > 0 || $value["CreditsPlan"]["debt_honor"] > 0 || $value["CreditsPlan"]["debt_add"] > 0 || $value["CreditsPlan"]["interest_add"] > 0 || $value["CreditsPlan"]["others_add"] > 0){
					$this->CreditsRequest->CreditLimit->updateAll(
						["CreditLimit.active" => 0],
						[
							"CreditLimit.state"  => [1,3,4,5],
							"CreditLimit.customer_id" => $value["CreditsPlan"]["customer_id"]
						]
					);
				}else{
					$this->loadModel("CreditsRequest");
					$dateLimit 				= $this->Credit->field("deadline",[ "id"=>$value["CreditsPlan"]["credit_id"] ]);
					$credits_request_id 	= $this->Credit->field("credits_request_id",[ "id"=>$value["CreditsPlan"]["credit_id"] ]);
					$customer_id 			= $this->Credit->field("customer_id",[ "id"=>$value["CreditsPlan"]["credit_id"] ]);

					$dateLimit = date("Y-m-d",strtotime($dateLimit."+ 7 days"));

					$datosLimit = [
						"CreditLimit" => [
							"value" 	 			=> $paymentCapital["Payment"]["value"],
							"state" 	 			=> 5,
							"reason"	 			=> "Preaprobado por restante de solicitud",
							"type_movement" 		=> 1,
							"credits_request_id" 	=> $credits_request_id,
							"user_id"			 	=> AuthComponent::user("id"),
							"deadline"			 	=> $dateLimit,
							"customer_id"			=> $customer_id,
							"credit_id"				=> $value["CreditsPlan"]["credit_id"],
							"active" 				=> 1,
							"payment_id"			=> $paymentID
						]
					];
					$this->CreditsRequest->CreditLimit->create();
					$this->CreditsRequest->CreditLimit->save($datosLimit);
				}



				if($value["CreditsPlan"]["capital_payment"] == $value["CreditsPlan"]["capital_value"]){
					$value["CreditsPlan"]["state"] = 1;
				}
			}
		}

		$value["CreditsPlan"]["date_payment"] 	= date("Y-m-d");
		$this->Credit->CreditsPlan->save($value);

		if($paymentValue > 0){

			$cuotes = $this->Session->read("CUOTESP");
			$cuotes[$value["CreditsPlan"]["number"]] = $paymentValue;
			$this->Session->write("CUOTESP",$cuotes);
			// $this->sendReceipt($value["CreditsPlan"]["id"],$value["CreditsPlan"]["credit_id"],$paymentValue);

			if (AuthComponent::user("role") == 11) {

				$this->loadModel("History");

				$type = AuthComponent::user("role") == 11 ? 1 : 0;

				$dataNote = [ "History" => [
					"credits_plan_id" => $value["CreditsPlan"]["id"],
					"user_id" 		  => AuthComponent::user("id"),
					"type" 		  	  => $type,
					"action"		  => "Se realizó pago por: $".number_format($paymentValue),
				]  ];

				$this->History->create();
				$this->History->save($dataNote);

			}

		}

		return $valorPago;

	}

	public function payment_quotes($dataWeb = null){
		if (is_null($dataWeb)) {
			$this->autoRender = false;
		}

		$type 		= $this->request->data["type"];
		$valorPago 	= isset($this->request->data["value"]) ? $this->request->data["value"] : 0;

		$creditId 	= $this->decrypt($this->request->data["credit_id"]);
		$quotes 	= isset($this->request->data["ids"]) ? $this->request->data["ids"] : null;

		$this->Session->write("CUOTESP",[]);

		if($type == "2"){
			$totalApagar = $this->getTotalFinal($creditId);
			if($valorPago == $totalApagar){
				$quotes 		= $this->Credit->CreditsPlan->getCuotesInformation($creditId,null,0);
				$this->paymentTotalCredit($quotes,$creditId,$dataWeb);
			}else{
				$quotes 		= $this->Credit->CreditsPlan->getCuotesInformation($creditId,null,0);
				foreach ($quotes as $key => $value) {
					$valorPago 		= $this->payment_cuotes_normal($valorPago,$value,null,$dataWeb);
				}
			}
		}else{
			foreach ($quotes as $key => $value) {
				$quotes[$key] = $this->decrypt($value);
			}
			$totalCuotesNoPayment = $this->Credit->CreditsPlan->find("count",["conditions"=>["CreditsPlan.credit_id"=>$creditId,"CreditsPlan.state" => 0]]);

			if ( ($totalCuotesNoPayment !=  count($quotes) && $totalCuotesNoPayment != 0) || ($totalCuotesNoPayment == 1 && count($quotes) == 1)  ) {
				$quotes 		= $this->Credit->CreditsPlan->getCuotesInformation($creditId,$quotes,0);

				foreach ($quotes as $key => $value) {
					$capital 	= $value["CreditsPlan"]["capital_value"]-$value["CreditsPlan"]["capital_payment"];
	                $interes 	= $value["CreditsPlan"]["interest_value"]-$value["CreditsPlan"]["interest_payment"];
	                $others  	= $value["CreditsPlan"]["others_value"]-$value["CreditsPlan"]["others_payment"];

	                $valorPago 	= $capital+$interes+$others+$value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"]+$value["CreditsPlan"]["others_add"]+$value["CreditsPlan"]["interest_add"]+$value["CreditsPlan"]["debt_add"];

					$valorPago 	= $this->payment_cuotes_normal($valorPago,$value,null,$dataWeb);
				}
			}else{
				$quotes 		= $this->Credit->CreditsPlan->getCuotesInformation($creditId,null,0);
				$this->paymentTotalCredit($quotes,$creditId,$dataWeb);
			}
		}

		$credit  = $this->Credit->find("first",["conditions"=>["id"=>$creditId],"recursive" => -1]);
		$credit["Credit"]["last_payment_date"] = date("Y-m-d");

		$credit["Credit"]["state"] = $this->Credit->CreditsPlan->find("count",["conditions"=>["CreditsPlan.credit_id"=>$creditId,"CreditsPlan.state" => 0]]) == 0 ? 1 : 0;

		$normal = 0;
		if($credit["Credit"]["juridico"] == 1 && $credit["Credit"]["state"] == 1){
			$credit["Credit"]["juridico"] = 0;
			$normal = 1;
		}
		$this->Credit->save($credit);

		if($normal == 1){
			$allCredits = $this->Credit->findAllByCustomerIdAndJuridico($credit["Credit"]["customer_id"],1);
			if(count($allCredits) == 0){
				$this->loadModel("User");
				$this->User->recursive = -1;
				$user = $this->User->findByCustomerId($credit["Credit"]["customer_id"]);
				$user["User"]["state"] = 1;
				$this->User->save($user);
			}
		}

		$cuotes = $this->Session->read("CUOTESP");

		$this->loadModel("Payment");
		$this->Payment->setReceipts();

		if(!empty($cuotes)){
			$this->sendReceipt($cuotes,$credit["Credit"]["id"],$dataWeb);
		}
		die;

		$this->Session->setFlash(__('Pago realizado correctamente'), 'flash_success');

	}

	public function sendReceipt($quotes,$creditId,$dataWeb = null){

		$credit = $this->Credit->findById($creditId);

		if(empty($credit["Customer"]["email"])){
			return false;
		}

		$quotaID 		= $this->Session->read("CUOTAID");

		$this->loadModel("Payment");

		$cuotaData 		= $this->Payment->findById($quotaID);

		$shopCommerce 	= $this->Credit->CreditsRequest->ShopCommerce->findById($credit["CreditsRequest"]["shop_commerce_id"]);


		$options = [
			"subject" 	=> "Pago realizado al crédito: ".$credit["CreditsRequest"]["code_pay"],
			"to"   		=> $credit["Customer"]["email"],
			"vars" 	    => ["credit" => $credit,"quotes" => $quotes,"shop_commerce" => $shopCommerce,"cuotaData"=>$cuotaData,"dataWeb" => $dataWeb],
			"template"	=> "payment_quote",
		];

		$this->sendMail($options);
	}

	private function paymentTotalCredit($cuotes,$creditId,$dataWeb = null){
		$num 			= 0;
		$credit 		= $this->Credit->find("first",["conditions"=>["Credit.id"=>$creditId],"recursive" => 1]);
		$capitalTotal 	= 0;

		foreach ($cuotes as $key => $value) {
			$capitalTotal += ($value["CreditsPlan"]["capital_value"]-$value["CreditsPlan"]["capital_payment"]);
		}
		foreach ($cuotes as $key => $value) {
			if($num == 0){
				$fechaRefIni 	= is_null($credit["Credit"]["last_payment_date"]) ? date("Y-m-d",strtotime($credit["Credit"]["created"])) : $credit["Credit"]["last_payment_date"];

				$deadline 		= new DateTime($fechaRefIni);
				$nowDate 		= new DateTime(date("Y-m-d"));
				$difference 	= $deadline->diff($nowDate);
				$days			= $difference->days;

				if ($days <= 30) {
					$interesesPasados 	= $value["CreditsPlan"]["interest_value"];
					$interesesOther 	= $value["CreditsPlan"]["others_value"];
				}else{
					$interesesPasados 	= $value["CreditsPlan"]["interest_value"];
					$interesesOther 	= $value["CreditsPlan"]["others_value"];
				}

				$paymentValue 	= 0;

				$this->loadModel("Payment");

				// if( $value["CreditsPlan"]["others_add"] > 0 && $valorPago > 0 ){
				// 	$paymentDebt = [
				// 		"Payment" => [
				// 			"credits_plan_id" => $value["CreditsPlan"]["id"],
				// 			"value"			  => $valorPago > $value["CreditsPlan"]["others_add"] ? $value["CreditsPlan"]["others_add"] : $valorPago,
				// 			"user_id"		   => AuthComponent::user("id"),
				// 			"shop_commerce_id" => AuthComponent::user("role") == 11 ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
				// 			"type"			   => 6,
				// 			"web"		   	   => $dataWeb,
				// 			"juridic"		   => AuthComponent::user("role") == 11 ? 1 : 0
				// 		]
				// 	];
				// 	$this->Payment->create();
				// 	if($this->Payment->save($paymentDebt)){
				// 		$this->Session->write("CUOTAID",$this->Payment->id);
				// 		$valorPago -= $paymentDebt["Payment"]["value"];
				// 		$paymentValue += $paymentDebt["Payment"]["value"];
				// 		$value["CreditsPlan"]["date_debt"] 	= date("Y-m-d");
				// 	}else{
				// 		$this->log($this->Payment->validationErrors,"debug");
				// 	}
				// }

				// if( $value["CreditsPlan"]["interest_add"] > 0 && $valorPago > 0 ){
				// 	$paymentDebt = [
				// 		"Payment" => [
				// 			"credits_plan_id" => $value["CreditsPlan"]["id"],
				// 			"value"			  => $valorPago > $value["CreditsPlan"]["interest_add"] ? $value["CreditsPlan"]["interest_add"] : $valorPago,
				// 			"user_id"		   => AuthComponent::user("id"),
				// 			"shop_commerce_id" => AuthComponent::user("role") == 11 ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
				// 			"type"			   => 7,
				// 			"web"		   	   => $dataWeb,
				// 			"juridic"		   => AuthComponent::user("role") == 11 ? 1 : 0
				// 		]
				// 	];
				// 	$this->Payment->create();
				// 	if($this->Payment->save($paymentDebt)){
				// 		$this->Session->write("CUOTAID",$this->Payment->id);
				// 		$valorPago -= $paymentDebt["Payment"]["value"];
				// 		$paymentValue += $paymentDebt["Payment"]["value"];
				// 		$value["CreditsPlan"]["date_debt"] 	= date("Y-m-d");
				// 	}else{
				// 		$this->log($this->Payment->validationErrors,"debug");
				// 	}
				// }

				// if( $value["CreditsPlan"]["debt_add"] > 0 && $valorPago > 0 ){
				// 	$paymentDebt = [
				// 		"Payment" => [
				// 			"credits_plan_id" => $value["CreditsPlan"]["id"],
				// 			"value"			  => $valorPago > $value["CreditsPlan"]["debt_add"] ? $value["CreditsPlan"]["debt_add"] : $valorPago,
				// 			"user_id"		   => AuthComponent::user("id"),
				// 			"shop_commerce_id" => AuthComponent::user("role") == 11 ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
				// 			"type"			   => 8,
				// 			"web"		   	   => $dataWeb,
				// 			"juridic"		   => AuthComponent::user("role") == 11 ? 1 : 0
				// 		]
				// 	];
				// 	$this->Payment->create();
				// 	if($this->Payment->save($paymentDebt)){
				// 		$this->Session->write("CUOTAID",$this->Payment->id);
				// 		$valorPago -= $paymentDebt["Payment"]["value"];
				// 		$paymentValue += $paymentDebt["Payment"]["value"];
				// 		$value["CreditsPlan"]["date_debt"] 	= date("Y-m-d");
				// 	}else{
				// 		$this->log($this->Payment->validationErrors,"debug");
				// 	}
				// }


				if($value["CreditsPlan"]["debt_value"] > 0 ){
					$paymentDebt = [
						"Payment" => [
							"credits_plan_id" => $value["CreditsPlan"]["id"],
							"value"			  => $value["CreditsPlan"]["debt_value"],
							"user_id"		   => AuthComponent::user("id"),
							"web"		   	   => $dataWeb,
							"shop_commerce_id" => AuthComponent::user("role") == 11 ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
							"type"			   => 4
						]
					];
					$this->Payment->create();
					if($this->Payment->save($paymentDebt)){
						$this->Session->write("CUOTAID",$this->Payment->id);
						$paymentValue += $value["CreditsPlan"]["debt_value"];

						$value["CreditsPlan"]["date_debt"] 	= date("Y-m-d");
					}
				}

				if($value["CreditsPlan"]["debt_honor"] > 0 ){
					$paymentHonor = [
						"Payment" => [
							"credits_plan_id"  => $value["CreditsPlan"]["id"],
							"value"			   => $value["CreditsPlan"]["debt_honor"],
							"user_id"		   => AuthComponent::user("id"),
							"web"		   	   => $dataWeb,
							"shop_commerce_id" => AuthComponent::user("role") == 11 ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
							"type"			   => 5
						]
					];
					$this->Payment->create();
					if($this->Payment->save($paymentHonor)){
						$this->Session->write("CUOTAID",$this->Payment->id);
						$paymentValue += $value["CreditsPlan"]["debt_honor"];
						$value["CreditsPlan"]["date_debt"] 		= date("Y-m-d");
					}
				}

				$paymentOthers = [
					"Payment" => [
						"credits_plan_id" => $value["CreditsPlan"]["id"],
						"value"			   => $interesesOther,
						"user_id"		   => AuthComponent::user("id"),
						"web"		   	   => $dataWeb,
						"shop_commerce_id" => AuthComponent::user("role") == 11 ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
						"type"			   => 3
					]
				];

				$this->Payment->create();
				if($this->Payment->save($paymentOthers)){
					$this->Session->write("CUOTAID",$this->Payment->id);
					$paymentValue += $paymentOthers["Payment"]["value"];
					$value["CreditsPlan"]["others_payment"] 	+= $paymentOthers["Payment"]["value"];
				}

				$paymentInteres = [
					"Payment" => [
						"credits_plan_id" => $value["CreditsPlan"]["id"],
						"value"			  => $interesesPasados,
						"user_id"		   => AuthComponent::user("id"),
						"web"		   	   => $dataWeb,
						"shop_commerce_id" => AuthComponent::user("role") == 11 ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
						"type"			   => 2
					]
				];

				$this->Payment->create();
				if($this->Payment->save($paymentInteres)){
					$this->Session->write("CUOTAID",$this->Payment->id);
					$paymentValue += $paymentInteres["Payment"]["value"];
					$value["CreditsPlan"]["interest_payment"] 	+= $paymentInteres["Payment"]["value"];
				}

				$valorPagoCapital = $value["CreditsPlan"]["capital_value"]-$value["CreditsPlan"]["capital_payment"];

				$paymentCapital = [
					"Payment" => [
						"credits_plan_id" =>  $value["CreditsPlan"]["id"],
						"value"			  =>  $valorPagoCapital,
						"user_id"		   => AuthComponent::user("id"),
						"web"		   	   => $dataWeb,
						"shop_commerce_id" => AuthComponent::user("role") == 11 ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
						"type"			   => 1
					]
				];
				$this->Payment->create();
				if($this->Payment->save($paymentCapital)){
					$paymentID = $this->Payment->id;
					$this->Session->write("CUOTAID",$this->Payment->id);

					$credito = $this->Credit->find("first",["recursive" => -1, "conditions" => ["Credit.id" => $value["CreditsPlan"]["credit_id"] ] ]);

					$credito["Credit"]["value_pending"]-=$paymentCapital["Payment"]["value"];

					$this->Credit->save($credito);

					$paymentValue += $paymentCapital["Payment"]["value"];

					$value["CreditsPlan"]["capital_payment"] 	+= $paymentCapital["Payment"]["value"];
					if($value["CreditsPlan"]["capital_payment"] == $value["CreditsPlan"]["capital_value"]){
						$value["CreditsPlan"]["state"] = 1;
					}

					if($value["CreditsPlan"]["debt_value"] > 0 || $value["CreditsPlan"]["debt_honor"] > 0){
						$this->loadModel("CreditLimit");
						$this->CreditLimit->updateAll(
							["CreditLimit.active" => 0],
							[
								"CreditLimit.state"  => [1,3,4,5],
								"CreditLimit.customer_id" => $credit["Credit"]["customer_id"]
							]
						);
					}else{
						$this->loadModel("CreditsRequest");
						$dateLimit 	= $this->Credit->field("deadline",[ "id"=>$value["CreditsPlan"]["credit_id"] ]);
						$datosLimit = [
							"CreditLimit" => [
								"value" 	 			=> $paymentCapital["Payment"]["value"],
								"state" 	 			=> 5,
								"reason"	 			=> "Preaprobado por restante de solicitud",
								"type_movement" 		=> 1,
								"credits_request_id" 	=> $credit["Credit"]["credits_request_id"],
								"user_id"			 	=> AuthComponent::user("id"),
								"deadline"			 	=> $dateLimit,
								"customer_id"			=> $credit["Credit"]["customer_id"],
								"credit_id"				=> $value["CreditsPlan"]["credit_id"],
								"active" 				=> 1,
								"payment_id"			=> $paymentID
							]
						];
						$this->CreditsRequest->CreditLimit->create();
						$this->CreditsRequest->CreditLimit->save($datosLimit);
					}

				}

				$value["CreditsPlan"]["date_payment"] 	= date("Y-m-d");
				$this->Credit->CreditsPlan->save($value);

				if($paymentValue > 0){
					$cuotes = $this->Session->read("CUOTESP");
					$cuotes[$value["CreditsPlan"]["number"]] = $paymentValue;
					$this->Session->write("CUOTESP",$cuotes);

					//$this->sendReceipt($value["CreditsPlan"]["id"],$value["CreditsPlan"]["credit_id"],$paymentValue);
				}
				$num++;
			}else{
				$total = $value["CreditsPlan"]["capital_value"]-$value["CreditsPlan"]["capital_payment"];

				if ($value["CreditsPlan"]["debt_value"] > 0 || $value["CreditsPlan"]["debt_honor"] > 0) {

					$total += $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"];
					$total += $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"];
					$total += $value["CreditsPlan"]["debt_value"];
					$total += $value["CreditsPlan"]["debt_honor"];
				}

				$this->payment_cuotes_normal($total,$value,true,$dataWeb);
			}
		}
	}


	public function payment_view($creditId){
		$creditId 		= $this->decrypt($creditId);
		$cuotes 		= $this->Session->read("CUOTESP");

		$quotaID 		= $this->Session->read("CUOTAID");

		$this->loadModel("Payment");

		$cuotaData 		= $this->Payment->findById($quotaID);
		$credit 		= $this->Credit->findById($creditId);
		$shopCommerce 	= $this->Credit->CreditsRequest->ShopCommerce->findById($credit["CreditsRequest"]["shop_commerce_id"]);

		$totalCredit 	= $this->Payment->CreditsPlan->getCreditDeuda($credit["Credit"]["id"]);

		$numbers = [];
		$cuotasId = [];

		foreach ($cuotes as $key => $value) {
			$numbers[] = $key;
		}

		$quotesData = $this->Payment->CreditsPlan->findAllByNumberAndCreditId($numbers,$credit["Credit"]["id"]);

		if (!empty($quotesData)) {
			foreach ($quotesData as $key => $value) {
				$cuotasId[$value["CreditsPlan"]["number"]] = $value["CreditsPlan"]["id"];
			}
		}

		$saldoCliente = $this->totalQuote(true, $credit["Credit"]["customer_id"]);

		$this->set("credit",$credit);
		$this->set("quotes",$cuotes);
		$this->set("totalCredit",$totalCredit);
		$this->set("cuotasId",$cuotasId);
		$this->set("saldoCliente",$saldoCliente);
		$this->set("shop_commerce",$shopCommerce);
		$this->set("cuotaData",$cuotaData);
	}
}
