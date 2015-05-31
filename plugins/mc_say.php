<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) OR (isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != "xmlhttprequest")) die("Ajax Only!");
$p = basename(__FILE__, ".php");
defined("config_present") or require "../config.inc.php";
defined("mc_config_present") or require "../config.minecraft.php";
in_array($p, $c['sms']['plugins']['mc']) or die(baltsms::alert("Spraudnis nav ievadīts atļauto spraudņu sarakstā!", "danger"));
/*
-----------------------------------------------------
    Minecraft čata ieraksta spraudņa konfigurācija
-----------------------------------------------------
*/

/*
    Čata ieraksta veikšanas komanda
*/
$c[$p]['commands']['sendMessage'] = "say <MESSAGE>";


$c[$p]['prices'] = array(
    "skyblock" => 50,
    "test" => 100
);

$c['lang'][$p]['lv'] = array(
    "instructions" => "Lai izsūtītu ziņu par <PRICE> EUR izvēlētajā serverī, sūti kodu <b><KEYWORD><CODE></b> uz <b><NUMBER></b>, lai saņemtu atslēgas kodu!",
	# Kļūdas
    "error_empty_message" => "Ievadi savu ziņu!",
    "error_empty_server" => "Izvēlies serveri!",
    "error_empty_code" => "Ievadi atslēgas kodu!",
    "error_invalid_code" => "Atslēgas kods nav pareizi sastādīts!",
    "message_sent_successfully" => "Ziņa veiksmīgi nosūtīta!",
	# Forma
    "form_price" => "Cena",
    "form_code" => "Atslēgas kods",
    "form_message" => "Ziņa",
    "form_server" => "Serveris",
    "form_unlock_code" => "Atslēgas kods",
    "form_send" => "Sūtīt",
);

$c['lang'][$p]['en'] = array(
	"instructions" => "To purchase the ban removal for <PRICE> EUR in the selected server, send the following code: <b><KEYWORD><CODE></b> to <b><NUMBER></b> to receive an unclock code!",
	# Kļūdas
	"error_empty_message" => "Enter your message!",
	"error_empty_server" => "Select the server!",
	"error_empty_code" => "Enter the unlock code!",
	"error_invalid_code" => "The format of the unlock code is not valid!",
	"message_sent_successfully" => "Message sent successfully!",
	# Forma
	"form_price" => "Price",
	"form_code" => "Unlock code",
	"form_message" => "Message",
	"form_server" => "Server",
	"form_unlock_code" => "Unlock code",
	"form_send" => "Send",
	);
/*
-----------------------------------------------------
    Minecraft čata ieraksta spraudņa konfigurācija
-----------------------------------------------------
*/
$lang[$p] = $c['lang'][$p][$c['page']['lang_personal']];
?>
<?php if(isset($_POST['code'])): ?>
	<?php
	$errors = array();

	if(empty($_POST['message'])){
		$errors[] = $lang[$p]['error_empty_message'];
	}

	if(empty($_POST['server'])){
		$errors[] = $lang[$p]['error_empty_server'];
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
		$baltsms->setPrice($c[$p]['prices'][$_POST['server']]);
		$baltsms->setCode($_POST['code']);
		$baltsms->sendRequest();
		if($baltsms->getResponse() === true){
			$sendMessage = str_replace(
				array("<MESSAGE>"),
				array($_POST['message']),
				$c[$p]['commands']['sendMessage']
				);
			$mc['rcon'][$_POST['server']]->send_command($sendMessage);
			echo baltsms::alert($lang[$p]['message_sent_successfully'], "success");
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
	<form class="form-horizontal" method="POST" id="<?php echo $p; ?>">
		<div class="alert alert-info" id="instructions"><?php echo baltsms::instructionTemplate($lang[$p]['instructions'], array("price" => baltsms::returnPrice(array_values($c[$p]['prices'])[0]), "code" => array_values($c[$p]['prices'])[0])); ?></div>
		<div id="alerts"></div>
		<div class="form-group">
			<label for="message" class="col-sm-2 control-label"><?php echo $lang[$p]['form_message']; ?></label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="message" placeholder="<?php echo $lang[$p]['form_message']; ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="server" class="col-sm-2 control-label"><?php echo $lang[$p]['form_server']; ?></label>
			<div class="col-sm-10">
				<select class="form-control" name="server" onChange="changePrice(this)">
					<?php foreach($c[$p]['prices'] as $server => $price): ?>
						<?php if($mc['servers'][$server]->show !== false): ?>
							<option value="<?php echo $server; ?>" data-price="<?php echo $price; ?>"><?php echo $mc['servers'][$server]->title; ?> - <?php echo baltsms::returnPrice($price); ?> EUR</option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
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
				<button type="submit" class="btn btn-primary"><?php echo $lang[$p]['form_send']; ?></button>
			</div>
		</div>
	</form>
<?php endif; ?>