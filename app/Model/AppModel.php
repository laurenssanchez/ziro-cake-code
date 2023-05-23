<?php

App::uses('Model', 'Model');

class AppModel extends Model {

	public function renameFile($field, $currentName, $data, $options = array()) {
		$rand        = time();
		$rand_v2     = uniqid();
		$rand_v3     = strtotime(date('Y-m-d H:i:s'));
		$nameContent = explode(".", $currentName);
		$ext         = end($nameContent);
		if(count($nameContent) == 1){
			$ext = "jpg";
		}
		$newName     = $this->alias."_{$rand}_{$rand_v2}_{$rand_v3}.{$ext}";
		return $newName;
 	}
}
