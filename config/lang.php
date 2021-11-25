<?php

	require_once 'userset.php';

	$def = json_decode(file_get_contents(__DIR__."/../resource/lang/en.json"), true);
	$usr = json_decode(file_get_contents(__DIR__."/../resource/lang/".USERSET["locale"]["lang"].".json"), true);
	$userlang = array_merge($def, $usr);

	define("USERLANG", $userlang);
?>