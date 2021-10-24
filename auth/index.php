<?php
require_once __DIR__."/../src/Auth.php";
require_once __DIR__."/../src/MetaTagger.php";
require_once __DIR__."/../src/Style.php";

require_once '../config/userset.php';
require_once '../config/lang.php';

$pw = $_POST['pass'];
$rt = $_GET['r'] ?? "../";
$title = USERLANG["auth"]["required"];

define("AUTH", true);

if (!include ('auth_code.php')) { // If there is no password file (sorry),
    if (isset($pw)) { // ... But a new password is set!
        $crypt = str_replace("$", "\\$", password_hash($pw, PASSWORD_DEFAULT));
        $txt = "<?php require 'hidepage.php';\n\$auth=\"{$crypt}\"; ?>"; // Compose auth_code.php
        $newPass = fopen("auth_code.php", "w");
        fwrite($newPass, $txt);
        fclose($newPass);
        header("Location: ".$rt);
    }
    $msg = USERLANG["auth"]["setPass"];
} elseif (!isset($pw)) {
    $msg = USERLANG["auth"]["enterPass"];
} else {
    $msg = USERLANG["auth"]["wrongPass"];
}

if (Auth::isAuthed()) {
    header("Location: ".$rt); // Return to the editor
    exit;
} else {
	http_response_code(401);
}


if (isset($pw) && password_verify($pw, $auth)) { // Password is correct.
    $_SESSION['authorized'] = 1;
    header("Location: ".$rt); // Go to the editor
    exit;
} else { //Password is INCORRECT or UNSET
    unset($_SESSION['authorized']); // Revoke session and post login form
{?>

<!DOCTYPE HTML>
<html lang="<?=USERSET["lang"]?>">
<head>
	<meta charset="utf-8">
	<title><?=$title?></title>
	<link rel="stylesheet" 			href="../resource/css/<?=USERSET["stylesheet"]?>">
	<?=Style::colorPalette();?>
	<meta name="viewport" 			content="width=device-width, initial-scale=1">
	<?php
		$meta = new MetaTagger($title, $description, USERSET["author"]);
		$meta->changeSetting("name",	"robots",		"noindex");
		$meta->changeSetting("name",	"theme-color",	USERSET["themeColor"]);
		$meta->changeSetting("property","og:site_name",	USERSET["siteName"]);
		$meta->render(1);
	?>
</head>
<body>
	<h3 class="banner right"><?=USERSET["siteName"] ." - ". $title?></h3>
	<div id="body">
        <h3><?=$msg?></h3>
	    <form method="POST" action="">
		<?=USERLANG["auth"]["pass"]?> <input type="password" name="pass">
            <input type="submit" name="submit" value="Go">
        </form>
	</div>
	<h4 class="banner left">&copy; <?=date("Y") . " " . USERSET["copyright"]?></h4>
</body>
</html>

<?php }}?>
