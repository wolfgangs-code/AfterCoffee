<?php

if(!is_file(__DIR__."/../meta.json")) {
	$userset = json_decode(file_get_contents(__DIR__."/../resource/default.config"), true);
	define("USERSET", $userset);
} else {
	$def = json_decode(file_get_contents(__DIR__."/../resource/default.config"), true);
	$usr = json_decode(file_get_contents(__DIR__."/../meta.json"), true);
	define("USERSET", array_merge($def, $usr));
}
?>