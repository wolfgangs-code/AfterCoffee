<?php
session_name("AfterCoffeeID");
session_start();
require './lib/Parsedown.php';
require './lib/ParsedownExtra.php';
$Parsedown = new Parsedown();
$Extra = new ParsedownExtra();

# Defines the settings declared in meta.json
require_once './config/userset.php';

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
    $date = date(USERSET["dateFormat"] ?? "Y-m-d", filemtime($apath));
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
