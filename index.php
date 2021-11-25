<?php require_once __DIR__."/src/CoffeeBeans.php";?>
<?php require_once __DIR__."/src/MetaTagger.php";?>

<!DOCTYPE HTML>
<html lang="<?=USERSET["locale"]["lang"]?>">
<head>
	<?php $plugins->load("addHead") ?>
	<?php if (defined('metaImg')) {echo "<meta property=\"og:image\" content=\"{$image}\">";}?>
	<link rel="stylesheet" href="./resource/css/<?=USERSET["style"]["stylesheet"]?>">
	<?=Style::colorPalette(USERSET["style"]["colorsheet"]);?>
	<meta charset="utf-8">
	<?php
		$meta = new MetaTagger($title, $description, USERSET["identity"]["author"]);
		$meta->changeSetting("name",	"robots",						indexOption(PAGETAGS));
		$meta->changeSetting("name",	"theme-color",					USERSET["style"]["themeColor"]);
		$meta->changeSetting("property","og:article:published_time",	$date);
		$meta->changeSetting("property","og:site_name",USERSET["identity"]["siteName"]);
		$meta->changeSetting("property","og:url",						getURL($page));
		$meta->render(1);
	?>
	<title><?="{$title} – " . USERSET["identity"]["siteName"]?></title>
</head>
<body>
	<h3 class="banner right">
		<a href="<?="/".basename(__DIR__)?>"><?=USERSET["identity"]["siteName"]?></a>
	</h3>
	<div class="info">
		<div class="infoBlock">
			<span><?=USERLANG["ac_lastEdited"]?></span>
			<time datetime="<?=$dateISO?>"><?=$date?></time>
		</div>
		<div><?php $plugins->load("addInfo") ?></div>
	</div>
	<article>
		<?=$html?>
	</article>
	<?=controlPanel($page);?>
	<h4 class="banner left">
		<?php
			print("&copy;". date("Y") . " " . USERSET["identity"]["copyright"]);
			if (USERSET["identity"]['devCredits'] === "True") {
				print(" – ".USERLANG["ac_madeWithAC"]." <a href=\"https://github.com/wolfgang-degroot/AfterCoffee\">AfterCoffee</a>");
			}
		?>
	</h4>
	<?php $plugins->load("addFooter") ?>
</body>
</html>