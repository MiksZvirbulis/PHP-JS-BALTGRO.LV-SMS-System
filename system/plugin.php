<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != "xmlhttprequest") die("Ajax Only!");
if(isset($_POST['plugin'])){
	require "../config.inc.php";
	if(file_exists($c['dir'] . "/plugins/" . $_POST['plugin'] . ".php")){
		include $c['dir'] . "/plugins/" . $_POST['plugin'] . ".php";
	}else{
		echo baltsms::alert(str_replace("<PLUGIN>", $_POST['plugin'] . ".php", $c['lang']['lv']['plugin_not_found']), "danger");
	}
}else{
	exit;
}