<?php
require_once '../auth/auth_check.php';

function publishPage($text, $title) {
	$path = "../pages/".$title;
	if (!is_dir($path)) mkdir($path, 0777, true);
	$newPage = fopen($path.".md", "w");
	fwrite($newPage, $text);
    fclose($newPage);
	header('Location: ../?page='.$title);
}

if (isset($_SESSION['authorized']) && $_SESSION['authorized'] == 1) {
    // Logged in, ready to go.
	publishPage($_POST['textbox'], $_POST['pageName']);
} else {
	// You aren't logged in, but are trying to publish...
	header('Location: ../'); // Boot back to the pages
    exit;
}

?>