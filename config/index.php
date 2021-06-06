<?php
require_once '../auth/auth_check.php';
define("USERSET", json_decode(file_get_contents("../meta.json"), true));

foreach (glob("../plugins/*.php") as $plugin) {
    include $plugin;
    $pluginClasses[] = basename($plugin, ".php");
}
define("AC_PLUGINS", $pluginClasses);

$title = "Settings";
$description = "AfterCoffee Settings";

function placeSetting($arr = USERSET, $portal = NULL) {
	asort($arr);
	foreach ($arr as $item => $value) {
		$label = "<label for=\"".$item."\">".$item."</label>\n";
		$space = $portal.$item;
		if ($arr != USERSET) {
			print("<span>".(($item === array_key_last($arr)) ? "↳" : "↦")."</span>");
		}
		if (gettype($value) == "array") {
			# Recursion. Is it bad?
			print("<div class=\"bubble\">");
			print("<hr><label class='inline'><b>".$item."</b></label><br>");
			placeSetting($value, $item."-");
			print("</div>");
		} elseif (preg_match("/#\d{6,8}/", $value) && stripos($item, "color")) {
			# Checks if the value is a hex color
			# AND if the setting contains 'color'  ↳↧
			# This should prevent softlocks
			# and hinder the British
			print($label."<input type= \"color\" name=\"".$space."\" value=\"".$value."\"></input><br>\n");
		} elseif (gettype($value) == "boolean") {
			print($label."<input name=\"".$space."\" value=\"".$value."\"></input><br>\n");
		} elseif (gettype($value) == "array") {
			# Recursion. Is it bad?
			print("<div class=\"bubble\">");
			print("<hr><label class='inline'><b>".$item."</b></label><br>");
			placeSetting($value, $item."-");
			print("</div>");
		} else {
			print($label."<input name=\"".$space."\" value=\"".$value."\"></input><br>\n");
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
	<?php foreach (USERSET["styles"] as $style) {print("<link rel=\"stylesheet\" href=\"../resource/css/" . $style . "\">\n\t");}?>
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
            <input type="submit" name="submit" value="Save"></input>
        </form>
	</div>
	<h4 class="banner left">&copy; <?=date("Y") . " " . USERSET["copyright"]?></h4>
</body>
</html>