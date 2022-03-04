<?php
require_once("Auth.php");
require_once("PluginManager.php");
require_once(__DIR__ . "/../lib/Parsedown.php");
require_once(__DIR__ . "/../lib/ParsedownExtra.php");
class Page
{
	public $exists; //	true or false.
	public $name; //	"page" OR "folder/page"
	public $path; //	"/var/www/AfterCoffee/pages/folder/page.md"
	private $md; //		A full Markdown file string.
	private $dm; //		DateTime object.

	public function __construct($path = ["default-index"])
    {
		// Set the name
		$this->name = rtrim(implode("/", $path), "/");
		// Set the path (canonical)
		$this->path = substr(__DIR__, 0, -3) . "pages/{$this->name}.md";
		$this->exists = file_exists($this->path);
    }

	// Returns the raw markdown of a page.
	public function getMarkdown()
	{
		if (isset($this->md)) {
			return $this->md;
		} else {
			$path = $this->path;
			$this->md = $this->exists? file_get_contents($path): false;
			return $this->md;
		}
	}

	// Returns the rendered HTML of a page.
	public function getHTML($disablePlugins = false)
	{
		$Parsedown = new ParsedownExtra();
		//
		$markdown = $this->getMarkdown();
		// Return false if file does not exist.
		if ($markdown === false) {
			return false;
		} else {
			$rawHTML = $Parsedown->setBreaksEnabled(true)->text($markdown);
			// Return raw until PluginManager is rewritten.
			return $rawHTML;
		}
	}

	// Returns a DateTime object of when the page was last modified.
	public function getDateModified()
	{
		if (isset($this->dm)) {
			// If we've already known the date modified.
			return $this->dm;
		} elseif ($this->exists) {
			// If we need to find a date for an existing file.
			$this->dm = new DateTime(strtotime(filemtime($this->path)));
			return $this->dm;
		} else {
			// Default to now for non-existant files.
			$this->dm = new DateTime("now");
			return $this->dm;
		}
	}

}
