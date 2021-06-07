<?php
require_once '../auth/auth_check.php';

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
print("<pre>");
print_r(bob($_POST));
print("</pre>");

$file = fopen("../meta.json", "w");
fwrite($file, json_encode(bob($_POST), JSON_PRETTY_PRINT));
fclose($file);

print("Saved.");
header("Location: ./");
