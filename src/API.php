<?php
require_once("Router.php");
require_once("Page.php");
require_once("Globals.php");

class API
{
	// Universal Responses
	public $statusCode; //	e.g. "200", "404", "500"
	public $contentType; //	e.g. "text/html", "application/json"
	public $body; //		e.g. A full Markdown file
	// Page
	public $dateModified; //A DateTime object

	public function __construct()
	{
		$this->statusCode = 200;
		$this->contentType = "application/json";
	}

	// Send out the final response.
	public function respond()
	{
		// HTTP response code
		http_response_code($this->statusCode);

		// Use the 'Content-Type' header.
		#header("Content-Type: {$this->contentType}; ; charset=utf-8");

		// If any Last-Modified set, use the 'Last-Modified' header.
		if (isset($this->dateModified)) {
			header("Last-Modified: {$this->dateModified->format(DATE_RFC7231)}");
		}

		// Finally, *wink*
		$signature = Globals::signature();
		header("X-Powered-By: {$signature}");
		print($this->body);
	}
}
?>