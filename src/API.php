<?php
require_once("Router.php");
require_once("Page.php");
require_once("Globals.php");

class API
{
	// Universal Responses
	public $statusCode; //	e.g. "200", "404", "500"
	public $body; //		e.g. A full Markdown file
	private $status; //		e.g. "success", "fail", or "error".
	// Page
	public $dateModified; //A DateTime object

	public function __construct()
	{
		// Everything is OK by default.
		$this->statusCode = 200;
		$this->status = "success";
	}

	public function setStatus($statusCode, $message = null)
	{
		$this->statusCode = $statusCode;
		$category = $statusCode - $statusCode % 100; // e.g. 404 becomes 400
		switch($category)
		{
			case 200:
			case 300:
				$this->status = "success";
				break;
			case 400:
				$this->status = "fail";
				$this->body = $message ?? "An unknown error occured with the client.";
				break;
			case 500:
				$this->status = "error";
				$this->body = $message ?? "An unknown error occured with the server.";
			default:
				$this->statusCode = 500;
				$this->status = "error";
				$this->body = "Invalid HTTP response code '{$statusCode}' attempted.";
				return false;
		}
	}

	// Send out the final response.
	public function respond()
	{
		// HTTP response code
		http_response_code($this->statusCode);

		// Signal that we are responding with JSON.
		header("Content-Type: application/json; charset=utf-8");

		// If any Last-Modified set, use the 'Last-Modified' header.
		if (isset($this->dateModified)) {
			header("Last-Modified: {$this->dateModified->format(DATE_RFC7231)}");
		}

		// Finally, *wink*
		$signature = Globals::signature();
		header("X-Powered-By: {$signature}");

		// Print response body and quit everything. We're done here.
		print(json_encode([
			""
		]));
		exit;
	}
}
?>