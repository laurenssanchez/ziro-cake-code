<?php

App::uses('CakeTime', 'Utility');

class UtilidadesHelper extends AppHelper
{
	public $helpers = array('Time');

	function __construct()
	{
		// App::import("Model","Sector");
		// $this->Sector = new Sector();
	}

	public function encrypt($value=null){
      if(!$value){return false;}
      $text = $value;
      $skey = "$%&/()=?*-+/1jf8";
      $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
      $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
      $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $skey, $text, MCRYPT_MODE_ECB, $iv);
      return trim($this->safe_b64encode($crypttext));
    }

    public function decrypt($value=null){
      if(!$value){return false;}
      $skey = "$%&/()=?*-+/1jf8";
      $crypttext = $this->safe_b64decode($value);
      $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
      $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
      $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $skey, $crypttext, MCRYPT_MODE_ECB, $iv);
      return trim($decrypttext);
    }

    private  function safe_b64encode($string) {
      $data = base64_encode($string);
      $data = str_replace(array('+','/','='),array('-','_',''),$data);
      return $data;
    }

    private function safe_b64decode($string) {
      $data = str_replace(array('-','_'),array('+','/'),$string);
      $mod4 = strlen($data) % 4;
      if ($mod4) {
       $data .= substr('====', $mod4);
     }
     return base64_decode($data);
   }

   public function jsontoHtml($jsonText = '')
    {
        $arr = json_decode($jsonText, true);
        $html = "";
        if ($arr && is_array($arr)) {
            $html .= $this->_arrayToHtmlTableRecursive($arr);
        }
        return $html;
    }

    private function _arrayToHtmlTableRecursive($arr) {
        $str = "<table class='table table-hovered table-bordered responsecentral'><tbody>";
        foreach ($arr as $key => $val) {
            $str .= "<tr>";
            $str .= "<td><b>$key</b></td>";
            if (is_array($val)) {
            $str .= "<td class='p-0'>";
                if (!empty($val)) {
                    $str .= $this->_arrayToHtmlTableRecursive($val);
                }
            } else {
            $str .= "<td>";
                $str .= "<strong>$val</strong>";
            }
            $str .= "</td></tr>";
        }
        $str .= "</tbody></table>";

        return $str;
    }

    public function date_castellano($fecha){ //Formato de fecha en español
    $fecha = trim($fecha);
    if ($fecha == '0000-00-00' || $fecha == '0000-00-00 00:00:00' || empty($fecha)) {
      $nombre = 'No hay información';
    } else {
      $fechaFinal   = explode(' ', $fecha);
      $fecha      = substr($fechaFinal[0], 0, 10);
      $numeroDia    = date('d', strtotime($fecha));
      $dia      = date('l', strtotime($fecha));
      $mes      = date('F', strtotime($fecha));
      $anio       = date('Y', strtotime($fecha));
      $dias_ES    = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
      $dias_EN    = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
      $nombredia    = str_replace($dias_EN, $dias_ES, $dia);
      $meses_ES     = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
      $meses_EN     = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
      $nombreMes    = str_replace($meses_EN, $meses_ES, $mes);
      if (isset($fechaFinal[1])) {
        $nombre     = $nombredia.", ".$numeroDia."/".$nombreMes."/".$anio.' '.$fechaFinal[1];
      } else {
        $nombre     = $nombredia.", ".$numeroDia."/".$nombreMes."/".$anio;
      }
    }
    return $nombre;
  }

  public function date_castellano2($fecha){ //Formato de fecha en español
    $fecha = trim($fecha);
    if ($fecha == '0000-00-00' || $fecha == '0000-00-00 00:00:00' || empty($fecha)) {
      $nombre = 'No hay información';
    } else {
      $fechaFinal   = explode(' ', $fecha);
      $fecha      = substr($fechaFinal[0], 0, 10);
      $numeroDia    = date('d', strtotime($fecha));
      $dia      = date('l', strtotime($fecha));
      $mes      = date('F', strtotime($fecha));
      $anio       = date('Y', strtotime($fecha));
      $dias_ES    = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
      $dias_EN    = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
      $nombredia    = str_replace($dias_EN, $dias_ES, $dia);
      $meses_ES     = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
      $meses_EN     = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
      $nombreMes    = str_replace($meses_EN, $meses_ES, $mes);
      if (isset($fechaFinal[1])) {
        $nombre     = $numeroDia."/".$nombreMes."/".$anio.' '.$fechaFinal[1];
      } else {
        $nombre     = $numeroDia."/".$nombreMes."/".$anio;
      }
    }
    return $nombre;
  }


  public function date_castellano3($fecha){ //Formato de fecha en español
    $fecha = trim($fecha);
    if ($fecha == '0000-00-00' || $fecha == '0000-00-00 00:00:00' || empty($fecha)) {
      $nombre = 'No hay información';
    } else {
      $fechaFinal   = explode(' ', $fecha);
      $fecha      = substr($fechaFinal[0], 0, 10);
      $numeroDia    = date('d', strtotime($fecha));
      $dia      = date('l', strtotime($fecha));
      $mes      = date('F', strtotime($fecha));
      $anio       = date('Y', strtotime($fecha));
      $dias_ES    = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
      $dias_EN    = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
      $nombredia    = str_replace($dias_EN, $dias_ES, $dia);
      $meses_ES     = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
      $meses_EN     = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
      $nombreMes    = str_replace($meses_EN, $meses_ES, $mes);
      if (isset($fechaFinal[1])) {
        $nombre     = $numeroDia."/".$nombreMes."/".$anio;
      } else {
        $nombre     = $numeroDia."/".$nombreMes."/".$anio;
      }
    }
    return $nombre;
  }

  public function day_month_castellano($fecha){
    $fecha      = substr($fecha, 0, 10);
    $numeroDia    = date('d', strtotime($fecha));
    $dia      = date('l', strtotime($fecha));
    $mes      = date('F', strtotime($fecha));
    $dias_ES    = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
    $dias_EN    = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
    $nombredia    = str_replace($dias_EN, $dias_ES, $dia);
    $meses_ES     = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $meses_EN     = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $nombreMes    = str_replace($meses_EN, $meses_ES, $mes);
    $nombre     = $numeroDia." de ".$nombreMes;
    return $nombre;
  }

  public function mes_castellano($mes){
    $mes = (int) $mes;
    switch ($mes) {
        case 1:
            $mesNombre  = 'Ene.';
            break;
        case 2:
            $mesNombre  = 'Feb.';
            break;
        case 3:
            $mesNombre  = 'Mar.';
            break;
        case 4:
            $mesNombre  = 'Abr.';
            break;
        case 5:
            $mesNombre  = 'May.';
            break;
        case 6:
            $mesNombre  = 'Jun.';
            break;
        case 7:
            $mesNombre  = 'Jul.';
            break;
        case 8:
            $mesNombre  = 'Ago.';
            break;
        case 9:
            $mesNombre  = 'Sept.';
            break;
        case 10:
            $mesNombre  = 'Oct.';
            break;
        case 11:
            $mesNombre  = 'Nov.';
            break;
        case 12:
            $mesNombre  = 'Dic.';
            break;

        default:
            $mesNombre  = '';
            break;
    }
    return $mesNombre;
  }

  public function calcularDeudaCuota($cuota_id,$credit_id){
    App::import('Model', 'CreditsPlan');
    $this->CreditsPlan = new CreditsPlan();
    return $this->CreditsPlan->getValuependiente($cuota_id, $credit_id);
  }

	public function getInfoCreditCliente($cliente) {
		$data=[];
		$data['cupoTotal']=0;
		$data['valorGastado']=0;
		$data['valorLibre']=0;
		App::import('Model', 'ShopCommerce');
		$this->ShopCommerce = new ShopCommerce();
		$SearchShopCommerce = $this->ShopCommerce->buscarPorCodigo($cliente['Customer']['code']);


		$data['shop']=$SearchShopCommerce[0]['shop']['social_reason'];
		$data['commerce']=$SearchShopCommerce[0]['shop_commerce']['name'];
		$data['commerceCode']=$SearchShopCommerce[0]['shop_commerce']['code'];

		if(isset($cliente['CreditLimit'])) {
			foreach ($cliente['CreditLimit'] as $limit) {
				if ($limit["reason"]=='Aprobación de cupo') {
					$data['cupoTotal']+= $limit["value"];
				}
			}
		}

		App::import('Controller', 'AppController');
		$this->AppController = new AppController();

		$validacion= $this->AppController->totalQuote(true,$cliente['Customer']["id"],true,2);
		$data['valorLibre']=$validacion[0];
		$data['mora']=$validacion[1];
		if ($data['valorLibre'] > $data['cupoTotal']) {
			$data['valorLibre'] = $data['cupoTotal'];
		}
		$data['valorGastado']=($data['cupoTotal']) - ($data['valorLibre']);

		return $data;
	}

}


