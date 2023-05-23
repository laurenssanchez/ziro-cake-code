<?php
App::uses('AppModel', 'Model');
date_default_timezone_set('America/Bogota');


class CreditsPlan extends AppModel {

	public $validate = array(
		'credit_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'credit_id'),
		),
		'capital_value' => array('numeric' => array('rule' => array('numeric'),'message' => 'capital_value'),
		),
		'interest_value' => array('numeric' => array('rule' => array('numeric'),'message' => 'interest_value'),
		),
		'others_value' => array('numeric' => array('rule' => array('numeric'),'message' => 'others_value'),
		),
		'deadline' => array('date' => array('rule' => array('date'),'message' => 'deadline'),
		),
		'value_pending' => array('numeric' => array('rule' => array('numeric'),'message' => 'value_pending'),
		),
		'capital_value_proy' => array('numeric' => array('rule' => array('numeric'),'message' => 'capital_value_proy'),
		),
	);


	public $belongsTo = array(
		'Credit' => array('className' => 'Credit','foreignKey' => 'credit_id',)
	);

	public $hasMany = array(
		'Receipt' => array('className' => 'Receipt','foreignKey' => 'id',)
	);

	public function setCobrosUnions(){
		$this->hasMany = [
			'Commitment' => array('className' => 'Commitment','foreignKey' => 'credits_plan_id','dependent' => false,),
			'Note' 		 => array('className' => 'Note','foreignKey' => 'credits_plan_id','dependent' => false,),
			'History' 	 => array('className' => 'History','foreignKey' => 'credits_plan_id','dependent' => false,),
		];
	}

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(CreditsPlan.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

	public function changeJuridico($quoteId){


		$sql = "SELECT CreditsPlan.id,CreditsPlan.deadline,Credit.customer_id,CreditsPlan.date_debt,CreditsPlan.credit_id,User.id,
		CASE WHEN CreditsPlan.date_payment IS NULL THEN
		DATEDIFF (CURDATE(), CreditsPlan.deadline)
		ELSE DATEDIFF (CURDATE(), CreditsPlan.date_payment) END AS dias
		FROM credits_plans as CreditsPlan
		INNER JOIN credits as Credit on Credit.id = CreditsPlan.credit_id
		INNER JOIN customers as Customer on Customer.id = Credit.customer_id
		LEFT JOIN users as User on User.customer_id = Customer.id
		where CreditsPlan.id = ".$quoteId;

		$response = $this->query($sql);

		if(!empty($response)){
			foreach ($response as $key => $value) {
				$dataCredit = [ "Credit" => [ "id" => $value["CreditsPlan"]["credit_id"], "juridico" => 1 ] ];
				$this->Credit->save($dataCredit);
				$this->Credit->updateAll(
					["Credit.juridico" => 1,"date_juridico" => "'".date("Y-m-d")."'" ],
					["Credit.customer_id" => $value["Credit"]["customer_id"]]
				);
				if (!empty($value["User"]["id"])) {					
					$dataUser  = [ "User" => [ "id" => $value["User"]["id"], "state" => 0 ] ];
					$this->Credit->Customer->User->save($dataUser);
				}
			}
		}
	}


	public function getQuotesCobranzas($quoteId = null,$dayIni = null, $dayEnd = null, $customer = null, $start =0, $limit=50){

		$sql = "SELECT CreditsPlan.id,CreditsPlan.deadline,CreditsPlan.date_debt,CreditsPlan.credit_id,User.name,
		CASE WHEN CreditsPlan.date_debt IS NULL THEN
		DATEDIFF (CURDATE(), CreditsPlan.deadline)
		ELSE DATEDIFF (CURDATE(), CreditsPlan.date_debt) END AS dias
		FROM credits_plans as CreditsPlan
		INNER JOIN credits as Credit on Credit.id = CreditsPlan.credit_id
		INNER JOIN customers as Customer on Customer.id = Credit.customer_id
		LEFT  JOIN (
         SELECT min(deadline) as deadlineD,credit_id
         FROM `credits_plans`
         WHERE state=0
           GROUP by credit_id

        ) maximo on maximo.credit_id = CreditsPlan.credit_id
		LEFT JOIN users as User on User.id = Credit.user_id
		where  Credit.credits_request_id != 0 AND CreditsPlan.state < 1 AND Credit.juridico = 0 AND DATEDIFF(CURDATE(),CreditsPlan.deadline ) > 0 and CreditsPlan.deadline = maximo.deadlineD";

		if(is_null($dayIni)){
			$sql .= " AND
			(
				CASE WHEN CreditsPlan.date_debt IS NULL THEN
				DATEDIFF (CURDATE(), CreditsPlan.deadline)
				ELSE DATEDIFF (CURDATE(), CreditsPlan.date_debt) END
			) >= 1";
		}else{
			$sql .= " AND
			(
				CASE WHEN CreditsPlan.date_debt IS NULL THEN
				DATEDIFF (CURDATE(), CreditsPlan.deadline)
				ELSE DATEDIFF (CURDATE(), CreditsPlan.date_debt) END
			) >= ${dayIni} AND
			(
				CASE WHEN CreditsPlan.date_debt IS NULL THEN
				DATEDIFF (CURDATE(), CreditsPlan.deadline)
				ELSE DATEDIFF (CURDATE(), CreditsPlan.date_debt) END
			) <= ${dayEnd} ";
		}
		if(!is_null($quoteId)){
			$sql.=" AND CreditsPlan.id = ".$quoteId;
		}

		if(!is_null($customer) && !empty($customer) ){
			$sql.=" AND Customer.identification = ".$customer;
		}

		if(is_null($quoteId)){
			$sql .= " ORDER BY CASE WHEN CreditsPlan.date_debt IS NULL THEN DATEDIFF (CURDATE(), CreditsPlan.deadline) ELSE DATEDIFF(CURDATE(), CreditsPlan.date_debt) END
    		 	LIMIT ${start} , ${limit} ";
		}


		 $response = $this->query($sql);

		foreach ($response as $key => $value) {
			$datos = $this->getCuotesInformation($value["CreditsPlan"]["credit_id"],$value["CreditsPlan"]["id"], null, 1);
			$this->Credit->CreditsRequest->recursive = 1;
			$datos["Customer"] = $this->Credit->CreditsRequest->Customer->findById($datos["Credit"]["customer_id"])["Customer"];
			$response[$key] = array_merge($response[$key],$datos);
		}

		if(!is_null($quoteId)){
			$response = $response[$key];
			$response["Customer"]["phone"] = $this->Credit->CreditsRequest->Customer->CustomersPhone->field("phone_number",["customer_id"=>$response["Customer"]["id"]]);
		}

		return $response;
	}

	public function getQuotesCobranzasCount($quoteId = null,$dayIni = null, $dayEnd = null, $customer = null){

		$sql = "SELECT Count(CreditsPlan.id) as cantidad
		FROM credits_plans as CreditsPlan
		INNER JOIN credits as Credit on Credit.id = CreditsPlan.credit_id
		INNER JOIN customers as Customer on Customer.id = Credit.customer_id
		LEFT JOIN users as User on User.id = Credit.user_id
		where Credit.credits_request_id != 0 AND CreditsPlan.state < 1 AND Credit.juridico = 0 AND DATEDIFF(CURDATE(),CreditsPlan.deadline ) > 0";

		if(is_null($dayIni)){
			$sql .= " AND
			(
				CASE WHEN CreditsPlan.date_debt IS NULL THEN
				DATEDIFF (CURDATE(), CreditsPlan.deadline)
				ELSE DATEDIFF (CURDATE(), CreditsPlan.date_debt) END
			) >= 1";
		}else{
			$sql .= " AND
			(
				CASE WHEN CreditsPlan.date_debt IS NULL THEN
				DATEDIFF (CURDATE(), CreditsPlan.deadline)
				ELSE DATEDIFF (CURDATE(), CreditsPlan.date_debt) END
			) >= ${dayIni} AND
			(
				CASE WHEN CreditsPlan.date_debt IS NULL THEN
				DATEDIFF (CURDATE(), CreditsPlan.deadline)
				ELSE DATEDIFF (CURDATE(), CreditsPlan.date_debt) END
			) <= ${dayEnd} ";
		}
		if(!is_null($quoteId)){
			$sql.=" AND CreditsPlan.id = ".$quoteId;
		}

		if(!is_null($customer) && !empty($customer) ){
			$sql.=" AND Customer.identification = ".$customer;
		}


		$response = $this->query($sql);

		foreach ($response as $key => $value) {
			$response = $value[0]["cantidad"];
		}

		return $response;
	}

	public function getQuotesCobranzasTotal($quoteId = null){

		$sql = "SELECT CreditsPlan.id,CreditsPlan.deadline,CreditsPlan.date_debt,CreditsPlan.credit_id,
		CASE WHEN CreditsPlan.date_payment IS NULL THEN
		DATEDIFF (CURDATE(), CreditsPlan.deadline)
		ELSE DATEDIFF (CURDATE(), CreditsPlan.date_payment) END AS dias
		FROM credits_plans as CreditsPlan
		INNER JOIN credits as Credit on Credit.id = CreditsPlan.credit_id
		INNER JOIN customers as Customer on Customer.id = Credit.customer_id
		where Credit.credits_request_id != 0 && CreditsPlan.state < 1 AND Credit.juridico = 0 AND DATEDIFF(CURDATE(),CreditsPlan.deadline ) > 0 ";

		$sql .= " AND
		(
			CASE WHEN CreditsPlan.date_payment IS NULL THEN
			DATEDIFF (CURDATE(), CreditsPlan.deadline)
			ELSE DATEDIFF (CURDATE(), CreditsPlan.date_payment) END
		) >= 1 order by dias DESC";


		$response = $this->query($sql);

		foreach ($response as $key => $value) {
			$datos = $this->getCuotesInformation($value["CreditsPlan"]["credit_id"],$value["CreditsPlan"]["id"], null, 1);
			$response[$key] = array_merge($response[$key],$datos);
		}

		return $response;
	}


	/*
	public function getQuotesCobranzas($quoteId = null,$dayIni = null, $dayEnd = null, $customer = null){

		$sql = "SELECT CreditsPlan.id,CreditsPlan.deadline,CreditsPlan.date_debt,CreditsPlan.credit_id,User.name,
		CASE WHEN CreditsPlan.date_payment IS NULL THEN
		DATEDIFF (CURDATE(), CreditsPlan.deadline)
		ELSE DATEDIFF (CURDATE(), CreditsPlan.date_payment)  END  AS dias
		FROM credits_plans as CreditsPlan
		INNER JOIN credits as Credit on Credit.id = CreditsPlan.credit_id
		INNER JOIN customers as Customer on Customer.id = Credit.customer_id
        LEFT  JOIN (
         SELECT min(deadline) as deadlineD,credit_id
         FROM `credits_plans`
         WHERE state=0
           GROUP by credit_id

        ) maximo on maximo.credit_id = CreditsPlan.credit_id
		LEFT JOIN users as User on User.id = Credit.user_id

		where  Credit.credits_request_id != 0 AND CreditsPlan.state < 1 AND Credit.juridico = 0 AND DATEDIFF(CURDATE(),CreditsPlan.deadline ) > 0 and CreditsPlan.deadline = maximo.deadlineD";

		if(is_null($dayIni)){
			$sql .= " AND
			(
				CASE WHEN CreditsPlan.date_payment IS NULL THEN
				DATEDIFF (CURDATE(), CreditsPlan.deadline)
				ELSE DATEDIFF (CURDATE(), CreditsPlan.date_payment) END
			) >= 1";
		}else{
			$sql .= " AND
			(
				CASE WHEN CreditsPlan.date_payment IS NULL THEN
				DATEDIFF (CURDATE(), CreditsPlan.deadline)
				ELSE DATEDIFF (CURDATE(), CreditsPlan.date_payment) END
			) >= ${dayIni} AND
			(
				CASE WHEN CreditsPlan.date_payment IS NULL THEN
				DATEDIFF (CURDATE(), CreditsPlan.deadline)
				ELSE DATEDIFF (CURDATE(), CreditsPlan.date_payment) END
			) <= ${dayEnd} ";
		}
		if(!is_null($quoteId)){
			$sql.=" AND CreditsPlan.id = ".$quoteId;
		}

		if(!is_null($customer) && !empty($customer) ){
			$sql.=" AND Customer.identification = ".$customer;
		}

		$response = $this->query($sql);

		foreach ($response as $key => $value) {
			$datos = $this->getCuotesInformation($value["CreditsPlan"]["credit_id"],$value["CreditsPlan"]["id"], null, 1);
			$this->Credit->CreditsRequest->recursive = 1;
			$datos["Customer"] = $this->Credit->CreditsRequest->Customer->findById($datos["Credit"]["customer_id"])["Customer"];
			$response[$key] = array_merge($response[$key],$datos);


		if(!is_null($quoteId)){
			$response = $response[$key];
			$response["Customer"]["phone"] = $this->Credit->CreditsRequest->Customer->CustomersPhone->field("phone_number",["customer_id"=>$response["Customer"]["id"]]);
		}
	}
		return $response;
	}

	public function getQuotesCobranzasCount($quoteId = null,$dayIni = null, $dayEnd = null, $customer = null){

		$sql = "SELECT Count(CreditsPlan.id) as cantidad
		FROM credits_plans as CreditsPlan
		INNER JOIN credits as Credit on Credit.id = CreditsPlan.credit_id
		INNER JOIN customers as Customer on Customer.id = Credit.customer_id
		LEFT JOIN users as User on User.id = Credit.user_id
		where Credit.credits_request_id != 0 AND CreditsPlan.state < 1 AND Credit.juridico = 0 AND DATEDIFF(CURDATE(),CreditsPlan.deadline ) > 0";

		if(is_null($dayIni)){
			$sql .= " AND
			(
				CASE WHEN CreditsPlan.date_payment IS NULL THEN
				DATEDIFF (CURDATE(), CreditsPlan.deadline)
				ELSE DATEDIFF (CURDATE(), CreditsPlan.date_payment) END
			) >= 1";
		}else{
			$sql .= " AND
			(
				CASE WHEN CreditsPlan.date_payment IS NULL THEN
				DATEDIFF (CURDATE(), CreditsPlan.deadline)
				ELSE DATEDIFF (CURDATE(), CreditsPlan.date_payment) END
			) >= ${dayIni} AND
			(
				CASE WHEN CreditsPlan.date_payment IS NULL THEN
				DATEDIFF (CURDATE(), CreditsPlan.deadline)
				ELSE DATEDIFF (CURDATE(), CreditsPlan.date_payment) END
			) <= ${dayEnd} ";
		}

		if(!is_null($quoteId)){
			$sql.=" AND CreditsPlan.id = ".$quoteId;
		}

		if(!is_null($customer) && !empty($customer) ){
			$sql.=" AND Customer.identification = ".$customer;
		}


		$response = $this->query($sql);

		foreach ($response as $key => $value) {
			$response = $value[0]["cantidad"];
		}

		return $response;
	}

	public function getQuotesCobranzasTotal($quoteId = null){

		$sql = "SELECT CreditsPlan.id,CreditsPlan.deadline,CreditsPlan.date_debt,CreditsPlan.credit_id,
		CASE WHEN CreditsPlan.date_payment IS NULL THEN
		DATEDIFF (CURDATE(), CreditsPlan.deadline)
		ELSE DATEDIFF (CURDATE(), CreditsPlan.date_payment) END AS dias
		FROM credits_plans as CreditsPlan
		INNER JOIN credits as Credit on Credit.id = CreditsPlan.credit_id
		INNER JOIN customers as Customer on Customer.id = Credit.customer_id
		where Credit.credits_request_id != 0 && CreditsPlan.state < 1 AND Credit.juridico = 0 AND DATEDIFF(CURDATE(),CreditsPlan.deadline ) > 0 ";

		$sql .= " AND
		(
			CASE WHEN CreditsPlan.date_payment IS NULL THEN
			DATEDIFF (CURDATE(), CreditsPlan.deadline)
			ELSE DATEDIFF (CURDATE(), CreditsPlan.date_payment) END
		) >= 1 order by dias DESC";


		$response = $this->query($sql);

		foreach ($response as $key => $value) {
			$datos = $this->getCuotesInformation($value["CreditsPlan"]["credit_id"],$value["CreditsPlan"]["id"], null, 1);
			$response[$key] = array_merge($response[$key],$datos);
		}

		return $response;
	}
	*/

	public function validateJuridicoQuotes(){
		$sql = "SELECT CreditsPlan.id,CreditsPlan.deadline,Credit.customer_id,CreditsPlan.date_debt,CreditsPlan.credit_id,User.id,
		CASE WHEN CreditsPlan.date_payment IS NULL THEN
		DATEDIFF (CURDATE(), CreditsPlan.deadline)
		ELSE DATEDIFF (CURDATE(), CreditsPlan.date_payment) END AS dias
		FROM credits_plans as CreditsPlan
		INNER JOIN credits as Credit on Credit.id = CreditsPlan.credit_id
		INNER JOIN customers as Customer on Customer.id = Credit.customer_id
		INNER JOIN users as User on User.customer_id = Customer.id
		where CreditsPlan.state < 1 AND Credit.juridico = 0 AND DATEDIFF(CURDATE(),CreditsPlan.deadline ) > 0";

			$sql .= " AND
			(
				CASE WHEN CreditsPlan.date_payment IS NULL THEN
				DATEDIFF (CURDATE(), CreditsPlan.deadline)
				ELSE DATEDIFF (CURDATE(), CreditsPlan.date_payment) END
			) >= 120000000";

		$response = $this->query($sql);

		if(!empty($response)){
			foreach ($response as $key => $value) {
				$dataCredit = [ "Credit" => [ "id" => $value["CreditsPlan"]["credit_id"], "juridico" => 1 ] ];
				$this->Credit->save($dataCredit);
				$this->Credit->updateAll(
					["Credit.juridico" => 0],
					["Credit.date_juridico" => now()],
					["Credit.customer_id" => $value["Credit"]["customer_id"]]
				);
				$dataUser  = [ "User" => [ "id" => $value["User"]["id"], "state" => 0 ] ];
				$this->Credit->Customer->User->save($dataUser);
			}
		}
	}


	public function revertJuridico($CreditId,$customer_id){


        //$CreditId = 58;
		$this->Credit->updateAll(
			["Credit.juridico" => 0],
			["Credit.date_juridico " => null],
			["Credit.id" => $CreditId]
		);


		$this->query("update users set state=1 where customer_id=" .$customer_id);



	}

	public function getQuotesJuridico($quoteId = null, $customer = null){

		$sql = "SELECT CreditsPlan.id,CreditsPlan.deadline,CreditsPlan.date_debt,CreditsPlan.credit_id,
		CASE WHEN CreditsPlan.date_payment IS NULL THEN
		DATEDIFF (CURDATE(), CreditsPlan.deadline)
		ELSE DATEDIFF (CURDATE(), CreditsPlan.date_payment) END AS dias
		FROM credits_plans as CreditsPlan
		INNER JOIN credits as Credit on Credit.id = CreditsPlan.credit_id
		INNER JOIN customers as Customer on Customer.id = Credit.customer_id
		where CreditsPlan.state < 1";

		$sql .= " AND Credit.juridico = 1";

		if(!is_null($quoteId)){
			$sql.=" AND CreditsPlan.id = ".$quoteId;
		}

		if(!is_null($customer) && !empty($customer) ){
			$sql.=" AND Customer.identification = ".$customer;
		}

		$response = $this->query($sql);


		//json_encode($response);
		foreach ($response as $key => $value) {
			$datos = $this->getCuotesInformation($value["CreditsPlan"]["credit_id"],$value["CreditsPlan"]["id"], null, 1);
			$this->Credit->CreditsRequest->recursive = 1;
			$datos["Customer"] = $this->Credit->CreditsRequest->Customer->findById($datos["Credit"]["customer_id"])["Customer"];
			$response[$key] = array_merge($response[$key],$datos);

		//	echo json_encode($response);
	}

	if(!is_null($quoteId)){
		$response = $response[$key];
		$response["Customer"]["phone"] = $this->Credit->CreditsRequest->Customer->CustomersPhone->field("phone_number",["customer_id"=>$response["Customer"]["id"]]);
	}


		return $response;
	}


	public function getTotalDeudaCredit($credit_id){
		$dataQuotes = $this->getCuotesInformation($credit_id);
		$total 		= 0;

		if (!empty($dataQuotes)) {
			foreach ($dataQuotes as $key => $value) {
				if($value["CreditsPlan"]["state"] == 0){
					$total+= ( $value["CreditsPlan"]["capital_value"] ) ;//+ ( $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"] ) + ( $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"] ) + $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]
				}
			}
		}
		return $total;
	}

	public function getMinValue($credit_id){
		$dataQuotes = $this->getCuotesInformation($credit_id);
		$creditInfo = $this->Credit->findById($credit_id);
		$total 		= 0;
		$swich 		= 0;
		$dateUltPago = null;
		$idLast     = null;
		$lastQuote  = null;
		$DateLast 	= "";
		$firstDate  = "";

		$cuotaacumulada = 0;
        $cuenta = $creditInfo["Credit"]["number_fee"] - 1;
        $totalNoPayment = $this->find("count",["conditions"=>["CreditsPlan.state" => 0, "CreditsPlan.credit_id" => $creditInfo["Credit"]["id"] ]]);
        

		if (!empty($dataQuotes)) {
			foreach ($dataQuotes as $key => $value) {
	            if ($cuenta > 0) {
	                $pagoA = ($value["CreditsPlan"]["state"]);
	                if ($pagoA == 1) {
	                    $cuotaacumulada = $cuotaacumulada + $value["CreditsPlan"]["capital_value"];
	                }
	            }
	            $cuenta--;
	        }
			foreach ($dataQuotes as $key => $value) {
				if ($value["CreditsPlan"]["credit_old"] == 10) {
	                $idLast = $value["CreditsPlan"]["id"];
	                continue;
	            }
	            $capital = $value["CreditsPlan"]["capital_value"] - $value["CreditsPlan"]["capital_payment"];
	            $interes = $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"];
	            $others = $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"];

	            //Calculo Interes corriente
	            if ($firstDate == "") {
	                $firstDate = $creditInfo["CreditsRequest"]["date_disbursed"];
	            } else {
	                $firstDate = $DateLast;

	            }

	            $secondDate = $value["CreditsPlan"]["deadline"];
	            $DateLast = $secondDate;

	            $fecha1 = new DateTime($firstDate);
	            $fecha2 = new DateTime($secondDate);
	            $resultado = $fecha1->diff($fecha2);
	            $days = $resultado->format('%a');

	            if ($swich == 0) {
	                $swich = 1;
	                $days = $days + 1;
	            }

	            if ($days == 31) {
	                $days = 30;
	            }

	            if ($firstDate != $creditInfo["CreditsRequest"]["date_disbursed"]) {

	                $interesesT = (($dateUltPago < $secondDate)) ? ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["interes_rate"]) / 100) / 30) * $days : ((($deudaF * $creditInfo["Credit"]["interes_rate"]) / 100) / 30) * $days;
	                //Fin Interes corriente

	                //otros intereses
	                $interesesOT = (($dateUltPago < $secondDate)) ? ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["others_rate"]) / 100) / 30) * $days : ((($deudaF * $creditInfo["Credit"]["others_rate"]) / 100) / 30) * $days;

	                //capital
	                $CapitalN = $creditInfo["Credit"]["quota_value"] - $interesesOT - $interesesT;

	            } else {

	                $interesesT = ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["interes_rate"]) / 100) / 30) * $days;
	                //Fin Interes corriente

	                //otros intereses
	                $interesesOT =  ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["others_rate"]) / 100) / 30) * $days;

	                //capital
	                $CapitalN = $creditInfo["Credit"]["quota_value"] - $interesesOT - $interesesT;

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

	            $this->updateAll(
	                ["CreditsPlan.capital_value" => (($value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0 and $value["CreditsPlan"]["state"] == 0) ? ROUND($CapitalN) : ROUND($value["CreditsPlan"]["capital_value"]),
	                    "CreditsPlan.interest_value" => (($value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0 and $value["CreditsPlan"]["state"] == 0) ? ROUND($interesesT) : ROUND($value["CreditsPlan"]["interest_value"]),
	                    "others_value" => (($value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0 and $value["CreditsPlan"]["state"] == 0) ? ROUND($interesesOT) : ROUND($value["CreditsPlan"]["others_value"])],
	                ["CreditsPlan.id" => $value["CreditsPlan"]["id"]]
	            );
	            $lastQuote = $value;
			}
			$totalCap = $this->find("first", ["conditions" => ["credit_id" => $value["CreditsPlan"]["credit_id"], "CreditsPlan.credit_old" => null], "fields" => ["SUM(capital_value) as total"]]);

	        if (!empty($totalCap)) {

	            $totalCap = $totalCap["0"]["total"];

	            if ($creditInfo["CreditsRequest"]["value_disbursed"] > $totalCap || !is_null($idLast)) {

	                if (!is_null($idLast) && $totalNoPayment > 1) {
	                    $this->delete($idLast);
	                }

	                $diferenciaQ = $creditInfo["CreditsRequest"]["value_disbursed"] - $totalCap;
	                
	                if (($diferenciaQ >= 1000 && $creditInfo["Credit"]["state"] == 0 && $totalNoPayment > 1) || ($diferenciaQ >= 1000 && $creditInfo["Credit"]["state"] == 0 && is_null($idLast) ) ) {
	                    $this->create();
	                    $lastQuote["CreditsPlan"]["credit_old"] = 10;
	                    $lastQuote["CreditsPlan"]["capital_value"] = $diferenciaQ;
	                    $lastQuote["CreditsPlan"]["capital_payment"] = 0;
	                    $lastQuote["CreditsPlan"]["interest_value"] = 0;
	                    $lastQuote["CreditsPlan"]["interest_payment"] = 0;
	                    $lastQuote["CreditsPlan"]["others_value"] = 0;
	                    $lastQuote["CreditsPlan"]["others_payment"] = 0;
	                    $lastQuote["CreditsPlan"]["others_add"] = 0;
	                    $lastQuote["CreditsPlan"]["interest_add"] = 0;
	                    $lastQuote["CreditsPlan"]["debt_add"] = 0;
	                    $lastQuote["CreditsPlan"]["date_payment"] = null;
	                    $lastQuote["CreditsPlan"]["date_debt"] = null;
	                    $lastQuote["CreditsPlan"]["value_pending"] = $diferenciaQ;
	                    $lastQuote["CreditsPlan"]["capital_value_proy"] = $diferenciaQ;
	                    $lastQuote["CreditsPlan"]["number"]++;

	                    unset($lastQuote["CreditsPlan"]["created"],$lastQuote["CreditsPlan"]["modified"],$lastQuote["CreditsPlan"]["id"],$lastQuote["CreditsPlan"]["state"]);
	                    $lastQuote["CreditsPlan"]["state"] = 0;
	                    $this->save($lastQuote);
	                }

	            } //add

	        }
			$dataQuotes = $this->getCuotesInformation($credit_id);
			foreach ($dataQuotes as $key => $value) {
				if($value["CreditsPlan"]["state"] == 0){
					$total+= ( $value["CreditsPlan"]["capital_value"] - $value["CreditsPlan"]["capital_payment"] ) + ( $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"] ) + ( $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"] ) + $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
					break;
				}
			}
		}
		return $total;
	}

	public function getDataQuotes($quotes,$lastPaymentDate,$debtRate,$credit_id){
		foreach ($quotes as $key => $value) {
			$quotes[$key] = $this->calculateDebtReports($value,$lastPaymentDate,$debtRate,$credit_id);
		}
		return $quotes;
	}

	public function getDataQuotesNewData($quotes,$lastPaymentDate,$debtRate,$credit_id){
		foreach ($quotes as $key => $value) {
			$quotes[$key] = $this->calculateDebtReportsNewData($value,$lastPaymentDate,$debtRate,$credit_id);
		}
		return $quotes;
	}

	/*public function getDataQuotes($quotes,$lastPaymentDate,$debtRate,$credit_id){
		foreach ($quotes as $key => $value) {
			$quotes[$key] = $this->temp($value,$lastPaymentDate,$debtRate,$credit_id);
		//	$this->calculateDebt2($value,$lastPaymentDate,$debtRate,$credit_id);
			//$quotes[$key] = $this->calculateDebtReports($value,$lastPaymentDate,$debtRate,$credit_id);calculateDebt2
		}
		return $quotes;
	}*/




	public function getDataQuotes2($quotes,$lastPaymentDate,$debtRate,$credit_id){
		foreach ($quotes as $key => $value) {
			$quotes[$key] = $this->calculateDebt($value,$lastPaymentDate,$debtRate,$credit_id);
		//	$this->calculateDebt2($value,$lastPaymentDate,$debtRate,$credit_id);
			//$quotes[$key] = $this->calculateDebtReports($value,$lastPaymentDate,$debtRate,$credit_id);calculateDebt2
		}
		return $quotes;
	}

	/**
	 * metodo que nos devuelve la totalidad del credito a fecha.
	 * @param credit_id, state,qouteId
	 */
	public function getCreditDeuda($credit_id,$state = null,$quoteId = null, $totalWeb = null){

		$creditInfo = $this->Credit->find("first",["recursive"=>-1, "conditions"=>["Credit.id" => $credit_id]]);

		$lastNumber  = $creditInfo["Credit"]["number_fee"];
		$penultimate = $lastNumber-1;

		$penultimateQuote 	 = $this->find("first",["recursive"=>-1,"conditions"=>["credit_id"=>$credit_id,"number"=>$penultimate]]);

		$lastQuote 	 = $this->find("first",["recursive"=>-1,"conditions"=>["credit_id"=>$credit_id,"number"=>$lastNumber]]);

		$lastQuoteDebt = $this->find("first",["recursive"=>-1,"conditions"=>["credit_id"=>$credit_id,"credit_old"=> 10 ]]);


		$quoteParam  = 0;

		if (!is_null($totalWeb)) {
			$quoteParam = 1;
		}elseif ( 
			($penultimateQuote["CreditsPlan"]["state"] == 0 && strtotime($penultimateQuote["CreditsPlan"]["deadline"]) <= strtotime(date("Y-m-d"))) 
			|| 
			($penultimateQuote["CreditsPlan"]["state"] == 0 && strtotime($penultimateQuote["CreditsPlan"]["deadline"]) >= strtotime(date("Y-m-d")) && strtotime($penultimateQuote["CreditsPlan"]["dateini"]) <= strtotime(date("Y-m-d")) ) 
		) {
			$quoteParam = 1;
		}elseif (
			($lastQuote["CreditsPlan"]["state"] == 0 && strtotime($lastQuote["CreditsPlan"]["deadline"]) <= strtotime(date("Y-m-d"))) 
			|| 
			($lastQuote["CreditsPlan"]["state"] == 0 && strtotime($lastQuote["CreditsPlan"]["deadline"]) >= strtotime(date("Y-m-d")) && strtotime($lastQuote["CreditsPlan"]["dateini"]) <= strtotime(date("Y-m-d")) )
		) {
			$quoteParam = 1;
		}elseif(!empty($lastQuoteDebt) && ( ($lastQuoteDebt["CreditsPlan"]["state"] == 0 && strtotime($lastQuoteDebt["CreditsPlan"]["deadline"]) <= strtotime(date("Y-m-d"))) 
			|| 
			($lastQuoteDebt["CreditsPlan"]["state"] == 0 && strtotime($lastQuoteDebt["CreditsPlan"]["deadline"]) >= strtotime(date("Y-m-d")) && strtotime($lastQuoteDebt["CreditsPlan"]["dateini"]) <= strtotime(date("Y-m-d")) ) )){
			$quoteParam = 1;
		}

		if ($quoteParam == 1) {
			$total = 0;
			$quotes 	= $this->getCuotesInformation($credit_id);
			foreach ($quotes as $key => $value) {
				if ($value["CreditsPlan"]["state"] == 0) {

					$pay = $this->Credit->query("select sum(value) as PaymentA from payments where credits_plan_id= ' " . $value["CreditsPlan"]["id"] . " ' ");
            		$value["CreditsPlan"]["TotalAbo"] = $pay[0][0]["PaymentA"];

					$capital = $value["CreditsPlan"]["capital_value"]; 
                    $interes = $value["CreditsPlan"]["interest_value"];
                    $others  = $value["CreditsPlan"]["others_value"];
					$TotalAbonado = $value["CreditsPlan"]["TotalAbo"];

					$cuotaNormal = $capital;
					$totalCuota  = ($cuotaNormal+$others+$interes+$value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"]) - $TotalAbonado;

                	if ($totalCuota <= 1000) {
                		$totalCuota = 0;
                	}
                	$total += $totalCuota;

				}
			}

			if ($total < 0 ) {
				$total = 0;
			}
			return $total;
		}

		/**
		 * Fecha del desembolso del credito
		 */
		$Date_Disbursed = $this->Credit->CreditsRequest->field("date_disbursed",["CreditsRequest.Credit_id"=>$credit_id]);

		$Date_Disbursed = date("d-m-Y", strtotime($Date_Disbursed));


        //$totalCap = $this->Credit->CreditsPlan->

        $Date_deadline_quote_pay = $this->Credit->CreditsPlan->find("first", ["conditions" => ["CreditsPlan.credit_id" => $credit_id,"CreditsPlan.state"=>1], "fields" => ["ifnull(max(CreditsPlan.deadline),'nul') as deadline"]]);

		$Date_deadline_quote_pay = $Date_deadline_quote_pay["0"]["deadline"];

		$Date_inital = $Date_deadline_quote_pay=="nul"?$Date_Disbursed:$Date_deadline_quote_pay; // asigno a partir de que fecha se va calcular;

		$Date_inital = new DateTime($Date_inital);

		$Date_now  = new DateTime(date("Y-m-d"));

		/**Bloque calculo de dias */

		$difference = $Date_inital->diff($Date_now);
        $days = $difference->days;
		$days = ($Date_deadline_quote_pay=="nul" && $days < 30)?30:$days; //si el credito no se ha empezado a pagar y los dias no superan los 30 dias,cobrar la totalidad a 30 dias,cobrar


		if ($Date_deadline_quote_pay!="nul"){
			if ($Date_inital > $Date_now){
				$days=0;
			}
		}

		/** Final calculo de dias */
		$Value_pending = $creditInfo["Credit"]["value_pending"];
		$valorAbono    = $this->field("SUM(capital_payment)",["credit_id"=>$credit_id,"state"=>0]);
		$valorPagado   = $this->field("SUM(capital_payment)",["credit_id"=>$credit_id]);
		$valorDesembolso = $creditInfo["Credit"]["value_request"];
		$Value_pending = ROUND($valorDesembolso-$valorPagado);

        /** calculo de intereses  */
        $TypeCredit   = $creditInfo["Credit"]["type"]; 
	    $Interes_rate = $creditInfo["Credit"]["interes_rate"]; 
		$Others_rate  = $creditInfo["Credit"]["others_rate"]; 
		$debtRate 	  = $creditInfo["Credit"]["debt_rate"]; 


		$TotalInteres = ROUND(((($Value_pending * $Interes_rate) / 100) / 30) * $days);
		$TotalOthers  = ROUND(((($Value_pending * $Others_rate) / 100) / 30) * $days);

		$lastPaymentDate = $creditInfo["Credit"]["last_payment_date"];
		$crediType 		 = $TypeCredit;
		$crediState		 = $creditInfo["Credit"]["state"];

		$conditions = ["credit_id" => $credit_id];
		if(!is_null($state)){
			$conditions["state"] = 0;
		}

		$quotes 	= $this->find("all", ["conditions"=>$conditions, "order" => ["number"=>"ASC"] ] );
		$total = 0;
		$totalDB = 0;


		$this->recursive = -1;
		foreach ($quotes as $key => $value) {

			if(!is_null($quoteId) && $value["CreditsPlan"]["id"] == $quoteId && $value["CreditsPlan"]["state"] == 1){
				continue;
			}

			$quotes[$key] = $this->calculateDebt($value,$lastPaymentDate,$debtRate,$credit_id);
			$value 		  = $quotes[$key];
			if($value["CreditsPlan"]["state"] == 0) {
				$totalDB+= $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
			}
		}

		//$totalDB = 5615;
		$totalDebt    = ROUND($totalDB);

		$TotalPay     = ROUND($Value_pending + ($TotalInteres) + $TotalOthers + $totalDebt);

		$total = $TotalPay ;
		if ($total < 0) {
			$total = 0;
		}
		return $total;

	}

	public function getValuependiente($cuotaId,$credit_id){
		return $this->getCreditDeuda($credit_id,null,$cuotaId);
	}

	public function getCuotesInformation3($credit_id,$cuote_id = null,$state = null, $recursive = -1){

		$lastPaymentDate = $this->Credit->field("last_payment_date",["Credit.id"=>$credit_id]);
		$debtRate 		 = $this->Credit->field("debt_rate",["Credit.id"=>$credit_id]);
		$crediType 		 = $this->Credit->field("type",["Credit.id"=>$credit_id]);

		$this->recursive = $recursive;
		if(is_null($cuote_id)){
			$conditions = ["credit_id" => $credit_id];
			if(!is_null($state)){
				$conditions["state"] = 0;
			}
			$quotes 	= $this->find("all", ["conditions"=>$conditions, "order" => ["number"=>"ASC"] ] );

			foreach ($quotes as $key => $value) {
				$quotes[$key] = $this->calculateDebt3($value,$lastPaymentDate,$debtRate,$credit_id);
			}
		}else{
			$typeFind = is_array($cuote_id) ? "all" : "first";;
			$conditions = ["CreditsPlan.credit_id" => $credit_id,"CreditsPlan.id" => $cuote_id];

			if(!is_null($state)){
				$conditions["state"] = 0;
			}
			$quotes 	= $this->find($typeFind, ["conditions"=>$conditions, "order" => ["number"=>"ASC"] ] );

			if(is_array($cuote_id)){
				foreach ($quotes as $key => $value) {
					$quotes[$key] = $this->calculateDebt3($value,$lastPaymentDate,$debtRate,$credit_id);
				}
			}else{
				$quotes = $this->calculateDebt3($quotes,$lastPaymentDate,$debtRate,$credit_id);
			}
		}
		if (isset($quotes[0])) {
			$totalDebt = 0;
			foreach ($quotes as $key => $value) {
				if($value["CreditsPlan"]["debt_value"] > 0 || $value["CreditsPlan"]["debt_honor"] > 0){
					$totalDebt++;
				}
			}
			if($totalDebt == 0){
				$credit["Credit"]["id"] = $credit_id;
				$credit["Credit"]["debt"] = 0;
				$credit["Credit"]["modified"] = date("Y-m-d H:i:s");
				$this->Credit->save($credit);
			}
		}
		return $quotes;

	}
	public function calculateDebt3($quote,$last_payment_date,$debtRate, $credit_id = null){

		$dateReference  =  is_null($quote["CreditsPlan"]["date_debt"]) ? $quote["CreditsPlan"]["deadline"] : $quote["CreditsPlan"]["date_debt"];

		$deadlineTime 	=  strtotime($quote["CreditsPlan"]["deadline"]);
		$nowDateTime 	=  strtotime(date("Y-m-d"));
		$debtValue      =  0;
		$diasMax 		= 0;

		if($nowDateTime > $deadlineTime){
			$deadline 		= new DateTime($dateReference);
			$nowDate 		= new DateTime(date("Y-m-d"));
			$difference 	= $deadline->diff($nowDate);

			$number         = 30;

			$days			= $difference->days;
			$debtRate 		= ( $debtRate/100 )/$number;
			$debtRateDays	= $debtRate*$days;
			$debtValue 		= 0;
			

			if ($days > $diasMax) {
            	$diasMax = $days;
            }

			if($quote["CreditsPlan"]["capital_payment"] == 0){
				$capital = $quote["CreditsPlan"]["capital_value"];
			}elseif($quote["CreditsPlan"]["capital_payment"] < $quote["CreditsPlan"]["capital_value"]){
				$capital = $quote["CreditsPlan"]["capital_value"] - $quote["CreditsPlan"]["capital_payment"];
			}else{
				$capital = 0;
			}

			if($capital != 0){
				$debtValue = $capital * $debtRate * $days;
				$honorarios = $this->getHonorarios($days,$quote);
				$quote["CreditsPlan"]["debt_value"] = round($debtValue);
				$quote["CreditsPlan"]["debt_honor"] = $honorarios;

				if( ($debtValue > 0 || $honorarios > 0) && !is_null($credit_id) ){
						$this->Credit->updateAll(
                            array('Credit.debt' => 1,"Credit.debt_days" => $diasMax),
                            array('Credit.id' => $credit_id)
                        );
				}

			}else{
				$quote["CreditsPlan"]["debt_value"] = 0;
				$quote["CreditsPlan"]["debt_honor"] = 0;
			}
		}else{
			$quote["CreditsPlan"]["debt_value"] = 0;
			$quote["CreditsPlan"]["debt_honor"] = 0;
		}
		return $quote;
	}
	public function getCuotesInformation2($credit_id,$cuote_id = null,$state = null, $recursive = -1){

		$lastPaymentDate = $this->Credit->field("last_payment_date",["Credit.id"=>$credit_id]);
		$debtRate 		 = $this->Credit->field("debt_rate",["Credit.id"=>$credit_id]);
		$crediType 		 = $this->Credit->field("type",["Credit.id"=>$credit_id]);

		$this->recursive = $recursive;

		if(is_null($cuote_id)){

			$conditions = ["credit_id" => $credit_id];
			if(!is_null($state)){
				$conditions["state"] = 0;
			}
			$quotes 	= $this->find("all", ["conditions"=>$conditions, "order" => ["number"=>"ASC"] ] );

			foreach ($quotes as $key => $value) {
				$quotes[$key] = $this->calculateDebt2($value,$lastPaymentDate,$debtRate,$credit_id);
			}



		}else{

			$typeFind = is_array($cuote_id) ? "all" : "first";;
			$conditions = ["CreditsPlan.credit_id" => $credit_id,"CreditsPlan.id" => $cuote_id];

			if(!is_null($state)){
				$conditions["state"] = 0;
			}
			$quotes 	= $this->find($typeFind, ["conditions"=>$conditions, "order" => ["number"=>"ASC"] ] );

			if(is_array($cuote_id)){
				foreach ($quotes as $key => $value) {
					$quotes[$key] = $this->calculateDebt2($value,$lastPaymentDate,$debtRate,$credit_id);
				}
			}else{
				$quotes = $this->calculateDebt2($quotes,$lastPaymentDate,$debtRate,$credit_id);
			}
		}
		if (isset($quotes[0])) {
			$totalDebt = 0;
			foreach ($quotes as $key => $value) {
				if($value["CreditsPlan"]["debt_value"] > 0 || $value["CreditsPlan"]["debt_honor"] > 0){
					$totalDebt++;
				}
			}
			if($totalDebt == 0){
				$credit["Credit"]["id"] = $credit_id;
				$credit["Credit"]["debt"] = 0;
				$credit["Credit"]["modified"] = date("Y-m-d H:i:s");
				$this->Credit->save($credit);
			}
		}

		return $quotes;

	}

	public function getCuotesInformation($credit_id,$cuote_id = null,$state = null, $recursive = -1){

		$lastPaymentDate = $this->Credit->field("last_payment_date",["Credit.id"=>$credit_id]);
		$debtRate 		 = $this->Credit->field("debt_rate",["Credit.id"=>$credit_id]);
		$crediType 		 = $this->Credit->field("type",["Credit.id"=>$credit_id]);

		$this->recursive = $recursive;

		if(is_null($cuote_id)){

			$conditions = ["credit_id" => $credit_id];
			if(!is_null($state)){
				$conditions["state"] = 0;
			}
			$quotes 	= $this->find("all", ["conditions"=>$conditions, "order" => ["number"=>"ASC"] ] );

			foreach ($quotes as $key => $value) {
				$quotes[$key] = $this->calculateDebt($value,$lastPaymentDate,$debtRate,$credit_id);
			}



		}else{

			$typeFind = is_array($cuote_id) ? "all" : "first";;
			$conditions = ["CreditsPlan.credit_id" => $credit_id,"CreditsPlan.id" => $cuote_id];

			if(!is_null($state)){
				$conditions["state"] = 0;
			}
			$quotes 	= $this->find($typeFind, ["conditions"=>$conditions, "order" => ["number"=>"ASC"] ] );

			if(is_array($cuote_id)){
				foreach ($quotes as $key => $value) {
					$quotes[$key] = $this->calculateDebt($value,$lastPaymentDate,$debtRate,$credit_id);
				}
			}else{
				$quotes = $this->calculateDebt($quotes,$lastPaymentDate,$debtRate,$credit_id);
			}
		}
		if (isset($quotes[0])) {
			$totalDebt = 0;
			foreach ($quotes as $key => $value) {
				if($value["CreditsPlan"]["debt_value"] > 0 || $value["CreditsPlan"]["debt_honor"] > 0){
					$totalDebt++;
				}
			}
			if($totalDebt == 0){
				$credit["Credit"]["id"] = $credit_id;
				$credit["Credit"]["debt"] = 0;
				$credit["Credit"]["modified"] = date("Y-m-d H:i:s");
				$this->Credit->save($credit);
			}
		}
		return $quotes;

	}

	public function validateSaldo(){
		$this->Credit->updateAll(
		    array('Credit.debt' => 0),
		    array('Credit.state' => 1)
		);
		$idsCredits = $this->Credit->find("list",["fields" => ["Credit.id","Credit.id"],"conditions" => ["Credit.state" => 0] ]);
		if(!empty($idsCredits)){
			foreach ($idsCredits as $key => $value) {
				$this->getCuotesInformation($value,null,1);
			}
		}

	}


	public function calculateDebt2($quote,$last_payment_date,$debtRate, $credit_id = null){

		//$quote = ["CreditsPlan"=>$quote];
	//	echo Json_encode($quote);


		//$dateReference  =  is_null($quote["CreditsPlan"]["date_debt"]) ? $quote["CreditsPlan"]["deadline"] : $quote["CreditsPlan"]["date_debt"];

		$dateReference  =  $quote["CreditsPlan"]["deadline"] ;

		$deadlineTime 	=  strtotime($quote["CreditsPlan"]["deadline"]);

		$nowDateTime 	=  strtotime(date("Y-m-d"));
		$debtValue      =  0;
		$diasMax 		=  0;
		$datepay        = $quote["CreditsPlan"]["date_payment"];

		$MoraAbo = $this->Credit->query("select sum(value) as PaymentA from payments where credits_plan_id= ' " . $quote["CreditsPlan"]["id"] . " '  AND type IN (4) ");
		$MoraAbo =  $MoraAbo[0][0]["PaymentA"];

		$HonorP = $this->Credit->query("select sum(value) as PaymentA from payments where credits_plan_id= ' " . $quote["CreditsPlan"]["id"] . " '  AND type IN (5) ");
		$HonorP = $HonorP[0][0]["PaymentA"];


		if($nowDateTime > $deadlineTime){


			if ( is_null($datepay)){
				$Calcular = 1;
			}else if( ($datepay<=$deadlineTime)){
                $Calcular = 0;
			}else{
                $Calcular = 1;
			}

			//$Calcular = is_null($datepay)? 1 : ($datepay<=$deadlineTime)? 0 : 1;
			//echo "<br>" . Json_encode( $Calcular);

            if ($Calcular==1) {


                $deadline 		= new DateTime($dateReference);
                $nowDate 		= new DateTime(is_null($datepay)?date("Y-m-d"):$datepay);
                $difference 	= $deadline->diff($nowDate);

                $number         = 30;

                $days			= $difference->days;
                $debtRate 		= ($debtRate/100)/$number;
                $debtRateDays	= $debtRate*$days;
                $debtValue 		= 0;

                try {
                	if ($days > $diasMax) {
                		$diasMax = $days;
                	}
                } catch (Exception $e) {
                	$diasMax = 0;		
                }


                if ($quote["CreditsPlan"]["state"]==0) {
                    if ($quote["CreditsPlan"]["capital_payment"] == 0) {
                        $capital = $quote["CreditsPlan"]["capital_value_proy"];
                    } elseif ($quote["CreditsPlan"]["capital_payment"] < $quote["CreditsPlan"]["capital_value_proy"]) {
                        $capital = $quote["CreditsPlan"]["capital_value_proy"] - $quote["CreditsPlan"]["capital_payment"];
                    } else {
                        $capital =  $quote["CreditsPlan"]["capital_value_proy"]; //0
                    }
                } else {
                    $capital =  $quote["CreditsPlan"]["capital_value_proy"]; //0
                }


                if ($capital != 0) {
                    $debtValue = $capital * $debtRate * $days;
                    $honorarios = $this->getHonorarios($days, $quote);
                    $quote["CreditsPlan"]["debt_value"] = round($debtValue - $MoraAbo) ;
                    $quote["CreditsPlan"]["debt_honor"] = $honorarios - $HonorP ;

                    if (($debtValue > 0 || $honorarios > 0) && !is_null($credit_id)) {
                        $this->Credit->updateAll(
                            array('Credit.debt' => 1,"Credit.debt_days" => $diasMax),
                            array('Credit.id' => $credit_id)
                        );
                    }

                } else {
                    $quote["CreditsPlan"]["debt_value"] = 0;
                    $quote["CreditsPlan"]["debt_honor"] = 0;
                }
            }else{
				$quote["CreditsPlan"]["debt_value"] = 0;
				$quote["CreditsPlan"]["debt_honor"] = 0;
			}

		}else{
			$quote["CreditsPlan"]["debt_value"] = 0;
			$quote["CreditsPlan"]["debt_honor"] = 0;
		}

		return $quote;
	}

	public function calculateDebt($quote,$last_payment_date,$debtRate, $credit_id = null){

		//$dateReference  =  is_null($quote["CreditsPlan"]["date_debt"]) ? $quote["CreditsPlan"]["deadline"] : $quote["CreditsPlan"]["date_debt"];
		//
		$diasMax 		= 0;

		$dateReference  =  $quote["CreditsPlan"]["deadline"] ;

		$deadlineTime 	=  strtotime($quote["CreditsPlan"]["deadline"]);
		$nowDateTime 	=  strtotime(date("Y-m-d"));
		$debtValue      =  0;
		$datepay        = $quote["CreditsPlan"]["date_payment"];
		$fecJuridico    = null;

		$fecJuridico    = $this->query("SELECT date_Juridico   FROM credits WHERE id=" . $credit_id);

		$fecJuridico    =  $fecJuridico[0]["credits"]["date_Juridico"];




		//$MoraAbo = $this->Credit->query("select sum(value) as PaymentA from payments where credits_plan_id= ' " . $quote["CreditsPlan"]["id"] . " '  AND type IN (4) ");
		$MoraAbo = 0;// $MoraAbo[0][0]["PaymentA"];

		//$HonorP = $this->Credit->query("select sum(value) as PaymentA from payments where credits_plan_id= ' " . $quote["CreditsPlan"]["id"] . " '  AND type IN (5) ");
		$HonorP = 0; //$HonorP[0][0]["PaymentA"];


		if($nowDateTime > $deadlineTime){

			$Calcular = 1;//is_null($datepay)?1:($datepay>$deadlineTime)?1:0;

            if ($Calcular==1) { //ultimadd
                $deadline 		= new DateTime($dateReference);
                $nowDate 		= is_null($fecJuridico) ?new DateTime(is_null($datepay)?date("Y-m-d"):$datepay) : new DateTime($fecJuridico);
                $difference 	= $deadline->diff($nowDate);

                $number         = 30;

                $days			= $difference->days;
                $debtRate 		= ($debtRate/100)/$number;
                $debtRateDays	= $debtRate*$days;
                $debtValue 		= 0;

                if ($days > $diasMax) {
                	$diasMax = $days;
                }


                if ($quote["CreditsPlan"]["state"]==0) {
                    if ($quote["CreditsPlan"]["capital_payment"] == 0) {
                        $capital = $quote["CreditsPlan"]["capital_value_proy"];
                    } elseif ($quote["CreditsPlan"]["capital_payment"] < $quote["CreditsPlan"]["capital_value_proy"]) {
                        $capital = $quote["CreditsPlan"]["capital_value_proy"] - $quote["CreditsPlan"]["capital_payment"];
                    } else {
                        $capital =  $quote["CreditsPlan"]["capital_value_proy"]; //0
                    }
                } else {
                    $capital =  $quote["CreditsPlan"]["capital_value"]; //0
                }

                if ($capital != 0) {
                    $debtValue = $capital * $debtRate * $days;
                    $honorarios = $this->getHonorarios($days, $quote);
                    $quote["CreditsPlan"]["debt_value"] = round($debtValue - $MoraAbo) ;
                    $quote["CreditsPlan"]["debt_honor"] = $honorarios - $HonorP ;

                    if (($debtValue > 0 || $honorarios > 0) && !is_null($credit_id)) {
                        $this->Credit->updateAll(
                            array('Credit.debt' => 1,"Credit.debt_days" => $diasMax),
                            array('Credit.id' => $credit_id)
                        );
                    }
                } else {
                    $quote["CreditsPlan"]["debt_value"] = 0;
                    $quote["CreditsPlan"]["debt_honor"] = 0;
                }
            }else{
				$quote["CreditsPlan"]["debt_value"] = 0;
				$quote["CreditsPlan"]["debt_honor"] = 0;
			}
		}else{
			$quote["CreditsPlan"]["debt_value"] = 0;
			$quote["CreditsPlan"]["debt_honor"] = 0;
		}






		return $quote;
	}

	public function calculateDebtReportsNewData($quote,$last_payment_date,$debtRate, $credit_id = null){

		if (!is_null($quote["date_debt"]) && floatval($quote["capital_payment"]) < floatval($quote["capital_value"]) ) {
			$dateReference = $quote["deadline"];
		}elseif(!is_null($quote["date_debt"]) && floatval($quote["capital_payment"]) >= floatval($quote["capital_value"])){
			$dateReference = $quote["date_debt"];
		}else{
			$dateReference = $quote["deadline"];	
		}
		$deadlineTime 	=  strtotime($quote["deadline"]);
		$nowDateTime 	=  strtotime(date("Y-m-d"));
		$debtValue      =  0;
		$quote["fecha"] = null;
		
		if($nowDateTime > $deadlineTime){
			
			$totalOtros = 0;

			if($quote["capital_payment"] == 0){
				$capital = $quote["capital_value"];
			}elseif(floatval($quote["capital_payment"]) < floatval($quote["capital_value"])){
				$capital = $quote["capital_value"] - $quote["capital_payment"];
			}else{
				$sumValue = $this->Receipt->Payment->find("first",["fields" => ["SUM(value) total"],"conditions" => ["credits_plan_id" => $quote["id"],"DATE(created) <=" => $dateReference,"type" => 1  ], "recursive" => -1 ]);

				$sumValueOtros = $this->Receipt->Payment->find("first",["fields" => ["SUM(value) total"],"conditions" => ["credits_plan_id" => $quote["id"],"DATE(created) <=" => $dateReference,"type" => [2,3]  ], "recursive" => -1 ]);

				$sumValue2 = $this->Receipt->Payment->find("first",["fields" => ["MAX(DATE(created)) fecha"],"conditions" => ["credits_plan_id" => $quote["id"],"DATE(created) <=" => $dateReference,"type" => 1  ], "recursive" => -1 ]);

				$quote["final"] = null;
				if ($sumValue[0]["total"] != null) {
					$capital = $quote["capital_value"] - $sumValue[0]["total"];
					$quote["final"] = $capital;
				}else{
					$capital = 0; 
				}

				if ($sumValueOtros[0]["total"] != null) {
					$totalOtros = $quote["interest_value"] + $quote["others_value"] - $totalOtros[0]["total"];
				}

				if ($sumValue2[0]["fecha"] != null) {
					$quote["fecha"] = $sumValue2[0]["fecha"];
					$last_payment_date = $quote["fecha"];
				}
			}

			$deadline 		= new DateTime($dateReference);
			$nowDate 		= strtotime($last_payment_date) < strtotime(date("Y-m-d H:i:s")) ? new DateTime($last_payment_date) : new DateTime(date("Y-m-d"));

			$difference 	= $deadline->diff($nowDate);

			$number         = 30;

			$days			= $difference->days;
			$debtRate 		= ( $debtRate/100 )/$number;
			$debtRateDays	= $debtRate*$days;
			$debtValue 		= 0;
			$quote["days"]  = $days;


			if($capital != 0){
				$debtValue = $capital * $debtRate * $days;
				$honorarios = $this->getHonorarios($days,$quote);
				// $quote["debt_value"] = $capital+round($debtValue);
				$quote["debt_value"] = round($debtValue);
				$quote["debt_value_add"] = $capital+$totalOtros+round($debtValue);
				$quote["debt_honor"] = $honorarios;

				if( ($debtValue > 0 || $honorarios > 0) && !is_null($credit_id)){
					$this->Credit->updateAll(
					    array('Credit.debt' => 1),
					    array('Credit.id' => $credit_id)
					);
				}

			}else{
				$quote["debt_value"] = 0;
				$quote["debt_honor"] = 0;
			}
		}else{
			$quote["debt_value"] = 0;
			$quote["debt_honor"] = 0;
		}
		return $quote;
	}

	public function calculateDebtReports($quote,$last_payment_date,$debtRate, $credit_id = null){

		$dateReference  =  is_null($quote["date_debt"]) ? $quote["deadline"] : $quote["date_debt"];

		$deadlineTime 	=  strtotime($quote["deadline"]);
		$nowDateTime 	=  strtotime(date("Y-m-d"));
		$debtValue      =  0;

		if($nowDateTime > $deadlineTime){
			$deadline 		= new DateTime($dateReference);
			$nowDate 		= new DateTime(date("Y-m-d"));
			$difference 	= $deadline->diff($nowDate);

			$number         = 30;

			$days			= $difference->days;
			$debtRate 		= ( $debtRate/100 )/$number;
			$debtRateDays	= $debtRate*$days;
			$debtValue 		= 0;
			$quote["days"]  = $days;

			if($quote["capital_payment"] == 0){
				$capital = $quote["capital_value"];
			}elseif($quote["capital_payment"] < $quote["capital_value"]){
				$capital = $quote["capital_value"] - $quote["capital_payment"];
			}else{
				$capital = 0;
			}

			if($capital != 0){
				$debtValue = $capital * $debtRate * $days;
				$honorarios = $this->getHonorarios($days,$quote);
				$quote["debt_value"] = round($debtValue);
				$quote["debt_honor"] = $honorarios;

				if( ($debtValue > 0 || $honorarios > 0) && !is_null($credit_id)){
					$this->Credit->updateAll(
					    array('Credit.debt' => 1),
					    array('Credit.id' => $credit_id)
					);
				}

			}else{
				$quote["debt_value"] = 0;
				$quote["debt_honor"] = 0;
			}
		}else{
			$quote["debt_value"] = 0;
			$quote["debt_honor"] = 0;
		}
		return $quote;
	}

	public function getHonorarios($days,$cuote){
		return 0;
		App::import("model","CollectionFee");
		$collectionFee = new CollectionFee();

		$rate = $collectionFee->field("rate",["CollectionFee.day_ini <= "=>$days,"CollectionFee.day_end >=" => $days]);

		if(!$rate){
			$rate = 0;
		}else{
			$rate = $rate / 100;
		}

		$totalCuote = $cuote["CreditsPlan"]["capital_value_proy"] + $cuote["CreditsPlan"]["interest_value"] + $cuote["CreditsPlan"]["others_value"];

		$totalHonorarios = $totalCuote*$rate;

		return round($totalHonorarios);

	}

}
