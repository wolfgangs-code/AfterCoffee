  <?php
require_once __DIR__ . "/../src/Auth.php";
require_once __DIR__ . "/../src/MetaTagger.php";
require_once __DIR__ . "/../src/PluginManager.php";
require_once __DIR__ . "/../src/Style.php";

require_once "userset.php";
require_once "lang.php";

Auth::demandAuth();

$plugins = new PluginManager;
$plugins->init();

define("POTSET", array_merge($plugins->settings(), USERSET));

$title = USERLANG["ac_settings"];
$description = "AfterCoffee " . USERLANG["ac_settings"];

function getAllStylesheets()
{
    $cssPath = __DIR__ . "/../resource/css";
    $stylesheets = [];
    foreach (glob("{$cssPath}/*.css") as $filePath) {
        $fileName = substr($filePath, strrpos($filePath, "/") + 1, -4);
        $stylesheets[] = $fileName;
    }
    return $stylesheets;
}

function getAllPalettes()
{
    $palettePath = __DIR__ . "/../resource/color";
    $palettes = [];
    foreach (glob("{$palettePath}/*.palette") as $filePath) {
        $fileName = substr($filePath, strrpos($filePath, "/"), -8);
        $palettes[] = $fileName;
    }

    return $palettes;
}

function placeSetting($arr = POTSET, $portal = null)
{
    asort($arr);
    foreach ($arr as $item => $value) {
        array_key_exists($item, USERLANG["cfg"]) ? $tname = USERLANG["cfg"][$item] : $tname = $item;
        $label = "<div class=\"line\"><label for=\"{$item}\"><span>{$tname}</span></label>\n";
        $space = $portal . $item;
        # If it's the language option
        if ($item === "lang") {
            print("{$label}<select name=\"{$space}\" value=\"{$value}\"><hr></div><br>\n");
            $langs = array_diff(scandir("../resource/lang"), ["..", "."]);
            foreach ($langs as $lang) {
                $lang = pathinfo($lang, PATHINFO_FILENAME);
                ($lang == POTSET["lang"]) ? $ss = "selected" : $ss = "";
                print("<option value =\"{$lang}\"{$ss}>{$lang}</option>");
            }
            print("</select><hr></div><br>");
            continue;
        }
        if ($item === "stylesheet") {
            print("{$label}<select name=\"{$space}\" value=\"{$value}\"><hr></div><br>\n");
            $allSheets = getAllStylesheets();
            foreach ($allSheets as $sheet) {
                ($sheet . ".css" == POTSET[$item]) ? $ss = "selected" : $ss = "";
                $sheet = ($item == "colorsheet") ? substr($sheet, strpos($sheet, "-") + 1) : $sheet;
                print("<option value =\"{$sheet}.css\"{$ss}>{$sheet}</option>");
            }
            print("</select><hr></div><br>");
            continue;
        }

        if ($item === "colorsheet") {
            print("{$label}<select name=\"{$space}\" value=\"{$value}\"><hr></div><br>\n");
            $allPalettes = getAllPalettes();
            foreach ($allPalettes as $palette) {
                (substr($palette, 1) . ".palette" == POTSET[$item]) ? $ss = "selected" : $ss = "";
				print("<!-- {$palette}, ".POTSET[$item]." -->");
                $palette = ($item == "colorsheet") ? substr($palette, strpos($palette, "-") + 1) : $palette;
                print("<option value =\"{$palette}.palette\"{$ss}>{$palette}</option>");
            }
            print("</select><hr></div><br>");
            continue;
        }

        if ($arr != POTSET) {
            print("<span>" . (($item === array_key_last($arr)) ? "↳" : "↦") . "</span>");
        }

        if (gettype($value) == "array") {
            # Works if the option is an array of values
            # Recursion. Is it bad?
            print("<hr class=\"left\"><div class=\"bubble\">");
            print("<label class='inline'><b>" . $item . "</b></label><br>");
            placeSetting($value, $item . "-");
            print("</div>");
        } elseif (preg_match("/#\d{6,8}/", $value) && stripos($item, "color")) {
            # Checks if the value is a hex color
            # AND if the setting contains 'color'
            # This should prevent softlocks
            # and hinder the British
            print("{$label}<input type= \"color\" name=\"{$space}\" value=\"{$value}\"><hr></div><br>\n");
        } elseif (gettype($value) == "boolean" || in_array($value, ["True", "False"])) {
            # Works if the option is a boolean
            $c = $value == "True" ? " checked" : "";
            print("<input type=\"hidden\" name=\"{$space}\" value=\"False\">"); # hidden input so unchedked POSTs
            print("{$label}<input type=\"checkbox\" name=\"{$space}\"{$c}><hr></div><br>");
        } else {
            # If none else, treat the option as text
            print("{$label}<input name=\"{$space}\" value=\"{$value}\"><hr></div><br>\n");
        }
    }
}

?>
<!DOCTYPE HTML>
<html lang="<?=USERSET["lang"]?>">
<head>
	<meta charset="utf-8">
	<title><?=USERSET["siteName"] . " " . $title?></title>
	<link rel="stylesheet" 			href="../resource/css/<?=USERSET["stylesheet"]?>">
	<?=Style::colorPalette(USERSET["colorsheet"]);?>
	<?php
$meta = new MetaTagger($title, $description, USERSET["author"]);
$meta->changeSetting("name", "theme-color", USERSET["themeColor"]);
$meta->changeSetting("property", "og:site_name", USERSET["siteName"]);
$meta->render(1);
?>
</head>
<body>
	<h3 class="banner right">
		<a href="../"><?=USERSET["siteName"]?></a> - <?=$title?>
	</h3>
	<div id="body">
        <form method="POST" class="config" action="save.php">
            <?=placeSetting()?>
			<hr>
            <input type="submit" value="<?=USERLANG["ac_bSave"]?>">
        </form>
	</div>
	<h4 class="banner left">&copy; <?=date("Y") . " " . USERSET["copyright"]?></h4>
</body>
</html>