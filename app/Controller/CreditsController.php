<?php

require_once '../Vendor/spreadsheet/vendor/autoload.php';

use Cake\Log\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

//use Cake\ORM\TableRegistry;

set_time_limit(0);

App::uses('AppController', 'Controller');
date_default_timezone_set('America/Bogota');

class CreditsController extends AppController
{

    public $components = array('Paginator');

    public function beforeFilter()
    {
        parent::beforeFilter();
      //  $this->Auth->allow('search_user', 'set_debts', 'get_credit_customer', 'get_data_payment', "payment_web", "payment_web_response");
		$this->Auth->allow('search_user', 'set_debts', 'get_credit_customer', 'get_data_payment', "payment_web", "payment_web_response","payment_crediventas","get_payment_data","plan_payemts_pdf","filter_crediventas","last_quote_capital");
	}

    public function customer_request() {
        if ($this->request->is("post")) {

            $data = $this->request->data;
            $this->loadModel("Customer");

            $customer = $this->Customer->find("first",["conditions" => ["identification"=>$data["Customer"]["identification"],"type"=>0],"recursive" => -1 ]);

            if(empty($customer)){
                $this->Customer->Create();
                $customer = $data["Customer"];
            }else{
                $customer["Customer"] = array_merge($customer["Customer"],$data["Customer"]);
            }

            if($this->Customer->save($customer)){
                $customerID = $this->Customer->id;

                $this->Customer->CustomersPhone->deleteAll(array('CustomersPhone.customer_id' => $customerID), false);
                $this->Customer->CustomersAddress->deleteAll(array('CustomersAddress.customer_id' => $customerID), false);
                $this->Customer->CustomersReference->deleteAll(array('CustomersReference.customer_id' => $customerID), false);

                if(!empty($data["CustomersReference"])){
                  foreach ($data["CustomersReference"] as $key => $value) {
                    $value["customer_id"] = $customerID;
                    $this->Customer->CustomersReference->create();
                    $this->Customer->CustomersReference->save($value);
                  }
                }

                if(!empty($data["CustomersAddress"])){
                  $data["CustomersAddress"]["customer_id"] = $customerID;
                  $this->Customer->CustomersAddress->create();
                  $this->Customer->CustomersAddress->save($data["CustomersAddress"]);
                }

                if(!empty($data["CustomersPhone"])){
                  foreach ($data["CustomersPhone"] as $key => $value) {
                    $value["customer_id"] = $customerID;
                    if(!empty($value["phone_number"])){
                      $this->Customer->CustomersPhone->create();
                      $this->Customer->CustomersPhone->save($value);
                    }
                  }
                }

                $this->loadModel("CreditsLine");
                $this->CreditsLine->recursive = -1;
                $creditLine = $this->CreditsLine->findByState(1);
                $creditLineId = $creditLine["CreditsLine"]["id"];

                $dataRequest = [
                  "CreditsRequest" => [
                    "customer_id" => $customerID,
                    "request_value" => 100000,
                    "request_number" => 1,
                    "credits_line_id" => is_null($creditLineId) ? 1 : $creditLine["CreditsLine"]["id"],
                    "empresa_id" => AuthComponent::user("empresa_id"),
                    "request_type" => 1,
                    "type" => 1,
                    "complete" => 1,
                  ]
                ];
                $this->loadModel("CreditsRequest");
                $this->CreditsRequest->create();
                if ($this->CreditsRequest->save($dataRequest)) {
                    $requestID = $this->CreditsRequest->id;
                    $this->loadModel("Document");
                    if(!empty($data["Document"])){
                      foreach ($data["Document"] as $key => $value) {

                        if (empty($value["file"]["name"])) {
                            continue;
                        }

                        $value["credits_request_id"] = $requestID;
                        $value["user_id"]       = AuthComponent::user("id");
                        $value["state"]         = 1;
                        $value["type"]          = 0;
                        $value["state_request"] = 1;
                        $this->Document->create();
                        $this->Document->save($value);
                      }
                    }

                    $this->Session->setFlash(__('Solicitud creada correctamente'), 'flash_success');
                }
            }else{
                $this->Session->setFlash(__('No se pudo crear la orden'), 'flash_error');
            }

            $this->redirect(["action"=>"customer_request"]);

        }
    }

    public function last_quote_capital($ini = 0,$limit = 100){
        $this->autoRender = false;
        $querySqlIds = "SELECT id FROM credits as Credit WHERE state = 0 and id IN ( SELECT credit_id FROM credits_plans WHERE days > 0 AND state = 0 ) AND Id NOT IN ( SELECT credit_id FROM credits_plans WHERE credit_old = 10 ) AND juridico = 0 AND credits_request_id != 0 LIMIT $ini,$limit;";

        $response = $this->Credit->query($querySqlIds);
        if (!empty($response)) {
            $ids = Set::extract($response,"{n}.Credit.id");
            foreach ($ids as $key => $value) {
                $this->Credit->CreditsPlan->getMinValue($value);
            }
            echo count($ids)." créditos aplicados";
        }else{
            echo "No hay créditos por aplicar";
        }
    }

	public function filter_crediventas($state = 0){
        $this->autoRender = false;
        $this->loadModel("Receipt");

        $receipts = $this->Receipt->find("list",["fields"=>["id","id"],
            "joins" => [['table' => 'payments','alias' => 'Payment','type' => 'INNER','conditions' => array('Payment.receipt_id = Receipt.id AND Payment.state_credishop ='.$state)]],
            "conditions" => ["Receipt.ext" => 1]
        ]);

        $list = [];

        if (!empty($receipts)) {
            foreach ($receipts as $key => $value) {
                $list[] = $this->encrypt($value);
            }
        }
        return json_encode($list);
    }

	public function get_payment_data(){
        $this->autoRender = false;
        $this->loadModel("Customer");
        $this->loadModel("Receipt");

        $receipt        = $this->request->data["receipt"];
        $identification = $this->request->data["identification"];
        $state          = 0;
        $customer       = $identification;

        $this->Customer->recursive = -1;
        $customerData = $this->Customer->findByIdentificationAndType($identification,1);

        if (!empty($customerData)) {
            $customer = $identification." - ".$customerData["Customer"]["name"]." ".$customerData["Customer"]["last_name"];
        }

        $receiptData  = $this->Receipt->findById($this->decrypt($receipt));

        if (!empty($receiptData) && isset($receiptData["Payment"]) && !empty($receiptData["Payment"])) {
            $pagoData   = end($receiptData["Payment"]);
            $state      = $pagoData["state_credishop"];
        }
        return json_encode(compact("state","customer"));
    }


    public function centrales()
    {
        $limit = 400;
        if (!isset($this->request->query["ini"])) {
            $fechaInicioReporte = date("Y-m-d");
        } else {
            $fechaInicioReporte = $this->request->query["ini"];
        }

        if (!isset($this->request->query["end"])) {
            $fechaFinReporte = date("Y-m-d");
        } else {
            $fechaFinReporte = $this->request->query["end"];
        }

        $dateEnd = date("Y-m-t");
        $conditions = [ "OR" => [
            ["Credit.credits_request_id != " => 0, "Credit.state" => 0],
            ["Credit.state"=>1,"Credit.credits_request_id != " => 0,'Credit.last_payment_date BETWEEN ? AND ?' => array(date("Y-m-1"), date("Y-m-t"))]
         ] ];
        $data = $this->Credit->find("count",compact("conditions"));

        $pages      = $data / $limit;
        $roundPages = round($pages);
        $difPages   = $pages - $roundPages;

        $totalPages = $difPages <= 0 ? $roundPages : $roundPages + 1;

        $pages = [];

        for ($i=1; $i <= $totalPages ; $i++) {
            $pages[$i] = "Página ${i}";
        }

        if ($this->request->is("post")) {


            if ($this->request->data["Credit"]["type"] == 1) {
                $this->datacredito($this->request->data, $conditions);
            } else {
                $this->procredito($data, $conditions);
            }
        }

        $this->set(compact("fechaInicioReporte", "fechaFinReporte","data","totalPages","limit","pages"));
    }

    private function procredito($data, $conditions)
    {

        die;

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
            $credits = $this->Credit->find("all", ["conditions" => $conditions]);

        } catch (Exception $e) {
            $credits = [];
        }

        if (!empty($credits)) {
            $i = 2;
            foreach ($credits as $key => $value) {
                $this->Credit->Customer->unBindModel(["hasMany" => ["Credit", "CustomersReference", "CreditsRequest", "User"]]);
                $value["Customer"] = $this->Credit->Customer->findById($value["Customer"]["id"]);
                $value["saldos"] = $this->calculateTotales($value["Credit"], $value["CreditsPlan"]);

                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, "1");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $value["Customer"]["Customer"]["identification"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $value["Customer"]["Customer"]["name"] . " " . $value["Customer"]["Customer"]["last_name"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, "1");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, "00");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, "15");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, $value["Credit"]["credits_request_id"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, "3");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, "");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, date("d/m/Y", strtotime($value["Credit"]["created"])));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, $value["Credit"]["type"] == "1" ? "30" : "15");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, $value["CreditsRequest"]["value_disbursed"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, "");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, $value["Credit"]["value_pending"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('O' . $i, $value["saldos"]["debt"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('P' . $i, $value["Credit"]["number_fee"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q' . $i, $value["saldos"]["totalCanceladas"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('R' . $i, $value["saldos"]["totalDebt"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('S' . $i, $value["Credit"]["quota_value"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('T' . $i, $value["CreditsRequest"]["value_disbursed"] - $value["Credit"]["value_pending"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('U' . $i, $value["saldos"]["dias"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('V' . $i, date("Ymd", strtotime($value["Credit"]["last_payment_date"])));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('W' . $i, date("d/m/Y", strtotime($value["Credit"]["deadline"])));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('X' . $i, $value["CreditsRequest"]["value_approve"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('Y' . $i, $value["CreditsRequest"]["value_disbursed"]);

                $spreadsheet->setActiveSheetIndex(0)->setCellValue('Z' . $i, $value["Customer"]["Customer"]["type_contract"] == "Indefinido" ? "1" : "0");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('AA' . $i, "");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('AB' . $i, "");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('AC' . $i, $value["Credit"]["state"] == 1 ? "0" : "");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('AD' . $i, "");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('AE' . $i, "COLOMBIA");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('AF' . $i, $this->getDepartMent($value["Customer"]["CustomersAddress"]["0"]["address_city"]));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('AG' . $i, strtoupper($value["Customer"]["CustomersAddress"]["0"]["address_city"]));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('AH' . $i, "1");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('AI' . $i, strtoupper($value["Customer"]["CustomersAddress"]["0"]["address"]));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('AJ' . $i, "2");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('AK' . $i, strtoupper($value["Customer"]["CustomersPhone"]["0"]["phone_number"]));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('AL' . $i, "2");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('AM' . $i, $value["Customer"]["Customer"]["email"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('AN' . $i, "");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('AO' . $i, "");

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

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $name = "files/procredito_" ."_".date("Y-m-d"). ".xlsx";
        $writer->save($name);

        $url = Router::url("/", true) . $name;
        $this->redirect($url);
    }

    private function getDepartMent($ciudad)
    {
        $datos = Configure::read("CIUDADES");

        foreach ($datos as $departamento => $ciudades) {
            foreach ($ciudades as $key => $value) {
                if ($ciudad == $value) {
                    return $departamento;
                }
            }
        }
    }

    private function eliminar_tildes($cadena){

        //Codificamos la cadena en formato utf8 en caso de que nos de errores
        $cadena = utf8_encode($cadena);

        //Ahora reemplazamos las letras
        $cadena = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $cadena
        );

        $cadena = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $cadena );

        $cadena = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $cadena );

        $cadena = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $cadena );

        $cadena = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $cadena );

        $cadena = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $cadena
        );

        return $cadena;
    }

    public function juridico() {
        $this->loadModel("CreditsPlan");
        $this->CreditsPlan->update_cuotes_days();
        $filter = false;

        if(isset($this->request->query["q"])){
            $customer = $this->request->query["q"];
        }else{
            $customer = null;
        }

        $conditions = [ "CreditsPlan.state" => 0, "Credit.credits_request_id !=" => 0,"Credit.juridico" => 1 ];

        if(!is_null($customer) && !empty($customer) ){
            $conditions["Customer.identification"] = $customer;
            $filter = true;
        }

        if (!isset($this->request->query["ini"])) {
            $fechaInicioReporte = date("Y-m-d");
        } else {
            $fechaInicioReporte = $this->request->query["ini"];
        }

        if (!isset($this->request->query["end"])) {
            $fechaFinReporte = date("Y-m-d");
        } else {
            $fechaFinReporte = $this->request->query["end"];
        }

        if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
            $conditions["date_juridico >="] = $fechaInicioReporte;
            $conditions["date_juridico <="] = $fechaFinReporte;
            $filter = true;
        }

        $options = [
            "fields" => ['CreditsPlan.id','CreditsPlan.deadline','CreditsPlan.date_debt','CreditsPlan.credit_id','days AS dias',' Customer.*'],
            "joins"  => [
                ['table' => 'credits','alias' => 'Credit','type' => 'INNER','conditions' => array('Credit.id = CreditsPlan.credit_id')],
                ['table' => 'customers','alias' => 'Customer','type' => 'INNER','conditions' => array('Customer.id = Credit.customer_id')],
            ],
            "conditions" => $conditions,
            "recursive"  => -1,
            "limit"      => 20,
        ];

        if (!isset($this->request->query["excel_data"])) {
            $this->Paginator->settings = $options;
            $datos = $this->Paginator->paginate("CreditsPlan");
        }else{
            unset($options["limit"]);
            $datos = $this->CreditsPlan->find("all",$options);
        }

        if (!empty($datos)) {
            foreach ($datos as $key => $value) {
                $datosCuotas = $this->CreditsPlan->getCuotesInformation($value["CreditsPlan"]["credit_id"],$value["CreditsPlan"]["id"], null, 1);
                $datos[$key] = array_merge($datos[$key],$datosCuotas);
                $datos[$key]["saldos"] = $this->CreditsPlan->getCreditDeuda($value["CreditsPlan"]["credit_id"],null,null,true);
            }
        }

        if (isset($this->request->query["excel_data"])) {
            $this->autoRender = false;
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            $spreadsheet->getProperties()->setCreator('CREDISHOP')
                ->setLastModifiedBy('CREDISHOP')
                ->setTitle('Créditos jurídicos')
                ->setSubject('Créditos jurídicos')
                ->setDescription('Créditos jurídicos ZÍRO')
                ->setKeywords('Créditos jurídicos')
                ->setCategory('Créditos jurídicos');

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'ID Crédito')
                ->setCellValue('B1', 'Cliente')
                ->setCellValue('C1', 'Cédula')
                ->setCellValue('D1', 'Mora')
                ->setCellValue('E1', 'Honorarios')
                ->setCellValue('F1', 'Valor Cuota')
                ->setCellValue('G1', 'Estado Cuota')
                ->setCellValue('H1', 'Valor Mora/Honorarios/Intereses')
                ->setCellValue('I1', 'Saldo total Cuota')
                ->setCellValue('J1', 'Valor capital restante')
                ->setCellValue('K1', 'Valor total del crédito')
                ->setCellValue('L1', 'Fecha de retiro credito')
                ->setCellValue('M1', 'Fecha de reporte jurídico');

            if (!empty($datos)) {
                $i = 2;
                foreach ($datos as $key => $valueVal) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, $valueVal["Credit"]["code_pay"]);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $valueVal["Customer"]["name"]);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $valueVal["Customer"]["identification"] );
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $valueVal["CreditsPlan"]["days"] < 0 ? 0 : $valueVal["CreditsPlan"]["days"]);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, $valueVal["CreditsPlan"]["debt_honor"]);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, $valueVal["Credit"]["quota_value"]);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, $valueVal["Credit"]["state"] == 1 ? "Pagada" : "Sin pagar");
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $valueVal["CreditsPlan"]["debt_value"]+$valueVal["CreditsPlan"]["debt_honor"]);

                    $totalDeuda = $valueVal["CreditsPlan"]["debt_value"]+$valueVal["CreditsPlan"]["debt_honor"] + ($valueVal["CreditsPlan"]["capital_value"]-$valueVal["CreditsPlan"]["capital_payment"]) + ($valueVal["CreditsPlan"]["interest_value"]-$valueVal["CreditsPlan"]["interest_payment"]) + ($valueVal["CreditsPlan"]["others_value"]-$valueVal["CreditsPlan"]["others_payment"]);

                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $totalDeuda);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, $valueVal["Credit"]["value_pending"]);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, $valueVal["saldos"]);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, $valueVal["Credit"]["created"]);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, $valueVal["Credit"]["date_juridico"]);
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

            $spreadsheet->getActiveSheet()->setTitle('Créditos jurícos');
            $spreadsheet->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);
            //$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

            $writer = IOFactory::createWriter($spreadsheet, 'Xls');
            $name = "files/junidico_" . time() . ".xls";
            $writer->save($name);

            $url = Router::url("/", true) . $name;
            $this->redirect($url);

            die;
        }

        $this->set("datos",$datos);
        $this->set("fechaInicioReporte",$fechaInicioReporte);
        $this->set("fechaFinReporte",$fechaFinReporte);
        $this->set("filter",$filter);
    }

    private function datacredito($data, $conditions)
    {

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
            $this->Credit->unBindModel(["hasMany"=>["CreditLimit"]]);
            $credits = $this->Credit->find("all", ["fields" => ["Credit.*","CreditsRequest.*"] , "conditions" => $conditions,"limit" => $data["Credit"]["limit"],"page"=>$data["Credit"]["page"], "order" => ["Credit.deadline" => "DESC"] ]);

        } catch (Exception $e) {
            $credits = [];
        }

        if (!empty($credits)) {
            $i = 2;
            foreach ($credits as $key => $value) {
                $this->Credit->Customer->unBindModel(["hasMany" => ["Credit", "CustomersReference", "CreditsRequest", "User","CreditLimit"]]);
                $value["Customer"]  = $this->Credit->Customer->findById($value["Credit"]["customer_id"]);
                $value["saldos"]    = $this->calculateTotales($value["Credit"], $value["CreditsPlan"]);

                $valorMora = $value["saldos"]["debt"] + $value["Credit"]["value_pending"];
                $valorMora = $valorMora <= 1 || $value["Credit"]["state"] == 1 || $value["saldos"]["debt"] <= 0 ? 0 : $valorMora;
                $value_pending = $value["Credit"]["value_pending"] <= 1 ? 0 : $value["Credit"]["value_pending"];
                $disponible = $value["CreditsRequest"]["value_disbursed"] - $value_pending;
                $disponible = $disponible <= 1 || $value["Credit"]["quote_days"] >= 10 ? 0 : $disponible;

                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, "1");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $value["Customer"]["Customer"]["identification"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, strtoupper( $this->eliminar_tildes( strtolower( utf8_decode($value["Customer"]["Customer"]["name"]) . " " . $value["Customer"]["Customer"]["last_name"] ) )  ) );
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $value["Credit"]["credits_request_id"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, date("Ymd", strtotime($value["Credit"]["created"])));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, date("Ymd", strtotime($value["Credit"]["deadline"])));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, "00");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $this->getNovedadAndData($value["Credit"], $value["saldos"]));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, "0");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, $value["CreditsRequest"]["value_disbursed"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, $value_pending);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, $disponible);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, $value["Credit"]["quota_value"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, $valorMora);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('O' . $i, $value["Credit"]["number_fee"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('P' . $i, $value["saldos"]["totalCanceladas"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q' . $i, $value["Credit"]["number_fee"] == $value["saldos"]["totalCanceladas"] || $value["Credit"]["state"] == 1 ? 0 : $value["saldos"]["totalDebt"] );
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('R' . $i, date("Ymd", strtotime($value["saldos"]["deadline"])));

                $fechaDePago = is_null($value["saldos"]["lastDate"]) ? "" : $value["saldos"]["lastDate"];

                $spreadsheet->setActiveSheetIndex(0)->setCellValue('S' . $i, $fechaDePago);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('T' . $i, strtoupper(empty($value["Customer"]["CustomersAddress"]) ? "" : $value["Customer"]["CustomersAddress"]["0"]["address_city"]));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('U' . $i, strtoupper(empty($value["Customer"]["CustomersAddress"]) ? "" : $value["Customer"]["CustomersAddress"]["0"]["address"]));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('V' . $i, $value["Customer"]["Customer"]["email"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('W' . $i, isset($value["Customer"]["CustomersPhone"]["0"]["phone_number"]) ? $value["Customer"]["CustomersPhone"]["0"]["phone_number"] : "" );

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

        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
        $name = "files/datacredito_" . time() . ".xls";
        $writer->save($name);

        $url = Router::url("/", true) . $name;
        $this->redirect($url);
    }

    private function getNovedadAndData($credit, $saldos)
    {

        $code = "01";
        if ($credit["juridico"] == 1) {
            $code = "13";
        } elseif ($credit["state"] == 1) {
            $code = "05";
        } elseif ($saldos["debt"] > 0 && $saldos["dias"] > 0 && $saldos["dias"] <= 30) {
            $code = "06";
        } elseif ($saldos["debt"] > 0 && $saldos["dias"] > 31 && $saldos["dias"] <= 60) {
            $code = "07";
        } elseif ($saldos["debt"] > 0 && $saldos["dias"] > 61 && $saldos["dias"] <= 90) {
            $code = "08";
        } elseif ($saldos["debt"] > 0 && $saldos["dias"] > 91) {
            $code = "09";
        } elseif ($credit["state"] == 0 || $credit["state"] == 1) {
            $code = "01";
        }



        return $code;
    }

    public function set_debts()
    {
        $this->autoRender = false;
        $this->Credit->CreditsPlan->validateSaldo();
    }

    public function cartera()
    {
        if (!isset($this->request->query["tab"])) {
            $this->redirect(["action" => "intereses", "?" => ["tab" => 1]]);
        }

        $this->Credit->CreditsPlan->update_cuotes_days();
        $this->Credit->CreditsPlan->update_credits_days();

		if ($this->request->query["accion"]=='exportar') {
			$this->cartera_exporte_final();
		} else {
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
			$this->set("tab", $this->request->query["tab"]);
		}

    }

    public function cartera_exporte_final()
    {
		ini_set('max_execution_time', 0);
        $this->autoRender = false;
        if (!isset($this->request->query["tab"])) {
            $this->redirect(["action" => "intereses", "?" => ["tab" => 1]]);
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $spreadsheet->getProperties()->setCreator('CREDISHOP')
            ->setLastModifiedBy('CREDISHOP')
            ->setTitle('CARTERA')
            ->setSubject('CARTERA')
            ->setDescription('CARTERA ZÍRO')
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

	public function cupos() {
		$data=[];
		$i=0;

		$this->loadModel("Customer");
		if (isset($this->request->query["cedula"]) && !empty($this->request->query["cedula"])) {
			$conditions["Customer.identification" ] = $this->request->query["cedula"];
			$clientes = $this->Paginator->paginate($this->Customer, $conditions);

        } else {
			$clientes=$this->Customer->find("all");
			$clientes = $this->Paginator->paginate($this->Customer);
		}

		if (!isset($this->request->query['accion']) || $this->request->query['accion'] =='buscar') {
			$datos=$this->request->query;

			$this->set(compact("clientes","datos"));
		} else{
			$this->cuposExport();
		}

	}


	public function cuposExport() {

        $this->autoRender = false;
		$this->loadModel("Customer");
		$i=2;
		$this->loadModel("Customer");
		if (isset($this->request->query["cedula"]) && !empty($this->request->query["cedula"])) {
			$conditions["Customer.identification" ] = $this->request->query["cedula"];
			$clientes = $this->Customer->find("all", ["conditions" => $conditions]);
        } else {
			$clientes=$this->Customer->find("all");
		}

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getProperties()->setCreator('CREDISHOP')
		->setLastModifiedBy('CREDISHOP')
		->setTitle('CUPOS')
		->setSubject('CUPOS')
		->setDescription('CUPOS ZÍRO')
		->setKeywords('CUPOS')
		->setCategory('CUPOS');

		$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('A1', 'PROVEEDOR')
		->setCellValue('B1', 'COMERCIO')
		->setCellValue('C1', 'CODIGO COMERCIO')
		->setCellValue('D1', 'CÉDULA')
		->setCellValue('E1', 'NOMBRE COMPLETO')
		->setCellValue('F1', 'CELULAR')
		->setCellValue('G1', 'CUPO REAL')
		->setCellValue('H1', 'VALOR GASTADO')
		->setCellValue('I1', 'VALOR DISPONIBLE PARA GASTAR')
		->setCellValue('J1', 'MORA')
		->setCellValue('K1', 'FECHA REGISTRO');


		foreach ($clientes as $value) {
			if (!empty($value['Customer']['code'])) {
				$this->ShopCommerce = new ShopCommerce();
				$SearchShopCommerce = $this->ShopCommerce->buscarPorCodigo($value['Customer']['code']);
				if (!empty($SearchShopCommerce)) {
					$infoCliente= $this->getInfoCreditCliente($value);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'. $i, $infoCliente["shop"] );
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'. $i, $infoCliente["commerce"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'. $i, $infoCliente["commerceCode"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'. $i, $value["Customer"]["identification"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'. $i, ucfirst($value["Customer"]["name"])." ".ucfirst($value["Customer"]["last_name"]));
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'. $i, $value["Customer"]["celular"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'. $i, number_format($infoCliente["cupoTotal"]));
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'. $i, number_format($infoCliente["valorGastado"]));
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('I'. $i, number_format($infoCliente['valorLibre']));
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('J'. $i, $infoCliente['mora']=='true' ? 'SI' : '---');
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('K'. $i, $value["Customer"]['created']);
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
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->setTitle('Cupos Ziro');
        $spreadsheet->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $name = "files/cupos_ziro" ."_".date("Y-m-d"). ".xlsx";
        $writer->save($name);

		$url = Router::url("/", true) . $name;
		$this->redirect($url);

		var_dump($url);
		die;

	}

    private function credOtorgados()
    {

        $conditions = ["Credit.credits_request_id != " => 0];
        $totalCartera = 0;

        if (!isset($this->request->query["ini"])) {
            $fechaInicioReporte = date("Y-m-d");
        } else {
            $fechaInicioReporte = $this->request->query["ini"];
        }

        if (!isset($this->request->query["end"])) {
            $fechaFinReporte = date("Y-m-d");
        } else {
            $fechaFinReporte = $this->request->query["end"];
        }

        if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
            $conditions["DATE(Credit.created) >="] = $this->request->query["ini"];
            $conditions["DATE(Credit.created) <="] = $this->request->query["end"];
            $this->set("fechas", true);
        }

        $query = $this->request->query;
        if (isset($query["range"]) && !empty($query["range"])) {
            $valuesRange = explode(";", $query["range"]);
            if (count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])) {

                $conditions["Credit.value_request >= "] = $valuesRange[0];
                $conditions["Credit.value_request <= "] = $valuesRange[1];

                $min = $valuesRange[0];
                $max = $valuesRange[1];

            } else {
                $conditions["Credit.id"] = null;
            }
        } else {
            $min = 1;
            $max = 1000000;
        }

        if (isset($query['commerce']) && !empty($query['commerce'])) {
            $this->loadModel("ShopCommerce");
            $shopCommerce = $this->ShopCommerce->findByCode($query['commerce']);
            if (!empty($shopCommerce)) {
                $creditsRequests = Set::extract($shopCommerce["CreditsRequest"], "{n}.credit_id");
                foreach ($creditsRequests as $clave => $valor) {
                    if (empty($valor)) {
                        unset($creditsRequests[$clave]);
                    }

                }
                $conditions["Credit.id"] = $creditsRequests;
            } else {
                $conditions["Credit.id"] = null;
            }
            $this->Set("commerce", $query['commerce']);
        }

		if (isset($query['cedula']) && !empty($query['cedula'])) {
            $this->loadModel("Customer");
            $customer = $this->Customer->findByIdentification($query['cedula']);
            if (!empty($customer)) {

                $conditions["Credit.customer_id"] = $customer['Customer']['id'];
            } else {
				$conditions["Credit.customer_id"] = null;
			}
            $this->Set("cedula", $query['cedula']);
        }


        if (isset($query['n_obligacion']) && !empty($query['n_obligacion'])) {
            $conditions["Credit.code_pay LIKE"] = '%' . $query['n_obligacion'] . '%';
            $this->Set("n_obligacion", $query['n_obligacion']);
        }

        try {
            $this->Paginator->settings = ["conditions" => $conditions];
            $credits = $this->Paginator->paginate();

            if (!empty($credits)) {
                $this->loadModel("ShopCommerce");

                foreach ($credits as $key => $value) {
                    try {
                        $credits[$key]["Comercio"] = $this->ShopCommerce->findById($value["CreditsRequest"]['shop_commerce_id']);

                    } catch (Exception $e) {
                        $credits[$key]["Comercio"] = [];
                    }
                    $credits[$key]["Customer"] = $this->Credit->Customer->findById($value["Customer"]["id"]);
                }
            }

            $totalCartera = $this->Credit->find("first", ["conditions" => $conditions, "fields" => ["SUM(value_request) as total"]]);

            if (!empty($totalCartera)) {
                $totalCartera = $totalCartera["0"]["total"];
            }

        } catch (Exception $e) {
            $credits = [];
        }

        $this->set(compact("fechaInicioReporte", "fechaFinReporte", "min", "max", "credits", "totalCartera"));
    }

    private function credOtorgadosExport($spreadsheet)
    {
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'FECHA RETIRADO')
            ->setCellValue('B1', 'NÚMERO DE OBLIGACIÓN')
            ->setCellValue('C1', 'CÉDULA')
            ->setCellValue('D1', 'NOMBRE COMPLETO')
            ->setCellValue('E1', 'TELÉFONO')
            ->setCellValue('F1', 'DIRECCIÓN')
            ->setCellValue('G1', 'VALOR RETIRADO')
            ->setCellValue('H1', 'FRECUENCIA')
            ->setCellValue('I1', 'ESTADO DEL CRÉDITO')
            ->setCellValue('J1', 'NRO CUOTAS')
            ->setCellValue('K1', 'PROVEEDOR')
            ->setCellValue('L1', 'CORREO')
            ->setCellValue('M1', 'VALOR APROBADO')
            ->setCellValue('N1', 'FECHA DE PAGO')
            ->setCellValue('O1', 'FECHA PAGO CLIENTE');


        $conditions = ["Credit.credits_request_id != " => 0];

        if (!isset($this->request->query["ini"])) {
            $fechaInicioReporte = date("Y-m-d");
        } else {
            $fechaInicioReporte = $this->request->query["ini"];
        }

        if (!isset($this->request->query["end"])) {
            $fechaFinReporte = date("Y-m-d");
        } else {
            $fechaFinReporte = $this->request->query["end"];
        }

        if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
            $conditions["DATE(Credit.created) >="] = $this->request->query["ini"];
            $conditions["DATE(Credit.created) <="] = $this->request->query["end"];
            $this->set("fechas", true);
        }
        $query = $this->request->query;

        if (isset($query["range"]) && !empty($query["range"])) {
            $valuesRange = explode(";", $query["range"]);
            if (count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])) {

                $conditions["Credit.value_request >= "] = $valuesRange[0];
                $conditions["Credit.value_request <= "] = $valuesRange[1];

                $min = $valuesRange[0];
                $max = $valuesRange[1];

            } else {
                $conditions["Credit.id"] = null;
            }
        } else {
            $min = 0;
            $max = 1000000;
        }

        if (isset($query['commerce']) && !empty($query['commerce']) ) {
            $this->loadModel("ShopCommerce");
            $shopCommerce = $this->ShopCommerce->findByCode($query['commerce']);
            if (!empty($shopCommerce)) {
                $creditsRequests = Set::extract($shopCommerce["CreditsRequest"], "{n}.credit_id");
                foreach ($creditsRequests as $clave => $valor) {
                    if (empty($valor)) {
                        unset($creditsRequests[$clave]);
                    }

                }
                $conditions["Credit.id"] = $creditsRequests;
            } else {
                $conditions["Credit.id"] = null;
            }
            $this->Set("commerce", $query['commerce']);
        }

        try {
            $credits = $this->Credit->find("all", ["conditions" => $conditions]);

        } catch (Exception $e) {
            $credits = [];
        }

        if (!empty($credits)) {
            $i = 2;
            $this->loadModel("ShopCommerce");


            foreach ($credits as $key => $value) {
				$comercio = ["Comercio" => $this->ShopCommerce->findById($value["CreditsRequest"]['shop_commerce_id'])];

                $customer = $this->Credit->Customer->findById($value["Customer"]["id"]);

				if (!empty($customer) && !empty($comercio) && isset($comercio["Comercio"]["Shop"])) {
					//fechas de pago
					$fechasPago='';
					foreach ($value['CreditsPlan'] as $keyPlan=> $plan) {
						$fechasPago.=$plan['deadline'];
					}

					if ($value["Credit"]["type"] == 1)
						$tipoCredito= "Mensual";
					else if($value["Credit"]["type"] == 3)
						$tipoCredito= "45 días";
					else if($value["Credit"]["type"] == 4)
						$tipoCredito= "60 días";
					else
						$tipoCredito= "Quincenal";

					$direccion= !empty($customer["CustomersAddress"]["0"]["address"]) ? $customer["CustomersAddress"]["0"]["address"] : $customer["CustomersAddress"]["0"]["address_street"];

					$spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, date("d-m-Y", strtotime($value["Credit"]["created"])));
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $value["Credit"]["code_pay"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $value["Customer"]["identification"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $customer["Customer"]["name"] . " " . $customer["Customer"]["last_name"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, $customer["CustomersPhone"]["0"]["phone_number"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i,  $direccion);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, ($value["Credit"]["value_request"]));
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, ($tipoCredito));

					if ($value["Credit"]["debt"]) {
						$state = "Mora";
					} else {
						$state = $value["Credit"]["state"] == 1 ? "Cancelado" : "No finalizado";
					}
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $state);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, ($value["Credit"]["number_fee"]));
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, $comercio["Comercio"]["Shop"]["social_reason"] . " - " . $comercio["Comercio"]["ShopCommerce"]["name"] . " - " . $comercio["Comercio"]["ShopCommerce"]["code"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, ($customer["Customer"]["email"]));
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, ($value["Credit"]["value_aprooved"]));
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, ($fechasPago));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('O' . $i, ($value["Credit"]["last_payment_date"]));
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
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);

        $spreadsheet->getActiveSheet()->setTitle('Cartera otorgada');
        $spreadsheet->getActiveSheet()->getStyle('A1:O1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $name = "files/cartera_otorgados_" ."_".date("Y-m-d"). ".xlsx";
        $writer->save($name);
		$url = Router::url("/", true) . $name;
		$this->redirect($url);
        die;
    }

    private function credVigentes()
    {

        $conditions = ["Credit.credits_request_id != " => 0, "Credit.state" => 0];
        $totalCartera = 0;
        $fechas = false;

        if (!isset($this->request->query["ini"])) {
            $fechaInicioReporte = date("Y-m-d");
        } else {
            $fechaInicioReporte = $this->request->query["ini"];
        }

        if (!isset($this->request->query["end"])) {
            $fechaFinReporte = date("Y-m-d");
        } else {
            $fechaFinReporte = $this->request->query["end"];
        }

        if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
            $conditions["DATE(Credit.created) >="] = $this->request->query["ini"];
            $conditions["DATE(Credit.created) <="] = $this->request->query["end"];
            $this->set("fechas", true);
            $fechas = true;
        }
        $query = $this->request->query;

        if (isset($query["range"]) && !empty($query["range"])) {
            $valuesRange = explode(";", $query["range"]);
            if (count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])) {

                $conditions["Credit.value_request >= "] = $valuesRange[0];
                $conditions["Credit.value_request <= "] = $valuesRange[1];

                $min = $valuesRange[0];
                $max = $valuesRange[1];

            } else {
                $conditions["Credit.id"] = null;
            }
        } else {
            $min = 1;
            $max = 1000000;
        }

        if (isset($query['commerce']) && !empty($query['commerce'])) {
            $this->loadModel("ShopCommerce");
            $shopCommerce = $this->ShopCommerce->findByCode($query['commerce']);
            if (!empty($shopCommerce)) {
                $creditsRequests = Set::extract($shopCommerce["CreditsRequest"], "{n}.credit_id");
                foreach ($creditsRequests as $clave => $valor) {
                    if (empty($valor)) {
                        unset($creditsRequests[$clave]);
                    }

                }
                $conditions["Credit.id"] = $creditsRequests;
            } else {
                $conditions["Credit.id"] = null;
            }
            $this->Set("commerce", $query['commerce']);
        }


		if (isset($query['cedula']) && !empty($query['cedula'])) {
            $this->loadModel("Customer");
            $customer = $this->Customer->findByIdentification($query['cedula']);
            if (!empty($customer)) {

                $conditions["Credit.customer_id"] = $customer['Customer']['id'];
            } else {
				$conditions["Credit.customer_id"] = null;
			}
            $this->Set("cedula", $query['cedula']);
        }


		if (isset($query['n_obligacion']) && !empty($query['n_obligacion'])) {
			$conditions["Credit.code_pay"] = $query['n_obligacion'];
            $this->Set("n_obligacion", $query['n_obligacion']);
        }


        try {
            $this->Paginator->settings = ["conditions" => $conditions];
            $credits = $this->Paginator->paginate();

            if (!empty($credits)) {
                $this->loadModel("ShopCommerce");
                foreach ($credits as $key => $value) {
                    $this->Credit->Customer->unBindModel(["hasMany" => ["Credit", "CreditLimit", "CreditsRequest", "User"]]);
                    $credits[$key]["Customer"] = $this->Credit->Customer->findById($value["Customer"]["id"]);
                    $credits[$key]["saldos"] = $this->calculateTotales($value["Credit"], $value["CreditsPlan"],true);
                    try {
                        $credits[$key]["Comercio"] = $this->ShopCommerce->findById($value["CreditsRequest"]['shop_commerce_id']);

                    } catch (Exception $e) {
                        $credits[$key]["Comercio"] = [];
                    }
                }
            }

            $totalCartera = null;

            if ($fechas || isset($conditions["Credit.id"])) {
                $totalCartera = 0;
                $totalCarteraIds = $this->Credit->find("list", ["conditions" => $conditions, "fields" => ["id","id"]]);
                if (!empty($totalCarteraIds)) {
                    foreach ($totalCarteraIds as $key => $value) {
                        $totalCartera += $this->Credit->CreditsPlan->getCreditDeuda($value,null,null,true);
                    }
                }
            }

        } catch (Exception $e) {
            $credits = [];
        }

        $this->set(compact("fechaInicioReporte", "fechaFinReporte", "min", "max", "credits", "totalCartera"));
    }

    private function credVigentesExport($spreadsheet)
    {

		$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('A1', 'FECHA RETIRADO')
		->setCellValue('B1', 'NÚMERO DE OBLIGACIÓN')
		->setCellValue('C1', 'CÉDULA')
		->setCellValue('D1', 'NOMBRE COMPLETO')
		->setCellValue('E1', 'TELÉFONO')
		->setCellValue('F1', 'DIRECCIÓN')
		->setCellValue('G1', 'VALOR APROBADO')
		->setCellValue('H1', 'SALDO')
		->setCellValue('I1', 'PROVEEDOR')
		->setCellValue('J1', 'FECHA PAGO');

        $conditions = ["Credit.credits_request_id != " => 0, "Credit.state" => 0];
        $totalCartera = 0;

        if (!isset($this->request->query["ini"])) {
            $fechaInicioReporte = date("Y-m-d");
        } else {
            $fechaInicioReporte = $this->request->query["ini"];
        }

        if (!isset($this->request->query["end"])) {
            $fechaFinReporte = date("Y-m-d");
        } else {
            $fechaFinReporte = $this->request->query["end"];
        }

        if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
            $conditions["DATE(Credit.created) >="] = $this->request->query["ini"];
            $conditions["DATE(Credit.created) <="] = $this->request->query["end"];
            $this->set("fechas", true);
        }
        $query = $this->request->query;

        if (isset($query["range"]) && !empty($query["range"])) {
            $valuesRange = explode(";", $query["range"]);
            if (count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])) {

                $conditions["Credit.value_request >= "] = $valuesRange[0];
                $conditions["Credit.value_request <= "] = $valuesRange[1];

                $min = $valuesRange[0];
                $max = $valuesRange[1];

            } else {
                $conditions["Credit.id"] = null;
            }
        } else {
            $min = 1;
            $max = 1000000;
        }

        if (isset($query['commerce']) && !empty($query['commerce'])) {
            $this->loadModel("ShopCommerce");
            $shopCommerce = $this->ShopCommerce->findByCode($query['commerce']);
            if (!empty($shopCommerce)) {
                $creditsRequests = Set::extract($shopCommerce["CreditsRequest"], "{n}.credit_id");
                foreach ($creditsRequests as $clave => $valor) {
                    if (empty($valor)) {
                        unset($creditsRequests[$clave]);
                    }

                }
                $conditions["Credit.id"] = $creditsRequests;
            } else {
                $conditions["Credit.id"] = null;
            }
            $this->Set("commerce", $query['commerce']);
        }

        try {
            $credits = $this->Credit->find("all", ["conditions" => $conditions]);
        } catch (Exception $e) {
            $credits = [];
        }

        if (!empty($credits)) {
            $i = 2;
            $this->loadModel("ShopCommerce");
            foreach ($credits as $key => $value) {

				$fechasPago='';
				foreach ($value['CreditsPlan'] as $keyPlan=> $plan) {
					$fechasPago.=$plan['deadline'];
				}

                $this->Credit->Customer->unBindModel(["hasMany" => ["Credit", "CreditLimit", "CreditsRequest", "User"]]);
                $customer = $this->Credit->Customer->findById($value["Customer"]["id"]);
				$comercio = ["Comercio" => $this->ShopCommerce->findById($value["CreditsRequest"]['shop_commerce_id'])];
                $saldos = $this->calculateTotales($value["Credit"], $value["CreditsPlan"],true);

                $customer = $this->Credit->Customer->findById($value["Customer"]["id"]);

				if (!empty($customer) && !empty($comercio) && isset($comercio["Comercio"]["Shop"])) {
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, date("d-m-Y", strtotime($value["Credit"]["created"])));
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $value["Credit"]["code_pay"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $customer["Customer"]["identification"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $customer["Customer"]["name"] . " " . $customer["Customer"]["last_name"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, $customer["CustomersPhone"]["0"]["phone_number"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, $customer["CustomersAddress"]["0"]["address"] . " " . $customer["CustomersAddress"]["0"]["address_city"] . " " . $customer["CustomersAddress"]["0"]["address_street"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, ($value["Credit"]["value_request"]));

					$spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $saldos["saldo"]);

					$spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $comercio["Comercio"]["Shop"]["social_reason"] . " - " . $comercio["Comercio"]["ShopCommerce"]["name"] . " - " . $comercio["Comercio"]["ShopCommerce"]["code"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, $fechasPago);

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
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);


        $spreadsheet->getActiveSheet()->setTitle('Cartera vigentes');
        $spreadsheet->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $name = "files/cartera_vigentes_" ."_".date("Y-m-d"). ".xlsx";
		$writer->save($name);
		$url = Router::url("/", true) . $name;
		$this->redirect($url);
    }

    private function credVigMora()
    {

        $conditions = ["Credit.credits_request_id != " => 0, "Credit.state" => 0, "Credit.debt" => 1];
        $totalCartera = null;

        if (!isset($this->request->query["ini"])) {
            $fechaInicioReporte = date("Y-m-d");
        } else {
            $fechaInicioReporte = $this->request->query["ini"];
        }

        if (!isset($this->request->query["end"])) {
            $fechaFinReporte = date("Y-m-d");
        } else {
            $fechaFinReporte = $this->request->query["end"];
        }

        if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
            $conditions["DATE(Credit.created) >="] = $this->request->query["ini"];
            $conditions["DATE(Credit.created) <="] = $this->request->query["end"];
            $this->set("fechas", true);
        }
        $query = $this->request->query;

        if (isset($query["range"]) && !empty($query["range"])) {
            $valuesRange = explode(";", $query["range"]);
            if (count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])) {

                $conditions["Credit.value_request >= "] = $valuesRange[0];
                $conditions["Credit.value_request <= "] = $valuesRange[1];

                $min = $valuesRange[0];
                $max = $valuesRange[1];

            } else {
                $conditions["Credit.id"] = null;
            }
        } else {
            $min = 1;
            $max = 1000000;
        }

        if (isset($query['commerce']) && !empty($query['commerce'])) {
            $this->loadModel("ShopCommerce");
            $shopCommerce = $this->ShopCommerce->findByCode($query['commerce']);
            if (!empty($shopCommerce)) {
                $creditsRequests = Set::extract($shopCommerce["CreditsRequest"], "{n}.credit_id");
                foreach ($creditsRequests as $clave => $valor) {
                    if (empty($valor)) {
                        unset($creditsRequests[$clave]);
                    }

                }
                $conditions["Credit.id"] = $creditsRequests;
            } else {
                $conditions["Credit.id"] = null;
            }
            $this->Set("commerce", $query['commerce']);
        }

		if (isset($query['cedula']) && !empty($query['cedula'])) {
            $this->loadModel("Customer");
            $customer = $this->Customer->findByIdentification($query['cedula']);
            if (!empty($customer)) {

                $conditions["Credit.customer_id"] = $customer['Customer']['id'];
            } else {
				$conditions["Credit.customer_id"] = null;
			}
            $this->Set("cedula", $query['cedula']);
        }


		if (isset($query['n_obligacion']) && !empty($query['n_obligacion'])) {
			$conditions["Credit.code_pay"] = $query['n_obligacion'];
            $this->Set("n_obligacion", $query['n_obligacion']);
        }

        try {
            $this->Paginator->settings = ["conditions" => $conditions];
            $credits = $this->Paginator->paginate();

            if (!empty($credits)) {
                $this->loadModel("ShopCommerce");
                foreach ($credits as $key => $value) {
                    $this->Credit->Customer->unBindModel(["hasMany" => ["Credit", "CreditLimit", "CreditsRequest", "User"]]);
                    $credits[$key]["Customer"] = $this->Credit->Customer->findById($value["Customer"]["id"]);
                    $credits[$key]["saldos"] = $this->calculateTotales($value["Credit"], $value["CreditsPlan"],true);
                    try {
                        $credits[$key]["Comercio"] = $this->ShopCommerce->findById($value["CreditsRequest"]['shop_commerce_id']);

                    } catch (Exception $e) {
                        $credits[$key]["Comercio"] = [];
                    }
                }
            }

            // $idsTotalCartera = $this->Credit->find("list", ["conditions" => $conditions, "fields" => ["id","id"]]);
            $idsTotalCartera = [];

            if (!empty($idsTotalCartera)) {
                $totalCartera = 0;
                foreach ($idsTotalCartera as $key => $value) {
                    $totalCartera += $this->Credit->CreditsPlan->getMinValue($value);
                }
            }

        } catch (Exception $e) {
            $credits = [];
        }

        $this->set(compact("fechaInicioReporte", "fechaFinReporte", "min", "max", "credits", "totalCartera"));
    }

    private function credVigMoraExport($spreadsheet)
    {
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'FECHA RETIRADO')
            ->setCellValue('B1', 'NÚMERO DE OBLIGACIÓN')
            ->setCellValue('C1', 'CÉDULA')
            ->setCellValue('D1', 'NOMBRE COMPLETO')
            ->setCellValue('E1', 'TELÉFONO')
            ->setCellValue('F1', 'DIRECCIÓN')
            ->setCellValue('G1', 'VALOR RETIRADO')
            ->setCellValue('H1', 'SALDO VIGENTE')
            ->setCellValue('I1', 'SALDO EN MORA')
            ->setCellValue('J1', 'DIAS EN MORA')
            // ->setCellValue('K1', 'VALOR CUOTA')
            ->setCellValue('K1', 'CANTIDAD CUOTAS EN MORA')
            ->setCellValue('L1', 'PROVEEDOR')
            ->setCellValue('M1', 'FECHAS PAGO');

        $conditions = ["Credit.credits_request_id != " => 0, "Credit.state" => 0, "Credit.debt" => 1];
        $totalCartera = 0;

        if (!isset($this->request->query["ini"])) {
            $fechaInicioReporte = date("Y-m-d");
        } else {
            $fechaInicioReporte = $this->request->query["ini"];
        }

        if (!isset($this->request->query["end"])) {
            $fechaFinReporte = date("Y-m-d");
        } else {
            $fechaFinReporte = $this->request->query["end"];
        }

        if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
            $conditions["DATE(Credit.created) >="] = $this->request->query["ini"];
            $conditions["DATE(Credit.created) <="] = $this->request->query["end"];
            $this->set("fechas", true);
        }
        $query = $this->request->query;

        if (isset($query["range"]) && !empty($query["range"])) {
            $valuesRange = explode(";", $query["range"]);
            if (count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])) {

                $conditions["Credit.value_request >= "] = $valuesRange[0];
                $conditions["Credit.value_request <= "] = $valuesRange[1];

                $min = $valuesRange[0];
                $max = $valuesRange[1];

            } else {
                $conditions["Credit.id"] = null;
            }
        } else {
            $min = 0;
            $max = 1000000;
        }

        if (isset($query['commerce']) && !empty($query['commerce'])) {
            $this->loadModel("ShopCommerce");
            $shopCommerce = $this->ShopCommerce->findByCode($query['commerce']);
            if (!empty($shopCommerce)) {
                $creditsRequests = Set::extract($shopCommerce["CreditsRequest"], "{n}.credit_id");
                foreach ($creditsRequests as $clave => $valor) {
                    if (empty($valor)) {
                        unset($creditsRequests[$clave]);
                    }

                }
                $conditions["Credit.id"] = $creditsRequests;
            } else {
                $conditions["Credit.id"] = null;
            }
            $this->Set("commerce", $query['commerce']);
        }

        try {
            $credits = $this->Credit->find("all", ["conditions" => $conditions]);

        } catch (Exception $e) {
            $credits = [];
        }

        if (!empty($credits)) {
            $i = 2;
            $this->loadModel("ShopCommerce");
            foreach ($credits as $key => $value) {

				$fechasPago='';
				foreach ($value['CreditsPlan'] as $keyPlan=> $plan) {
					$fechasPago.=$plan['deadline'];
				}

                $this->Credit->Customer->unBindModel(["hasMany" => ["Credit", "CreditLimit", "CreditsRequest", "User"]]);
                $customer = $this->Credit->Customer->findById($value["Customer"]["id"]);
				$comercio = ["Comercio" => $this->ShopCommerce->findById($value["CreditsRequest"]['shop_commerce_id'])];

				if (!empty($customer) && !empty($comercio) && isset($comercio["Comercio"]["Shop"])) {
					$saldos = $this->calculateTotales($value["Credit"], $value["CreditsPlan"],true);
					$value["saldos"] = $this->calculateTotales($value["Credit"], $value["CreditsPlan"],true);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, date("d-m-Y", strtotime($value["Credit"]["created"])));
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $value["Credit"]["code_pay"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $customer["Customer"]["identification"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $customer["Customer"]["name"] . " " . $customer["Customer"]["last_name"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, $customer["CustomersPhone"]["0"]["phone_number"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, $customer["CustomersAddress"]["0"]["address"] . " " . $customer["CustomersAddress"]["0"]["address_city"] . " " . $customer["CustomersAddress"]["0"]["address_street"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, ($value["Credit"]["value_request"]));

					$spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $saldos["saldo"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $saldos["debt"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, $value["Credit"]["quote_days"]);
					// $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, $value["Credit"]["quota_value"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, $saldos["totalDebt"]);

					$spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, $comercio["Comercio"]["Shop"]["social_reason"] . " - " . $comercio["Comercio"]["ShopCommerce"]["name"] . " - " . $comercio["Comercio"]["ShopCommerce"]["code"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, $fechasPago);

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
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        // $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);

        $spreadsheet->getActiveSheet()->setTitle('Cartera vigentes EN MORA');
        $spreadsheet->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $name = "files/cartera_vigentes_mora_" ."_".date("Y-m-d"). ".xlsx";
		$writer->save($name);
		$url = Router::url("/", true) . $name;
		$this->redirect($url);
    }

    private function credCancelados()
    {

        $conditions = ["Credit.credits_request_id != " => 0, "Credit.state" => 1];
        $totalCartera = 0;

        if (!isset($this->request->query["ini"])) {
            $fechaInicioReporte = date("Y-m-d");
        } else {
            $fechaInicioReporte = $this->request->query["ini"];
        }

        if (!isset($this->request->query["end"])) {
            $fechaFinReporte = date("Y-m-d");
        } else {
            $fechaFinReporte = $this->request->query["end"];
        }

        if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {

            $campo = $this->request->query["type_date"] == 1 ? "created" : "deadline";
            $conditions["DATE(Credit.${campo}) >="] = $this->request->query["ini"];
            $conditions["DATE(Credit.${campo}) <="] = $this->request->query["end"];
            $this->set("fechas", true);
            $this->set("type_date", $this->request->query["type_date"]);
        }
        $query = $this->request->query;

        if (isset($query["range"]) && !empty($query["range"])) {
            $valuesRange = explode(";", $query["range"]);
            if (count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])) {

                $conditions["Credit.value_request >= "] = $valuesRange[0];
                $conditions["Credit.value_request <= "] = $valuesRange[1];

                $min = $valuesRange[0];
                $max = $valuesRange[1];

            } else {
                $conditions["Credit.id"] = null;
            }
        } else {
            $min = 1;
            $max = 1000000;
        }

        if (isset($query['commerce']) && !empty($query['commerce'])) {
            $this->loadModel("ShopCommerce");
            $shopCommerce = $this->ShopCommerce->findByCode($query['commerce']);
            if (!empty($shopCommerce)) {
                $creditsRequests = Set::extract($shopCommerce["CreditsRequest"], "{n}.credit_id");
                foreach ($creditsRequests as $clave => $valor) {
                    if (empty($valor)) {
                        unset($creditsRequests[$clave]);
                    }

                }
                $conditions["Credit.id"] = $creditsRequests;
            } else {
                $conditions["Credit.id"] = null;
            }
            $this->Set("commerce", $query['commerce']);
        }

		if (isset($query['cedula']) && !empty($query['cedula'])) {
            $this->loadModel("Customer");
            $customer = $this->Customer->findByIdentification($query['cedula']);
            if (!empty($customer)) {

                $conditions["Credit.customer_id"] = $customer['Customer']['id'];
            } else {
				$conditions["Credit.customer_id"] = null;
			}
            $this->Set("cedula", $query['cedula']);
        }


		if (isset($query['n_obligacion']) && !empty($query['n_obligacion'])) {
			$conditions["Credit.code_pay"] = $query['n_obligacion'];
            $this->Set("n_obligacion", $query['n_obligacion']);
        }

        try {
            $this->Paginator->settings = ["conditions" => $conditions];
            $credits = $this->Paginator->paginate();

            if (!empty($credits)) {
                $this->loadModel("ShopCommerce");
                foreach ($credits as $key => $value) {
                    $this->Credit->Customer->unBindModel(["hasMany" => ["Credit", "CreditLimit", "CreditsRequest", "User"]]);
                    $credits[$key]["Customer"] = $this->Credit->Customer->findById($value["Customer"]["id"]);

                    $totalDebts = 0;

                    foreach ($value["CreditsPlan"] as $keyData => $valueData) {
                        if (!is_null($valueData["date_debt"])) {
                            $totalDebts++;
                        }
                    }

                    $credits[$key]["debts"] = $totalDebts;
                    try {
                        $credits[$key]["Comercio"] = $this->ShopCommerce->findById($value["CreditsRequest"]['shop_commerce_id']);

                    } catch (Exception $e) {
                        $credits[$key]["Comercio"] = [];
                    }
                }
            }

            $totalCartera = $this->Credit->find("first", ["conditions" => $conditions, "fields" => ["SUM(value_request) as total"]]);

            if (!empty($totalCartera)) {
                $totalCartera = $totalCartera["0"]["total"];
            }

        } catch (Exception $e) {
            $credits = [];
        }

        $this->set(compact("fechaInicioReporte", "fechaFinReporte", "min", "max", "credits", "totalCartera"));
    }

    private function credCanceladosExport($spreadsheet)
    {
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'FECHA RETIRADO')
            ->setCellValue('B1', 'NÚMERO DE OBLIGACIÓN')
            ->setCellValue('C1', 'CÉDULA')
            ->setCellValue('D1', 'NOMBRE COMPLETO')
            ->setCellValue('E1', 'TELÉFONO')
            ->setCellValue('F1', 'DIRECCIÓN')
            ->setCellValue('G1', 'VALOR APROBADO')
            ->setCellValue('H1', 'CUOTAS PAGADAS EN MORA')
            ->setCellValue('I1', 'PROVEEDOR')
            ->setCellValue('J1', 'FECHA FINALIZACIÓN')
            ->setCellValue('K1', 'FECHAS PAGO');

        $conditions = ["Credit.credits_request_id != " => 0, "Credit.state" => 1];
        $totalCartera = 0;

        if (!isset($this->request->query["ini"])) {
            $fechaInicioReporte = date("Y-m-d");
        } else {
            $fechaInicioReporte = $this->request->query["ini"];
        }

        if (!isset($this->request->query["end"])) {
            $fechaFinReporte = date("Y-m-d");
        } else {
            $fechaFinReporte = $this->request->query["end"];
        }

        if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
            $campo = $this->request->query["type_date"] == 1 ? "created" : "deadline";
            $conditions["DATE(Credit.${campo}) >="] = $this->request->query["ini"];
            $conditions["DATE(Credit.${campo}) <="] = $this->request->query["end"];
            $this->set("fechas", true);
        }
        $query = $this->request->query;

        if (isset($query["range"]) && !empty($query["range"])) {
            $valuesRange = explode(";", $query["range"]);
            if (count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])) {

                $conditions["Credit.value_request >= "] = $valuesRange[0];
                $conditions["Credit.value_request <= "] = $valuesRange[1];

                $min = $valuesRange[0];
                $max = $valuesRange[1];

            } else {
                $conditions["Credit.id"] = null;
            }
        } else {
            $min = 0;
            $max = 1000000;
        }

        if (isset($query['commerce']) && !empty($query['commerce'])) {
            $this->loadModel("ShopCommerce");
            $shopCommerce = $this->ShopCommerce->findByCode($query['commerce']);
            if (!empty($shopCommerce)) {
                $creditsRequests = Set::extract($shopCommerce["CreditsRequest"], "{n}.credit_id");
                foreach ($creditsRequests as $clave => $valor) {
                    if (empty($valor)) {
                        unset($creditsRequests[$clave]);
                    }

                }
                $conditions["Credit.id"] = $creditsRequests;
            } else {
                $conditions["Credit.id"] = null;
            }
            $this->Set("commerce", $query['commerce']);
        }

        try {
            $credits = $this->Credit->find("all", ["conditions" => $conditions]);
        } catch (Exception $e) {
            $credits = [];
        }

        if (!empty($credits)) {
            $i = 2;
            foreach ($credits as $key => $value) {

				$fechasPago='';
				foreach ($value['CreditsPlan'] as $keyPlan=> $plan) {
					$fechasPago.=$plan['deadline'];
				}

                $this->Credit->Customer->unBindModel(["hasMany" => ["Credit", "CreditLimit", "CreditsRequest", "User"]]);
                $customer = $this->Credit->Customer->findById($value["Customer"]["id"]);

				if (!empty($customer)) {
					$totalDebts = 0;

					foreach ($value["CreditsPlan"] as $keyData => $valueData) {
						if (!is_null($valueData["date_debt"])) {
							$totalDebts++;
						}
					}

					$spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, date("d-m-Y", strtotime($value["Credit"]["created"])));
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $value["Credit"]["code_pay"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $customer["Customer"]["identification"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $customer["Customer"]["name"] . " " . $customer["Customer"]["last_name"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, isset($customer["CustomersPhone"]) && !empty($customer["CustomersPhone"]) ?  $customer["CustomersPhone"]["0"]["phone_number"] : "");
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, isset($customer["CustomersAddress"]) && !empty($customer["CustomersAddress"]) ? $customer["CustomersAddress"]["0"]["address"] . " " . $customer["CustomersAddress"]["0"]["address_city"] . " " . $customer["CustomersAddress"]["0"]["address_street"] : "");
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, ($value["Credit"]["value_request"]));

					$spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $totalDebts);

					$comercio = ["Comercio" => $this->ShopCommerce->findById($value["CreditsRequest"]['shop_commerce_id'])];
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $comercio["Comercio"]["Shop"]["social_reason"] . " - " . $comercio["Comercio"]["ShopCommerce"]["name"] . " - " . $comercio["Comercio"]["ShopCommerce"]["code"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, $value["Credit"]["deadline"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, $fechasPago);

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
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);


        $spreadsheet->getActiveSheet()->setTitle('Cartera cancelados');
        $spreadsheet->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $name = "files/cartera_cancelados_" ."_".date("Y-m-d"). ".xlsx";
		$writer->save($name);
		$url = Router::url("/", true) . $name;
		$this->redirect($url);

    }

    /**
     * revisar 1
     */
    private function calculateTotales($credit, $quotes, $totalData = null)
    {
        $quotes = $this->Credit->CreditsPlan->getDataQuotes($quotes, $credit["last_payment_date"], $credit["debt_rate"], $credit["id"]);

        $capitalTotal = $othersValue = $interesValue = 0;

        $totalDebt = 0;
        $totalCredit = 0;
        $totalQuoteDebt = 0;
        $totalCanceladas = 0;
        $dias     = 0;
        $lastDate = null;
        $deadline = $credit["state"] == 1 ? $credit["deadline"] : 0;

        foreach ($quotes as $keyQt => $valueQt) {

            $capitalTotal = floatval($valueQt["capital_value"] - $valueQt["capital_payment"]);
            $othersValue = floatval($valueQt["others_value"] - $valueQt["others_payment"]);
            $othersValue = floatval($valueQt["interest_value"] - $valueQt["interest_payment"]); //floatval($valueQt["interest_value"]-$valueQt["interest_payment"]);

            $totalCredit += floatVal($capitalTotal + $othersValue + $interesValue + $valueQt["debt_value"] + $valueQt["debt_honor"]);

            if ($valueQt["state"] == 0) {
                $totalDebt += floatVal($valueQt["debt_value"] + $valueQt["debt_honor"]);
                if ($deadline == 0) {
                    $deadline = $valueQt["deadline"];
                }
            }

            if (!is_null($valueQt["date_payment"])) {
                $lastDate  = $valueQt["date_payment"];
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

        $dataReturn = ["debt" => $totalDebt, "totalDebt" => $totalQuoteDebt, "totalCanceladas" => $totalCanceladas, "dias" => $dias,"lastDate"=>$lastDate,"deadline"=>$deadline ];

        if (!is_null($totalData)) {
            $dataReturn["saldo"] = $this->Credit->CreditsPlan->getCreditDeuda($credit["id"],null,null,true);
            $dataReturn["min_value"] = $this->Credit->CreditsPlan->getMinValue($credit["id"]);
        }else{
            $dataReturn["saldo"] = $this->Credit->CreditsPlan->getCreditDeuda($credit["id"]);
        }

        return $dataReturn;

    }

    private function calculateTotalesNewData($credit, $quotes,$deadline = null)
    {
        $fechaData  = !is_null($deadline) ? $deadline : $credit["last_payment_date"];
        $fechaPago  = null;
        $valorReal  = "";

        // if (!is_null($deadline)) {
        //     foreach ($quotes as $key => $value) {

        //         if (!is_null($quotes[$key]["date_payment"]) && strtotime($quotes[$key]["date_payment"]) > strtotime($deadline) ) {
        //             $quotes[$key]["date_payment"]  = null;
        //             $quotes[$key]["date_debt"]     = null;
        //             $credit["date_debt"]        = null;
        //         }

        //     }
        // }

        $quotes     = $this->Credit->CreditsPlan->getDataQuotesNewData($quotes, $deadline , $credit["debt_rate"], $credit["id"]);



        if (!is_null($deadline)) {
            foreach ($quotes as $key => $value) {
                $fechaPago = $quotes[$key]["fecha"];
                if (isset($quotes[$key]["debt_value_add"])) {
                    $valorReal += $quotes[$key]["debt_value_add"];
                }
            }
        }

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
                $totalQuoteDebt++;
                $dias += $valueQt["days"];
            }

            if ($valueQt["state"] == 1) {
                $totalCanceladas++;
            }

        }

        return ["saldo" => $totalCredit, "debt" => $totalDebt, "totalDebt" => $totalQuoteDebt, "totalCanceladas" => $totalCanceladas, "dias" => $dias, "fechaPago" => $fechaPago,"valorReal"=>$valorReal];

    }

    public function recaudos()
    {
        $this->loadModel("Receipt");

        $query = $this->request->query;
        $conditions = [];
        $creditsCero = $this->Credit->find("list", ["fields" => ["id", "id"], "conditions" => ["Credit.credits_request_id" => 0]]);

        if (!empty($creditsCero)) {
            $conditions["CreditsPlan.credit_id <>"] = $creditsCero;
        }

        if (!isset($this->request->query["ini"])) {
            $fechaInicioReporte = date("Y-m-d");
        } else {
            $fechaInicioReporte = $this->request->query["ini"];
        }

        if (!isset($this->request->query["end"])) {
            $fechaFinReporte = date("Y-m-d");
        } else {
            $fechaFinReporte = $this->request->query["end"];
        }

        $conditions["DATE(Payment.created) >="] = $fechaInicioReporte;
        $conditions["DATE(Payment.created) <="] = $fechaFinReporte;

        if (isset($query['commerce']) && !empty($query['commerce'])) {
            $conditions["ShopCommerce.code"] = $query["commerce"];
            $this->Set("commerce", $query['commerce']);
        }

        if (isset($query['type_payment']) && !empty($query['type_payment'])) {
            $this->set("type_payment",$query["type_payment"]);
            switch ($query["type_payment"]) {
                case '1':
                    $conditions["Payment.shop_commerce_id !="] = null;
                    $conditions["Payment.juridic"] = 0;
                    break;
                case '2':
                    $conditions["Payment.shop_commerce_id"] = null;
                    $conditions["Payment.juridic"] = 0;
                    break;
                case '3':
                    // $conditions["Payment.shop_commerce_id"] = null;
                    $conditions["Payment.juridic"] = 1;
                    break;
            }
        }

        $totalReceipt = 0;
        $conditions["Payment.value >"] = 0;

        $joins = [ ["table"=>"payments","alias"=>"Payment","type"=>"INNER","conditions"=>["Payment.receipt_id = Receipt.id"]] ];

        try {

            $this->Paginator->settings = ["conditions" => $conditions, "joins" => $joins, "group" => "Payment.receipt_id" ];
            $receipts = $this->Paginator->paginate($this->Receipt);

            $dataReceipt = $this->Receipt->find("first", ["conditions" => $conditions, "fields" => ["SUM(Payment.value) as total",],"joins" => $joins]);

            if (!empty($dataReceipt)) {
                $totalReceipt = $dataReceipt["0"]["total"];
            }

            if (!empty($receipts)) {
                foreach ($receipts as $key => $value) {
                    $this->Receipt->ShopCommerce->Shop->recursive = -1;
                    $this->Credit->Customer->unBindModel(["hasMany" => ["Credit"]]);
                    $customer = $this->Credit->Customer->findById($this->Credit->field("customer_id", ["id" => $value["CreditsPlan"]["credit_id"]]));

                    $shop = $this->Receipt->ShopCommerce->Shop->field("social_reason", ["id" => $value["ShopCommerce"]["shop_id"]]);
                    $receipts[$key]["customer"] = $customer;
                    $receipts[$key]["ShopCommerce"]["shop"] = $shop;
                    $receipts[$key]["Receipt"]["obligacion"] = $this->Credit->field("credits_request_id", ["id" => $value["CreditsPlan"]["credit_id"]]);
                }
            }

        } catch (Exception $e) {
            $receipts = [];
        }

        $this->set(compact("fechaInicioReporte", "fechaFinReporte", "receipts", "totalReceipt"));
    }

    public function intereses_export($time = null)
    {
        $this->autoRender = false;
        $this->loadModel("CreditsPlan");
        $conditions = ["Credit.credits_request_id != " => 0];
        $group = ["CreditsPlan.credit_id"];
        $having = [];
        $totalInteres = 0;

        if (!isset($this->request->query["ini"])) {
            $fechaInicioReporte = date("Y-m-d");
        } else {
            $fechaInicioReporte = $this->request->query["ini"];
        }

        if (!isset($this->request->query["end"])) {
            $fechaFinReporte = date("Y-m-d");
        } else {
            $fechaFinReporte = $this->request->query["end"];
        }

        $query = $this->request->query;

        if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
            $conditions["DATE(Credit.created) >="] = $this->request->query["ini"];
            $conditions["DATE(Credit.created) <="] = $this->request->query["end"];
            $this->set("fechas", true);
        }

        if (isset($query['commerce']) && !empty($query['commerce'])) {
            $this->loadModel("ShopCommerce");
            $shopCommerce = $this->ShopCommerce->findByCode($query['commerce']);
            if (!empty($shopCommerce)) {
                $creditsRequests = Set::extract($shopCommerce["CreditsRequest"], "{n}.credit_id");
                foreach ($creditsRequests as $clave => $valor) {
                    if (empty($valor)) {
                        unset($creditsRequests[$clave]);
                    }

                }
                $conditions["Credit.id"] = $creditsRequests;
            } else {
                $conditions["Credit.id"] = null;
            }
            $this->Set("commerce", $query['commerce']);
        }
        $query = $this->request->query;

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $spreadsheet->getProperties()->setCreator('CREDISHOP')
            ->setLastModifiedBy('CREDISHOP')
            ->setTitle('INTERESES')
            ->setSubject('INTERESES')
            ->setDescription('INTERESES ZÍRO')
            ->setKeywords('INTERESES')
            ->setCategory('INTERESES');

        if ($query["tab"] == 1) {
            if (isset($query["range"]) && !empty($query["range"])) {
                $valuesRange = explode(";", $query["range"]);
                if (count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])) {

                    $having["OR"][] = ["SUM(CreditsPlan.interest_value) >= " => $valuesRange[0],
                        "SUM(CreditsPlan.interest_value) <= " => $valuesRange[1]];

                    $having["OR"][] = ["SUM(CreditsPlan.others_value) >= " => $valuesRange[0],
                        "SUM(CreditsPlan.others_value) <= " => $valuesRange[1]];

                } else {
                    $conditions["Credit.id"] = null;
                }
            }

            if (isset($query["state"]) && ($query["state"]) != "" ) {
                $conditions["Credit.state"] = $query["state"];
                $this->set("state",$query["state"]);
            }

            // Add some data
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'OBLIGACIÓN')
                ->setCellValue('B1', 'FECHA RETIRO')
                ->setCellValue('C1', 'ESTADO CRÉDITO')
                ->setCellValue('D1', 'PROVEEDOR')
                ->setCellValue('E1', 'CAPITAL RESTANTE')
                ->setCellValue('F1', 'INTERESES CORRIENTES')
                ->setCellValue('G1', 'OTROS CARGOS');

            try {
                $valuesQuotes = $this->CreditsPlan->find("all", ["fields" => ["Credit.*", "CreditsPlan.*", "SUM(CreditsPlan.interest_value) as INTERES", "SUM(CreditsPlan.others_value) as OTROS"], "group" => $group, "conditions" => $conditions, "having" => $having]);
            } catch (Exception $e) {
                $valuesQuotes = [];
            }

            $i = 2;

            if (!empty($valuesQuotes)) {
                foreach ($valuesQuotes as $key => $value) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, $value["Credit"]["code_pay"]);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, date("d-m-Y", strtotime($value["Credit"]["created"])));

                    $estado = $value["Credit"]["state"] == 1 ? "Pagado" : "No finalizado";

                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $estado);

                    $shopCommerce = $this->Credit->CreditsRequest->field("shop_commerce_id", ["id" => $value["Credit"]["credits_request_id"]]);

                    $shopCommerce = $this->Credit->CreditsRequest->ShopCommerce->findById($shopCommerce);

                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $shopCommerce["ShopCommerce"]["name"] . " - " . $shopCommerce["Shop"]["social_reason"] . " - " . $shopCommerce["ShopCommerce"]["code"]);

                    $totalByCredit = 0;

                    if ($value["Credit"]["state"] == 0) {
                        $quotes = $this->Credit->CreditsPlan->getCuotesInformation($value["Credit"]["id"], null, 0);
                        $capitalTotal = $othersValue = $interesValue = 0;
                        $totalCredit = 0;

                        foreach ($quotes as $keyQt => $valueQt) {

                            $capitalTotal = floatval($valueQt["CreditsPlan"]["capital_value"] - $valueQt["CreditsPlan"]["capital_payment"]);
                            $othersValue  = floatval($valueQt["CreditsPlan"]["others_value"] - $valueQt["CreditsPlan"]["others_payment"]);
                            $othersValue  = floatval($valueQt["CreditsPlan"]["interest_value"] - $valueQt["CreditsPlan"]["interest_payment"]);

                            $totalCredit += floatVal($capitalTotal + $othersValue + $interesValue + $valueQt["CreditsPlan"]["debt_value"] + $valueQt["CreditsPlan"]["debt_honor"]);
                        }
                        $totalByCredit += $totalCredit;
                    }

                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, $value["Credit"]["value_pending"]);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, $value["0"]["INTERES"]);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, $value["0"]["OTROS"]);
                    $i++;

                }
            }
        } else {

            // Add some data ..
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'OBLIGACIÓN')
                ->setCellValue('B1', 'FECHA RETIRO')
                ->setCellValue('C1', 'ESTADO CRÉDITO')
                ->setCellValue('D1', 'PROVEEDOR')
                ->setCellValue('E1', 'CUOTAS PAGADAS')
                ->setCellValue('F1', 'INTERESES CORRIENTES')
                ->setCellValue('G1', 'OTROS CARGOS');

            if (isset($query["range"]) && !empty($query["range"])) {
                $valuesRange = explode(";", $query["range"]);
                if (count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])) {

                    $having["OR"][] = ["SUM(CreditsPlan.interest_payment) >= " => $valuesRange[0],
                        "SUM(CreditsPlan.interest_payment) <= " => $valuesRange[1]];

                    $having["OR"][] = ["SUM(CreditsPlan.others_payment) >= " => $valuesRange[0],
                        "SUM(CreditsPlan.others_payment) <= " => $valuesRange[1]];

                } else {
                    $conditions["Credit.id"] = null;
                }
            }

            if (isset($query["state"]) && ($query["state"]) != "" ) {
                $conditions["Credit.state"] = $query["state"];
                $this->set("state",$query["state"]);
            }

            try {
                $valuesQuotes = $this->CreditsPlan->find("all", ["fields" => ["Credit.*", "CreditsPlan.*", "SUM(CreditsPlan.interest_payment) as INTERES", "SUM(CreditsPlan.others_payment) as OTROS"], "group" => $group, "conditions" => $conditions, "having" => $having]);
            } catch (Exception $e) {
                $valuesQuotes = [];
            }

            $i = 2;

            if (!empty($valuesQuotes)) {
                foreach ($valuesQuotes as $key => $value) {
                    $totalByCredit = 0;

                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, $value["Credit"]["code_pay"]);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, date("d-m-Y", strtotime($value["Credit"]["created"])));

                    $estado = $value["Credit"]["state"] == 1 ? "Pagado" : "No finalizado";

                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $estado);

                    $shopCommerce = $this->Credit->CreditsRequest->field("shop_commerce_id", ["id" => $value["Credit"]["credits_request_id"]]);

                    $shopCommerce = $this->Credit->CreditsRequest->ShopCommerce->findById($shopCommerce);

                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $shopCommerce["ShopCommerce"]["name"] . " - " . $shopCommerce["Shop"]["social_reason"] . " - " . $shopCommerce["ShopCommerce"]["code"]);

                    $this->Credit->CreditsPlan->recursive = -1;
                    $quotes = $this->Credit->CreditsPlan->findAllByCreditId($value["Credit"]["id"]);

                    foreach ($quotes as $keyQt => $valueQt) {
                        if ($valueQt["CreditsPlan"]["state"] == 1) {
                            $totalByCredit++;
                        }
                    }

                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, $totalByCredit);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, $value["0"]["INTERES"]);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, $value["0"]["OTROS"]);
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
        $name = "files/intereses_" . $title . "_" ."_".date("Y-m-d"). ".xlsx";
        $writer->save($name);

        return Router::url("/", true) . $name;

    }

    public function recaudos_export()
    {
        $this->autoRender = false;

        $this->loadModel("Receipt");

        $query = $this->request->query;
        $conditions = [];
        $creditsCero = $this->Credit->find("list", ["fields" => ["id", "id"], "conditions" => ["Credit.credits_request_id" => 0]]);

        if (!empty($creditsCero)) {
            $conditions["CreditsPlan.credit_id <>"] = $creditsCero;
        }

        if (!isset($this->request->query["ini"])) {
            $fechaInicioReporte = date("Y-m-d");
        } else {
            $fechaInicioReporte = $this->request->query["ini"];
        }

        if (!isset($this->request->query["end"])) {
            $fechaFinReporte = date("Y-m-d");
        } else {
            $fechaFinReporte = $this->request->query["end"];
        }

        $conditions["DATE(Payment.created) >="] = $fechaInicioReporte;
        $conditions["DATE(Payment.created) <="] = $fechaFinReporte;

        if (isset($query['commerce']) && !empty($query['commerce'])) {
            $conditions["ShopCommerce.code"] = $query["commerce"];
            $this->Set("commerce", $query['commerce']);
        }

         if (isset($query['type_payment']) && !empty($query['type_payment'])) {
            $this->set("type_payment",$query["type_payment"]);
            switch ($query["type_payment"]) {
                case '1':
                    $conditions["Payment.shop_commerce_id !="] = null;
                    $conditions["Payment.juridic"] = 0;
                    break;
                case '2':
                    $conditions["Payment.shop_commerce_id"] = null;
                    $conditions["Payment.juridic"] = 0;
                    break;
                case '3':
                    // $conditions["Payment.shop_commerce_id"] = null;
                    $conditions["Payment.juridic"] = 1;
                    break;
            }
        }

        $totalReceipt = 0;
        $conditions["Payment.value >"] = 0;

        $joins = [ ["table"=>"payments","alias"=>"Payment","type"=>"INNER","conditions"=>["Payment.receipt_id = Receipt.id"]] ];

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
        ->setCellValue('B1', 'NUMERO DE OBLIGACIÓN')
		->setCellValue('C1', 'FECHA RETIRO')
        ->setCellValue('D1', 'CÉDULA')
        ->setCellValue('E1', 'NOMBRE COMPLETO')
        ->setCellValue('F1', 'TELÉFONO')
        ->setCellValue('G1', 'DIRECCIÓN')
        ->setCellValue('H1', 'VALOR RECAUDADO')
        ->setCellValue('I1', 'PROVEEDOR')
        ->setCellValue('J1', 'RECAUDO')
        ->setCellValue('K1', 'CAPITAL')
        ->setCellValue('L1', 'INTERESES')
        ->setCellValue('M1', 'OTROS')
        ->setCellValue('N1', 'INTERES MORA');

        $receipts = $this->Receipt->find("all", ["conditions" => $conditions, "joins"=>$joins,"group"=>"Payment.receipt_id"]);

        $i = 2;

        if (!empty($receipts)) {
            foreach ($receipts as $key => $value) {

				$this->loadModel('CreditsRequest');
				$credit= $this->Credit->findById($value['CreditsPlan']['credit_id']);



                $this->Credit->Customer->unBindModel(["hasMany" => ["Credit","CreditLimit","CreditsRequest","CustomersReference","User"]]);
                $customer = $this->Credit->Customer->findById($this->Credit->field("customer_id", ["id" => $value["CreditsPlan"]["credit_id"]]));
                $fecha = !empty($value["Payment"]) ? $value["Payment"][0]["created"] : $value["CreditsPlan"]["date_payment"];
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, date("d-m-Y", strtotime($fecha)));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $this->Credit->field("code_pay", ["id" => $value["CreditsPlan"]["credit_id"]]));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $credit['CreditsRequest']['date_disbursed']);

                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $customer["Customer"]["identification"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, $customer["Customer"]["name"] . " " . $customer["Customer"]["last_name"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, isset($customer["CustomersPhone"]["0"]["phone_number"]) ? $customer["CustomersPhone"]["0"]["phone_number"] : "" );
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i,isset($customer["CustomersAddress"]["0"]["address"]) ? $customer["CustomersAddress"]["0"]["address"] . " " . $customer["CustomersAddress"]["0"]["address_city"] . " " . $customer["CustomersAddress"]["0"]["address_street"] : "");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $value["Receipt"]["total_payments"]);

                $this->Receipt->ShopCommerce->Shop->recursive = -1;

                $shop = $this->Receipt->ShopCommerce->Shop->field("social_reason", ["id" => $value["ShopCommerce"]["shop_id"]]);
                //   $comercioData = empty($value["ShopCommerce"]["code"]) ? "PAGO WEB" : $value["ShopCommerce"]["code"] . " - " . $shop . " - " . $value["ShopCommerce"]["name"];

                if ($value["Payment"]["0"]["juridic"] == 1) {
                    $commerceData = "PAGO JURIDICA";
                }else{
                    if (empty($value["ShopCommerce"]["code"])){
                        $commerceData = "PAGO WEB";
                    }else{
                        $commerceData = $value["ShopCommerce"]["code"] . " - " . $shop . " - " . $value["ShopCommerce"]["name"];
                    }
                }

                $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $commerceData);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, $value["User"]["name"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, $value["Receipt"]["total_capital"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, $value["Receipt"]["total_intereses"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, $value["Receipt"]["total_otros"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, $value["Receipt"]["total_debts"]);
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
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);

        $spreadsheet->getActiveSheet()->setTitle('Recaudos');
        $spreadsheet->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold(true);
        //$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
		$name = "files/recaudos_" . time() . ".xls";
		$writer->save($name);

		$url = Router::url("/", true) . $name;
		$this->redirect($url);

        var_dump($receipts);
		die;

    }

    public function intereses()
    {
        if (!isset($this->request->query["tab"])) {
            $this->redirect(["action" => "intereses", "?" => ["tab" => 1]]);
        }

        $this->loadModel("CreditsPlan");
        $conditions = ["Credit.credits_request_id != " => 0];
        $group = ["CreditsPlan.credit_id"];
        $having = [];
        $totalInteres = 0;

        if (!isset($this->request->query["ini"])) {
            $fechaInicioReporte = date("Y-m-d");
        } else {
            $fechaInicioReporte = $this->request->query["ini"];
        }

        if (!isset($this->request->query["end"])) {
            $fechaFinReporte = date("Y-m-d");
        } else {
            $fechaFinReporte = $this->request->query["end"];
        }

        $query = $this->request->query;

        if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
            $conditions["DATE(Credit.created) >="] = $this->request->query["ini"];
            $conditions["DATE(Credit.created) <="] = $this->request->query["end"];
            $this->set("fechas", true);
        }

        if (isset($query['commerce']) && !empty($query['commerce'])) {
            $this->loadModel("ShopCommerce");
            $shopCommerce = $this->ShopCommerce->findByCode($query['commerce']);
            if (!empty($shopCommerce)) {
                $creditsRequests = Set::extract($shopCommerce["CreditsRequest"], "{n}.credit_id");
                foreach ($creditsRequests as $clave => $valor) {
                    if (empty($valor)) {
                        unset($creditsRequests[$clave]);
                    }

                }
                $conditions["Credit.id"] = $creditsRequests;
            } else {
                $conditions["Credit.id"] = null;
            }
            $this->Set("commerce", $query['commerce']);
        }
        $query = $this->request->query;

        if ($query["tab"] == 1) {
            if (isset($query["range"]) && !empty($query["range"])) {
                $valuesRange = explode(";", $query["range"]);
                if (count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])) {

                    $having["OR"][] = ["SUM(CreditsPlan.interest_value) >= " => $valuesRange[0],
                        "SUM(CreditsPlan.interest_value) <= " => $valuesRange[1]];

                    $having["OR"][] = ["SUM(CreditsPlan.others_value) >= " => $valuesRange[0],
                        "SUM(CreditsPlan.others_value) <= " => $valuesRange[1]];

                    $min = $valuesRange[0];
                    $max = $valuesRange[1];

                } else {
                    $conditions["Credit.id"] = null;
                }
            } else {
                $min = 1;
                $max = 1000000;
            }

            if (isset($query["state"]) && ($query["state"]) != "" ) {
                $conditions["Credit.state"] = $query["state"];
                $this->set("state",$query["state"]);
            }

            $this->Paginator->settings = ["fields" => ["Credit.*", "CreditsPlan.*", "SUM(CreditsPlan.interest_value) as INTERES", "SUM(CreditsPlan.others_value) as OTROS"], "group" => $group, "conditions" => $conditions, "having" => $having];
            $valuesQuotes = $this->Paginator->paginate($this->CreditsPlan);

            $total = 0;
            if (!empty($valuesQuotes)) {
                $totalInteres = $this->CreditsPlan->find("first", ["conditions" => $conditions, "fields" => ["SUM(CreditsPlan.interest_value + CreditsPlan.others_value) as total"]]);
                if (!empty($totalInteres)) {
                    $totalInteres = $totalInteres["0"]["total"];
                }
                foreach ($valuesQuotes as $key => $value) {
                    $totalByCredit = 0;

                    if ($value["Credit"]["state"] == 0) {
                        $quotes = $this->Credit->CreditsPlan->getCuotesInformation($value["Credit"]["id"], null, 0);

                        $capitalTotal = $othersValue = $interesValue = 0;

                        $totalCredit = 0;

                        foreach ($quotes as $keyQt => $valueQt) {

                            $capitalTotal = floatval($valueQt["CreditsPlan"]["capital_value"] - $valueQt["CreditsPlan"]["capital_payment"]);
                            $othersValue = floatval($valueQt["CreditsPlan"]["others_value"] - $valueQt["CreditsPlan"]["others_payment"]);
                            $othersValue = floatval($valueQt["CreditsPlan"]["interest_value"] - $valueQt["CreditsPlan"]["interest_payment"]);

                            $totalCredit += floatVal($capitalTotal + $othersValue + $interesValue + $valueQt["CreditsPlan"]["debt_value"] + $valueQt["CreditsPlan"]["debt_honor"]);
                        }
                        $totalByCredit += $totalCredit;
                    }

                    $valuesQuotes[$key]["Credit"]["saldo"] = $totalByCredit;

                    $shopCommerce = $this->Credit->CreditsRequest->field("shop_commerce_id", ["id" => $value["Credit"]["credits_request_id"]]);

                    $valuesQuotes[$key]["Credit"]["comercio"] = $this->Credit->CreditsRequest->ShopCommerce->findById($shopCommerce);

                }
            }
            $this->set("valuesQuotes", $valuesQuotes);
        } else {
            if (isset($query["range"]) && !empty($query["range"])) {
                $valuesRange = explode(";", $query["range"]);
                if (count($valuesRange) == 2 && is_numeric($valuesRange[0]) && is_numeric($valuesRange[1])) {

                    $having["OR"][] = ["SUM(CreditsPlan.interest_payment) >= " => $valuesRange[0],
                        "SUM(CreditsPlan.interest_payment) <= " => $valuesRange[1]];

                    $having["OR"][] = ["SUM(CreditsPlan.others_payment) >= " => $valuesRange[0],
                        "SUM(CreditsPlan.others_payment) <= " => $valuesRange[1]];

                    $min = $valuesRange[0];
                    $max = $valuesRange[1];

                } else {
                    $conditions["Credit.id"] = null;
                }
            } else {
                $min = 1;
                $max = 1000000;
            }

            if (isset($query["state"]) && ($query["state"]) != "" ) {
                $conditions["Credit.state"] = $query["state"];
                $this->set("state",$query["state"]);
            }

            $this->Paginator->settings = ["fields" => ["Credit.*", "CreditsPlan.*", "SUM(CreditsPlan.interest_payment) as INTERES", "SUM(CreditsPlan.others_payment) as OTROS"], "group" => $group, "conditions" => $conditions, "having" => $having];
            $valuesQuotes = $this->Paginator->paginate($this->CreditsPlan);

            if (!empty($valuesQuotes)) {
                $totalInteres = $this->CreditsPlan->find("first", ["conditions" => $conditions, "fields" => ["SUM(CreditsPlan.interest_payment + CreditsPlan.others_payment) as total"]]);
                if (!empty($totalInteres)) {
                    $totalInteres = $totalInteres["0"]["total"];
                }
                foreach ($valuesQuotes as $key => $value) {
                    $totalByCredit = 0;
                    $this->Credit->CreditsPlan->recursive = -1;
                    $quotes = $this->Credit->CreditsPlan->findAllByCreditId($value["Credit"]["id"]);

                    foreach ($quotes as $keyQt => $valueQt) {
                        if ($valueQt["CreditsPlan"]["state"] == 1) {
                            $totalByCredit++;
                        }
                    }

                    $valuesQuotes[$key]["Credit"]["totales"] = $totalByCredit;

                    $shopCommerce = $this->Credit->CreditsRequest->field("shop_commerce_id", ["id" => $value["Credit"]["credits_request_id"]]);
                    $valuesQuotes[$key]["Credit"]["comercio"] = $this->Credit->CreditsRequest->ShopCommerce->findById($shopCommerce);

                }
            }
            $this->set("valuesQuotes", $valuesQuotes);

        }

        $this->set("tab", $this->request->query["tab"]);
        $this->set(compact("fechaInicioReporte", "fechaFinReporte", "min", "max", "totalInteres"));
    }

    public function index()
    {
        $conditions = $this->Credit->buildConditions($this->request->query);

        if (AuthComponent::user("role") == 5) {
            $conditions["Credit.customer_id"] = AuthComponent::user("customer_id");
        } elseif (AuthComponent::user("role") == 4 || AuthComponent::user("role") == 7) {
            $this->loadModel("ShopCommerce");
            $conditions2 = ["ShopCommerce.shop_id" => AuthComponent::user("shop_id")];
            $commerces = $this->ShopCommerce->find("all", ["fields" => ["id"], "recursive" => -1, "conditions" => $conditions2]);
            if (!empty($commerces)) {
                $commerces = Set::extract($commerces, "{n}.ShopCommerce.id");
                $requests = $this->Credit->CreditsRequest->find("list", ["conditions" => ["shop_commerce_id" => $commerces]]);
                if (!empty($requests)) {
                    $conditions["Credit.credits_request_id"] = $requests;
                } else {
                    $conditions["Credit.credits_request_id"] = 0;
                }
            } else {
                $conditions["Credit.credits_request_id"] = 0;
            }
        } elseif ( AuthComponent::user("role") == 6) {
			$this->loadModel("ShopCommerce");
			$conditions["Credit.credits_request_id"] = $this->Credit->CreditsRequest->find("list", [
				"conditions" => ["shop_commerce_id" => AuthComponent::user("shop_commerce_id")]
			]) ?: 0;

        }  elseif (AuthComponent::user("role") == 6) {
            $requests = $this->Credit->CreditsRequest->find("list", ["conditions" => ["shop_commerce_id" => AuthComponent::user("shop_commerce_id")]]);
            if (!empty($requests)) {
                $conditions["Credit.credits_request_id"] = $requests;
            } else {
                $conditions["Credit.credits_request_id"] = 0;
            }
        }

        $conditions["Credit.credits_request_id !="] = 0;

        $q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
        $this->set("q", $q);
        $this->Credit->recursive = 0;
        $this->Paginator->settings = array('order' => array('Credit.modified' => 'DESC'), "group" => ["Credit.credits_request_id"]);
        $credits = $this->Paginator->paginate(null, $conditions);
        $this->set(compact('credits'));
    }

	public function index_export()
	{
		$conditions = $this->Credit->buildConditions($this->request->query);

		if (AuthComponent::user("role") == 5) {
			$conditions["Credit.customer_id"] = AuthComponent::user("customer_id");
		} elseif (AuthComponent::user("role") == 4 || AuthComponent::user("role") == 7) {
			$this->loadModel("ShopCommerce");
			$conditions2 = ["ShopCommerce.shop_id" => AuthComponent::user("shop_id")];
			$commerces = $this->ShopCommerce->find("all", ["fields" => ["id"], "conditions" => $conditions2]);
			if (!empty($commerces)) {
				$commerces = Set::extract($commerces, "{n}.ShopCommerce.id");
				$requests = $this->Credit->CreditsRequest->find("list", ["conditions" => ["shop_commerce_id" => $commerces]]);
				if (!empty($requests)) {
					$conditions["Credit.credits_request_id"] = $requests;
				} else {
					$conditions["Credit.credits_request_id"] = 0;
				}
			} else {
				$conditions["Credit.credits_request_id"] = 0;
			}
		} elseif ( AuthComponent::user("role") == 6) {
			$this->loadModel("ShopCommerce");
			$conditions["Credit.credits_request_id"] = $this->Credit->CreditsRequest->find("list", [
				"conditions" => ["shop_commerce_id" => AuthComponent::user("shop_commerce_id")]
			]) ?: 0;

        } elseif (AuthComponent::user("role") == 6) {
			$requests = $this->Credit->CreditsRequest->find("list", ["conditions" => ["shop_commerce_id" => AuthComponent::user("shop_commerce_id")]]);
			if (!empty($requests)) {
				$conditions["Credit.credits_request_id"] = $requests;
			} else {
				$conditions["Credit.credits_request_id"] = 0;
			}
		}

		$conditions["Credit.credits_request_id !="] = 0;

		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q", $q);
		$credits = $this->Credit->find("all", [
			"conditions" => $conditions,
			"contain" => ["CreditsPlan"]
		]);

		// Crear un objeto Spreadsheet
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// Encabezados de columna
		$sheet->setCellValue('A1', 'Fecha');
		$sheet->setCellValue('B1', 'Número de obligación');
		$sheet->setCellValue('C1', 'Identificación del cliente');
		$sheet->setCellValue('D1', 'Nombre del cliente');
		$sheet->setCellValue('E1', 'Teléfono del cliente');
		$sheet->setCellValue('F1', 'Dirección del cliente');
		$sheet->setCellValue('G1', 'Valor Retirado');
		$sheet->setCellValue('H1', 'Tipo de Crédito');
		$sheet->setCellValue('I1', 'Estado');
		$sheet->setCellValue('J1', 'Cuotas');
		$sheet->setCellValue('K1', 'Comercio');
		$sheet->setCellValue('L1', 'Correo electrónico');
		$sheet->setCellValue('M1', 'Valor aprobado');
		$sheet->setCellValue('N1', 'Fechas de pago');
		$sheet->setCellValue('O1', 'Última fecha de pago');

		// Datos
		// Datos de los créditos
		$i = 2;
		foreach ($credits as $key => $value) {
			$comercio = ["Comercio" => $this->ShopCommerce->findById($value["CreditsRequest"]['shop_commerce_id'])];

			$customer = $this->Credit->Customer->findById($value["Customer"]["id"]);

			if (!empty($customer) && !empty($comercio) && isset($comercio["Comercio"]["Shop"])) {
				// Fechas de pago
				$fechasPago = '';
				foreach ($value['CreditsPlan'] as $keyPlan => $plan) {
					$fechasPago .= $plan['deadline'];
				}

				if ($value["Credit"]["type"] == 1) {
					$tipoCredito = "Mensual";
				} elseif ($value["Credit"]["type"] == 3) {
					$tipoCredito = "45 días";
				} elseif ($value["Credit"]["type"] == 4) {
					$tipoCredito = "60 días";
				} else {
					$tipoCredito = "Quincenal";
				}

				$direccion = !empty($customer["CustomersAddress"]["0"]["address"]) ? $customer["CustomersAddress"]["0"]["address"] : $customer["CustomersAddress"]["0"]["address_street"];

				$sheet->setCellValue('A' . $i, date("d-m-Y", strtotime($value["Credit"]["created"])));
				$sheet->setCellValue('B' . $i, $value["Credit"]["code_pay"]);
				$sheet->setCellValue('C' . $i, $value["Customer"]["identification"]);
				$sheet->setCellValue('D' . $i, $customer["Customer"]["name"] . " " . $customer["Customer"]["last_name"]);
				$sheet->setCellValue('E' . $i, $customer["CustomersPhone"]["0"]["phone_number"]);
				$sheet->setCellValue('F' . $i, $direccion);
				$sheet->setCellValue('G' . $i, $value["Credit"]["value_request"]);
				$sheet->setCellValue('H' . $i, $tipoCredito);

				if ($value["Credit"]["debt"]) {
					$state = "Mora";
				} else {
					$state = $value["Credit"]["state"] == 1 ? "Cancelado" : "No finalizado";
				}
				$sheet->setCellValue('I' . $i, $state);
				$sheet->setCellValue('J' . $i, $value["Credit"]["number_fee"]);
				$sheet->setCellValue('K' . $i, $comercio["Comercio"]["Shop"]["social_reason"] . " - " . $comercio["Comercio"]["ShopCommerce"]["name"] . " - " . $comercio["Comercio"]["ShopCommerce"]["code"]);
				$sheet->setCellValue('L' . $i, $customer["Customer"]["email"]);
				$sheet->setCellValue('M' . $i, $value["Credit"]["value_aprooved"]);
				$sheet->setCellValue('N' . $i, $fechasPago);
				$sheet->setCellValue('O' . $i, $value["Credit"]["last_payment_date"]);

				$i++;
			}
		}

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $name = "files/cartera_otorgados_" ."_".date("Y-m-d"). ".xlsx";
        $writer->save($name);

		$url = Router::url("/", true) . $name;
		$this->redirect($url);

		var_dump($url);
		die;

	}




		public function search_user()
		{
			$this->layout = false;

			$this->Credit->Customer->recursive = -1;
			$customer = $this->Credit->Customer->findByIdentification($this->request->data["identification"]);
			$creditsCliente = [];

			if (!empty($customer)) {
				$this->Session->write("customer_id", $customer["Customer"]["id"]);
				$creditsCliente = $this->Credit->find("all", ["recursive" => -1, "conditions" => ["Credit.state" => 0, "Credit.customer_id" => $customer["Customer"]["id"], "Credit.credits_request_id != " => 0, "Credit.juridico" => 0]]);

				if (!empty($creditsCliente)) {
					$creditsCliente = $this->getSaldosByCredit($creditsCliente);
				}
			}

			if (isset($this->request->data["plataforma"])) {
				$this->autoRender = false;
				return json_encode(compact("customer", "creditsCliente"));
			}

			$this->set(compact("customer", "creditsCliente"));

		}

		public function payment_web()
		{
			$this->autoRender = false;
			$requestData = $this->request->input('json_decode');
			if (isset($requestData->event) && $requestData->event == 'transaction.updated' && isset($requestData->data) && isset($requestData->data->transaction) ) {
				$continue = true;
				if (isset($requestData->signature) && isset($requestData->signature->properties)) {
					$strValue = '';
					foreach ($requestData->signature->properties as $key => $str) {
						$str = str_replace("transaction.", "", $str);
						$strValue.=$requestData->data->transaction->$str;
					}
					$strValue.=$requestData->timestamp;
					$strValue.=Configure::read("PAYMENT.eventos");

					$finalHas = hash("sha256", $strValue);
					if ($finalHas != $requestData->signature->checksum) {
						$continue = false;
					}

				}else{
					$continue = false;
				}

				if ($requestData->data->transaction->status == "APPROVED" && $continue) {
					$referenceParts = explode("ZR", $requestData->data->transaction->reference);
					$credit_id      = $referenceParts["1"];
					$valorStr       = $requestData->data->transaction->amount_in_cents;
					$valor          = substr($valorStr, 0, -2);

					$data = ["type" => 2, "value" => $valor, "credit_id" => $credit_id];

					$this->request->data = $data;

					$this->payment_quotes(json_encode($requestData));

				}
			} else {
				$this->log($this->request->input('json_decode'), "debug");
			}
			$this->log("json", "debug");
			$this->log($this->request->input('json_decode'), "debug");
			// $this->log($this->request, "debug");
			$this->log("final", "debug");

			return json_encode([]);
		}

		public function payment_web_response()
		{
			$this->layout = false;
			$this->log($this->request, "debug");
		}

		public function get_data_payment()
		{
			$this->autoRender = false;

			$credit = $this->Credit->find("first", ["recursive" => 2, "conditions" => ["Credit.id" => $this->decrypt($this->request->data["credit"])]]);

			$datos = [
				"currency" => "COP",
				"amountInCents" => $this->request->data["value"]."00",
				"description" => "Pago Zíro, obligación #" . $credit["Credit"]["code_pay"],
				"reference" => date("YmdHis")."ZR".$this->request->data["credit"],
				"extra1" => $this->request->data["credit"],
				"customerData" => [ "legalId" => $credit["Customer"]["identification"], "fullName" => $credit["Customer"]["name"] . " " . $credit["Customer"]["last_name"],"legalIdType" => "CC" ],

				"redirectUrl" => Router::url("/", true) . "payment_web_credishop_response",
				"name_billing" => $credit["Customer"]["name"] . " " . $credit["Customer"]["last_name"],
				"publicKey" => Configure::read("PAYMENT.key")
			];

			$signature = hash ("sha256", $datos["reference"].$datos["amountInCents"].$datos["currency"].Configure::read("PAYMENT.integridad"));

			$datos["signature"]["integrity"] = $signature;

			return json_encode(["datos" => $datos]);
		}

		public function get_credit_customer()
		{
			$this->layout = false;

			if (isset($this->request->data["creditPayment"])) {
				$creditInfo = $this->Credit->find("first", ["recursive" => -1, "conditions" => ["id" => $this->decrypt($this->request->data["creditPayment"])]]);
				$creditsCliente = $this->getSaldosByCredit([$creditInfo])[$this->request->data["creditPayment"]];
				$cuotesPayment = $this->Credit->CreditsPlan->find("count", ["conditions" => ["CreditsPlan.state" => 1, "CreditsPlan.credit_id" => $creditInfo["Credit"]["id"]]]);

				if (isset($this->request->data["plataforma"])) {
					$this->autoRender = false;
					return json_encode(compact("creditInfo", "creditsCliente", "cuotesPayment"));
				}
			}
			$this->set(compact("creditInfo", "creditsCliente", "cuotesPayment"));

		}

		/*
		private function getSaldosByCredit($credits)
		{
			$totalByCredit = [];
			foreach ($credits as $key => $value) {

				$commerceData = $this->Credit->CreditsRequest->ShopCommerce->findById($this->Credit->CreditsRequest->field("shop_commerce_id", ["id" => $value["Credit"]["credits_request_id"]]));

				$totalByCredit[$this->encrypt($value["Credit"]["id"])] = [
					"values" => [
						"total" => $this->Credit->CreditsPlan->getTotalDeudaCredit($value["Credit"]["id"]),
						"min_value" => $this->Credit->CreditsPlan->getMinValue($value["Credit"]["id"]),
					], "fecha" => date("Y-m-d", strtotime($value["Credit"]["created"])), "numero" => $value["Credit"]["code_pay"], "commerce" => $commerceData["Shop"]["social_reason"] . " - " . $commerceData["ShopCommerce"]["name"],
				];
			}
			return $totalByCredit;
		}*/

		public function view($id = null)
		{
			$id = $this->decrypt($id);
			if (!$this->Credit->exists($id)) {
				throw new NotFoundException(__('Página no encontrada'));
			}
			$this->Credit->recursive = 0;
			$conditions = array('Credit.' . $this->Credit->primaryKey => $id);
			$this->set('credit', $this->Credit->find('first', compact('conditions')));
		}

		public function add()
		{
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

		public function edit($id = null)
		{
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

		/**
		 * inicio detail y pago
		 */
		public function payment_detail($creditRequestId,$return=false)
		{
			if(!$return) {
				$creditRequestId = $this->decrypt($creditRequestId);
			}

			$this->Credit->CreditsPlan->setCuotasValue();
			$fecmin = $this->Credit->query("SELECT MIN(payments.CREATED) as fechamin  from payments where payments.receipt_id  IS NULL");

			$this->Credit->CreditsRequest->recursive = in_array(AuthComponent::user("role"), [1, 2, 3, 5, 4, 6, 7]) ? 2 : 1;

			$this->Credit->CreditsRequest->unBindModel(
				["belongsTo" => ["CreditsLine"]]
			);
			$creditRequest = $this->Credit->CreditsRequest->findById($creditRequestId);



			$creditInfo = $this->Credit->findById($creditRequest["CreditsRequest"]["credit_id"]);
			$this->Credit->CreditsRequest->recursive = in_array(AuthComponent::user("role"), [1, 2, 3, 5, 4, 6, 7]) ? 2 : 1;

			$this->Credit->CreditsRequest->unBindModel(
				["belongsTo" => ["CreditsLine"]]
			);

			$this->Credit->CreditsPlan->update_cuotes_days();
			$quotes = $this->Credit->CreditsPlan->getCuotesInformation($creditRequest["CreditsRequest"]["credit_id"]);

			$totalNoPayment = $this->Credit->CreditsPlan->find("count",["conditions"=>["CreditsPlan.state" => 0, "CreditsPlan.credit_id" => $creditInfo["Credit"]["id"] ]]);

			$juridic = $this->Credit->find("count",["conditions"=>["Credit.juridico"=>1,"Credit.customer_id"=>$creditInfo["Credit"]["customer_id"]]]);

			if ($juridic > 0) {
				$this->Session->setFlash(__('Crédito en estado Jurídico'),'flash_error');
			}

			//actualizr contriller

			$deudaTotal = $creditInfo["Credit"]["value_request"];
			$firstDate = "";
			$DateLast = "";
			$swich = 0;
			$pago = 0;
			$pagoA = 0;
			$dateLastPay = null;
			$dateUltPago = null;
			$deudaF = 0;
			$cuotaacumulada = 0;
			$cuenta = $creditInfo["Credit"]["number_fee"] - 1;
			$idLast     = null;

			foreach ($quotes as $key => $value) {
				if ($value["CreditsPlan"]["credit_old"] == 10) {
					$idLast = $value["CreditsPlan"]["id"];
					continue;
				}
				if ($cuenta > 0) {
					$pagoA = ($value["CreditsPlan"]["state"]);
					if ($pagoA == 1) {
						$cuotaacumulada = $cuotaacumulada + $value["CreditsPlan"]["capital_value"];
					}
				}
				$cuenta--;
			}


			$lastQuote  = [];

			foreach ($quotes as $key => $value) {
				$capital = $value["CreditsPlan"]["capital_value"] - $value["CreditsPlan"]["capital_payment"];
				$interes = $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"];
				$others = $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"];

				//Calculo Interes corriente
				if ($firstDate == "") {
					$firstDate = $creditInfo["CreditsRequest"]["date_disbursed"];

					//  $DateLast = $value["CreditsPlan"]["deadline"];
				} else {
					$firstDate = $DateLast;

				}

				$secondDate = $value["CreditsPlan"]["deadline"]; //$value["CreditsPlan"]["deadline"];
				// $dateUltPago = $value["CreditsPlan"]["date_payment"];
				$DateLast = $secondDate;

				$fecha1 = new DateTime($firstDate);
				$fecha2 = new DateTime($secondDate);
				$resultado = $fecha1->diff($fecha2);
				$days = $resultado->format('%a');

				if ($swich == 0) {
					$swich = 1;
					$days = $days + 1;
				}

				if ($days >= 31) {
					$days = 30;
				}

				if ($creditInfo["Credit"]["type"] == 1 && $days < 30) {
					$days = 30;
				}

				if ($creditInfo["Credit"]["type"] != 1 && $days < 15) {
					$days = 15;
				}


				if ($firstDate != $creditInfo["CreditsRequest"]["date_disbursed"]) {

					$interesesT = (($dateUltPago < $secondDate)) ? ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["interes_rate"]) / 100) / 30) * $days : ((($deudaF * $creditInfo["Credit"]["interes_rate"]) / 100) / 30) * $days;
					//Fin Interes corriente

					//otros intereses
					$interesesOT = (($dateUltPago < $secondDate)) ? ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["others_rate"]) / 100) / 30) * $days : ((($deudaF * $creditInfo["Credit"]["others_rate"]) / 100) / 30) * $days;

					//capital
					$CapitalN = $value["CreditsPlan"]["capital_value"];

				} else {

					$interesesT = ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["interes_rate"]) / 100) / 30) * $days;
					//Fin Interes corriente

					//otros intereses
					$interesesOT =  ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["others_rate"]) / 100) / 30) * $days;

					//capital
					$CapitalN =  $value["CreditsPlan"]["capital_value"];

				}

				if ( $value["CreditsPlan"]["interest_value"] <= $value["CreditsPlan"]["interest_payment"] ) {
					$interesesT = $value["CreditsPlan"]["interest_payment"];
					$value["CreditsPlan"]["interest_value"] = $value["CreditsPlan"]["interest_payment"];
				}

				if ( $value["CreditsPlan"]["others_value"] <= $value["CreditsPlan"]["others_payment"] ) {
					$interesesOT = $value["CreditsPlan"]["others_payment"];
					$value["CreditsPlan"]["others_value"] = $value["CreditsPlan"]["others_payment"];
				}

				$pago = ($value["CreditsPlan"]["state"]);

				if ($pago == 1) {

					if ($dateUltPago == null) {
						// echo $cuotaacumulada;
						$dateUltPago = $value["CreditsPlan"]["date_payment"];
						//$cuotaacumulada = $cuotaacumulad  +  $value["CreditsPlan"]["capital_value"];
						$deudaF = $cuotaacumulada + $creditInfo["Credit"]["value_pending"];

					} else if ($dateUltPago < $value["CreditsPlan"]["deadline"]) {

						$dateUltPago = $value["CreditsPlan"]["date_payment"];
						$deudaF = $value["CreditsPlan"]["capital_value"] + $creditInfo["Credit"]["value_pending"];

					}

				}

				$this->loadModel("CreditsPlan");
				$this->CreditsPlan->updateAll(
					["CreditsPlan.capital_value" => (
						(
							$value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0
							and $value["CreditsPlan"]["state"] == 0
						) ? ROUND($CapitalN) : ROUND($value["CreditsPlan"]["capital_value"]),
						"CreditsPlan.interest_value" => (
							($value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0
							and $value["CreditsPlan"]["state"] == 0) ? ROUND($interesesT) : ROUND($value["CreditsPlan"]["interest_value"]),
						"others_value" => (
							($value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0
							and $value["CreditsPlan"]["state"] == 0) ? ROUND($interesesOT) : ROUND($value["CreditsPlan"]["others_value"])
					],
					["CreditsPlan.id" => $value["CreditsPlan"]["id"]]
				);
				$lastQuote = $value;

			}

			$totalCap = 0;
			$plan_id = 0;
			$valorUltQ = 0;


			$quotes = $this->Credit->CreditsPlan->getCuotesInformation($creditRequest["CreditsRequest"]["credit_id"]);
			//actualizar cuotas del credito que no tengan mora
			$this->loadModel('CreditsPlan'); // Cargar el modelo CreditsPlan
			$this->CreditsPlan->updateAll(
				array(
					'interest_value' => 0,
					'interest_payment' => 0,
					'others_value' => 0,
					'others_payment' => 0
				),
				array(
					'credit_id' => $creditInfo["Credit"]["id"],
					'days <= ' => 0
				)
			);
			//fin de la función
			$totalCredit = $this->Credit->CreditsPlan->getCreditDeuda($creditInfo["Credit"]["id"]);
			$totalCreditFinal = $this->Credit->CreditsPlan->getCreditDeuda($creditInfo["Credit"]["id"],null,null,true);


			for ($i = 0; $i < sizeof($quotes); $i++) {

				$whereData = "";

				$pay = $this->Credit->query("select sum(value) as PaymentA from payments where credits_plan_id= ' " . $quotes[$i]["CreditsPlan"]["id"] . " ' ".$whereData);
				$quotes[$i]["CreditsPlan"] += ["TotalAbo" => $pay[0][0]["PaymentA"]];

				$capital = $quotes[$i]["CreditsPlan"]["capital_value"];
				$interes = $quotes[$i]["CreditsPlan"]["interest_value"];
				$others  = $quotes[$i]["CreditsPlan"]["others_value"];
				$totalCP = $capital + $interes + $others;

			}

			$creditsPlansIds=[];
			foreach ($quotes as $key => $value) {
				array_push($creditsPlansIds, $value['CreditsPlan']['id']);
			}

			$this->loadModel("Payment");
			$payments = $this->Payment->find("all",
				["conditions" => [
						"Payment.credits_plan_id" => $creditsPlansIds
					]
				]
			);

			if ($return) {
				return [
					'creditRequest' => $creditRequest,
					'creditInfo' => $creditInfo,
					'quotes' => $quotes,
					'totalCredit' => $totalCredit,
					'fecmin' => $fecmin,
					'totalCreditFinal' => $totalCreditFinal,
					'payments' => $payments
				];
			} else {
				$this->set(compact("creditRequest", "layout", "creditInfo", "quotes", "totalCredit","fecmin","totalCreditFinal", "payments"));
			}

		}

		/**
		 * obtener total deuda final
		 */
		private function getTotalFinal($creditId)
		{

			$total = $this->Credit->CreditsPlan->getCreditDeuda($creditId); //se obtine el total

			return $total;
		}

		public function quotes_value()
		{

			$this->autoRender = false;
			$creditId = $this->decrypt($this->request->data["credit_id"]);
			$quotes = $this->request->data["quotes"];

			$total = 0;

			$totalCuotesNoPayment = $this->Credit->CreditsPlan->find("count", ["conditions" => ["CreditsPlan.credit_id" => $creditId, "CreditsPlan.state" => '0']]);

			if ($totalCuotesNoPayment == count($quotes) && $totalCuotesNoPayment != 0 && $totalCuotesNoPayment > 1) {
				$total = $this->getTotalFinal($creditId);

			} else {

				/**
				 * desencriptar  id de las coutas
				 */
				foreach ($quotes as $key => $value) {
					$quotes[$key] = $this->decrypt($value); //añadir propiedad nueva con su key desincriptado
				}

				foreach ($quotes as $key => $value) {
					$dataQuote = $this->Credit->CreditsPlan->getCuotesInformation($creditId, $value);

					$capital = $dataQuote["CreditsPlan"]["capital_value"]; //- $dataQuote["CreditsPlan"]["capital_payment"];

					$interes = $dataQuote["CreditsPlan"]["interest_value"]; // - $dataQuote["CreditsPlan"]["interest_payment"];

					$others = $dataQuote["CreditsPlan"]["others_value"]; //- $dataQuote["CreditsPlan"]["others_payment"];

					$pay = $this->Credit->query("select sum(value) as PaymentA from payments where credits_plan_id= ' " . $dataQuote["CreditsPlan"]["id"] . " '");
					$pay = $pay[0][0]["PaymentA"];

					$total += $capital + $interes + $others + $dataQuote["CreditsPlan"]["debt_value"] + $dataQuote["CreditsPlan"]["debt_honor"];
					$total = $total - $pay;

				}

			}

			if ($total < 0) {
				$total = 0;
			}

			return $total;
		}

		public function payment_crediventas(){
			$this->autoRender = false;
			$this->loadModel("ShopCommerce");
			$this->loadModel("Receipt");

			$existsCommerce = $this->ShopCommerce->field("id",["code" => '73221084',"state" => 1]);
			$recibo = $this->payment_quotes(["id_ext"=>$existsCommerce],true);

			$this->Receipt->updateAll(["Receipt.ext"=> 1 ],["Receipt.id" => $recibo]);

			return $this->encrypt($recibo);
		}


		public function payment_quotes($dataWeb = null,$return = null)
		{
			if (is_null($dataWeb)) {
				$this->autoRender = false;
			}


			$type   = $this->request->data["type"];

			$uid    = time().uniqid();

			$typeini = explode(',',$this->request->data["credit_id"]);

			$creditId = $this->decrypt($typeini[0]);

			$valorPago = 0;//

			$Pago = intval($typeini[1]);


			$valorPago  = $Pago;
			$quotes   = isset($this->request->data["ids"]) ? $this->request->data["ids"] : null;

			$this->Session->write("CUOTESP", []);

			if ($type == "2") {

				//RECETEO DE DATOS
				$valorPago 	= isset($this->request->data["value"]) ? $this->request->data["value"] : 0;

				$creditId 	= $this->decrypt($this->request->data["credit_id"]);
				$quotes 	= isset($this->request->data["ids"]) ? $this->request->data["ids"] : null;

				$Pago  =  $valorPago;
				$this->Session->write("CUOTESP",[]);
				//////////////

					$quotes = $this->Credit->CreditsPlan->getCuotesInformation($creditId, null, 0); // obtengo su estado actual por couta
					foreach ($quotes as $key => $value) {
						$valorPago = $this->payment_cuotes_normal($valorPago, $value, null, $dataWeb,$uid);
					}

			} else if($type == "1"){

				foreach ($quotes as $key => $value) {
					$quotes[$key] = $this->decrypt($value);
				}

				$totalCuotesNoPayment = $this->Credit->CreditsPlan->find("count", ["conditions" => ["CreditsPlan.credit_id" => $creditId, "CreditsPlan.state" => 0]]);
				echo  $totalCuotesNoPayment;

				if (($totalCuotesNoPayment != count($quotes) && $totalCuotesNoPayment != 0) || ($totalCuotesNoPayment == 1 && count($quotes) == 1)) {

					$quotes = $this->Credit->CreditsPlan->getCuotesInformation($creditId, $quotes, 0);

					foreach ($quotes as $key => $value) {

						$capital = $value["CreditsPlan"]["capital_value"] - $value["CreditsPlan"]["capital_payment"];
						$interes = $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"];
						$others = $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"];

						$valorPago = $capital + $interes + $others + $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
						$valorPago = $this->payment_cuotes_normal($valorPago, $value, null, $dataWeb,$uid);


					}

					/*for ($i = 0; $i <=$valorPago; $i++){
						echo "<br>" . $valorPago;
						$valorPago = $this->payment_cuotes_normal($valorPago, $value, null, $dataWeb);
					}*/


				} else {
					$this->loadModel("CreditsPlan");
					$credit_id  = $creditId;
					$creditInfo = $this->Credit->find("first",["recursive"=>-1, "conditions"=>["Credit.id" => $credit_id]]);

					$lastNumber  = $creditInfo["Credit"]["number_fee"];

					$lastQuote   = $this->CreditsPlan->find("first",["recursive"=>-1,"conditions"=>["credit_id"=>$credit_id,"number"=>$lastNumber]]);

					$lastQuoteDebt = $this->CreditsPlan->find("first",["recursive"=>-1,"conditions"=>["credit_id"=>$credit_id,"credit_old"=> 10 ]]);


					$quoteParam  = 0;

					if (
						($lastQuote["CreditsPlan"]["state"] == 0 && strtotime($lastQuote["CreditsPlan"]["deadline"]) <= strtotime(date("Y-m-d")))
						||
						($lastQuote["CreditsPlan"]["state"] == 0 && strtotime($lastQuote["CreditsPlan"]["deadline"]) >= strtotime(date("Y-m-d")) && strtotime($lastQuote["CreditsPlan"]["dateini"]) < strtotime(date("Y-m-d")) )
					) {
						$quoteParam = 1;
					}elseif(!empty($lastQuoteDebt) && ( ($lastQuoteDebt["CreditsPlan"]["state"] == 0 && strtotime($lastQuoteDebt["CreditsPlan"]["deadline"]) <= strtotime(date("Y-m-d")))
						||
						($lastQuoteDebt["CreditsPlan"]["state"] == 0 && strtotime($lastQuoteDebt["CreditsPlan"]["deadline"]) >= strtotime(date("Y-m-d")) && strtotime($lastQuoteDebt["CreditsPlan"]["dateini"]) < strtotime(date("Y-m-d")) ) )){
						$quoteParam = 1;
					}

					if ($quoteParam == 1) {
						$quotes = $this->Credit->CreditsPlan->getCuotesInformation($creditId, $quotes, 0);

						foreach ($quotes as $key => $value) {

							$capital = $value["CreditsPlan"]["capital_value"] - $value["CreditsPlan"]["capital_payment"];
							$interes = $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"];
							$others = $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"];

							$valorPago = $capital + $interes + $others + $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
							$valorPago = $this->payment_cuotes_normal($valorPago, $value, null, $dataWeb,$uid);

						}
					}else{
						$totalApagar   = $this->getTotalFinal($creditId); //obtengo dato si quiero pagar todo
						$quotes        = $this->Credit->CreditsPlan->getCuotesInformation($creditId,null,0);
						$this->paymentTotalCredit($quotes,$creditId,$dataWeb,0,$uid);
					}
				}
			}else{

				$totalApagar = $this->getTotalFinal($creditId); //obtengo dato si quiero pagar todo

				$pagoInicial = $valorPago;




				if ($valorPago == $totalApagar) { // si valor digitado es igual al total de la deuda
					$quotes = $this->Credit->CreditsPlan->getCuotesInformation($creditId, null, 0);
					$this->paymentTotalCredit($quotes, $creditId, $dataWeb,0,$uid); //llamo a la funcion pagar todo
				} else {
					$quotes = $this->Credit->CreditsPlan->getCuotesInformation($creditId, null, 0); // obtengo su estado actual por couta
					foreach ($quotes as $key => $value) {
						$valorPago = $this->payment_cuotes_normal($valorPago, $value, null, $dataWeb,$uid);
						if ($valorPago <= 0) {
							break;
						}
					}
				}
			}

			$credit = $this->Credit->find("first", ["conditions" => ["id" => $creditId], "recursive" => -1]);
			$credit["Credit"]["last_payment_date"] = date("Y-m-d");

			$credit["Credit"]["state"] = $this->Credit->CreditsPlan->find("count", ["conditions" => ["CreditsPlan.credit_id" => $creditId, "CreditsPlan.state" => 0]]) == 0 ? 1 : 0;

			$normal = 0;
			if ($credit["Credit"]["juridico"] == 1 && $credit["Credit"]["state"] == 1) {
				$credit["Credit"]["juridico"] = 0;
				$normal = 1;
			}
			$this->Credit->save($credit);

			if ($normal == 1) {
				$allCredits = $this->Credit->findAllByCustomerIdAndJuridico($credit["Credit"]["customer_id"], 1);
				if (count($allCredits) == 0) {
					$this->loadModel("User");
					$this->User->recursive = -1;
					$user = $this->User->findByCustomerId($credit["Credit"]["customer_id"]);
					$user["User"]["state"] = 1;
					$this->User->save($user);
				}
			}



			$cuotes = $this->Session->read("CUOTESP");



		/*
			if (!empty($FechaMIn)) {
				$FechaMIn = $FechaMIn[0][0]["fechamin"];
			}


			//$FechaMIn = null;

			$this->Credit->query("update payments set CREATED= ". $FechaMIn . "  where receipt_id  IS NULL");*/


			sleep(5);
			$this->loadModel("Payment");

			$recibo = $this->Payment->setReceipts($Pago,true);

			if (!empty($cuotes)) {
				$this->sendReceipt($cuotes, $credit["Credit"]["id"], $dataWeb);
			}

			if (!is_null($return)) {
				return $recibo;
			}
			return true;
			die;

			$this->Session->setFlash(__('Pago realizado correctamente'), 'flash_success');

		}

		private function payment_cuotes_normal($valorPago, $value, $capital = null, $dataWeb = null,$uid = null)
		{

			$paymentValue = 0;

			$credit = $this->Credit->find("first", ["conditions" => ["Credit.id" => $value["CreditsPlan"]["credit_id"]], "recursive" => 1]);

			$ext = false;
			if (isset($dataWeb["id_ext"])) {
				$credit["CreditsRequest"]["shop_commerce_id"] = $dataWeb["id_ext"];
				$dataWeb = null;
				$ext     = true;
			}

			$commerce_id = null;
			if ($ext) {
				$commerce_id = $credit["CreditsRequest"]["shop_commerce_id"];
			}else{
				if (AuthComponent::user("role") == 11) {
					$commerce_id = null;
				}else{
					$commerce_id = AuthComponent::user("shop_commerce_id");
				}
			}



			$this->loadModel("CreditsRequest");
			$this->loadModel("Payment");
			$this->loadModel("CreditsPlan");
			if ($value["CreditsPlan"]["debt_value"] > 0 && $valorPago > 0 && is_null($capital) ) {

				$pay = $this->Credit->query("select sum(value) as PaymentA from payments where credits_plan_id= ' " . $value["CreditsPlan"]["id"] . " '   AND  type=4");
				$pay = $pay[0][0]["PaymentA"];

				$debt_value = $value["CreditsPlan"]["debt_value"] - $pay;

				if ($debt_value > 0) {

					$paymentDebt = [
						"Payment" => [
							"credits_plan_id" => $value["CreditsPlan"]["id"],
							"value" => $valorPago > $debt_value ? abs($debt_value) : $valorPago,
							"user_id" => AuthComponent::user("id"),
							"shop_commerce_id" => $commerce_id,
							"type" => 4,
							"uid" => $uid,
							"web" => $dataWeb,
							"juridic" => AuthComponent::user("role") == 11 ? 1 : 0,
						],
					];
					$this->Payment->create();
					if ($this->Payment->save($paymentDebt)) {
						$this->Session->write("CUOTAID", $this->Payment->id);
						$valorPago -= $paymentDebt["Payment"]["value"];
						$paymentValue += $paymentDebt["Payment"]["value"];
						$value["CreditsPlan"]["date_debt"] = date("Y-m-d");
					} else {
						$this->log($this->Payment->validationErrors, "debug");
					}
				}
			}

			if ($value["CreditsPlan"]["debt_honor"] > 0 && $valorPago > 0 && is_null($capital)) {
				$pay = $this->Credit->query("select sum(value) as PaymentA from payments where credits_plan_id= ' " . $value["CreditsPlan"]["id"] . " '   AND  type=5");
				$pay = $pay[0][0]["PaymentA"] = 0;

				$debt_honor = $value["CreditsPlan"]["debt_honor"] - $pay;

				if ($debt_honor > 0) {
					$paymentHonor = [
						"Payment" => [
							"credits_plan_id" => $value["CreditsPlan"]["id"],
							"value" => $valorPago > $debt_honor ? abs($debt_honor) : $valorPago,
							"user_id" => AuthComponent::user("id"),
							"shop_commerce_id" => $commerce_id,
							"type" => 5,
							"uid" => $uid,
							"web" => $dataWeb,
							"juridic" => AuthComponent::user("role") == 11 ? 1 : 0,
						],
					];
					$this->Payment->create();
					if ($this->Payment->save($paymentHonor)) {
						$this->Session->write("CUOTAID", $this->Payment->id);
						$valorPago -= $paymentHonor["Payment"]["value"];
						$paymentValue += $paymentHonor["Payment"]["value"];
						$value["CreditsPlan"]["date_debt"] = date("Y-m-d");
						$this->CreditsPlan->save($value["CreditsPlan"]);
					} else {
						$this->log($this->Payment->validationErrors, "debug");
					}
				}
			}

			$this->loadModel("Payment");
			if ($value["CreditsPlan"]["others_payment"] == 0 || $value["CreditsPlan"]["others_payment"] != $value["CreditsPlan"]["others_value"] ) {
				$totalPayments = $this->Payment->find("first",["fields"=>["SUM(value) Total"],"conditions"=>["Payment.credits_plan_id" => $value["CreditsPlan"]["id"],"Payment.type" => 3 ],"recursive" => -1]);

				if (!empty($totalPayments) && !is_null($totalPayments["0"]["Total"])) {
					$value["CreditsPlan"]["others_payment"] = $totalPayments["0"]["Total"];
				}

			}

			if (($value["CreditsPlan"]["others_value"] > $value["CreditsPlan"]["others_payment"] && $valorPago > 0 && is_null($capital)) || (($value["CreditsPlan"]["debt_value"] > 0 || $value["CreditsPlan"]["debt_honor"] > 0) && $valorPago > 0 && is_null($capital) )) {
				$valorPagoOthers = $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"];

				$paymentOthers = [
					"Payment" => [
						"credits_plan_id" => $value["CreditsPlan"]["id"],
						"value" => $valorPago > abs($valorPagoOthers) ? abs($valorPagoOthers) : $valorPago,
						"user_id" => AuthComponent::user("id"),
						"shop_commerce_id" => $commerce_id,
						"type" => 3,
						"uid" => $uid,
						"web" => $dataWeb,
						"juridic" => AuthComponent::user("role") == 11 ? 1 : 0,
					],
				];

				$this->Payment->create();
				if ($this->Payment->save($paymentOthers)) {
					$this->Session->write("CUOTAID", $this->Payment->id);
					$valorPago -= $paymentOthers["Payment"]["value"];
					$paymentValue += $paymentOthers["Payment"]["value"];
					$value["CreditsPlan"]["others_payment"] += $paymentOthers["Payment"]["value"];
				} else {
					$this->log($this->Payment->validationErrors, "debug");
				}
			}


			if ($value["CreditsPlan"]["interest_payment"] == 0 || $value["CreditsPlan"]["interest_payment"] != $value["CreditsPlan"]["interest_value"]) {
				$totalPayments = $this->Payment->find("first",["fields"=>["SUM(value) Total"],"conditions"=>["Payment.credits_plan_id" => $value["CreditsPlan"]["id"],"Payment.type" => 2 ],"recursive" => -1]);

				if (!empty($totalPayments) && !is_null($totalPayments["0"]["Total"])) {
					$value["CreditsPlan"]["interest_payment"] = $totalPayments["0"]["Total"];
				}

			}

			if (($value["CreditsPlan"]["interest_value"] > $value["CreditsPlan"]["interest_payment"] && $valorPago > 0 && is_null($capital)) || (($value["CreditsPlan"]["debt_value"] > 0 || $value["CreditsPlan"]["debt_honor"] > 0) && $valorPago > 0 && is_null($capital))) {
				$valorPagoInteres = $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"];

				$paymentInteres = [
					"Payment" => [
						"credits_plan_id" => $value["CreditsPlan"]["id"],
						"value" => $valorPago > abs($valorPagoInteres) ? abs($valorPagoInteres) : $valorPago,
						"user_id" => AuthComponent::user("id"),
						"shop_commerce_id" => $commerce_id,
						"type" => 2,
						"uid" => $uid,
						"web" => $dataWeb,
						"juridic" => AuthComponent::user("role") == 11 ? 1 : 0,
					],
				];
				$this->Payment->create();
				if ($this->Payment->save($paymentInteres)) {
					$this->Session->write("CUOTAID", $this->Payment->id);
					$valorPago -= $paymentInteres["Payment"]["value"];
					$paymentValue += $paymentInteres["Payment"]["value"];
					$value["CreditsPlan"]["interest_payment"] += $paymentInteres["Payment"]["value"];
				} else {
					$this->log($this->Payment->validationErrors, "debug");
				}
			}

			if ($value["CreditsPlan"]["capital_value"] > $value["CreditsPlan"]["capital_payment"] && $valorPago > 0) {

				$totalPayments = $this->Payment->find("first",["fields"=>["SUM(value) Total"],"conditions"=>["Payment.credits_plan_id" => $value["CreditsPlan"]["id"],"Payment.type" => 1 ],"recursive" => -1]);

				if (!empty($totalPayments) && !is_null($totalPayments["0"]["Total"])) {
					$value["CreditsPlan"]["capital_payment"] = $totalPayments["0"]["Total"];
				}

				$valorPagoCapital = $value["CreditsPlan"]["capital_value"] - $value["CreditsPlan"]["capital_payment"];

				if ($valorPagoCapital > 0) {

					// ---------------------------------
					//

					$paymentCapital = [
						"Payment" => [
							"credits_plan_id" => $value["CreditsPlan"]["id"],
							"value" => $valorPago > $valorPagoCapital ? $valorPagoCapital : $valorPago,
							"user_id" => AuthComponent::user("id"),
							"shop_commerce_id" => $commerce_id,
							"type" => 1,
							"uid" => $uid,
							"web" => $dataWeb,
							"juridic" => AuthComponent::user("role") == 11 ? 1 : 0,
						],
					];
					$this->Payment->create();
					if ($this->Payment->save($paymentCapital)) {

						$paymentID = $this->Payment->id;

						$this->Session->write("CUOTAID", $this->Payment->id);
						$valorPago -= $paymentCapital["Payment"]["value"];
						$paymentValue += $paymentCapital["Payment"]["value"];
						$value["CreditsPlan"]["capital_payment"] += $paymentCapital["Payment"]["value"];

						if ($value["CreditsPlan"]["capital_payment"] >= $value["CreditsPlan"]["capital_value"]) {
							$value["CreditsPlan"]["state"] = 1;

							$value["CreditsPlan"]["date_payment"] = date("Y-m-d");
							$this->CreditsPlan->save($value["CreditsPlan"]);


							$credito = $this->Credit->find("first", ["recursive" => -1, "conditions" => ["Credit.id" => $value["CreditsPlan"]["credit_id"]]]);

							$credito["Credit"]["value_pending"] -= $value["CreditsPlan"]["capital_payment"];
							$credito["Credit"]["debt_days"] = 0;
							$this->CreditsPlan->save($value["CreditsPlan"]);

							$this->Credit->save($credito["Credit"]);

							/**
							 * codigo para activar los aprobados
							 */

							$this->loadModel("CreditsRequest");
							$dateLimit = $this->Credit->field("deadline", ["id" => $value["CreditsPlan"]["credit_id"]]);
							$credits_request_id = $this->Credit->field("credits_request_id", ["id" => $value["CreditsPlan"]["credit_id"]]);
							$customer_id = $this->Credit->field("customer_id", ["id" => $value["CreditsPlan"]["credit_id"]]);

							$dateLimit = date("Y-m-d", strtotime($dateLimit . "+ 7 days"));

							$datosLimit = [
								"CreditLimit" => [
									"value" => $paymentCapital["Payment"]["value"],
									"state" => 5,
									"reason" => "Preaprobado por restante de solicitud",
									"type_movement" => 1,
									"credits_request_id" => $credits_request_id,
									"user_id" => AuthComponent::user("id"),
									"deadline" => $dateLimit,
									"customer_id" => $customer_id,
									"credit_id" => $value["CreditsPlan"]["credit_id"],
									"active" => 1,
									"payment_id" => $paymentID,
								],
							];
							$this->CreditsRequest->CreditLimit->create();
							$this->CreditsRequest->CreditLimit->save($datosLimit);

							$this->CreditsRequest->CreditLimit->updateAll(
								["CreditLimit.active" => 0],
								[
									"CreditLimit.state" => [3, 4, 5],
									"CreditLimit.customer_id" => $customer_id,
								]
							);

							$result = $this->Credit->query("select DATEDIFF(CURDATE(),MIN(deadline)) dias FROM credits_plans where credit_id in (select credit_id from credits_requests where customer_id =" . $customer_id . ") and credits_plans.state = 0");

							if (!empty($result)) {
								$days = $result[0][0]["dias"];
							}

							$active = 0;

							if ($days <= 10) {
								$active = 1;

							} else {
								$active = 0;
							}

							$this->CreditsRequest->CreditLimit->updateAll(
								["CreditLimit.active" => $active],
								[
									"CreditLimit.state" => [3, 4, 5],
									"CreditLimit.customer_id" => $customer_id,
								]
							);
						}
					}

					// ---------------------------------
				}
			}

			if ($paymentValue > 0) {

				$cuotes = $this->Session->read("CUOTESP");
				$cuotes[$value["CreditsPlan"]["number"]] = $paymentValue;
				$this->Session->write("CUOTESP", $cuotes);
				// $this->sendReceipt($value["CreditsPlan"]["id"],$value["CreditsPlan"]["credit_id"],$paymentValue);

				if (AuthComponent::user("role") == 11) {

					$this->loadModel("History");

					$type = AuthComponent::user("role") == 11 ? 1 : 0;

					$dataNote = ["History" => [
						"credits_plan_id" => $value["CreditsPlan"]["id"],
						"user_id" => AuthComponent::user("id"),
						"type" => $type,
						"action" => "Se realizó pago por: $" . number_format($paymentValue),
					]];

					$this->History->create();
					$this->History->save($dataNote);

				}

			}

			return $valorPago;

		}

		public function sendReceipt($quotes, $creditId, $dataWeb = null)
		{

			$credit = $this->Credit->findById($creditId);

			if (empty($credit["Customer"]["email"])) {
				return false;
			}

			$quotaID = $this->Session->read("CUOTAID");

			$this->loadModel("Payment");

			$cuotaData = $this->Payment->findById($quotaID);

			$shopCommerce = $this->Credit->CreditsRequest->ShopCommerce->findById($credit["CreditsRequest"]["shop_commerce_id"]);

			$options = [
				"subject" => "Pago realizado al crédito: " . $credit["CreditsRequest"]["code_pay"],
				"to" => $credit["Customer"]["email"],
				"vars" => ["credit" => $credit, "quotes" => $quotes, "shop_commerce" => $shopCommerce, "cuotaData" => $cuotaData, "dataWeb" => $dataWeb],
				"template" => "payment_quote",
			];

			$this->sendMail($options);
		}

		private function paymentTotalCredit($cuotes, $creditId, $dataWeb = null, $totalApagar = 0,$uid = null)
		{
			$credit = $this->Credit->find("first", ["conditions" => ["Credit.id" => $creditId], "recursive" => 1]);

			$ext = false;
			if (isset($dataWeb["id_ext"])) {
				$credit["CreditsRequest"]["shop_commerce_id"] = $dataWeb["id_ext"];
				$dataWeb = null;
				$ext     = true;
			}

			$commerce_id = null;
			if ($ext) {
				$commerce_id = $credit["CreditsRequest"]["shop_commerce_id"];
			}else{
				if (AuthComponent::user("role") == 11) {
					$commerce_id = null;
				}else{
					$commerce_id = AuthComponent::user("shop_commerce_id");
				}
			}

			$credit_id = $creditId;


			// ---------------------------------------------------------------------------

			$Date_Disbursed = $credit["CreditsRequest"]["date_disbursed"];
			$Date_Disbursed = date("d-m-Y", strtotime($Date_Disbursed));

			$Date_deadline_quote_pay = $this->Credit->CreditsPlan->find("first", ["conditions" => ["CreditsPlan.credit_id" => $credit_id,"CreditsPlan.state"=>1], "fields" => ["ifnull(max(CreditsPlan.deadline),'nul') as deadline"]]);

			$Date_deadline_quote_pay = $Date_deadline_quote_pay["0"]["deadline"];

			$Date_inital = $Date_deadline_quote_pay=="nul"?$Date_Disbursed:$Date_deadline_quote_pay;

			$Date_inital = new DateTime($Date_inital);
			$Date_now    = new DateTime(date("Y-m-d"));
			$difference  = $Date_inital->diff($Date_now);
			$days        = $difference->days;
			$days        = ($Date_deadline_quote_pay=="nul" && $days < 30)?30:$days;

			if ($Date_deadline_quote_pay!="nul"){
				if ($Date_inital > $Date_now){
					$days=0;
				}
			}

			/** Final calculo de dias */
			$pay                = $this->Credit->query("select sum(value) as PaymentA from payments where credits_plan_id IN (SELECT id FROM credits_plans WHERE credit_id = $credit_id ) AND type = 1 ");
			$valorPagado        = $pay[0][0]["PaymentA"];

			$valorDesembolso    = $credit["Credit"]["value_request"];
			$Value_pending      = ROUND($valorDesembolso-$valorPagado);


			$TypeCredit    = $credit["Credit"]["type"];
			$Interes_rate  = $credit["Credit"]["interes_rate"];
			$Others_rate   = $credit["Credit"]["others_rate"];
			$debtRate      = $credit["Credit"]["debt_rate"];


			$TotalInteres = ROUND(((($Value_pending * $Interes_rate) / 100) / 30) * $days);
			$TotalOthers  = ROUND(((($Value_pending * $Others_rate) / 100) / 30) * $days);

			$lastPaymentDate = $credit["Credit"]["last_payment_date"];
			$crediType       = $TypeCredit;
			$crediState      = $credit["Credit"]["state"];

			$conditions = ["credit_id" => $credit_id];
			$conditions["state"] = 0;

			$quotes     = $this->Credit->CreditsPlan->find("all", ["conditions"=>$conditions, "order" => ["number"=>"ASC"],"recursive" => -1 ] );
			$total      = 0;
			$totalDB    = 0;

			foreach ($quotes as $key => $value) {
				$quotes[$key] = $this->Credit->CreditsPlan->calculateDebt($value,$lastPaymentDate,$debtRate,$credit_id);
				$value        = $quotes[$key];
				if($value["CreditsPlan"]["state"] == 0) {
					$totalDB+= $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
				}
			}

			// ---------------------------------------------------------------------------

			if ($totalApagar == 0) {
				$num = 0;

				$capitalTotal = 0;

				foreach ($cuotes as $key => $value) {
					$capitalTotal += ($value["CreditsPlan"]["capital_value"] - $value["CreditsPlan"]["capital_payment"]);
				}
				foreach ($cuotes as $key => $value) {
					if ($num == 0) {

						$interesesPasados   = $TotalInteres;
						$interesesOther     = $TotalOthers;

						$paymentValue = 0;

						$this->loadModel("Payment");

						if ($totalDB > 0) {
							$paymentDebt = [
								"Payment" => [
									"credits_plan_id" => $value["CreditsPlan"]["id"],
									"value" => $totalDB,
									"user_id" => AuthComponent::user("id"),
									"web" => $dataWeb,
									"shop_commerce_id" => $commerce_id,
									"type" => 4,
									"uid" => $uid,
								],
							];
							$this->Payment->create();
							if ($this->Payment->save($paymentDebt)) {
								$this->Session->write("CUOTAID", $this->Payment->id);
								$paymentValue += $paymentDebt["Payment"]["value"];

								///$value["CreditsPlan"]["date_debt"]     = date("Y-m-d");
							}
						}

						$paymentOthers = [
							"Payment" => [
								"credits_plan_id" => $value["CreditsPlan"]["id"],
								"value" => $interesesOther,
								"user_id" => AuthComponent::user("id"),
								"web" => $dataWeb,
								"shop_commerce_id" => AuthComponent::user("role") == 11 || $ext ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
								"type" => 3,
								"uid" => $uid,
							],
						];

						$this->Payment->create();
						if ($this->Payment->save($paymentOthers)) {
							$this->Session->write("CUOTAID", $this->Payment->id);
							$paymentValue += $paymentOthers["Payment"]["value"];
							$value["CreditsPlan"]["others_payment"] += $paymentOthers["Payment"]["value"];
						}

						$paymentInteres = [
							"Payment" => [
								"credits_plan_id" => $value["CreditsPlan"]["id"],
								"value" => $interesesPasados,
								"user_id" => AuthComponent::user("id"),
								"web" => $dataWeb,
								"shop_commerce_id" => $commerce_id,
								"type" => 2,
								"uid" => $uid,
							],
						];

						$this->Payment->create();
						if ($this->Payment->save($paymentInteres)) {
							$this->Session->write("CUOTAID", $this->Payment->id);
							$paymentValue += $paymentInteres["Payment"]["value"];
							$value["CreditsPlan"]["interest_payment"] += $paymentInteres["Payment"]["value"];
						}

						$valorPagoCapital = $value["CreditsPlan"]["capital_value"] - $value["CreditsPlan"]["capital_payment"];

						$paymentCapital = [
							"Payment" => [
								"credits_plan_id" => $value["CreditsPlan"]["id"],
								"value" => $valorPagoCapital,
								"user_id" => AuthComponent::user("id"),
								"web" => $dataWeb,
								"shop_commerce_id" => $commerce_id,
								"type" => 1,
								"uid" => $uid,
							],
						];
						$this->Payment->create();
						if ($this->Payment->save($paymentCapital)) {
							$paymentID = $this->Payment->id;
							$this->Session->write("CUOTAID", $this->Payment->id);

							$credito = $this->Credit->find("first", ["recursive" => -1, "conditions" => ["Credit.id" => $value["CreditsPlan"]["credit_id"]]]);

							$credito["Credit"]["value_pending"] -= $paymentCapital["Payment"]["value"];
							$credito["Credit"]["debt_days"] = 0;

							$this->Credit->save($credito);

							$value["CreditsPlan"]["date_payment"] = date("Y-m-d");
							$this->Credit->CreditsPlan->save($value);

							$paymentValue += $paymentCapital["Payment"]["value"];

							$value["CreditsPlan"]["capital_payment"] += $paymentCapital["Payment"]["value"];
							if ($value["CreditsPlan"]["capital_payment"] == $value["CreditsPlan"]["capital_value"]) {
								$value["CreditsPlan"]["state"] = 1;
							}

							$this->loadModel("CreditsRequest");
							$dateLimit = $this->Credit->field("deadline", ["id" => $value["CreditsPlan"]["credit_id"]]);
							$datosLimit = [
								"CreditLimit" => [
									"value" => $paymentCapital["Payment"]["value"],
									"state" => 5,
									"reason" => "Preaprobado por restante de solicitud",
									"type_movement" => 1,
									"credits_request_id" => $credit["Credit"]["credits_request_id"],
									"user_id" => AuthComponent::user("id"),
									"deadline" => $dateLimit,
									"customer_id" => $credit["Credit"]["customer_id"],
									"credit_id" => $value["CreditsPlan"]["credit_id"],
									"active" => 1,
									"payment_id" => $paymentID,
								],
							];
							$this->CreditsRequest->CreditLimit->create();
							$this->CreditsRequest->CreditLimit->save($datosLimit);

						}

						$value["CreditsPlan"]["date_payment"] = date("Y-m-d");
						$this->Credit->CreditsPlan->save($value);

						if ($paymentValue > 0) {
							$cuotes = $this->Session->read("CUOTESP");
							$cuotes[$value["CreditsPlan"]["number"]] = $paymentValue;
							$this->Session->write("CUOTESP", $cuotes);

							//$this->sendReceipt($value["CreditsPlan"]["id"],$value["CreditsPlan"]["credit_id"],$paymentValue);
						}
						$num++;
					} else {
						$total = $value["CreditsPlan"]["capital_value"] - $value["CreditsPlan"]["capital_payment"];

						if ($value["CreditsPlan"]["debt_value"] > 0 || $value["CreditsPlan"]["debt_honor"] > 0) {
							$total += $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"];
							$total += $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"];
							$total += $value["CreditsPlan"]["debt_value"];
							$total += $value["CreditsPlan"]["debt_honor"];
						}

						$this->payment_cuotes_normal($total, $value, true, $dataWeb,$uid);
					}
				}
			} else {

				$this->loadModel("CreditsPlan");
				$this->CreditsPlan->updateAll(
					["CreditsPlan.capital_payment" => "CreditsPlan.capital_value",
						"CreditsPlan.interest_payment" => "CreditsPlan.interest_value",
						"others_payment" => "others_value"],
					["CreditsPlan.id" => $creditId]
				);

				$this->loadModel("Payment");

				$paymentDebt = [
					"Payment" => [
						"credits_plan_id" => 0,
						"value" => $totalApagar,
						"user_id" => AuthComponent::user("id"),
						"web" => null,
						"shop_commerce_id" => AuthComponent::user("role") == 11 || $ext ? $credit["CreditsRequest"]["shop_commerce_id"] : AuthComponent::user("shop_commerce_id"),
						"type" => 7,
						"uid" => $uid
					],
				];
				$this->Payment->create();
				if ($this->Payment->save($paymentDebt)) {
					$this->Session->write("CUOTAID", $this->Payment->id);
					//$paymentValue += $value["CreditsPlan"]["debt_value"];

					///$value["CreditsPlan"]["date_debt"]     = date("Y-m-d");
				}

				/*foreach ($cuotes as $key => $value) {

			$value["CreditsPlan"]["interest_value"];
			$value["CreditsPlan"]["interest_value"];
			$value["CreditsPlan"]["interest_value"];
			$value["CreditsPlan"]["interest_value"];
			$value["CreditsPlan"]["interest_value"];
			$value["CreditsPlan"]["interest_value"];

			}*/

			}
		}

		///fin

		public function plan_payemts_pdf($requestId, $type = "view", $return = null)
		{

			$this->Credit->CreditsRequest->recursive = 2;

			$this->Credit->CreditsRequest->unBindModel(["belongsTo" => ["CreditsLine"]]);
			$creditRequest = $this->Credit->CreditsRequest->findById($this->decrypt($requestId));
			$creditInfo = $this->Credit->findById($creditRequest["CreditsRequest"]["credit_id"]);

			$quotes = $this->Credit->CreditsPlan->getCuotesInformation($creditRequest["CreditsRequest"]["credit_id"]);

			if ($type == "pdf") {
				$this->autoRender = false;
				$options = array(
					'template' => 'plans_payments',
					'ruta' => APP . 'webroot' . DS . 'files' . DS . 'plans_payment' . DS . md5($requestId) . ".pdf",
					'vars' => compact("creditRequest", "creditInfo", "quotes"),
				);
				$this->generatePdf($options);

				$urlPdf = Router::url("/", true) . 'files' . DS . 'plans_payment' . DS . md5($requestId) . ".pdf";

				if (!is_null($return)) {
					return $urlPdf;
				} else {
					$this->redirect($urlPdf);
				}
			} elseif ($type == "view") {
				if (!is_null($return)) {
					$this->autoRender = false;
					return json_encode(compact("creditRequest", "creditInfo", "quotes"));
				}else{
					$this->set(compact("creditRequest", "creditInfo", "quotes"));

				}
			}

		}

		public function payment_view($creditId)
		{
			$this->Credit->CreditsPlan->setCuotasValue();
			//$creditId,$pago
			$recibido =  explode(',',$creditId);
			$creditId = $recibido[0];

			$pago     =  $recibido[1];

			$creditId = $this->decrypt($creditId);
			$cuotes = $this->Session->read("CUOTESP");

			$quotaID = $this->Session->read("CUOTAID");

			$this->loadModel("Payment");
			$this->Payment->Receipt->setValorReal();

			$cuotaData = $this->Payment->findById($quotaID);
			$receipt   = $this->Payment->Receipt->findById($cuotaData["Payment"]["receipt_id"]);
			$credit = $this->Credit->findById($creditId);
			$shopCommerce = $this->Credit->CreditsRequest->ShopCommerce->findById($credit["CreditsRequest"]["shop_commerce_id"]);

			$totalCredit = $this->Payment->CreditsPlan->getCreditDeuda($credit["Credit"]["id"],null,null,true);

			$numbers = [];
			$cuotasId = [];

			foreach ($cuotes as $key => $value) {
				$numbers[] = $key;
			}

			$quotesData = $this->Payment->CreditsPlan->findAllByNumberAndCreditId($numbers, $credit["Credit"]["id"]);

			if (!empty($quotesData)) {
				foreach ($quotesData as $key => $value) {
					$cuotasId[$value["CreditsPlan"]["number"]] = $value["CreditsPlan"]["id"];
				}
			}

			$saldoCliente = $this->totalQuote(true, $credit["Credit"]["customer_id"]);

			$this->set("credit", $credit);
			$this->set("quotes", $cuotes);
			$this->set("totalCredit", $totalCredit);
			$this->set("totalpago", $pago);
			$this->set("cuotasId", $cuotasId);
			$this->set("saldoCliente", $saldoCliente);
			$this->set("shop_commerce", $shopCommerce);
			$this->set("cuotaData", $cuotaData);
			$this->set("receipt", $receipt);

		}


		public function editCreditValue() {
			$this->autoRender = false;
			$creditId=$this->request->data['credit_id'];
			$valorCredito=$this->request->data['value_credit'];
			$valorAnteriorCredito=$this->request->data['previous_value'];
			$credit=$this->Credit->findById($creditId);
			$customerId=$credit['Credit']['customer_id'];
			$creditRequestId=$credit['Credit']['credits_request_id'];

			$dateStartCredit= date("Y-m-d", strtotime($credit['Credit']['created']));
			$dateStartRequest= date("Y-m-d", strtotime($this->request->data['date_start']));

			$this->loadModel("Credits");
			$credit['Credit']['credit_total_od'] = $credit['Credit']['value_request'];
			$credit['Credit']['credit_quote_old'] = $credit['Credit']['quota_value'];
			$credit['Credit']['credit_start_old'] = $credit['Credit']['created'];
			$credit['Credit']['credit_deadline_old'] = $credit['Credit']['deadline'];

			if (!$this->Credit->save($credit)) {
				$this->Session->setFlash('Error al completar la solicitud.', 'default', array('class' => 'error-message'));
				$this->redirect($this->referer());
			}

			$frecuenciaPago=$credit['Credit']['type']== 2 ? 2 : 1;

			//alimentamos valores anteriores
			if($dateStartCredit !==$dateStartRequest || $credit['Credit']['value_request'] !==$this->request->data['value_credit']) {
				//se ajustan cuotas para poder operar
				$quotaTotal=$credit['Credit']['number_fee'];
				//el sistema divide por la frecuencia entonces como 45 y 60 dias es una cuota lo igualamos a uno
				$cuoteValuesData = $this->calculate_qoute($credit['Credit']['number_fee'], $this->request->data['value_credit'],$frecuenciaPago);
				$totalCuotes 		= $quotaTotal / $frecuenciaPago;
				if ($quotaTotal == 1){
					$days 	= $totalCuotes * 30;

				} elseif ($quotaTotal == 3){
					$days 	= 45;

				} elseif ($quotaTotal == 4){
					$days 	= 60;

				} else{
					$days 	= $totalCuotes*15;
				}

				$credit['Credit']['value_request'] = $this->request->data['value_credit'];
				$credit['Credit']['deadline'] = date("Y-m-d", strtotime($this->request->data['date_start'] . "+" . $days . " days"));
				$credit['Credit']['quota_value'] = $cuoteValuesData["cuote"];
				$credit['Credit']['value_pending'] = $this->request->data['value_credit'];
				$credit['Credit']['created'] = date('Y-m-d H:i:s', strtotime($this->request->data['date_start']));
				if (!$this->Credit->save($credit['Credit'])) {
					$this->Session->setFlash('Error al actualizar la información del crédito', 'default', array('class' => 'error-message'));
					$this->redirect($this->referer());
				}


				//actualizamos las fechas de los planes de pago
				$creditsPlans = $credit['CreditsPlan'];
				$priceValue = $this->request->data['value_credit'];
				$totalCapitalDeuda = $priceValue;
				$j = 0;
				$ultimoCap = 0;

				foreach ($creditsPlans as $key => $creditPlanRow) {
					$i=$key+1;
					$intereses = round($priceValue*($cuoteValuesData["intRate"]/ $frecuenciaPago));
					$interesesOtro = round($priceValue*($cuoteValuesData["intOther"]/ $frecuenciaPago));
					$capitalC = $cuoteValuesData["cuote"] - $intereses - $interesesOtro;
					$priceValue -= $capitalC;
					$totalCapitalDeuda -= $capitalC;

					if ($credit['Credit']['type'] == 1){
						$fecha = date("Y-m-d",strtotime($this->request->data['date_start']."$i month"));
						$fechaIni = date("Y-m-d",strtotime($fecha."-1 month"));

					} elseif ($credit['Credit']['type'] == 3){
						$days = 45;
						$fecha = date("Y-m-d",strtotime($this->request->data['date_start']."+$days days"));
						$fechaIni = date("Y-m-d",strtotime($fecha."-1 days"));

					} elseif ($credit['Credit']['type'] == 4){
						$days = 60;
						$fecha = date("Y-m-d",strtotime($this->request->data['date_start']."+$days days"));
						$fechaIni = date("Y-m-d",strtotime($fecha."-1 days"));

					} else{
						$days = $i*15;
						$fecha = date("Y-m-d",strtotime($this->request->data['date_start']."+$days days"));
						$fechaIni = date("Y-m-d",strtotime($fecha."-15 days"));
					}


					$capitalValue = round($totalCapitalDeuda) < 0 || round($totalCapitalDeuda) < 2000 ?  ($ultimoCap==0?floatval($capitalC) : $ultimoCap) : floatval($capitalC);
					$creditPlanRow['capital_value'] = $capitalValue;
					$creditPlanRow['interest_value'] = floatval($intereses);
					$creditPlanRow['others_value'] = floatval($interesesOtro);
					$creditPlanRow['deadline'] = $fecha;
					$creditPlanRow['dateini'] = $fechaIni;
					$creditPlanRow['value_pending'] = round($totalCapitalDeuda) < 0 || round($totalCapitalDeuda) < 2000 ? 0 : floatval(round($totalCapitalDeuda));
					$creditPlanRow['state'] = 0;
					$creditPlanRow['number'] = $i;
					$creditPlanRow['capital_value_proy'] = $capitalValue;
					$this->loadModel('CreditsPlan');
					if (!$this->CreditsPlan->save($creditPlanRow)) {
						$this->Session->setFlash('Error al completar al actualizar las cuotas del crédito.', 'default', array('class' => 'alert alert-danger'));
						$this->redirect(array('controller' => 'credits', 'action' => 'index'));
						break;
					}
					$ultimoCap = round($totalCapitalDeuda);
				}



				$credit['CreditsRequest']['request_value_api'] = $this->request->data['value_credit'];
				$credit['CreditsRequest']['value_disbursed'] = $this->request->data['value_credit'];
				$credit["CreditsRequest"]["date_disbursed"]= date('Y-m-d H:i:s', strtotime($this->request->data['date_start']));
				$this->loadModel("CreditsRequest");
				if (!$this->CreditsRequest->save($credit['CreditsRequest'])) {
					$this->Session->setFlash('Error al actualizar el crédito.', 'flash_error');
					$this->redirect($this->referer());
				}

				// Disbursement
				$this->loadModel("Disbursement");
				$disbursementSearch = $this->Disbursement->find('first', array('conditions' => array('Disbursement.credit_id' => $credit['Credit']['id'])));

				if ($disbursementSearch) {
					$disbursement['value'] = $this->request->data['value_credit'];
					$this->Disbursement->save($disbursementSearch);
				}


				$this->loadModel("CreditAudit");
				$data= [
					'user_id' => AuthComponent::user("id"),
					'credit_id' =>$creditId,
					'description' =>$this->request->data['motivo_edicion'],
					'previous_value' =>$valorAnteriorCredito,
					'new_value' =>$valorCredito,
					'action' =>'El usuario '.AuthComponent::user("name").' editó el credito id '.$creditId.' valor anterior: '.$valorAnteriorCredito .' a '.$valorCredito,
				];

				$this->CreditAudit->Create();
				$auditCredit = $data;

				if(!$this->CreditAudit->save($auditCredit)){
					$this->Session->setFlash(__('Error al crear la auditoria del cambio'), 'flash_error');
				}

			}

			$this->Session->setFlash('Crédito actualizado correctamente.', 'flash_success');
			$this->redirect($this->referer());
		}



		public function editCreditDate() {
			$this->autoRender = false;
			$creditId=$this->request->data['credit_id'];
			$creditPlanId=$this->request->data['credit_plan_id'];
			$nuevoValor=$this->request->data['new_value'];
			$valorAnterior=$this->request->data['previous_value'];
			$this->loadModel("Credit");
			$this->Credit->query("update credits set deadline= '". $nuevoValor . "'  where id = " .$creditId);

			$this->loadModel("CreditsPlan");
			$this->Credit->query("update credits_plans set deadline= '". $nuevoValor . "'  where id = " .$creditPlanId);

			$this->loadModel("CreditAudit");
			$data= [
				'user_id' => AuthComponent::user("id"),
				'credit_id' =>$creditId,
				'description' =>$this->request->data['motivo_edicion'],
				'previous_value' =>$valorAnterior,
				'new_value' =>$nuevoValor,
				'action' =>'El usuario '.AuthComponent::user("name").' editó el credito id '.$creditId.' credit plan id '.$creditPlanId.' valor anterior: '.$valorAnterior .' a '.$nuevoValor,
			];

			$this->CreditAudit->Create();
			$auditCredit = $data;

			if(!$this->CreditAudit->save($auditCredit)){
				$this->Session->setFlash(__('Error al crear la auditoria del cambio'), 'flash_error');
			} else {
				$this->Session->setFlash(__('Registro actualizado correctamente'), 'flash_success');
			}

			//redireccionar
			// $this->redirect(["controller" => "credits_requests", "action" => "index_lista"]);

			$this->redirect($this->referer());

			// $this->redirect(["controller" => "credits", "action" => "payment_detail"]);

		}

		public function actualizarCupoCliente($customerId) {
			$this->autoRender = false;
			$valueCupo= $this->request->data['valor_cupo'];
			$this->loadModel('CreditLimit');
			$creditLimit = $this->CreditLimit->find('all', array(
				'conditions' => array(
					'CreditLimit.customer_id' => $customerId,
					'CreditLimit.reason' => 'Aprobación de cupo'
				),
				"recursive" => -1
			));

			if ($creditLimit) {
				$totalValue = $valueCupo;
				$count = count($creditLimit);
				$value = round($valueCupo/$count, 2);
				foreach ($creditLimit as $row) {
					$credit = $row['CreditLimit'];
					$credit['value'] = $value;
					$credit['total_value'] = $totalValue;
					$this->CreditLimit->save($credit);
				}
				return 1;
			}
			return 0;
		}

		public function deleteCredit($creditRequestId) {

			$this->loadModel('CreditsRequest');
			$creditRequest = $this->CreditsRequest->findById($creditRequestId);
			$creditId=$creditRequest['CreditsRequest']['credit_id'];
			$this->loadModel('Credit');
			$credit = $this->Credit->findById($creditId);

			if(!empty($credit)) {
				unset($credit['CreditAudit']);

				$code_pay = $credit['Credit']['code_pay'];
				$data = array(
					'CreditDelete' => array(
						'info_credit' => json_encode($credit),
						'user_id' => $this->Auth->user('id'),
						'code_pay' => $code_pay
					)
				);
				//guardar auditoria de credito eliminado
				$this->loadModel('CreditDelete');
				$this->CreditDelete->save($data);

				//eliminamos el pago
				$this->loadModel('Disbursement');
				$this->Disbursement->deleteAll(array('credit_id' => $creditId));

				//eliminamos las cuotas
				$this->loadModel('CreditsPlan');
				$this->CreditsPlan->deleteAll(array('credit_id' => $creditId));

				//eliminamos la solicitud de credito
				$this->loadModel('CreditsRequest');
				$this->CreditsRequest->delete($creditRequestId);

				//eliminamos el credito
				$this->loadModel('Credit');
				$this->Credit->delete($creditId);
			} else {
				$this->loadModel('CreditsRequest');
				$this->CreditsRequest->delete($creditRequestId);
			}

			$this->Session->setFlash(__('Crédito eliminado correctamente'), 'flash_success');
			$this->redirect($this->referer());

		}
	}
