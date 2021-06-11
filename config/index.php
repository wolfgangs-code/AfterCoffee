<?php
require_once '../auth/auth_check.php';
require_once 'userset.php';

foreach (glob("../plugins/*.php") as $plugin) {
    include $plugin;
    $pluginClasses[] = basename($plugin, ".php");
}
define("AC_PLUGINS", $pluginClasses);

$pluginSettings = [];
foreach (AC_PLUGINS as $class) {
	$plugin = new $class;
	if (method_exists($plugin, "addSetting")) {
		foreach($plugin->addSetting() as $s) {
			$pluginSettings[get_class($plugin)][$s] = "";
		}
	}
}

define("POTSET", array_merge($pluginSettings, USERSET));

$title = "Settings";
$description = "AfterCoffee Settings";

function placeSetting($arr = POTSET, $portal = NULL) {
	asort($arr);
	foreach ($arr as $item => $value) {
		$label = "<label for=\"{$item}\">{$item}</label>\n";
		$space = $portal.$item;
		if ($arr != POTSET) print("<span>".(($item === array_key_last($arr)) ? "↳" : "↦")."</span>");
		if (gettype($value) == "array") {
			# Works if the option is an array of values
			# Recursion. Is it bad?
			print("<div class=\"bubble\">");
			print("<hr><label class='inline'><b>".$item."</b></label><br>");
			placeSetting($value, $item."-");
			print("</div>");
		} elseif (preg_match("/#\d{6,8}/", $value) && stripos($item, "color")) {
			# Checks if the value is a hex color
			# AND if the setting contains 'color'
			# This should prevent softlocks
			# and hinder the British
			print("{$label}<input type= \"color\" name=\"{$space}\" value=\"{$value}\"></input><br>\n");
		} elseif (gettype($value) == "boolean" || in_array($value, ["True", "False"])) {
			# Works if the option is a boolean
			$c = $value == "True" ? " checked" : "";
			print("<input type=\"hidden\" name=\"{$space}\" value=\"False\">\n"); # hidden input so unchedked POSTs
			print("{$label}<input type=\"checkbox\" name=\"{$space}\"{$c}></input><br>\n");
		} else {
			# If none else, treat the option as text
			print("{$label}<input name=\"{$space}\" value=\"{$value}\"></input><br>\n");
		}
	}
}

?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta property="og:type"        content="website">
	<title><?=USERSET["siteName"] . " " . $title?></title>
	<meta property="og:site_name"   content="<?=USERSET["siteName"]?>" />
	<meta name="theme-color"        content="<?=USERSET["themeColor"]?>">
	<meta name="generator" 			content="AfterCoffee">
	<meta property="og:title"       content="<?=$title?>" />
	<link rel="stylesheet" href="../resource/css/<?=USERSET["stylesheet"]?>">
	<meta name="viewport" 			content="width=device-width, initial-scale=1">
</head>
<body>
	<h3 class="banner right" style="text-decoration:none;color:var(--black)">
		<a href="../"><?=USERSET["siteName"]?></a> - <?=$title?>
	</h3>
	<div id="body">
	<h3><?=$title?></h3>
        <form method="POST" class="config" action="save.php">
            <?=placeSetting()?>
			<hr>
            <input type="submit" value="Save"></input>
        </form>
	</div>
	<h4 class="banner left">&copy; <?=date("Y") . " " . USERSET["copyright"]?></h4>
</body>
</html>