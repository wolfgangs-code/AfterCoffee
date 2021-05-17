<?php
session_start();

if (!include ('auth_code.php')) { // If there is no password file (sorry),
    if (isset($_POST['pass'])) { // ... But a new password is set!
        $txt = "<?php \$auth=\"{$_POST['pass']}\"; ?>"; // Compose auth_code.php
        $newPass = fopen("auth_code.php", "w");
        fwrite($newPass, $txt);
        fclose($newPass);
        header('Location: ./');
    }
    echo "NO PASSWORD SET. Set your password below.";
}

if (isset($_SESSION['authorized']) && $_SESSION['authorized'] == 1) {
    // Already authorized.
    header('Location: ./'); // Return to the editor
    exit;
}

if (isset($_POST['pass']) && $_POST['pass'] == $auth) { // Password is correct.
    $_SESSION['authorized'] = 1;
    header('Location: ./'); // Go to the editor
    exit;
} else { //Password is INCORRECT
    unset($_SESSION['authorized']); // Revoke session and post login form
    {?>
		<form method="POST" action="">
    	Pass <input type="password" name="pass"></input><br/>
    	<input type="submit" name="submit" value="Go"></input>
    	</form>
    <?php }

}
?>
