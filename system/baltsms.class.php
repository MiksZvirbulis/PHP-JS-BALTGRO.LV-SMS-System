<?php
/*
    BaltSMS - SMS Atslēgas vārda sistēma
    BaltSMS ir aplikācija, kura saistās ar baltgroup.eu hostinga un SMS pakalpojumu piedāvātāju. Šo aplikācija drīkst izmantot tikai baltgroup.eu klienti, kuriem ir vajadzīgie dati, lai aizpildītu konfigurāciju un izveidotu savienojumu
    Aplikāciju un pluginus veidoja Miks Zvirbulis
    http://twitter.com/MiksZvirbulis
*/
class baltsms{
	# BaltSMS API Saite uz kuru tiks izsaukts pieprasījums
	protected $baltsms_api_url = "http://run.baltgroup.eu/api/sms/charge/";
	# Atbilde
	public $response;
	# Cenas kods
	protected $price_code;
	# Saņemtais atslēgas kods
	protected $code;

	public static function alert($string, $type){
		return '<div class="alert alert-' . $type . '">' . $string . '</div>';
	}

	public static function createTable($plugin, $table){
		global $db;
		if($plugin == "donate"){
			$db->insert("CREATE TABLE `$table` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(32) NOT NULL, `message` varchar(250) NOT NULL, `amount` decimal(10,2) NOT NULL, `time` varchar(10) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");
		}elseif($plugin == "mc_group"){
			$db->insert("CREATE TABLE `$table` (`id` int(11) NOT NULL AUTO_INCREMENT, `nickname` varchar(55) NOT NULL, `server` varchar(25) NOT NULL, `mc_group` varchar(25) NOT NULL, `length` int(5) NOT NULL, `time` varchar(10) NOT NULL, `expires` varchar(10) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");
		}elseif($plugin == "mc_money"){
			$db->insert("CREATE TABLE `$table` (`id` int(11) NOT NULL AUTO_INCREMENT, `nickname` varchar(55) NOT NULL, `server` varchar(25) NOT NULL, `amount` int(10) NOT NULL, `time` varchar(10) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");
		}
	}

	public static function returnPrice($price_code){
		$price_code = $price_code * 0.01;
		return number_format($price_code / 0.702804, 2, ".", "");
	}

	public static function instructionTemplate($template, $data = array()){
		global $c;
		return str_replace(
			array(
				"<PRICE>",
				"<CODE>",
				"<LENGTH>",
				"<NUMBER>",
				"<KEYWORD>",
				),
			array(
				isset($data['code']) ? '<span id="price">' . $data['price'] . '</span>' : '',
				isset($data['code']) ? '<span id="code">' . $data['code'] . '</span>' : '',
				isset($data['length']) ? '<span id="length">' . $data['length'] . '</span>' : '',
				$c['sms']['number'],
				$c['sms']['keyword']
				),
			$template
			);
	}

	public function setPrice($price_code){
		$this->price_code = $price_code;
	}

	public function setCode($code){
		$this->code = $code;
	}

	public function sendRequest(){
		global $c;
		if($c['sms']['debug'] === true AND $this->code == $c['sms']['debug_code']){
			$this->response = "code_charged_ok";
		}else{
			$this->response = file_get_contents($this->baltsms_api_url . "?client=" . $c['sms']['client_id'] . "&code=" . $this->code . "&price=" . $this->price_code . "", FALSE, NULL, 0, 16);
		}
	}

	public function getResponse(){
		global $c;
		if($this->response == "code_charged_ok"){
			return true;
		}elseif($this->response == "code_wrong_price"){
			return self::alert($c['lang']['lv']['code_wrong_price'], "danger");
		}elseif($this->response == "code_not_found"){
			return self::alert($c['lang']['lv']['code_not_found'], "danger");
		}else{
			return self::alert($c['lang']['lv']['code_unkown_response'] . $this->response, "danger");
		}
	}
}