<?php
require_once("Auth.php");
require_once("PluginManager.php");
require_once(__DIR__ . "/../lib/Parsedown.php");
require_once(__DIR__ . "/../lib/ParsedownExtra.php");
class Page
{
	public $name; // "page" OR "folder/page"
	public $path; // "/var/www/AfterCoffee/pages/folder/page.md"

	public function __construct($path = ["default-index"])
    {
		// Set the name
		$this->name = rtrim(implode("/", $path), "/");
		// Set the path (canonical)
		$this->path = substr(__DIR__, 0, -3) . "pages/{$this->name}.md";
    }

	// Returns the raw markdown of a page.
	public function getMarkdown()
	{
		$path = $this->path;
		return file_exists($path)? file_get_contents($path): false;
	}

	public function getHTML($disablePlugins = false)
	{
		$Parsedown = new ParsedownExtra();
		//
		$markdown = $this->getMarkdown();
		// Return false if file does not exist
		if ($markdown === false) {
			return false;
		} else {
			$rawHTML = $Parsedown->setBreaksEnabled(true)->text($markdown);
			// Return raw until PluginManager is rewritten
			return $rawHTML;
		}
	}

}
