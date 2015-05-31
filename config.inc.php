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
define("config_present", true);
$c = array();
$c['dir'] = realpath(dirname(__FILE__));
$c['url'] = "http" . ((!empty($_SERVER['HTTPS'])) ? "s" : "") . "://" . $_SERVER['SERVER_NAME'];
/*
-----------------------------------------------------
Konfigurāciju rediģēt drīkst pēc šīs līnijas
-----------------------------------------------------
*/

/*
    Šī ir Tava klienta API atslēga, kuru var atrast baltgroup.eu kontroles panelī
*/
$c['sms']['api_key'] = "";

/*
    Šis ir Tavs klienta ID, kuru var atrast baltgroup.eu kontroles panelī
*/
$c['sms']['client_id'] = 2;

/*
    Šis ir Tavs baltgroup.eu kontroles paneļa reģistrētais atslēgas vārds, kurš tiks uzrādīts pie SMS sūtīšanas instrukcijām
*/
$c['sms']['keyword'] = "BTM";

/*
    Šis ieslēdz SMS debug, kas ļaus izmantot zemāk norādīto kodu, lai testētu pakalpojumu pieslēgšanu pēc tās apmaksas (ieslēgt/izslēgt - true/false)
*/
$c['sms']['debug'] = true;

/*
    Šis ir SMS debug atslēgas kods, kurš pieļaus neapmaksātu pakalpojumu apstiprinājumu kamēr SMS debug būs ieslēgts
*/
$c['sms']['debug_code'] = 123456789;

/*
    Šis ir Tavs baltgroup.eu SMS sistēmas telefona numurs uz kuru tiks sūtīts atslēgas vārda pieprasījums. Nemaini, ja baltgroup.eu to nepieprasa mainīt
*/
$c['sms']['number'] = 144;

/*
    Šis ir spraudņu tips, kurš tiks ielādēts pirmais uz lapas ielādi
*/
$c['sms']['primary'] = "web";

/*
    Šis ir spraudņu saraksts, kas tiek ievadīts masīvā. Lūdzu ievadi tos spraudņus, kurus vēlies redzēt savā veikalā un tos, kuri pastāv /plugins folderī
*/
$c['sms']['plugins'] = array(
	"web" => array(
        "donate"
    ),
    "mc" => array(
        "mc_group",
        "mc_unban",
        "mc_money",
        "mc_exp",
        "mc_fpower",
        "mc_fpower-expiry",
        "mc_fpeaceful",
        "mc_unjail",
        "mc_register",
        "mc_say"
    )
);

/*
    Šī ir direktorija pēc ROOT direktorijas, kas noved uz SMS veikala failiem
*/
$c['page']['directory'] = "";

/*
    Šis ļaus rediģēt lapas nosaukumu, kas ir <title> saturā
*/
$c['page']['title'] = "BaltSMS - Online SMS Services Application";

/*
    Šī ir sistēmas diagnostika, kura ieslēdz kļūdu reportēšanu. Lūdzu nesajauc šo ar SMS sistēmas debug
*/
$c['page']['debug'] = true;

/*
    Tava veikala platums - skaitļa vērtība tiks konvertēta un norādīta pikseļos
*/
$c['page']['width'] = 650;

/*
    Šis regulē vai CSS, JS un citi stila faili tiks ielādēti no baltgroup.eu servera (ieslēgt/izslēgt - true/false)
*/
$c['page']['external_assets'] = false;

/*
    Šis ieslēdz valodas karodziņu izvēli, kas ļaus mainīt aplikācijas valodu (ieslēgt/izslēgt - true/false)
*/
$c['page']['language'] = true;

/*
    Veikala noklusējuma valoda
*/
$c['page']['default_lang'] = "lv";

/*
    Valodas definīcijas
*/
$c['lang']['lv'] = array(
    "code_wrong_price" => "Norādītais atslēgas kods nav derīgs priekš izvēlētās summas!",
    "code_not_found" => "Norādītais atslēgas kods nav atrasts sistēmā!",
    "code_unkown_response" => "Sazinies ar administratoru nododot sekojošo atbildi: ",
    "plugin-type-web" => "Website",
    "plugin-type-mc" => "Minecraft",
	"plugin-donate" => "Ziedot",
    "plugin-mc_group" => "Grupas",
    "plugin-mc_unban" => "Bana noņemšana",
    "plugin-mc_money" => "Nauda",
    "plugin-mc_exp" => "EXP",
    "plugin-mc_fpower" => "Frakcijas Spēks",
    "plugin-mc_fpower-expiry" => "Frakcijas Spēks",
    "plugin-mc_fpeaceful" => "Frakcijas Peaceful",
    "plugin-mc_unjail" => "Unjail",
    "plugin-mc_register" => "Reģistrācija",
    "plugin-mc_say" => "Čata ziņa",
    "plugin_not_found" => "[plugin-not-found] Spraudnis netika atrasts. Pārbaudi vai fails <strong>plugins/<PLUGIN></strong> eksistē!"
);

$c['lang']['en'] = array(
    "code_wrong_price" => "The specified unlock code is not associated with the price chosen!",
    "code_not_found" => "The specified unlock code has not been found in the database!",
    "code_unkown_response" => "Contact the administrator by passing on this message: ",
    "plugin-type-web" => "Website",
    "plugin-type-mc" => "Minecraft",
    "plugin-donate" => "Donate",
    "plugin-mc_group" => "Groups",
    "plugin-mc_unban" => "Ban removal",
    "plugin-mc_money" => "Money",
    "plugin-mc_exp" => "EXP",
    "plugin-mc_fpower" => "Faction Power",
    "plugin-mc_fpower-expiry" => "Faction Power",
    "plugin-mc_fpeaceful" => "Faction Peaceful",
    "plugin-mc_unjail" => "Unjail",
    "plugin-mc_register" => "Registration",
    "plugin-mc_say" => "Chat message",
    "plugin_not_found" => "[plugin-not-found] Plugin was not found. Check if the file <strong>plugins/<PLUGIN></strong> exists!"
);

/*
-----------------------------------------------------
Konfigurāciju rediģēt drīkst līdz šai līnijai
-----------------------------------------------------
*/
if($c['page']['debug'] === true){
    error_reporting(E_ALL | E_STRICT);
    ini_set("display_errors", 1);
}else{
    error_reporting(0);
    ini_set("display_errors", 0);
}

$c['page']['lang_personal'] = (isset($_COOKIE['baltsms_language'])) ? $_COOKIE['baltsms_language'] : $c['page']['default_lang'];
$lang = $c['lang'][$c['page']['lang_personal']];

require $c['dir'] . "/system/db.class.php";
require $c['dir'] . "/system/baltsms.class.php";