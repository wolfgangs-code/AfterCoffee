<?php
include_once(__DIR__ . "/../auth/auth_code.php");
class Auth
{
	private static function keyCheck($key)
	{

	}

    public static function isAuthed()
    {
		$webAuth = isset($_SESSION["authorized"]) && $_SESSION["authorized"];
		$APIAuth = isset($_SERVER["PHP_AUTH_USER"]) && self::keyCheck($_SERVER["PHP_AUTH_PW"]);
		return $webAuth || $APIAuth;
    }

    public static function demandAuth()
    {
        if (!self::isAuthed()) {
			// TODO: IMPROVE LOCATION
            header("Location: ../auth/?r=" . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public static function continueSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name("AfterCoffeeID");
            session_start();
            return true; // true:	Session started!
        } else {
            return false; // false: Session already started.
        }
    }

    public static function unAuth()
    {
        if (self::isAuthed()) {
            $_SESSION["authorized"] = false;
			session_destroy();
			return true; //	true:	Successfully unauthenticated!
        } else {
            return false; //false:	Not authenticated already.
        }
    }
}
Auth::continueSession();