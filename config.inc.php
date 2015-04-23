<?php
/*
    NEAIZTIKT! AUTOMĀTISKI DEFINĒTĀS VĒRTĪBAS!
*/
define("config_present", true);
$c = array();
$c['dir'] = $_SERVER['DOCUMENT_ROOT'];
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
$c['sms']['client_id'] = 1;

/*
    Šis ir Tavs baltgroup.eu kontroles paneļa reģistrētais atslēgas vārds, kurš tiks uzrādīts pie SMS sūtīšanas instrukcijām
*/
$c['sms']['keyword'] = "BTM";

/*
    Šis ieslēdz SMS debug, kas ļaus izmantot zemāk norādīto kodu, lai testētu pakalpojumu pieslēgšanu pēc tās apmaksas (ieslēgt/izslēgt - true/false)
*/
$c['sms']['debug'] = false;

/*
    Šis ir SMS debug atslēgas kods, kurš pieļaus neapmaksātu pakalpojumu apstiprinājumu kamēr SMS debug būs ieslēgts
*/
$c['sms']['debug_code'] = 123456789;

/*
    Šis ir Tavs baltgroup.eu SMS sistēmas telefona numurs uz kuru tiks sūtīts atslēgas vārda pieprasījums. Nemaini, ja baltgroup.eu to nepieprasa mainīt
*/
$c['sms']['number'] = 144;

/*
    Šis ir pluginu saraksts, kas tiek ievadīts masīvā. Lūdzu ievadi tos pluginus, kurus vēlies redzēt savā veikalā un tos, kuri pastāv /plugins folderī
*/
$c['sms']['plugins'] = array(
    "donate"
    );


/*
    Šī ir sistēmas diagnostika, kura ieslēdz kļūdu reportēšanu. Lūdzu nesajauc šo ar SMS sistēmas debug
*/
$c['page']['debug'] = false;

/*
    Tava veikala platums - skaitļa vērtība tiks konvertēta un norādīta pikseļos
*/
$c['page']['width'] = 650;

/*
    Šis regulē vai CSS, JS un citi stila faili tiks ielādēti no baltgroup.eu servera (ieslēgt/izslēgt - true/false)
*/
$c['page']['external_assets'] = true;

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
    "plugin-donate" => "Ziedot",
    "plugin_not_found" => "Spraudnis netika atrasts. Pārbaudi /plugins/ direktoriju!"
    );

$c['lang']['en'] = array(
    "code_wrong_price" => "The specified unlock code is not associated with the price chosen!",
    "code_not_found" => "The specified unlock code has not been found in the database!",
    "code_unkown_response" => "Contact the administrator by passing on this message: ",
    "plugin-donate" => "Donate",
    "plugin_not_found" => "Plugin was not found. Check the /plugins/ directory!"
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