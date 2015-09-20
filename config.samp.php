<?php
/*
    BaltSMS - SMS Atslēgas vārda sistēma
    BaltSMS ir aplikācija, kura saistās ar baltgroup.eu hostinga un SMS pakalpojumu piedāvātāju. Šo aplikācija drīkst izmantot tikai baltgroup.eu klienti, kuriem ir vajadzīgie dati, lai aizpildītu konfigurāciju un izveidotu savienojumu
    Aplikāciju un tās spraudņus veidoja Miks Zvirbulis
    http://twitter.com/MiksZvirbulis
	https://twitter.com/mrYtteroy
*/
/*
    NEAIZTIKT! AUTOMĀTISKI DEFINĒTAS VĒRTĪBAS!
*/
define("samp_config_present", true);
$samp = array();
require $c['dir'] . "/system/samp.class.php";
/*
-----------------------------------------------------
Konfigurāciju rediģēt drīkst pēc šīs līnijas
-----------------------------------------------------
*/

/*
    Datubāzes servera adrese, pēc noklusējuma "localhost"
*/
$samp['db']['host'] = "";

/*
    Datubāzes pieejas lietotājvārds
*/
$samp['db']['username'] = "";

/*
    Datubāzes pieejas parole
*/
$samp['db']['password'] = "";

/*
    Datubāzes nosaukums, kur atrodas servera dati
*/
$samp['db']['database'] = "";

$samp['servers'] = array(
	"main" => (object)array(
		"title" => "",
		"ip_address" => "",
		"query_port" => 7777,
		"rcon_password" => "",
		"show" => true
		),
	);

foreach($samp['servers'] as $type => $data){
	if(!empty($data->query_port)){
		$samp['rcon'][$type] = new SampQueryAPI($data->ip_address, $data->query_port);
		if(!$samp['rcon'][$type]->isOnline()){
			$data->show = false;
			echo baltsms::alert("Nav iespējams savienoties ar SAMP serveri: <strong>" . $type . "</strong>. Pārbaudi pieejas datus!", "danger");
		}
	}
}