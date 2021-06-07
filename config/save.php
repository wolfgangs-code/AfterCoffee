<?php
require_once '../auth/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header("Location: ./");
	die();
}

function bob($arr)
{
    $data = [];
    foreach ($arr as $item => $value) {
        if ($pos = strpos($item, "-")) {
            $n = explode("-", $item);
            $data[$n[0]][$n[1]] = $value;
        } else {
            $data[$item] = $value;
        }

    }
    return $data;
}

$old = json_decode(file_get_contents("../meta.json"), true);
$final = array_merge($old, bob($_POST));
print("<pre>");
print_r($final);
print("</pre>");

$file = fopen("../meta.json", "w");
fwrite($file, json_encode($final, JSON_PRETTY_PRINT));
fclose($file);

print("Saved.");
header("Location: ./");
