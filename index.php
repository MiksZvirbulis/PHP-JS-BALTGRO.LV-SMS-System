<?php require "config.inc.php"; ?>
<!DOCTYPE html>
<html lang="lv">
<head>
	<meta charset="utf-8">
	<title>BaltSMS</title>
	<link rel="stylesheet" type="text/css" href="<?php echo ($c['page']['external_assets'] === true) ? "http://libary.baltgroup.eu/sms" : $c['url']; ?>/assets/css/baltsms.css">
	<link rel="stylesheet" type="text/css" href="<?php echo ($c['page']['external_assets'] === true) ? "http://libary.baltgroup.eu/sms" : $c['url']; ?>/assets/css/bootstrap-theme.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo ($c['page']['external_assets'] === true) ? "http://libary.baltgroup.eu/sms" : $c['url']; ?>/assets/css/bootstrap.css">
</head>
<body>
	<div class="progress" id="baltsms-loader">
		<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
			<span class="sr-only"></span>
		</div>
	</div>
	<ul id="tab-nav" class="nav nav-tabs" role="tablist" style="width: <?php echo $c['page']['width']; ?>px">
		<?php if($c['page']['language'] === true): ?>
			<div id="baltsms-flags">
				<?php foreach($c['lang'] as $language_key => $data): ?>
					<img src="<?php echo ($c['page']['external_assets'] === true) ? "http://libary.baltgroup.eu/sms" : $c['url']; ?>/assets/images/flags/<?php echo $language_key; ?>.gif" onClick="setLanguage('<?php echo $language_key; ?>')">
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php foreach($c['sms']['plugins'] as $index => $plugin): ?>
			<li role="presentation" class="<?php echo ($index == 0) ? "active" : ""; ?>" onClick="loadPlugin('<?php echo $plugin; ?>')"><a href="#<?php echo $plugin; ?>" aria-controls="<?php echo $plugin; ?>" role="tab" data-toggle="tab"><?php echo (isset($lang['plugin-' . $plugin])) ? $lang['plugin-' . $plugin] : "<span style='color: red'>not defined</span>"; ?></a></li>
		<?php endforeach; ?>
	</ul>
	<div id="baltsms-page" style="width: <?php echo $c['page']['width']; ?>px">
		<div id="baltsms-content">
			<div role="tabpanel">
				<div class="tab-content">
					<?php foreach($c['sms']['plugins'] as $index => $plugin): ?>
						<div role="tabpanel" class="tab-pane <?php echo ($index == 0) ? "active" : ""; ?>" id="<?php echo $plugin; ?>"></div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<script src="<?php echo ($c['page']['external_assets'] === true) ? "http://libary.baltgroup.eu/sms" : $c['url']; ?>/assets/js/jquery.min.js"></script>
	<script src="<?php echo ($c['page']['external_assets'] === true) ? "http://libary.baltgroup.eu/sms" : $c['url']; ?>/assets/js/bootstrap.min.js"></script>
	<script src="<?php echo ($c['page']['external_assets'] === true) ? "http://libary.baltgroup.eu/sms" : $c['url']; ?>/assets/js/jquery.stickytabs.js"></script>
	<script src="<?php echo ($c['page']['external_assets'] === true) ? "http://libary.baltgroup.eu/sms" : $c['url']; ?>/assets/js/jquery.cookie.js"></script>
	<script src="<?php echo ($c['page']['external_assets'] === true) ? "http://libary.baltgroup.eu/sms" : $c['url']; ?>/assets/js/baltsms.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			loadPlugin("<?php echo $c['sms']['plugins'][0]; ?>");
			if(window.location.hash){
				loadPlugin(window.location.hash.substring(1));
			}
			jQuery(".nav-tabs").stickyTabs();
		});
	</script>
</body>
</html>