<?php
require_once("Router.php");
require_once("Page.php");
$Router = new Router();

switch ($Router->base)
{
	case "page":
		$page = new Page($Router->path);
		break;
}
?>