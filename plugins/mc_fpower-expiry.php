<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != "xmlhttprequest") die("Ajax Only!");
$p = basename(__FILE__, ".php");
defined("config_present") or require "../config.inc.php";
defined("mc_config_present") or require "../config.minecraft.php";
in_array($p, $c['sms']['plugins']['mc']) or die(baltsms::alert("Spraudnis nav ievadīts atļauto spraudņu sarakstā!", "danger"));
/*
-----------------------------------------------------
    Minecraft frakciju spēka spraudņa konfigurācija
-----------------------------------------------------
*/

/*
    Frakciju spēka pircēju tabulas nosaukums
*/
$c[$p]['db']['table'] = "baltsms_mc_fpower-expiry";

/*
    Vai uzrādīt pircēju sarakstu - jā/nē - true/false
*/
$c[$p]['sms']['buyers'] = true;

/*
    Vai pēc veiksmīga pirkuma izsūtīt informatīvu paziņojumu uz serveri?
*/
$c[$p]['ingame']['notifications'] = true;

/*
    Kādu ziņu izsūtīt uz serveri?
*/
$c[$p]['ingame']['message'] = "<NICKNAME> just purchased faction power from our BaltSMS Shop!";

/*
    Frakcijas spēka iedošanas komanda. Pēc noklusējuma, pievienota Essentials komanda
*/
$c[$p]['commands']['giveFPower'] = "f powerboost player <NICKNAME> <AMOUNT>";

/*
    Frakcijas spēka noņemšanas komanda. Pēc noklusējuma, pievienota Essentials komanda
*/
$c[$p]['commands']['removeFPower'] = "f powerboost player <NICKNAME> 0";

$c[$p]['prices'] = array(
    "skyblock" => array(
    	50 => array(
    		50 => 5,
    		75 => 7,
    		100 => 10,
    	),
    	100 => array(
    		150 => 7,
    		200 => 10,
    	),
    	150 => array(
    		300 => 10,
    		400 => 14
    	)
    ),
    "test" => array(
    	100 => array(
    		30 => 5,
    		50 => 7
    	),
    	200 => array(
    		100 => 7,
    		150 => 10
    	),
    	250 => array(
    		150 => 10,
    		250 => 14
    	)
    )
);

$c['lang'][$p]['lv'] = array(
    "instructions" => "Lai iegādātos <LENGTH> frakcijas spēkus par <PRICE> EUR, sūti kodu <b><KEYWORD><CODE></b> uz <b><NUMBER></b>, lai saņemtu atslēgas kodu!",
	# Kļūdas
    "error_empty_nickname" => "Ievadi savu spēlētāja vārdu!",
    "error_empty_server" => "Izvēlies serveri!",
    "error_empty_power" => "Izvēlies spēku!",
    "error_empty_price" => "Izvēlies cenu!",
    "error_empty_code" => "Ievadi atslēgas kodu!",
    "error_invalid_code" => "Atslēgas kods nav pareizi sastādīts!",
    "error_price_not_listed" => "Izvēlētā cena nav atrasta priekš izvēlētā servera!",
    "fpower_purchased" => "Frakcijas spēks veiksmīgi iegādāts. Lai jauka spēlēšana!",
	# Forma
    "form_price" => "Cena",
    "form_code" => "Atslēgas kods",
    "form_days_for" => "dienas par",
    "form_player_name" => "Spēlētājs",
    "form_server" => "Serveris",
    "form_select_server" => "Izvēlies serveri",
    "form_power" => "Spēks",
	"form_select_power" => "Izvēlies spēku",
    "form_price" => "Cena",
    "form_select_price" => "Izvēlies cenu",
    "form_unlock_code" => "Atslēgas kods",
    "form_buy" => "Pirkt",
	# Tabula
    "table_nickname" => "Spēlētājs",
    "table_server" => "Serveris",
    "table_power" => "Spēks",
    "table_expires" => "Termiņa periods",
    "table_no_buyers" => "Neviens vēl nav iegādājies frakcijas spēku. Varbūt vēlies būt pirmais?"
);

$c['lang'][$p]['en'] = array(
	"instructions" => "To purchase <LENGTH> fraction power for <PRICE> EUR, send the following code: <b><KEYWORD><CODE></b> to <b><NUMBER></b> to receive an unclock code!",
	# Kļūdas
	"error_empty_nickname" => "Enter your nickname!",
	"error_empty_server" => "Select the server!",
	"error_empty_power" => "Select power!",
	"error_empty_price" => "Select the price!",
	"error_empty_code" => "Enter the unlock code!",
	"error_invalid_code" => "The format of the unlock code is not valid!",
	"error_price_not_listed" => "The selected price has not been found for the selected server!",
	"fpower_purchased" => "Faction power was purchased successfully. Have fun!",
	# Forma
	"form_price" => "Price",
	"form_code" => "Unlock code",
	"form_days_for" => "days for",
	"form_player_name" => "Player",
	"form_server" => "Server",
	"form_select_server" => "Select server",
	"form_power" => "Power",
	"form_select_power" => "Select power",
	"form_price" => "Price",
	"form_select_price" => "Select price",
	"form_power" => "power",
	"form_unlock_code" => "Unlock code",
	"form_buy" => "Buy",
	# Tabula
	"table_nickname" => "Player",
    "table_server" => "Server",
    "table_power" => "Power",
    "table_expires" => "Expiry period",
    "table_no_buyers" => "No one has bought fraction power yet. Would you like to be the first?"
);
/*
-----------------------------------------------------
    Minecraft frakcijas spēka spraudņa konfigurācija
-----------------------------------------------------
*/
$db = new db($mc['db']['host'], $mc['db']['username'], $mc['db']['password'], $mc['db']['database']);
if($db->connected === false) die(baltsms::alert("Nevar izveidot savienojumu ar MySQL serveri. Pārbaudi norādītos pieejas datus!", "danger"));
$lang[$p] = $c['lang'][$p][$c['page']['lang_personal']];
?>
<?php if(isset($_POST['code'])): ?>
	<?php
	$errors = array();

	if(empty($_POST['nickname'])){
		$errors[] = $lang[$p]['error_empty_nickname'];
	}

	if(empty($_POST['server'])){
		$errors[] = $lang[$p]['error_empty_server'];
	}

	if(empty($_POST['power'])){
		$errors[] = $lang[$p]['error_empty_power'];
	}

	if(empty($_POST['price']) AND !empty($_POST['server'])){
		$errors[] = $lang[$p]['error_empty_price'];
	}else{
		if(!isset($c[$p]['prices'][$_POST['server']][$_POST['power']][$_POST['price']])){
			$errors[] = $lang[$p]['error_price_not_listed'];
		}
	}

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
		$baltsms = new baltsms();
		$baltsms->setPrice($_POST['price']);
		$baltsms->setCode($_POST['code']);
		$baltsms->sendRequest();
		if($baltsms->getResponse() === true){
			if($db->count("SELECT `id` FROM `" . $c[$p]['db']['table'] . "` WHERE `nickname` = ? AND `server` = ?", array($_POST['nickname'], $_POST['server'])) == 0){
				$db->insert("INSERT INTO `" . $c[$p]['db']['table'] . "` (`nickname`, `server`, `power`, `time`, `expires`) VALUES (?, ?, ?, ?, ?)", array(
					$_POST['nickname'],
					$_POST['server'],
					$_POST['power'],
				    time(),
				    strtotime("+" . $c[$p]['prices'][$_POST['server']][$_POST['power']][$_POST['price']] . " days", time())
				));
		    }else{
		    	$db->update("UPDATE `" . $c[$p]['db']['table'] . "` SET `power` = ?, `time` = ?, `expires` = ? WHERE `nickname` = ? AND `server` = ?", array(
		    		$_POST['power'],
		    		time(),
				    strtotime("+" . $c[$p]['prices'][$_POST['server']][$_POST['power']][$_POST['price']] . " days", time()),
				    $_POST['nickname'],
				    $_POST['server']
		    	));
		    }

			$giveFPower = str_replace(
				array("<NICKNAME>", "<AMOUNT>"),
				array($_POST['nickname'], $_POST['power']),
				$c[$p]['commands']['giveFPower']
				);
			$mc['rcon'][$_POST['server']]->send_command($giveFPower);
			if($c[$p]['ingame']['notifications'] === true){
				$sendMessage = str_replace(
					array("<NICKNAME>"),
					array($_POST['nickname']),
					$c[$p]['ingame']['message']
				);
				$mc['rcon'][$_POST['server']]->send_command("say " . $sendMessage);
			}
			echo baltsms::alert($lang[$p]['fpower_purchased'], "success");
			?>
			<script type="text/javascript">
				setTimeout(function(){
					loadPlugin('<?php echo $p; ?>');
				}, 3000);
			</script>
			<?php
		}else{
			echo $baltsms->getResponse();
		}
	}
	?>
<?php else: ?>
	<?php
	if($db->tableExists($c[$p]['db']['table']) === false) echo baltsms::alert("Tabula netika atrasta datubāzē. Tā tika izveidota automātiski ar nosaukumu, kas norādīts konfigurācijā!", "success");
	if($db->tableExists($c[$p]['db']['table']) === false) echo baltsms::createTable($p, $c[$p]['db']['table']);
	?>
	<form class="form-horizontal" method="POST" id="<?php echo $p; ?>">
		<div class="alert alert-info" id="instructions" style="display: none;"><?php echo baltsms::instructionTemplate($lang[$p]['instructions'], array("price" => baltsms::returnPrice(0), "code" => 0, "length" => 0)); ?></div>
		<div id="alerts"></div>
		<div class="form-group">
			<label for="nickname" class="col-sm-2 control-label"><?php echo $lang[$p]['form_player_name']; ?></label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="nickname" placeholder="<?php echo $lang[$p]['form_player_name']; ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="server" class="col-sm-2 control-label"><?php echo $lang[$p]['form_server']; ?></label>
			<div class="col-sm-10">
				<select class="form-control" name="server" onChange="listGroups(this.value)">
					<option selected disabled><?php echo $lang[$p]['form_server']; ?></option>
					<?php foreach($c[$p]['prices'] as $server => $data): ?>
						<?php if($mc['servers'][$server]->show !== false): ?>
							<option value="<?php echo $server; ?>"><?php echo $mc['servers'][$server]->title; ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="form-group" id="group">
			<label for="power" class="col-sm-2 control-label"><?php echo $lang[$p]['form_power']; ?></label>
			<div class="col-sm-10">
				<select class="form-control" id="groups">
					<option selected disabled><?php echo $lang[$p]['form_select_server']; ?></option>
				</select>
				<?php foreach($c[$p]['prices'] as $server => $power): ?>
					<select class="form-control groups" name="power" id="<?php echo $server; ?>-groups" style="display: none;" onChange="listPrices(this.value, '<?php echo $server; ?>')" disabled>
						<option selected disabled><?php echo $lang[$p]['form_select_power']; ?></option>
						<?php foreach($power as $power_opt => $prices): ?>
							<option value="<?php echo $power_opt; ?>"><?php echo $power_opt; ?> power</option>
						<?php endforeach; ?>
					</select>
				<?php endforeach;  ?>
			</div>
		</div>
		<div class="form-group">
			<label for="price" class="col-sm-2 control-label"><?php echo $lang[$p]['form_price']; ?></label>
			<div class="col-sm-10">
				<select class="form-control" id="prices">
					<option selected disabled><?php echo $lang[$p]['form_select_power']; ?></option>
				</select>
				<?php foreach($c[$p]['prices'] as $server => $power): ?>
					<?php foreach($power as $power_opt => $prices): ?>
						<select class="form-control prices" name="price" id="<?php echo $power_opt . "-" . $server; ?>-prices" style="display: none;" onChange="changePrice(this)" disabled>
							<option selected disabled><?php echo $lang[$p]['form_select_price']; ?></option>
							<?php foreach($prices as $price_code => $days): ?>
								<option value="<?php echo $price_code; ?>" data-length="<?php echo $power_opt; ?>"><?php echo $days; ?> <?php echo $lang[$p]['form_days_for']; ?> <?php echo baltsms::returnPrice($price_code); ?> EUR</option>
							<?php endforeach; ?>
						</select>
					<?php endforeach; ?>
				<?php endforeach;  ?>
			</div>
		</div>
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
	<?php if($c[$p]['sms']['buyers'] === true): ?>
		<table class="table table-bordered">
			<thead>
				<th><?php echo $lang[$p]['table_nickname']; ?></th>
				<th><?php echo $lang[$p]['table_server']; ?></th>
				<th><?php echo $lang[$p]['table_expires']; ?></th>
				<th><?php echo $lang[$p]['table_power']; ?></th>
			</thead>
			<tbody>
				<?php $buyers = $db->fetchAll("SELECT * FROM `" . $c[$p]['db']['table'] . "` ORDER BY `time` DESC"); ?>
				<?php if(empty($buyers)): ?>
					<tr>
						<td colspan="4"><?php echo $lang[$p]['table_no_buyers']; ?></td>
					</tr>
				<?php else: ?>
					<?php foreach($buyers as $buyer): ?>
						<tr>
							<td><?php echo $buyer['nickname']; ?></td>
							<td><?php echo $mc['servers'][$buyer['server']]->title; ?></td>
							<td><?php echo date("d/m/y H:i", $buyer['time']); ?> - <?php echo date("d/m/y H:i", $buyer['expires']); ?></td>
							<td><?php echo $buyer['power']; ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	<?php endif; ?>
<?php endif; ?>
<?php
/*
    Neaiztikt!!! Frakciju spēka termiņa pārbaude un attiecīga dzēšana!
    Neliela informācija: frakcijas spēks tiks dzēsta no saraksta UN servera tikai un vienīgi, ja serveris būs tiešsaistē. Uz katru ielādi, tiks veikta termiņu pārbaude un ja serveris būs sasniedzams - tā tiks dzēsta gan no servera, gan no datubāzes.
*/
$purchases = $db->fetchAll("SELECT `id`, `nickname`, `server`, `expires` FROM `" . $c[$p]['db']['table'] . "`");
foreach($purchases as $purchase){
	if($purchase['expires'] <= time()){
		if($mc['rcon'][$purchase['server']]->connect() != false){
			$removeFPower = str_replace(
				array("<NICKNAME>"),
				array($purchase['nickname']),
				$c[$p]['commands']['removeFPower']
			);
			$mc['rcon'][$purchase['server']]->send_command($removeFPower);
			$db->delete("DELETE FROM `" . $c[$p]['db']['table'] . "` WHERE `id` = ?", array($purchase['id']));
		}
	}
}
/*
    Neaiztikt!!! Frakciju spēka termiņa pārbaude un attiecīga dzēšana!
*/