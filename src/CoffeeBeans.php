<?php
require_once "./src/Auth.php";
require_once "./src/PluginManager.php";
require './lib/Parsedown.php';
require './lib/ParsedownExtra.php';
$Parsedown = new Parsedown();
$Extra = new ParsedownExtra();

# Defines the settings declared in meta.json
require_once './config/userset.php';
require_once './config/lang.php';

# If no page is specified, show the set index page
$page = $_GET["page"] ?? USERSET["indexPage"];

function getURL($page)
{
    $url = "http";
    $url .= isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "s" : "";
    $url .= "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    return $url;
}

# Load all plugins and defines them into an array
$plugins = new PluginManager;
$plugins->init();

$apath = "./pages/{$page}.md";

# Gets content inside tags to get metadata directly from Markdown
function insideTag($string, $tagname)
{
    $pattern = "#<\s*?$tagname\b[^>]*>(.*?)</$tagname\b[^>]*>#s";
    preg_match($pattern, $string, $matches);
    return $matches[1] ?? null;
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
	$plugins->load("changeText", $html);
    # Uses regex to find the first image in the page,
    # And uses that as the Open Graph thumbnail
    if (preg_match('/!\[.*\]\((.*)\)/i', $md, $match)) {
        define('metaImg', true);
        $image = $match[1];
    }
    # Get the modified date directly from the markdown file,
    # Then get the title and description for the HTML from it as well.
    $date = date(USERSET["dateFormat"] ?? "Y-m-d", filemtime($apath));
    $dateISO = date("Y-m-d", filemtime($apath));
    $title = insideTag($html, "h1") ?? $page;
    $description = insideTag($html, "h2");
} else {
    # If there is no Markdown file for the request,
    # Return a 404 Error
    http_response_code(404);
    echo "{$page} " . USERLANG["ac_page404"];
    include USERSET["errorPath"]["404"];
    exit;
}

function pageTags($md)
{
    preg_match("/<!--(.*)-->/", $md, $out);
    return array_filter(preg_split("/[\s,]+/", $out[1] ?? null));
}
define("PAGETAGS", pageTags($md));

function indexOption($tags)
{
    return (in_array("NOINDEX", $tags)) ? "noindex" : "index";
}

function controlPanel($page)
{
    if (isset($_SESSION['authorized']) && $_SESSION['authorized'] == 1) {
		$buttomForm	= "<a class=\"infoBlock button\" href=\"./";
		$end		= "</a>";
        // Only show edit button if logged into the Editor.
		$editPage	= $buttomForm."editor/?page={$page}\">" . USERLANG["ac_editPage"]	. $end;
		$newPage	= $buttomForm."editor\">"				. USERLANG["ac_newPage"]	. $end;
		$settings	= $buttomForm."config\">"				. USERLANG["ac_settings"]	. $end;
		return "<div id=\"controlPanel\">".$editPage.$newPage.$settings."</div>";
    } else {
        return null;
    }
}
