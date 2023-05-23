<?php
App::uses('AppModel', 'Model');

class Payment extends AppModel {

	public $validate = array(
		'credits_plan_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'credits_plan_id'),
		),
		'value' => array('numeric' => array('rule' => array('numeric'),'message' => 'value'),
		),
		'type' => array('numeric' => array('rule' => array('numeric'),'message' => 'type'),
		),
	);


	public $belongsTo = array(
		'CreditsPlan' => array('className' => 'CreditsPlan','foreignKey' => 'credits_plan_id',),
		'User' => array('className' => 'User','foreignKey' => 'user_id',),
		'ShopCommerce' => array('className' => 'ShopCommerce','foreignKey' => 'shop_commerce_id',),
		'Receipt' => array('className' => 'Receipt','foreignKey' => 'receipt_id',)
	);

	public function getTotalesByCommerce($commerce_id = null, $pendings = null,$tab = null,$dates = []){
		$sql = 'SELECT SUM(Payment.value) total,Payment.shop_commerce_id,ShopCommerce.name, Shop.social_reason
		FROM receipts AS Receipt
		INNER JOIN payments as Payment on Payment.receipt_id = Receipt.id
		INNER JOIN shop_commerces ShopCommerce ON Payment.shop_commerce_id = ShopCommerce.id
		INNER JOIN shops Shop ON Shop.id = ShopCommerce.shop_id';

		if(!is_null($commerce_id)){
			$sql.= " WHERE Payment.shop_commerce_id = ${commerce_id}"; "AND Payment.state_credishop = 0 AND Payment.juridic=0";

			if(!is_null($pendings)){
				$sql.= " AND Payment.state_credishop = 0  AND Payment.juridic = 0";
			}
		}

		if(is_null($commerce_id)){
			if(!is_null($pendings)){
				$sql.= " WHERE Payment.state_credishop = 0 AND Payment.juridic=0";
			}
		}

		if(!is_null($tab)){
			$sqlNull = $tab == 1 ? "IS NOT NULL" : "IS NULL";

			if (is_null($commerce_id) && is_null($pendings)) {
				$sql.= " WHERE Receipt.shop_commerce_id ".$sqlNull;
			}else{
				$sql.= " AND Receipt.shop_commerce_id ".$sqlNull;
			}

		}

		if (!empty($dates)) {
			$pos = strpos($sql, "WHERE");
			$wherePart = $pos === false ? " WHERE " : " AND ";
			$sql.= $wherePart." DATE(Payment.created) >= '".$dates["ini"]."' AND DATE(Payment.created) <= '".$dates["end"]."' AND Payment.value >= 0";
		}

		$sql.= ' GROUP BY Payment.shop_commerce_id';

		$result = $this->query($sql);

		if(!empty($result)){

			return $result;

		}else{
			return null;
		}


	}


	public function setReceipts($pago, $return = null){
		App::import('Model', 'CreditLimit');

		$this->CreditLimit = new CreditLimit();



		// $fecmin = $this->query("SELECT MIN(payments.CREATED) as fechamin  from payments where payments.receipt_id  IS NULL");

  //       $fecmin =  $fecmin[0][0]["fechamin"] ;


		// $this->query("update payments set CREATED= '" . $fecmin . "'  , modified='" . $fecmin . "'  where receipt_id  IS NULL");


		$quotesNormal = $this->find("all",["conditions" => ["Payment.receipt_id" => null],"group" => ["Payment.uid"],"recursive" => -1 ]);

		$totalByCredit = 0;
		$totalCredit = 0;
		$receiptId = 0;


		if(!empty($quotesNormal)){
			foreach ($quotesNormal as $key => $value) {

				$payments = $this->find("all",["recursive" => -1, "conditions" => ["Payment.uid" => $value["Payment"]["uid"] ] ]);

				$creditId		 = $this->CreditsPlan->field("credit_id",["CreditsPlan.id"=>$value["Payment"]["credits_plan_id"]]);

				$customerId		 = $this->CreditsPlan->Credit->field("customer_id",["Credit.id"=>$creditId]);
				$state_credit    = $this->CreditsPlan->Credit->field("state",["Credit.id"=>$creditId]);

			    $quotes = $this->CreditsPlan->getCuotesInformation($creditId);

				foreach ($quotes as $keyQt => $valueQt) {

					$capitalTotal  =  floatval($valueQt["CreditsPlan"]["capital_value"] - $valueQt["CreditsPlan"]["capital_payment"]); //-
					$interesValue  = floatval($valueQt["CreditsPlan"]["interest_value"] - $valueQt["CreditsPlan"]["interest_payment"] );//

					$othersValue   = floatval($valueQt["CreditsPlan"]["others_value"] - $valueQt["CreditsPlan"]["others_payment"]); //

					if ($valueQt["CreditsPlan"]["state"]==0){
						$totalCredit  += floatVal($capitalTotal + $othersValue + $interesValue +  $valueQt["CreditsPlan"]["debt_value"] + $valueQt["CreditsPlan"]["debt_honor"]) ;
					}
				}

				$totalByCredit += $totalCredit;


				$saldo 			 = $this->CreditsPlan->getCreditDeuda($creditId,null,null,true);
				$disponible		 = $this->CreditLimit->totalQuote($customerId);

				if (!empty($payments)) {

					$totalValue  = $pago;//$this->getTotal($payments);
					$dataReceipt = ["Receipt" => ["value"=>$totalValue,"credits_plan_id" => $value["Payment"]["credits_plan_id"], "user_id" => $value["Payment"]["user_id"], "shop_commerce_id" => $value["Payment"]["shop_commerce_id"],"saldo" => $saldo,"disponible" => $disponible,"state_credit" => $state_credit ] ];
					$this->Receipt->create();
					$this->Receipt->save($dataReceipt);
					$receiptId = $this->Receipt->id;

					foreach ($payments as $keyPayment => $valuePayment) {
						$valuePayment["Payment"]["receipt_id"] = $receiptId;
						$this->save($valuePayment);
					}
				}
			}
			if (!is_null($return)) {
				return $receiptId;
			}
		}
	}

	private function getTotal($quotes){
		$total = 0;
		if(!empty($quotes)){
			foreach ($quotes as $key => $value) {
				$total+=$value["Payment"]["value"];
			}
		}
		return $total;
	}

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'DATE(Payment.created) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
