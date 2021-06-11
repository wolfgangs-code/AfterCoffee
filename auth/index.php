<?php
session_name("AfterCoffeeID");
session_start();

define("USERSET", json_decode(file_get_contents("../meta.json"), true));
$pw = $_POST['pass'];
$rt = $_GET['r'];
$title = "Authentication Required";

if (!include ('auth_code.php')) { // If there is no password file (sorry),
    if (isset($pw)) { // ... But a new password is set!
        $crypt = str_replace("$", "\\$", password_hash($pw, PASSWORD_DEFAULT));
        $txt = "<?php \$auth=\"{$crypt}\"; ?>"; // Compose auth_code.php
        $newPass = fopen("auth_code.php", "w");
        fwrite($newPass, $txt);
        fclose($newPass);
        header("Location: ".$rt);
    }
    $msg = "NO PASSWORD SET. Set your password below.";
} elseif (!isset($pw)) {
    $msg = "Please enter in your password.";
} else {
    $msg = "Incorrect password.";
}

if (isset($_SESSION['authorized']) && $_SESSION['authorized'] == 1) {
    // Already authorized.
    header("Location: ".$rt); // Return to the editor
    exit;
}

if (isset($pw) && password_verify($pw, $auth)) { // Password is correct.
    $_SESSION['authorized'] = 1;
    header("Location: ".$rt); // Go to the editor
    exit;
} else { //Password is INCORRECT or UNSET
    unset($_SESSION['authorized']); // Revoke session and post login form
{?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta property="og:type"        content="website">
	<title><?=$title?></title>
	<meta property="og:site_name"   content="<?=USERSET["siteName"]?>" />
	<meta name="theme-color"        content="<?=USERSET["themeColor"]?>">
	<meta name="generator" 			content="AfterCoffee">
	<meta property="og:title"       content="<?=$title?>" />
	<link rel="stylesheet" href="./resource/css/<?=USERSET["stylesheet"]?>">
	<meta name="viewport" 			content="width=device-width, initial-scale=1">
</head>
<body>
	<h3 style="text-decoration:none;color:var(--black)" class="banner right"><?=USERSET["siteName"] ." - ". $title?></h3>
	<div id="body">
        <h3><?=$msg?></h3>
	    <form method="POST" action="">
            Password <input type="password" name="pass"></input>
            <input type="submit" name="submit" value="Go"></input>
        </form>
	</div>
	<h4 class="banner left">&copy; <?=date("Y") . " " . USERSET["copyright"]?></h4>
</body>
</html>

<?php }}?>
