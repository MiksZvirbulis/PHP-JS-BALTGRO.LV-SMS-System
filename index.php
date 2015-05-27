<?php require "config.inc.php"; ?>
<!DOCTYPE html>
<html lang="lv">
<head>
	<meta charset="utf-8">
	<title>BaltSMS - Online SMS Services Application</title>
	<link rel="stylesheet" type="text/css" href="<?php echo ($c['page']['external_assets'] === true) ? "http://library.baltgroup.eu/sms" : $c['url'] . '/' . $c['page']['directory']; ?>/assets/css/baltsms.css">
	<link rel="stylesheet" type="text/css" href="<?php echo ($c['page']['external_assets'] === true) ? "http://library.baltgroup.eu/sms" : $c['url'] . '/' . $c['page']['directory']; ?>/assets/css/bootstrap.min.css">
</head>
<body class="baltsms">
	<div class="progress" id="baltsms-loader">
		<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
			<span class="sr-only"></span>
		</div>
	</div>
	<ul id="tab-nav" class="nav nav-tabs" role="tablist" style="width: <?php echo $c['page']['width']; ?>px">
		<?php if($c['page']['language'] === true): ?>
			<div id="baltsms-flags">
				<?php foreach($c['lang'] as $language_key => $data): ?>
					<img src="<?php echo ($c['page']['external_assets'] === true) ? "http://library.baltgroup.eu/sms" : $c['url'] . '/' . $c['page']['directory']; ?>/assets/images/flags/<?php echo $language_key; ?>.gif" onClick="setLanguage('<?php echo $language_key; ?>')">
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php foreach($c['sms']['plugins'] as $type => $plugins): ?>
			<li role="presentation" class="dropdown <?php echo ($c['sms']['primary'] == $type) ? "active" : ""; ?>">
				<a href="#" id="plugins-<?php echo $type; ?>" class="dropdown-toggle" data-toggle="dropdown" aria-controls="plugins-<?php echo $type; ?>-contents"><?php echo (isset($lang['plugin-type-' . $type])) ? $lang['plugin-type-' . $type] : "<span style='color: red'>language not found</span>"; ?> <span class="caret"></span></a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="plugins-<?php echo $type; ?>" id="plugins-<?php echo $type; ?>-contents">
					<?php foreach($plugins as $index => $plugin): ?>
						<li role="presentation" class="<?php echo ($c['sms']['primary'] == $type AND $index == 0) ? "active" : ""; ?>" onClick="loadPlugin('<?php echo $plugin; ?>')"><a href="#<?php echo $plugin; ?>" aria-controls="<?php echo $plugin; ?>" role="tab" data-toggle="tab"><?php echo (isset($lang['plugin-' . $plugin])) ? $lang['plugin-' . $plugin] : "<span style='color: red'>language not found</span>"; ?></a></li>
					<?php endforeach; ?>
				</ul>
			</li>
		<?php endforeach; ?>
	</ul>
	<div id="baltsms-page" style="width: <?php echo $c['page']['width']; ?>px">
		<div id="baltsms-content">
			<div role="tabpanel">
				<div class="tab-content">
					<?php foreach($c['sms']['plugins'] as $type => $plugins): ?>
						<?php foreach($plugins as $index => $plugin): ?>
							<div role="tabpanel" class="tab-pane <?php echo ($c['sms']['primary'] == $type AND $index == 0) ? "active" : ""; ?>" id="<?php echo $plugin; ?>"></div>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<script src="<?php echo ($c['page']['external_assets'] === true) ? "http://library.baltgroup.eu/sms" : $c['url'] . '/' . $c['page']['directory']; ?>/assets/js/jquery.min.js"></script>
	<script src="<?php echo ($c['page']['external_assets'] === true) ? "http://library.baltgroup.eu/sms" : $c['url'] . '/' . $c['page']['directory']; ?>/assets/js/bootstrap.min.js"></script>
	<script src="<?php echo ($c['page']['external_assets'] === true) ? "http://library.baltgroup.eu/sms" : $c['url'] . '/' . $c['page']['directory']; ?>/assets/js/jquery.stickytabs.js"></script>
	<script src="<?php echo ($c['page']['external_assets'] === true) ? "http://library.baltgroup.eu/sms" : $c['url'] . '/' . $c['page']['directory']; ?>/assets/js/jquery.cookie.js"></script>
	<script type="text/javascript">
		var baltsms_url = "<?php echo $c['url'] . '/' . $c['page']['directory']; ?>";
	</script>
	<script src="<?php echo ($c['page']['external_assets'] === true) ? "http://library.baltgroup.eu/sms" : $c['url'] . '/' . $c['page']['directory']; ?>/assets/js/baltsms.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			loadPlugin("<?php echo $c['sms']['plugins'][$c['sms']['primary']][0]; ?>");
			if(window.location.hash){
				loadPlugin(window.location.hash.substring(1));
			}
			jQuery(".nav-tabs").stickyTabs();
		});
	</script>
</body>
</html>