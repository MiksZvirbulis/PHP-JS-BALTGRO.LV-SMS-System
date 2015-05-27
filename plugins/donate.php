<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != "xmlhttprequest") die("Ajax Only!");
$p = basename(__FILE__, ".php");
defined("config_present") or require "../config.inc.php";
in_array($p, $c['sms']['plugins']['web']) or die(baltsms::alert("Spraudnis nav ievadīts atļauto spraudņu sarakstā!", "danger"));
/*
-----------------------------------------------------
    Ziedošanas spraudņa konfigurācija
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
    Ziedotāju tabulas nosaukums
*/
$c[$p]['db']['table'] = "baltsms_donate";

/*
    Vai uzrādīt ziedotāju sarakstu - jā/nē - true/false
*/
$c[$p]['sms']['donators'] = true;

/*
    Komentāra rakstzīmju ierobežojums
*/
$c[$p]['sms']['comment_char_limit'] = 250;

/*
    Ziedošanas pieļaujamās cenas, ievadītas masīvā cenu kodu formātā
*/
$c[$p]['prices'] = array(
	50,
	70,
	90,
	100,
	125,
	150,
	175,
	200,
	250,
	300,
	350,
	400,
	450,
	500,
	550
	);

$c['lang'][$p]['lv'] = array(
	"instructions" => "Lai ziedotu <PRICE> EUR sūti kodu <b><KEYWORD><CODE></b> uz <b><NUMBER></b>, lai saņemtu atslēgas kodu!",
	# Kļūdas
	"error_empty_name" => "Ievadi savu vārdu!",
	"error_empty_price" => "Izvēlies cenu!",
	"error_price_not_listed" => "Izvēlētā cena nav atrasta pieļaujamo cenu sarakstā!",
	"error_empty_comment" => "Ievadi komentāru!",
	"error_comment_char_limit" => "Komentāra garums nedrīkst pārsniegt " . $c[$p]['sms']['comment_char_limit'] . " rakstzīmes!",
	"error_empty_code" => "Ievadi atslēgas kodu!",
	"error_invalid_code" => "Atslēgas kods nav pareizi sastādīts!",
	"thanks_for_donating" => "Paldies par Tavu ziedojumu!",
	# Forma
	"form_name" => "Vārds",
	"form_price" => "Cena",
	"form_comment" => "Komentārs",
	"form_unlock_code" => "Atslēgas kods",
	"form_donate" => "Ziedot",
	# Tabula
	"table_donator" => "Ziedotājs",
	"table_comment" => "Komentārs",
	"table_amount" => "Summa",
	"table_time" => "Laiks",
	"table_no_donators" => "Neviens vēl nav ziedojis. Varbūt vēlies būt pirmais?"
	);

$c['lang'][$p]['en'] = array(
	"instructions" => "To donate <PRICE> EUR send the following code: <b><KEYWORD><CODE></b> to <b><NUMBER></b> to receive an unlock code!",
	# Kļūdas
	"error_empty_name" => "Enter your name!",
	"error_empty_price" => "Choose the price!",
	"error_price_not_listed" => "The chosen price is not listed in the allowed prices!",
	"error_empty_comment" => "Enter the comment!",
	"error_comment_char_limit" => "The comment is not allowed to contain more than " . $c[$p]['sms']['comment_char_limit'] . " characters!",
	"error_empty_code" => "Enter the unlock code!",
	"error_invalid_code" => "The format of the unlock code is not valid!",
	"thanks_for_donating" => "Thank you for your donation!",
	# Forma
	"form_name" => "Name",
	"form_price" => "Price",
	"form_comment" => "Comment",
	"form_unlock_code" => "Unlock code",
	"form_donate" => "Donate",
	# Tabula
	"table_donator" => "Donator",
	"table_comment" => "Comment",
	"table_amount" => "Sum",
	"table_time" => "Time",
	"table_no_donators" => "No one has donated yet. Would you like to be the first?"
	);
/*
-----------------------------------------------------
    Ziedošanas spraudņa konfigurācija
-----------------------------------------------------
*/
$db = new db($c[$p]['db']['host'], $c[$p]['db']['username'], $c[$p]['db']['password'], $c[$p]['db']['database']);
if($db->connected === false) die(baltsms::alert("Nevar izveidot savienojumu ar MySQL serveri. Pārbaudi norādītos pieejas datus!", "danger"));
$lang[$p] = $c['lang'][$p][$c['page']['lang_personal']];
?>

<?php if(isset($_POST['code'])): ?>
	<?php
	$errors = array();

	if(empty($_POST['name'])){
		$errors[] = $lang[$p]['error_empty_name'];
	}

	if(empty($_POST['price'])){
		$errors[] = $lang[$p]['error_empty_price'];
	}else{
		if(!in_array($_POST['price'], $c[$p]['prices'])){
			$errors[] = $lang[$p]['error_price_not_listed'];
		}
	}

	if(empty($_POST['message'])){
		$errors[] = $lang[$p]['error_empty_comment'];
	}else{
		if(strlen($_POST['message']) > $c[$p]['sms']['comment_char_limit']){
			$errors[] = $lang[$p]['error_comment_char_limit'];
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
			$db->insert("INSERT INTO `" . $c[$p]['db']['table'] . "` (`name`, `message`, `amount`, `time`) VALUES (?, ?, ?, ?)", array(
				$_POST['name'],
				$_POST['message'],
				baltsms::returnPrice($_POST['price']),
				time()
				));
			echo baltsms::alert($lang[$p]['thanks_for_donating'], "success");
			?>
			<script type="text/javascript">
				setTimeout(function(){
					loadPlugin('<?php echo $p; ?>');
				}, 3000 );
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
		<div class="alert alert-info" id="instructions"><?php echo baltsms::instructionTemplate($lang[$p]['instructions'], array("price" => baltsms::returnPrice($c[$p]['prices'][0]), "code" => $c[$p]['prices'][0])); ?></div>
		<div id="alerts"></div>
		<div class="form-group">
			<label for="name" class="col-sm-2 control-label"><?php echo $lang[$p]['form_name']; ?></label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="name" placeholder="<?php echo $lang[$p]['form_name']; ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="price" class="col-sm-2 control-label"><?php echo $lang[$p]['form_price']; ?></label>
			<div class="col-sm-10">
				<select class="form-control" name="price" onChange="changePrice(this)">
					<?php foreach($c[$p]['prices'] as $price_code): ?>
						<option value="<?php echo $price_code; ?>"><?php echo baltsms::returnPrice($price_code); ?> EUR</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="price" class="col-sm-2 control-label"><?php echo $lang[$p]['form_comment']; ?></label>
			<div class="col-sm-10">
				<textarea type="text" class="form-control" name="message" placeholder="<?php echo $lang[$p]['form_comment']; ?>"></textarea>
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
				<button type="submit" class="btn btn-primary"><?php echo $lang[$p]['form_donate']; ?></button>
			</div>
		</div>
	</form>
	<?php if($c[$p]['sms']['donators'] === true): ?>
		<table class="table table-bordered">
			<thead>
				<th><?php echo $lang[$p]['table_donator']; ?></th>
				<th><?php echo $lang[$p]['table_comment']; ?></th>
				<th><?php echo $lang[$p]['table_amount']; ?></th>
				<th><?php echo $lang[$p]['table_time']; ?></th>
			</thead>
			<tbody>
				<?php $donators = $db->fetchAll("SELECT * FROM `" . $c[$p]['db']['table'] . "` ORDER BY `amount` DESC"); ?>
				<?php if(empty($donators)): ?>
					<tr>
						<td colspan="4"><?php echo $lang[$p]['table_no_donators']; ?></td>
					</tr>
				<?php else: ?>
					<?php foreach($donators as $donator): ?>
						<tr>
							<td><?php echo $donator['name']; ?></td>
							<td><?php echo $donator['message']; ?></td>
							<td><?php echo $donator['amount']; ?> EUR</td>
							<td><?php echo date("d/m/Y H:i", $donator['time']); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	<?php endif; ?>
<?php endif; ?>