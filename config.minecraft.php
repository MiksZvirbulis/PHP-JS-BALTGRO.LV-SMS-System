<?php
/*
    BaltSMS - SMS Atslēgas vārda sistēma
    BaltSMS ir aplikācija, kura saistās ar baltgroup.eu hostinga un SMS pakalpojumu piedāvātāju. Šo aplikācija drīkst izmantot tikai baltgroup.eu klienti, kuriem ir vajadzīgie dati, lai aizpildītu konfigurāciju un izveidotu savienojumu
    Aplikāciju un tās spraudņus veidoja Miks Zvirbulis
    http://twitter.com/MiksZvirbulis
*/
/*
    NEAIZTIKT! AUTOMĀTISKI DEFINĒTAS VĒRTĪBAS!
*/
define("mc_config_present", true);
$mc = array();
require $c['dir'] . "/system/minecraft.class.php";
/*
-----------------------------------------------------
Konfigurāciju rediģēt drīkst pēc šīs līnijas
-----------------------------------------------------
*/

/*
    Datubāzes servera adrese, pēc noklusējuma "localhost"
*/
$mc['db']['host'] = "localhost";

/*
    Datubāzes pieejas lietotājvārds
*/
$mc['db']['username'] = "root";

/*
    Datubāzes pieejas parole
*/
$mc['db']['password'] = "password";

/*
    Datubāzes nosaukums
*/
$mc['db']['database'] = "baltsms";


$mc['servers'] = array(
	"skyblock" => (object)array(
		"title" => "Skyblock",
		"ip_address" => "127.0.0.1",
		"rcon_port" => 25575,
		"rcon_password" => "password",
		"show" => true
		),
	"test" => (object)array(
		"title" => "Test Server",
		"ip_address" => "127.0.0.1",
		"rcon_port" => 25575,
		"rcon_password" => "password",
		"show" => true
		),
	);

foreach($mc['servers'] as $type => $data){
	$mc['rcon'][$type] = new MinecraftRcon($data->ip_address, $data->rcon_port, $data->rcon_password, 10);
	if($mc['rcon'][$type]->connect() === false){
		$data->show = false;
		echo baltsms::alert("Nav iespējams savienoties ar Minecraft serveri: <strong>" . $type . "</strong>. Pārbaudi pieejas datus!", "danger");
	}
}