<?php require_once "beans.php";?>

<!DOCTYPE HTML>
<html lang="<?=USERSET["lang"]?>">
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
	<meta name="robots" 			content="<?=indexOption(PAGETAGS)?>">
	<meta name="viewport" 			content="width=device-width, initial-scale=1">
	<?php $plugins->load("addHead") ?>
</head>
<body>
	<h3 class="banner right">
		<a href="<?="/".basename(__DIR__)?>"><?=USERSET["siteName"]?></a>
	</h3>
	<p class="info">
		<span class="date"><?php echo editButton($page) . USERLANG["ac_lastEdited"] ." {$date}</span>"; ?>
		<?php $plugins->load("addInfo") ?>
		<?=controlPanel($page);?>
	</p>
	<div id="body" class="page">
		<?=$html?>
	</div>
	<h4 class="banner left">
		<?php
			print("&copy;". date("Y") . " " . USERSET["copyright"]);
			if (USERSET['devCredits'] === "True") {
				print(" - ".USERLANG["ac_madeWithAC"]." <a href=\"https://github.com/wolfgang-degroot/AfterCoffee\">AfterCoffee</a>");
			}
		?>
	</h4>
	<?php $plugins->load("addFooter") ?>
</body>
</html>