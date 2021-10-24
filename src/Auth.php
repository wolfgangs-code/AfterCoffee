<?php
class Auth
{
	// Authentication

    public static function isAuthed()
    {
        return (isset($_SESSION["authorized"]) && $_SESSION["authorized"] = true);
    }

    public static function demandAuth()
    {
        if (!$this->isAuthed()) {
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
        if ($this->isAuthed()) {
            $_SESSION["authorized"] = false;
			session_destroy();
			return true; //	true:	Successfully unauthenticated!
        } else {
            return false; //false:	Not authenticated already.
        }
    }
}
Auth::continueSession();