<?php require_once "beans.php"; ?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta property="og:type"        content="website">
	<meta property="og:url"         content="<?=getURL($page)?>">
	<title><?="{$title} - " . USERSET["siteName"]?></title>
	<meta property="og:site_name"   content="<?=USERSET["siteName"]?>" />
	<meta name="theme-color"        content="<?=USERSET["themeColor"]?>">
	<meta name="author"             content="<?=USERSET["author"]?>">
	<meta name="generator" 			content="AfterCoffee">
	<meta property="og:title"       content="<?=$title?>" />
	<meta name="description"        content="<?=$description?>">
	<meta property="og:description" content="<?=$description?>" />
	<meta name="twitter:card" 		content="summary_large_image" />
	<?php if (defined('metaImg')) {echo "<meta property=\"og:image\" content=\"{$image}\">";}?>
	<meta property="article:author" content="<?=USERSET["author"]?>" />
	<meta property="article:published_time" content="<?=$date?>">
	<link rel="stylesheet" href="./resource/css/<?=USERSET["stylesheet"]?>">
	<meta name="viewport" 			content="width=device-width, initial-scale=1">
	<?php
	# TODO: Make firing plugin functions cleaner
	foreach (AC_PLUGINS as $class) {
    	$plugin = new $class;
    	if (method_exists($plugin, "addHead")) {
        	$plugin->addHead($html);
    	}
	}
	?>
</head>
<body>
	<h3 class="banner right">
		<a href="<?="/".basename(__DIR__)?>" style="text-decoration:none;color:var(--black)"><?=USERSET["siteName"]?></a>
	</h3>
	<p class="info">
		<span class="date"><?php echo editButton($page) . "Last Updated: {$date}</span>"; ?>
		<?php
			# TODO: Make firing plugin functions cleaner
			foreach (AC_PLUGINS as $class) {
    			$plugin = new $class;
    			if (method_exists($plugin, "addInfo")) {
        		$plugin->addInfo($html);
    			}
			}
		?>
	</p>
	<div id="body">
		<?=$html?>
	</div>
	<h4 class="banner left">
		&copy;
		<?php
			print(date("Y") . " " . USERSET["copyright"]);
			if (USERSET['devCredits'] === True) {
				print(" - Made with <a href=\"https://github.com/wolfgang-degroot/AfterCoffee\">AfterCoffee</a>");
			}
		?>
	</h4>
	<?php
	# TODO: Make firing plugin functions cleaner
	foreach (AC_PLUGINS as $class) {
    	$plugin = new $class;
    	if (method_exists($plugin, "addFooter")) {
        	$plugin->addFooter($html);
    	}
	}
	?>
</body>
</html>