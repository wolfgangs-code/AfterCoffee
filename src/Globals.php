<?php
class Globals
{
	const AC_FORK = "AfterCoffee"; // To be changed by significant forks
	const VERSION = "1.0"; //
	const RELEASE = false; // Changes to 'true' when using the release builder
	const FOO	  = "BAR";

	public static function signature()
	{
		// Longform version,	e.g. "AfterCoffee 1.0-dev"
		$head = self::AC_FORK . " " . self::VERSION;
		$tail = self::RELEASE ?: "-dev";
		return $head . $tail;
	}
}
?>