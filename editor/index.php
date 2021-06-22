<?php
require_once '../auth/auth_check.php';
require_once '../config/userset.php';
require_once '../config/lang.php';

foreach (glob("../plugins/*.php") as $plugin) {
    include $plugin;
    $pluginClasses[] = basename($plugin, ".php");
}
define("AC_PLUGINS", $pluginClasses);

$editPage = $_GET["page"];
$apath = "../pages/{$editPage}.md";
if (file_exists($apath)) {
    $md = file_get_contents($apath);
}

// If there is a page, set rows to the page's contents plus one. If not, 8.
$lineCount = isset($editPage) ? substr_count( $md, "\n" ) + 1 : 8;

function modNameBox($editPage) {
	if (isset($editPage)) {
		return " value=\"{$editPage}\" readonly";
	} else {
		return null;
	}
}

/* The AfterCoffee Editor */

$title = USERLANG["ac_editor"];
$description = "AfterCoffee ".USERLANG["ac_editor"];

?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta property="og:type"        content="website">
	<title><?=USERSET["siteName"] . " {$title}"?></title>
	<meta property="og:site_name"   content="<?=USERSET["siteName"]?>" />
	<meta name="theme-color"        content="<?=USERSET["themeColor"]?>">
	<meta name="generator" 			content="AfterCoffee">
	<meta property="og:title"       content="<?=$title?>" />
	<link rel="stylesheet" href="../resource/css/<?=USERSET["stylesheet"]?>">
	<meta name="viewport" 			content="width=device-width, initial-scale=1">
</head>
<body>
	<h3 class="banner right">
		<a href="../"><?=USERSET["siteName"]?></a> - <?=$title?>
	</h3>
	<div id="body">
		<div id="guide">
			<h3><?=USERLANG["editor"]["mdGuide"]?>
				<a href="https://www.markdownguide.org/basic-syntax" target="_blank"><?=USERLANG["editor"]["mdBasic"]?></a>,
				<a href="https://www.markdownguide.org/extended-syntax" target="_blank"><?=USERLANG["editor"]["mdExtended"]?></a>
			</h3>
			<p><?=USERLANG["editor"]["unlistedHint"]?> <code>&lt;!-- NOINDEX --&gt;</code></p>
			<h3><?=USERLANG["editor"]["mdPlugin"]?></h3>
			<ul>
			<?php
				# TODO: Make firing plugin functions cleaner
				foreach (AC_PLUGINS as $class) {
    				$plugin = new $class;
    				if (method_exists($plugin, "editorGuide")) {
        				echo "<li>{$plugin->editorGuide()}</li>";
    				}
				}
			?>
			</ul>
		</div>
        <form method="POST" action="publish.php">
            <textarea id="textbox" name="textbox" rows="<?=$lineCount?>" cols="80"><?=$md?></textarea>
			<div class="editorPub">
				<span class="date"><?=USERLANG["editor"]["filename"]?></span>
            	<input type="text" name="pageName"<?php echo modNameBox($editPage); ?>>
            	<input type="submit" name="submit" value="Publish">
			</div>
        </form>
	</div>
	<h4 class="banner left">&copy; <?=date("Y") . " " . USERSET["copyright"]?></h4>
</body>
</html>