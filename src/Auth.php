<?php
class Auth
{
	function __construct()
	{
		$this->continueSession();
	}

	// Authentication

    public function isAuthed()
    {
        return (isset($_SESSION["authorized"]) && $_SESSION["authorized"] = true);
    }

    public function demandAuth()
    {
        if (!$this->isAuthed()) {
			// TODO: IMPROVE LOCATION
            header("Location: ../auth/?r=" . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function continueSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name("AfterCoffeeID");
            session_start();
            return true; // true:	Session started!
        } else {
            return false; // false: Session already started.
        }
    }

    public function unAuth()
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
$Auth = new Auth();