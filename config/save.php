<?php
require_once "../src/Auth.php";
Auth::demandAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	header("Location: ./");
	die();
}

function bob($arr)
{
    $data = [];
    foreach ($arr as $item => $value) {
		if ($value == "on") {
			$value = "True";
		} elseif ($value == "false") {
			$value == "False";
		}
        if ($pos = strpos($item, "-")) {
            $n = explode("-", $item);
            $data[$n[0]][$n[1]] = $value;
        } else {
            $data[$item] = $value;
        }

    }
    return $data;
}

if(is_file(__DIR__."/../meta.json")) {
	$old = json_decode(file_get_contents(__DIR__."/../meta.json"), true);
	$final = array_merge($old, bob($_POST));
} else {
	$final = bob($_POST);
}

print("<pre>");
print_r($final);
print("</pre>");

$file = fopen(__DIR__."/../meta.json", "w");
fwrite($file, json_encode($final, JSON_PRETTY_PRINT));
fclose($file);

print("Saved.");
http_response_code(204);