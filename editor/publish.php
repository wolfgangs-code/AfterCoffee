<?php
require_once "../src/Auth.php";
$Auth->demandAuth();

require_once '../config/userset.php';

foreach (glob("../plugins/*.php") as $plugin) {
    include $plugin;
    $pluginClasses[] = basename($plugin, ".php");
}
define("AC_PLUGINS", $pluginClasses);

function publishPage($text, $title)
{
	$title = trim($title, "/");
    $path = "../pages/" . $title;
    if (strpos($title, "/") && !is_dir(substr($path, 0, strrpos($path, "/")))) {
		$npath = substr($path, 0, strrpos($path, "/"));
        mkdir($npath, 0777, true);
    }

    $newPage = fopen($path . ".md", "w");
    fwrite($newPage, $text);
    fclose($newPage);

    # TODO: Make firing plugin functions cleaner
    foreach (AC_PLUGINS as $class) {
        $plugin = new $class;
        if (method_exists($plugin, "onSave")) {
			$plugin->onSave();
        }
    }

    # If the page is empty, delete it.
    if (empty($text)) {
        $errstr = "<!-- NOINDEX ERROR -->\n# There was an error deleting '{$title}'.\n";
        $errstr .= "## Please delete this file manually.";
        # If a page cannot be deleted, hide it and explain why.
        $t = 2;
        unlink(realpath($path . ".md")) ?: publishPage($errstr, $title);
        print("Successfully deleted '{$title}'.\nReturning to index in {$t} seconds...");
        header("refresh:{$t};url=../");
    } else {header("Location: ../?page=" . $title);}
}

if ($Auth->isAuthed()) {
    // Logged in, ready to go.
    publishPage(
		$_SESSION["postData"]["text"]	??	$_POST["textbox"],
		$_SESSION["postData"]["title"]	??	$_POST["pageName"]
	);
	unset($_SESSION["postData"]);
}
