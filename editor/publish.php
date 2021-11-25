<?php
require_once "../src/Auth.php";
require_once "../src/PluginManager.php";
require_once "../src/Style.php";
require_once "../config/userset.php";
require_once "../config/lang.php";


# Data recovery in the event of session loss
is_null($_POST["textbox"]) ?: $_SESSION["postData"]["text"] = $_POST["textbox"];
is_null($_POST["pageName"]) ?: $_SESSION["postData"]["title"] = $_POST["pageName"];

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
		http_response_code(202);
        header("refresh:{$t};url=../");
		return $t;
    } else {
		http_response_code(302);
		header("Location: ../?page=" . $title);
	}
}

if (Auth::isAuthed()) {
    // Logged in, ready to go.
	$text =  $_SESSION["postData"]["text"]	??	$_POST["textbox"];
	$title = $_SESSION["postData"]["title"]	??	$_POST["pageName"];

    $timeout = publishPage($text, $title, $Plugins) ?? 0;
	unset($_SESSION["postData"]);
}
?>
<!DOCTYPE HTML>
<html lang="<?=USERSET["lang"]?>">
<head>
	<meta charset="utf-8">
	<title><?=$title?></title>
	<link rel="stylesheet" 			href="../resource/css/<?=USERSET["stylesheet"]?>">
	<?=Style::colorPalette(USERSET["colorsheet"]);?>
	<meta name="viewport" 			content="width=device-width, initial-scale=1">
</head>
<body>
	<h3 class="banner right"><?=USERSET["siteName"] ." - ". USERLANG["ac_bPublish"]?></h3>
	<div id="loading">
		<h1>🗑️ - <?=$timeout?>s...</h1>
	</div>
</body>
</html>