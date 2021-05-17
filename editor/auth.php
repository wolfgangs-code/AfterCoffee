<?php
session_start();

$pw = $_POST['pass'];

if (!include ('auth_code.php')) { // If there is no password file (sorry),
    if (isset($pw)) { // ... But a new password is set!
        $crypt = password_hash($pw, PASSWORD_DEFAULT);
        $crypt = str_replace("$", "\\$", $crypt);
        $txt = "<?php \$auth=\"{$crypt}\"; ?>"; // Compose auth_code.php
        $newPass = fopen("auth_code.php", "w");
        fwrite($newPass, $txt);
        fclose($newPass);
        header('Location: ./');
    }
    $msg = "NO PASSWORD SET. Set your password below.";
} elseif (!isset($pw)) {
    $msg = "Please enter in your password.";
} else {
    $msg = "Incorrect password.";
}

if (isset($_SESSION['authorized']) && $_SESSION['authorized'] == 1) {
    // Already authorized.
    header('Location: ./'); // Return to the editor
    exit;
}

if (isset($pw) && password_verify($pw, $auth)) { // Password is correct.
    $_SESSION['authorized'] = 1;
    header('Location: ./'); // Go to the editor
    exit;
} else { //Password is INCORRECT or UNSET
    unset($_SESSION['authorized']); // Revoke session and post login form
    echo $msg;
    {?>
		<form method="POST" action="">
    	Password <input type="password" name="pass"></input><br/>
    	<input type="submit" name="submit" value="Go"></input>
    	</form>
    <?php }

}
?>
