<?php
require_once '../auth/auth_check.php';

function publishPage($text, $title) {
	$path = "../pages/".$title;
	if (strpos($title, "/") && !is_dir($path)) mkdir($path, 0777, true);
	$newPage = fopen($path.".md", "w");
	fwrite($newPage, $text);
    fclose($newPage);
	# If the page is empty, delete it.
	if (empty($text)) {
		$errstr = "<!-- NOINDEX ERROR -->\n# There was an error deleting '{$title}'.\n";
		$errstr.= "## Please delete this file manually.";
		# If a page cannot be deleted, hide it and explain why.
		$t = 2;
		unlink(realpath($path.".md")) ?: publishPage($errstr, $title);
		print("Successfully deleted '{$title}'.\nReturning to index in {$t} seconds...");
		header("refresh:{$t};url=../");
	} else {header("Location: ../?page=".$title);}
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