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
define("cs_config_present", true);
$cs = array();
require $c['dir'] . "/system/amx.class.php";
/*
-----------------------------------------------------
Konfigurāciju rediģēt drīkst pēc šīs līnijas
-----------------------------------------------------
*/

/*
    Datubāzes servera adrese, pēc noklusējuma "localhost"
*/
$cs['db']['host'] = "localhost";

/*
    Datubāzes pieejas lietotājvārds
*/
$cs['db']['username'] = "root";

/*
    Datubāzes pieejas parole
*/
$cs['db']['password'] = "password";

/*
    Datubāzes nosaukums
*/
$cs['db']['database'] = "baltsms";

/*
    AMXBans Versija - 5/6
*/
$cs['amx_version'] = 6;

/*
    Vai AMXBans ir nokonfigurēts, lai lietotu md5 hash?
*/
$cs['password_hash'] = false;