<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != "xmlhttprequest") die("Ajax Only!");
$p = basename(__FILE__, ".php");
defined("config_present") or require "../config.inc.php";
# defined("mc_config_present") or require "../config.minecraft.php"; /* Ja šis spraudnis saistās ar Minecraft, atkomentē šo līniju */
in_array($p, $c['sms']['plugins']['web']) or die(baltsms::alert("[plugin not defined] Spraudnis nav ievadīts atļauto spraudņu sarakstā!", "danger"));
/*
-----------------------------------------------------

    Jauna spraudņa piemērs - šis piemērs, kas ļaus Tev palīdzēt uzsākt sava spraudņa izveidi

-----------------------------------------------------
*/

/*
    Datubāzes servera adrese, pēc noklusējuma "localhost"
*/
$c[$p]['db']['host'] = "localhost";

/*
    Datubāzes pieejas lietotājvārds
*/
$c[$p]['db']['username'] = "root";

/*
    Datubāzes pieejas parole
*/
$c[$p]['db']['password'] = "password";

/*
    Datubāzes nosaukums
*/
$c[$p]['db']['database'] = "baltsms";

/*
    Tabulas nosaukums
*/
$c[$p]['db']['table'] = "baltsms_my_plugin";

/*
    Pakalpojuma cena
*/
$c[$p]['price'] = 50;

/*
    Valodas tulkojumi
*/
$c['lang'][$p]['lv'] = array(
    "instructions" => "Lai iegādātos gaisu par <PRICE> EUR, sūti kodu <b><KEYWORD><CODE></b> uz <b><NUMBER></b>, lai saņemtu atslēgas kodu!",
	# Kļūdas
    "error_empty_code" => "Ievadi atslēgas kodu!",
	"error_invalid_code" => "Atslēgas kods nav pareizi sastādīts!",
	"success" => "Atslēgas kods veiksmīgi izmantots!",
	# Forma
	"form_unlock_code" => "Atslēgas kods",
    "form_buy" => "Pirkt",
);

$c['lang'][$p]['en'] = array(
	"instructions" => "To purchase air for <PRICE> EUR, send the following code: <b><KEYWORD><CODE></b> to <b><NUMBER></b> to receive an unclock code!",
	# Kļūdas
	"error_empty_code" => "Enter the unlock code!",
	"error_invalid_code" => "The format of the unlock code is not valid!",
	"success" => "Unlock code successfully used!",
	# Forma
	"form_unlock_code" => "Unlock code",
	"form_buy" => "Buy",
	# Tabula
);
/*
-----------------------------------------------------

    Jauna spraudņa piemērs - šis piemērs, kas ļaus Tev palīdzēt uzsākt sava spraudņa izveidi

-----------------------------------------------------
*/
$db = new db($c[$p]['db']['host'], $c[$p]['db']['username'], $c[$p]['db']['password'], $c[$p]['db']['database']); # Savienojamies ar datubāzi
if($db->connected === false) die(baltsms::alert("Nevar izveidot savienojumu ar MySQL serveri. Pārbaudi norādītos pieejas datus!", "danger")); # Pārbaudam vai ir iespējams savienoties ar datubāzi
$lang[$p] = $c['lang'][$p][$c['page']['lang_personal']]; # Definējam lietotāja izvēlēto valodu
?>
<?php if(isset($_POST['code'])): # Pārliecinamies, ka forma pieprasa POST, nevis satura uzrādīšanu ?>
	<?php
	/*
	Formas pārstrāde un ievadīto vērtību pārbaude
	*/
	$errors = array(); # Kļudas izvadam ar $errors[], kas pēc tam tiks izvadītas ar ciklu

	if(empty($_POST['code'])){
		$errors[] = $lang[$p]['error_empty_code'];
	}else{
		if(strlen($_POST['code']) != 9 OR is_numeric($_POST['code']) === false){
			$errors[] = $lang[$p]['error_invalid_code'];
		}
	}

	if(count($errors) > 0){
		foreach($errors as $error){
			echo baltsms::alert($error, "danger");
		}
	}else{
		/*
		Pārbaudam atslēgas kodu un vai tas saskan ar apmaksāto kodu
		*/
		$baltsms = new baltsms();
		$baltsms->setPrice($c[$p]['price']); # Cena, kas norādīta augstāk. Attiecīgi nomaini, ja cenas ir vairākas un cena tiek izvilkta no <select>
		$baltsms->setCode($_POST['code']); # Ievadītais atslēgas kods
		$baltsms->sendRequest();
		if($baltsms->getResponse() === true){
			echo baltsms::alert($lang[$p]['success'], "success");
			/*
			Šeit vari droši ievadīt savu saturu, kas nodos iegādāto pakalpojumu. Tā var būt Minecraft komanda vai vienkāršs SQL kvērijs
			*/
			?>
			<script type="text/javascript">
				setTimeout(function(){
					loadPlugin('<?php echo $p; ?>');
				}, 3000);
			</script>
			<?php
		}else{
			echo $baltsms->getResponse();
			/*
			Šis izvadīs ievadītā atslēgas koda kļūdu, jo pārbaudes rezultāts nebūs veiksmīgs
			*/
		}
	}
	?>
<?php else: ?>
	<?php
	/*
	Šis veiks pārbaudi vai augstāk norādītais tabulas nosaukums eksistē datubāzē
	Ja vēlies automatizēt tabulas izveidi, atkomentē nākošās divas līnijas, kas satur if() un izveido jaunu SQL komandas līniju iekš baltsms.class.php createTable() funkcijas saturā
	*/
	# if($db->tableExists($c[$p]['db']['table']) === false) echo baltsms::alert("Tabula netika atrasta datubāzē. Tā tika izveidota automātiski ar nosaukumu, kas norādīts konfigurācijā!", "success");
	# if($db->tableExists($c[$p]['db']['table']) === false) echo baltsms::createTable($p, $c[$p]['db']['table']);
	?>
	<form class="form-horizontal" method="POST" id="<?php echo $p; ?>">
		<div class="alert alert-info" id="instructions"><?php echo baltsms::instructionTemplate($lang[$p]['instructions'], array("price" => baltsms::returnPrice($c[$p]['price']), "code" => $c[$p]['price'])); ?></div>
		<div id="alerts"></div>
		<div class="form-group">
			<label for="name" class="col-sm-2 control-label"><?php echo $lang[$p]['form_unlock_code']; ?></label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="code" placeholder="<?php echo $lang[$p]['form_unlock_code']; ?>" maxlength="9" autocomplete="off">
			</div>
		</div>
		<div class="form-group">
			<div id="baltsms-form-button">
				<button type="submit" class="btn btn-primary"><?php echo $lang[$p]['form_buy']; ?></button>
			</div>
		</div>
	</form>
<?php endif; ?>