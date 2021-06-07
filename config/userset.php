<?php

if(!is_file(__DIR__."/../meta.json")) {
	$userset = json_decode(file_get_contents(__DIR__."/../resource/default.json"), true);
} else {
	$userset = json_decode(file_get_contents(__DIR__."/../meta.json"), true);
}

define("USERSET", $userset);
?>