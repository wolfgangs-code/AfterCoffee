<?php
require_once __DIR__."/../src/Auth.php";
require_once __DIR__."/../src/MetaTagger.php";
require_once __DIR__."/../src/PluginManager.php";
require_once __DIR__."/../src/Style.php";

require_once __DIR__."/../config/userset.php";
require_once __DIR__."/../config/lang.php";

Auth::demandAuth();

$plugins = new PluginManager;
$plugins->init();

$editPage = $_GET["page"] ?? null;
$apath = "../pages/{$editPage}.md";
if (file_exists($apath)) {
    $md = file_get_contents($apath);
} elseif (USERSET["hidePageByDefault"] === "True") {
	$md = "<!-- NOINDEX -->\n\n";
} else {
	$md = null;
}

// If there is a page, set rows to the page's contents plus two. If not, 24.
$lineCount = (substr_count($md, "\n") > 24) ? substr_count( $md, "\n" ) + 2: 24;

function modNameBox($editPage) {
	return (isset($editPage)) ? " value=\"{$editPage}\" readonly" : null;
}

/* The AfterCoffee Editor */

$title = USERLANG["editor"]["editor"];
$description = "AfterCoffee ".USERLANG["editor"]["editor"];

?>
<!DOCTYPE HTML>
<html lang="<?=USERSET["lang"]?>">
<head>
	<meta charset="utf-8">
	<title><?=USERSET["siteName"] . " {$title}"?></title>
	<link rel="stylesheet" href="../resource/css/<?=USERSET["stylesheet"]?>">
	<?=Style::colorPalette(USERSET["colorsheet"]);?>
	<?php
		$meta = new MetaTagger($title, $description, USERSET["author"]);
		$meta->changeSetting("name",	"theme-color",	USERSET["themeColor"]);
		$meta->changeSetting("property","og:site_name",	USERSET["siteName"]);
		$meta->render(1);
	?>
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
				<?php $plugins->load("editorGuide");?>
			</ul>
		</div>
        <form method="POST" action="publish.php">
            <textarea id="textbox" name="textbox" rows="<?=$lineCount?>" cols="120"><?=$md?></textarea>
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