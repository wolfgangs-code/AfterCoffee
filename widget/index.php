<?php
require_once __DIR__."/../src/Auth.php";
require_once __DIR__."/../src/PluginManager.php";
require_once __DIR__."/../src/MetaTagger.php";
require_once __DIR__."/../config/userset.php";
require_once __DIR__."/../config/lang.php";

$Plugins = new PluginManager;
$Plugins->init();

$pluginGet = $_GET["plugin"];
$title = USERSET["siteName"]." - ".$pluginGet ?? "Select a Plugin";
$description = "Widget";

if (isset($_GET["action"])) {
	$Plugins->load("action", $pluginGet);
	die;
}

/* AfterCoffee Plugin Widgets */
?>
<!DOCTYPE HTML>
<html lang="<?=USERSET["lang"]?>">
<head>
	<meta charset="utf-8">
	<title><?=USERSET["siteName"] . " {$title}"?></title>
	<link rel="stylesheet" href="../resource/css/<?=USERSET["stylesheet"]?>">
	<link rel="stylesheet" href="../resource/css/<?=USERSET["colorsheet"]?>">
	<?php
		$meta = new MetaTagger($title, $description, USERSET["author"]);
		$meta->changeSetting("name",	"theme-color",	USERSET["themeColor"]);
		$meta->changeSetting("property","og:site_name",	USERSET["siteName"]);
		$meta->render(1);
	?>
</head>
<body>
	<h3 class="banner right">
		<a href="../"><?=USERSET["siteName"]?></a> - <?=ucwords($pluginGet)?>
	</h3>
	<div id="body">
		<?=$Plugins->load("widgetPage", $pluginGet);?>
	</div>
	<h4 class="banner left">&copy; <?=date("Y") . " " . USERSET["copyright"]?></h4>
</body>
</html>