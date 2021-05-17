<?php
require_once 'auth_check.php';
define("USERSET", json_decode(file_get_contents("../meta.json"), true));

foreach (glob("../plugins/*.php") as $plugin) {
    include $plugin;
    $pluginClasses[] = basename($plugin, ".php");
}
define("AC_PLUGINS", $pluginClasses);

$editPage = $_GET["page"];
$apath = "../pages/" . $editPage . ".md";
if (file_exists($apath)) {
    $md = file_get_contents($apath);
}

// If there is a page, set rows to the page's contents plus one. If not, 8.
$lineCount = isset($editPage) ? substr_count( $md, "\n" ) + 1 : 8;

function modNameBox($editPage) {
	if (isset($editPage)) {
		return " value=\"{$editPage}\" disabled";
	} else {
		return null;
	}
}

/* The AfterCoffee Editor */

$title = "Page Editor";
$description = "AfterCoffee Page Editor";

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
		<a href="./"><?=USERSET["siteName"]?></a> - <?=$title?>
	</h3>
	<div id="body">
        <form method="POST" action="publish.php">
            <textarea id="textbox" name="textbox" rows="<?=$lineCount?>" cols="80"><?=$md?></textarea><br>
            <span class="date">Page Title:</span>
            <input type="text" name="pageName"<?php echo modNameBox($editPage); ?>></input>
            <input type="submit" name="submit" value="Publish"></input>
        </form>
	</div>
	<h4 class="banner left">&copy; <?=date("Y") . " " . USERSET["copyright"]?></h4>
</body>
</html>