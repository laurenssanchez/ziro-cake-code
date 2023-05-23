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

	public function setCuotasValue(){
		$sql = "UPDATE credits_plans
				INNER JOIN credits ON credits.id = credits_plans.credit_id
				SET credits_plans.state = 1
				WHERE credits.state = 1;";
		$result = $this->query($sql);
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


	public function getQuotesCobranzas($quoteId = null,$dayIni = null, $dayEnd = null, $customer = null,$user = null,$commerce = null, $start =0, $limit=50, $group = null){

		$sqlUpdate = "UPDATE credits_plans AS CreditsPlan SET CreditsPlan.days =
		DATEDIFF (CURDATE(), CreditsPlan.deadline) WHERE state = 0;";


		$this->query($sqlUpdate);
		$this->update_credits_days();


		$sql = "SELECT Customer.*,CreditsPlan.*,User.name,Credit.quote_days as 'Credit__dias', Credit.credits_request_id,Credit.quota_value,Credit.value_pending,Credit.admin_date
		FROM credits_plans as CreditsPlan
		INNER JOIN credits as Credit on ( Credit.id = CreditsPlan.credit_id AND CreditsPlan.days = Credit.quote_days )
		INNER JOIN credits_requests as CreditsRequest ON CreditsRequest.credit_id = Credit.id
		INNER JOIN shop_commerces as ShopCommerce ON CreditsRequest.shop_commerce_id = ShopCommerce.id
		INNER JOIN customers as Customer on Customer.id = Credit.customer_id
		LEFT JOIN users as User on User.id = Credit.user_id
		where  Credit.credits_request_id != 0 AND CreditsPlan.state < 1 AND Credit.juridico = 0 AND Credit.quote_days > 0";

		if(is_null($dayIni)){
			$sql .= " AND quote_days >= 1";
		}else{
			$sql .= " AND quote_days >= ${dayIni} AND quote_days <= ${dayEnd} ";
		}
		if(!is_null($quoteId)){
			$sql.=" AND CreditsPlan.id = ".$quoteId;
		}

		if(!is_null($customer) && !empty($customer) ){
			$sql.=" AND Customer.identification = ".$customer;
		}

		if(!is_null($commerce) && !empty($commerce) ){
			$sql.=" AND ShopCommerce.code = ".$commerce;
		}

		if(!is_null($user) && !empty($user) ){
			$sql.=" AND User.id = ".$user;
		}

		$sql.= " GROUP BY CreditsPlan.credit_id ";

		if(is_null($quoteId)){
			$sql .= " ORDER BY quote_days ASC,Customer.id ASC
    		 	LIMIT ${start} , ${limit}";
		}

		$response = $this->query($sql);
		foreach ($response as $key => $value) {
			$datos = $this->getCuotesInformation($value["CreditsPlan"]["credit_id"],$value["CreditsPlan"]["id"], null, 1);
			$this->Credit->CreditsRequest->recursive = 1;
			$datos["Customer"] = $value["Customer"];
			$datos["Customer"]["phone"] = $this->Credit->CreditsRequest->Customer->CustomersPhone->field("phone_number",["customer_id"=>$value["Customer"]["id"]]);
			$response[$key] = array_merge($response[$key],$datos);
		}

		if(!is_null($quoteId) && !empty($response)){
			$response = $response[$key];
			$response["Customer"]["phone"] = $this->Credit->CreditsRequest->Customer->CustomersPhone->field("phone_number",["customer_id"=>$response["Customer"]["id"]]);
		}elseif(!is_null($quoteId)){
			$joins = [
					["table"=>"credits","alias"=>"Credit","type"=>"INNER","conditions"=>["Credit.id = CreditsPlan.credit_id"]],
					["table"=>"credits_requests","alias"=>"CreditsRequest","type"=>"INNER","conditions"=>["CreditsRequest.id = Credit.credits_request_id"]],
					["table"=>"customers","alias"=>"Customer","type"=>"INNER","conditions"=>["Customer.id = CreditsRequest.customer_id"]],
					["table"=>"shop_commerces","alias"=>"ShopCommerce","type"=>"INNER","conditions"=>["ShopCommerce.id = CreditsRequest.shop_commerce_id"]],
					["table"=>"shops","alias"=>"Shop","type"=>"INNER","conditions"=>["Shop.id = ShopCommerce.shop_id"]],
				];
			$response = $this->find("first",["conditions"=> ["CreditsPlan.id" => $quoteId], "recursive" => -1, "joins" => $joins, "fields" => ["Credit.*","CreditsPlan.*","Customer.*"] ]);
			if (!empty($response)) {

				$response["Customer"]["phone"] = $this->Credit->CreditsRequest->Customer->CustomersPhone->field("phone_number",["customer_id"=>$response["Customer"]["id"]]);
			}
		}

		return $response;
	}

	public function getQuotesCobranzasCount($quoteId = null,$dayIni = null, $dayEnd = null, $customer = null,$user = null, $commerce = null){

		$sql = "SELECT Count(CreditsPlan.id) as cantidad
		FROM credits_plans as CreditsPlan
		INNER JOIN credits as Credit on Credit.id = CreditsPlan.credit_id
		INNER JOIN customers as Customer on Customer.id = Credit.customer_id
		INNER JOIN credits_requests as CreditsRequest ON CreditsRequest.credit_id = Credit.id
		INNER JOIN shop_commerces as ShopCommerce ON CreditsRequest.shop_commerce_id = ShopCommerce.id
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

		if(!is_null($commerce) && !empty($commerce) ){
			$sql.=" AND ShopCommerce.code = ".$commerce;
		}

		if(!is_null($user) && !empty($user) ){
			$sql.=" AND User.id = ".$user;
		}

		// $sql.= " GROUP BY Customer.id";


		$response = $this->query($sql);

		foreach ($response as $key => $value) {
			$response = $value[0]["cantidad"];
		}

		return $response;
	}

	public function getQuotesCobranzasTotal($quoteId = null){

		$sql = "SELECT CreditsPlan.id,CreditsPlan.deadline,CreditsPlan.date_debt,CreditsPlan.credit_id,Credit.value_request,
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

	public function update_cuotes_days(){
		$sqlUpdate = "UPDATE credits_plans AS CreditsPlan SET CreditsPlan.days =
		DATEDIFF (CURDATE(), CreditsPlan.deadline) WHERE CreditsPlan.state = 0";

		$this->query($sqlUpdate);
		$this->update_credits_days();
	}

	public function update_credits_days(){
		$sqlQuery = "UPDATE credits CR INNER JOIN ( SELECT max(days) diasData, credit_id from credits_plans where state = 0 GROUP BY credit_id ) as QT on CR.id = QT.credit_id SET
		quote_days = QT.diasData WHERE CR.state = 0; UPDATE credits set quote_days = 0 WHERE state = 1";
		$this->query($sqlQuery);
	}

	public function getQuotesJuridico($quoteId = null, $customer = null){

		$this->update_cuotes_days();

		$sql = "SELECT CreditsPlan.id,CreditsPlan.deadline,CreditsPlan.date_debt,CreditsPlan.credit_id,days AS dias, Customer.*
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
		$this->setCuotasValue();
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
			foreach ($dataQuotes as $key => $value) {

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
	                $CapitalN = $value["CreditsPlan"]["capital_value"];

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

	            $this->updateAll(
	                ["CreditsPlan.capital_value" => (($value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0 and $value["CreditsPlan"]["state"] == 0) ? ROUND($CapitalN) : ROUND($value["CreditsPlan"]["capital_value"]),
	                    "CreditsPlan.interest_value" => (($value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0 and $value["CreditsPlan"]["state"] == 0) ? ROUND($interesesT) : ROUND($value["CreditsPlan"]["interest_value"]),
	                    "others_value" => (($value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0 and $value["CreditsPlan"]["state"] == 0) ? ROUND($interesesOT) : ROUND($value["CreditsPlan"]["others_value"])],
	                ["CreditsPlan.id" => $value["CreditsPlan"]["id"]]
	            );
	            $lastQuote = $value;
			}

			$dataQuotes = $this->getCuotesInformation($credit_id);
			foreach ($dataQuotes as $key => $value) {
				if($value["CreditsPlan"]["state"] == 0){

					$capital = $value["CreditsPlan"]["capital_value"];
	                $interes = $value["CreditsPlan"]["interest_value"];
	                $others  = $value["CreditsPlan"]["others_value"];

	                $pay 	 = $this->Credit->query("select sum(value) as PaymentA from payments where credits_plan_id= ' " . $value["CreditsPlan"]["id"] . " '");
	                $pay 	 = $pay[0][0]["PaymentA"];

	                $total 	 += $capital + $interes + $others + $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
	                $total 	 = $total - $pay;

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

		$creditInfo    = $this->Credit->find("first",["recursive"=>-1, "conditions"=>["Credit.id" => $credit_id]]);

		$lastNumber    = $creditInfo["Credit"]["number_fee"];
		$lastQuote 	   = $this->find("first",["recursive"=>-1,"conditions"=>["credit_id"=>$credit_id,"number"=>$lastNumber]]);

		$lastQuoteDebt = $this->find("first",["recursive"=>-1,"conditions"=>["credit_id"=>$credit_id,"credit_old"=> 10 ]]);

		$quoteParam    = 0;

		if (!is_null($totalWeb)) {
			$quoteParam = 1;
		}elseif (
			!is_null($lastQuote) &&
			($lastQuote["CreditsPlan"]["state"] == 0 && strtotime($lastQuote["CreditsPlan"]["deadline"]) <= strtotime(date("Y-m-d")))
			||
			($lastQuote["CreditsPlan"]["state"] == 0 && strtotime($lastQuote["CreditsPlan"]["deadline"]) >= strtotime(date("Y-m-d")) && strtotime($lastQuote["CreditsPlan"]["dateini"]) < strtotime(date("Y-m-d")) )
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

					$pay 		  = $this->Credit->query("select sum(value) as PaymentA from payments where credits_plan_id= ' " . $value["CreditsPlan"]["id"] . " ' ");
            		$value["CreditsPlan"]["TotalAbo"] = $pay[0][0]["PaymentA"];

					$capital 	  = $value["CreditsPlan"]["capital_value"];
                    $interes 	  = $value["CreditsPlan"]["interest_value"];
                    $others  	  = $value["CreditsPlan"]["others_value"];
					$TotalAbonado = $value["CreditsPlan"]["TotalAbo"];

					$cuotaNormal  = $capital;
					$totalCuota   = ($cuotaNormal+$others+$interes+$value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"]) - $TotalAbonado;

                	if ($totalCuota <= 1) {
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
		$Value_pending   = $creditInfo["Credit"]["value_pending"];
		$valorAbono      = $this->field("SUM(capital_payment)",["credit_id"=>$credit_id,"state"=>0]);
		$valorPagado   	 = $this->field("SUM(capital_payment)",["credit_id"=>$credit_id]);

		$pay = $this->Credit->query("select sum(value) as PaymentA from payments where credits_plan_id IN (SELECT id FROM credits_plans WHERE credit_id = $credit_id ) AND type = 1 ");
        $valorPagado = $pay[0][0]["PaymentA"];

		$valorDesembolso = $creditInfo["Credit"]["value_request"];
		$Value_pending   = ROUND($valorDesembolso-$valorPagado);

        /** calculo de intereses  */
        $TypeCredit   	 = $creditInfo["Credit"]["type"];
	    $Interes_rate 	 = $creditInfo["Credit"]["interes_rate"];
		$Others_rate  	 = $creditInfo["Credit"]["others_rate"];
		$debtRate 	  	 = $creditInfo["Credit"]["debt_rate"];


		$TotalInteres 	 = ROUND(((($Value_pending * $Interes_rate) / 100) / 30) * $days);
		$TotalOthers  	 = ROUND(((($Value_pending * $Others_rate) / 100) / 30) * $days);

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

			$quotes[$key] 	= $this->calculateDebt($value,$lastPaymentDate,$debtRate,$credit_id);
			$value 		  	= $quotes[$key];
			if($value["CreditsPlan"]["state"] == 0) {
				$totalDB+= $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
			}
		}

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

		$this->Credit->updateAll(
            array('Credit.debt' => 1,"Credit.debt_days" => 0),
            array('Credit.id' => $credit_id)
        );

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
				$capital = $quote["CreditsPlan"]["capital_value_proy"];
			}else{
				$capital = $quote["CreditsPlan"]["capital_value"];
			}

			if($capital != 0){
				$debtValue = $capital * $debtRate * $days;
				$honorarios = $this->getHonorarios($days,$quote);
				$quote["CreditsPlan"]["debt_value"] = round($debtValue);
				$quote["CreditsPlan"]["debt_honor"] = $honorarios;

				if (($debtValue > 0 || $honorarios > 0) && !is_null($credit_id) && $quote["CreditsPlan"]["state"] == 0) {
                        $this->Credit->updateAll(
                            array('Credit.debt' => 1,"Credit.debt_days" => $diasMax),
                            array('Credit.id' => $credit_id)
                        );
                    }else{
                    	$this->Credit->updateAll(
                            array('Credit.debt' => 0,"Credit.debt_days" => 0),
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

		$this->Credit->updateAll(
            array('Credit.debt' => 0,"Credit.debt_days" => 0),
            array('Credit.id' => $credit_id)
        );

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

		$this->Credit->updateAll(
            array('Credit.debt' => 0,"Credit.debt_days" => 0),
            array('Credit.id' => $credit_id)
        );

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
                    $capital =  $quote["CreditsPlan"]["capital_value_proy"];
                } else {
                    $capital =  $quote["CreditsPlan"]["capital_value_proy"]; //0
                }


                if ($capital != 0) {
                    $debtValue = $capital * $debtRate * $days;
                    $honorarios = $this->getHonorarios($days, $quote);
                    $quote["CreditsPlan"]["debt_value"] = round($debtValue - $MoraAbo) ;
                    $quote["CreditsPlan"]["debt_honor"] = $honorarios - $HonorP ;

                    if (($debtValue > 0 || $honorarios > 0) && !is_null($credit_id) && $quote["CreditsPlan"]["state"] == 0) {
                        $this->Credit->updateAll(
                            array('Credit.debt' => 1,"Credit.debt_days" => $diasMax),
                            array('Credit.id' => $credit_id)
                        );
                    }else{
                    	$this->Credit->updateAll(
                            array('Credit.debt' => 0,"Credit.debt_days" => 0),
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
		// $datepay        = $quote["CreditsPlan"]["date_payment"];
		$datepay        = null;
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
                $nowDate 		= is_null($fecJuridico) ? new DateTime( is_null($datepay) ? date("Y-m-d"):$datepay) : new DateTime($fecJuridico);
                $difference 	= $deadline->diff($nowDate);

                $number         = 30;

                $days					= $difference->days;
                $debtRate 		= ($debtRate/100)/$number;
                $debtRateDays	= $debtRate*$days;
                $debtValue 		= 0;

                if ($days > $diasMax) {
                	$diasMax = $days;
                }

                if ($quote["CreditsPlan"]["state"] == 0) {
                	$capital =  $quote["CreditsPlan"]["capital_value_proy"] == 0 ? $quote["CreditsPlan"]["capital_value"] : $quote["CreditsPlan"]["capital_value_proy"] ; //0
                }else{
                	$capital =  $quote["CreditsPlan"]["capital_value"]; //0
                }

                if ($capital != 0) {
                    $debtValue = $capital * $debtRate * $days;
                    $honorarios = $this->getHonorarios($days, $quote);
                    $quote["CreditsPlan"]["debt_value"] = round($debtValue - $MoraAbo) ;
                    $quote["CreditsPlan"]["debt_honor"] = $honorarios - $HonorP ;

                    if (($debtValue > 0 || $honorarios > 0) && !is_null($credit_id) && $quote["CreditsPlan"]["state"] == 0) {
                        $this->Credit->updateAll(
                            array('Credit.debt' => 1,"Credit.debt_days" => $diasMax),
                            array('Credit.id' => $credit_id)
                        );
                    }else{
                    	$this->Credit->updateAll(
                            array('Credit.debt' => 0,"Credit.debt_days" => 0),
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

				$sumValue2 = $this->Receipt->Payment->find("first",["fields" => ["MAX(DATE(created)) fecha"],"conditions" => ["credits_plan_id" => $quote["id"],"DATE(created) <=" => $dateReference,"type" => 1  ], "recursive" => -1 ]);

				$quote["final"] = null;
				if ($sumValue[0]["total"] != null) {
					$capital = $quote["capital_value"] - $sumValue[0]["total"];
					$quote["final"] = $capital;
				}else{
					$capital = 0;
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
				$quote["debt_value_add"] = $capital+round($debtValue);
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

		$diasMax 		= 0;

		$dateReference  =  $quote["deadline"] ;

		$deadlineTime 	=  strtotime($quote["deadline"]);
		$nowDateTime 	=  strtotime(date("Y-m-d"));
		$debtValue      =  0;
		$datepay        = $quote["date_payment"];
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


                if ($quote["state"]==0) {
                    $capital =  $quote["capital_value_proy"];
                } else {
                    $capital =  $quote["capital_value"]; //0
                }

                if ($capital != 0) {
                    $debtValue = $capital * $debtRate * $days;
                    $honorarios = $this->getHonorarios($days, $quote);
                    $quote["debt_value"] = round($debtValue - $MoraAbo) ;
                    $quote["debt_honor"] = $honorarios - $HonorP ;

                    if (($debtValue > 0 || $honorarios > 0) && !is_null($credit_id) && $quote["state"] == 0) {
                        $this->Credit->updateAll(
                            array('Credit.debt' => 1,"Credit.debt_days" => $diasMax),
                            array('Credit.id' => $credit_id)
                        );
                    }else{
                    	$this->Credit->updateAll(
                            array('Credit.debt' => 0,"Credit.debt_days" => 0),
                            array('Credit.id' => $credit_id)
                        );
                    }
                } else {
                    $quote["debt_value"] = 0;
                    $quote["debt_honor"] = 0;
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
