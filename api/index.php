<?php
require_once(__DIR__."/../src/API.php");
$Router = new Router();
$API = new API();

switch ($Router->base)
{
	case "page":
		$Page = new Page($Router->path);
		if (in_array("text/html", $Router->accept)) {
			$Page->getHTML();
		} else {
			$Page->getMarkdown();
		}
		break;
}
$API->respond();
?>