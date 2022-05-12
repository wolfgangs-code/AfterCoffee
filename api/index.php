<?php
require_once(__DIR__."/../src/API.php");
$Router = new Router();
$API = new API();

switch ($Router->base)
{
	case "page":
		$Page = new Page($Router->path);
		if (!$Page->exists) {
			$API->statusCode = 404;
			$API->body = json_encode("That page does not exist.");
			unset($API->dateModified);
			$API->respond();
		}

		// Heads up: This is tricky to test on browsers.
		// Be absolutely sure of your 'Accept' header.
		if (in_array("text/html", $Router->accept)) {
			// If the user is specifically requesting HTML
			$API->body = $Page->getHTML();
			$API->dateModified = $Page->getDateModified();
		} else {
			// If not, markdown.
			$API->body = $Page->getMarkdown();
			$API->dateModified = $Page->getDateModified();
		}
		break;
}
$API->respond();
?>