<?php
session_name("AfterCoffeeID");
session_start();
require './lib/Parsedown.php';
require './lib/ParsedownExtra.php';
$Parsedown = new Parsedown();
$Extra = new ParsedownExtra();

# Defines the settings declared in meta.json
define("USERSET", json_decode(file_get_contents("./meta.json"), true));

# If no page is specified, show index.md
$page = isset($_GET["page"]) ? $_GET["page"] : "index";

function getURL($page)
{
	$url = "htt";
    $url .= isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "s" : "";
    $url .= "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    $url .= "?page={$page}";
    return $url;
}

# Load all plugins and defines them into an array
foreach (glob("./plugins/*.php") as $plugin) {
    include $plugin;
    $pluginClasses[] = basename($plugin, ".php");
}
define("AC_PLUGINS", $pluginClasses);

$apath = "./pages/{$page}.md";

# Gets content inside tags to get metadata directly from Markdown
function insideTag($string, $tagname)
{
    $pattern = "#<\s*?$tagname\b[^>]*>(.*?)</$tagname\b[^>]*>#s";
    preg_match($pattern, $string, $matches);
    return $matches[1];
}

# Only compose page if the Markdown file exists
if (file_exists($apath)) {
    $md = file_get_contents($apath);
    $html = $Extra->setBreaksEnabled(true)->text($md);
	# TODO: Make firing plugin functions cleaner
    foreach (AC_PLUGINS as $class) {
        $plugin = new $class;
        if (method_exists($plugin, "changeText")) {
            $html = $plugin->changeText($html);
        }
    }
	# Uses regex to find the first image in the page,
	# And uses that as the Open Graph thumbnail
    if (preg_match('/!\[.*\]\((.*)\)/i', $md, $match)) {
        define('metaImg', true);
        $image = $match[1];
    }
    # Get the modified date directly from the markdown file,
    # Then get the title and description for the HTML from it as well.
    $date = date(isset(USERSET["dateFormat"]) ?? "Y-m-d", ufilemtime($apath));
    $title = insideTag($html, "h1");
    $description = insideTag($html, "h2");
} else {
	# If there is no Markdown file for the request,
	# Return a 404 Error
    http_response_code(404);
    echo "{$page} Not Found";
    include USERSET["errorPath"]["404"];
    exit;
}

function editButton($page)
{
    if (isset($_SESSION['authorized']) && $_SESSION['authorized'] == 1) {
        // Only show edit button if logged into the Editor.
        return "<a href=\"./editor/?page={$page}\">[Edit]</a> ";
    } else {
        return null;
    }
}

?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta property="og:type"        content="website">
	<meta property="og:url"         content="<?=getURL($page)?>">
	<title><?="{$title} - {USERSET["siteName"]}"?></title>
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
	<?php foreach (USERSET["styles"] as $style) {print("<link rel=\"stylesheet\" href=\"./resource/css/{$style}\">\n\t");}?>
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
	<h3 class="banner right"><a href="<?="/".basename(__DIR__)?>" style="text-decoration:none;color:var(--black)"><?=USERSET["siteName"]?></a></h3>
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
	<h4 class="banner left">&copy; <?=date("Y") . " " . USERSET["copyright"]?></h4>
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