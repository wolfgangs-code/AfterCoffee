<?php
require_once "../src/Auth.php";
require_once "../src/PluginManager.php";
require_once __DIR__."/../config/userset.php";
Auth::demandAuth();

# Load all plugins and defines them into an array
$Plugins = new PluginManager;
$Plugins->init();

function sanitize($input)
{
	$trimmedSlash = trim($input, "/");
	$extraSlashPos = strpos($trimmedSlash, "/");
	if ($extraSlashPos !== false) {
		$preString = substr($trimmedSlash, 0, ++$extraSlashPos);
		$postString = str_replace("/", "-", substr($trimmedSlash, $extraSlashPos));
		return $preString.$postString;
	} else {
		return $trimmedSlash;
	}
}

function publishPage($text, $title, $Plugins)
{
	$title = sanitize($title);
    $path = "../pages/" . $title;
    if (strpos($title, "/") && !is_dir(substr($path, 0, strrpos($path, "/")))) {
		$npath = substr($path, 0, strrpos($path, "/"));
        mkdir($npath, 0777, true);
    }

    $newPage = fopen($path . ".md", "w");
    fwrite($newPage, $text);
    fclose($newPage);

	$Plugins->load("onSave");

    # If the page is empty, delete it.
    if (empty($text)) {
        $errstr = "<!-- NOINDEX ERROR -->\n# There was an error deleting '{$title}'.\n";
        $errstr .= "## Please delete this file manually.";
        # If a page cannot be deleted, hide it and explain why.
        $t = 2;
        unlink(realpath($path . ".md")) ?: publishPage($errstr, $title);
        print("Successfully deleted '{$title}'.\nReturning to index in {$t} seconds...");
		http_response_code(202);
        header("refresh:{$t};url=../");
    } else {
		http_response_code(302);
		header("Location: ../?page=" . $title);
	}
}

if (Auth::isAuthed()) {
    // Logged in, ready to go.
	$text = $_SESSION["postData"]["text"]	??	$_POST["textbox"];
	$title = $_SESSION["postData"]["title"]	??	$_POST["pageName"];

    publishPage($text, $title, $Plugins);
	unset($_SESSION["postData"]);
}
