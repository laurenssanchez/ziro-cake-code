<?php
App::uses('AppModel', 'Model');

class Receipt extends AppModel {

	public $validate = array(
		'value' => array('numeric' => array('rule' => array('numeric'),'message' => 'value'),
		),
		'credits_plan_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'credits_plan_id'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
	);

	public function setValorReal(){
		$sql = "UPDATE receipts 
				INNER JOIN ( 
				SELECT SUM(payments.value) valor,receipt_id FROM payments WHERE payments.value >0  GROUP BY payments.receipt_id
				) pay ON pay.receipt_id = receipts.id
				SET receipts.value = pay.valor";
		$result = $this->query($sql);

	}

	

	public function afterFind($results, $primary = false) {

	    foreach ($results as $key => $value) {
	        if (isset($value['Receipt']['id'])) {
	        	$this->Payment->recursive = -1;
	        	$results[$key]["Receipt"]["total_payments"] = $this->Payment->field("SUM(value)",["receipt_id"=>$value["Receipt"]["id"],"value >"=>0]);
	        	$results[$key]["Receipt"]["total_debts"] = $this->Payment->field("SUM(value)",["receipt_id"=>$value["Receipt"]["id"],"value >"=>0,"type"=>array(4,5)]);
	        	$results[$key]["Receipt"]["total_otros"] = $this->Payment->field("SUM(value)",["receipt_id"=>$value["Receipt"]["id"],"value >"=>0,"type"=>3]);
	        	$results[$key]["Receipt"]["total_intereses"] = $this->Payment->field("SUM(value)",["receipt_id"=>$value["Receipt"]["id"],"value >"=>0,"type" => 2]);
	        	$results[$key]["Receipt"]["total_capital"] = $this->Payment->field("SUM(value)",["receipt_id"=>$value["Receipt"]["id"],"value >"=>0,"type" => 1]);
	        }
	    }
	    return $results;
	}


	public $belongsTo = array(
		'CreditsPlan' => array('className' => 'CreditsPlan','foreignKey' => 'credits_plan_id',),
		'User' => array('className' => 'User','foreignKey' => 'user_id',),
		'ShopCommerce' => array('className' => 'ShopCommerce','foreignKey' => 'shop_commerce_id',)
	);

	public $hasMany = array(
		'Payment' => array('className' => 'Payment','foreignKey' => 'receipt_id','dependent' => false,)
	);


	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = ltrim(trim(strtolower($params['q'])),'0');

			$conditions['OR'] = array(
				'Receipt.id LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
