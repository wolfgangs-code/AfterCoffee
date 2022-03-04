<?php
class Router
{
    public $route; //	e.g. "/page/folder/default-index"
	public $base; //	e.g. "page"
    public $path; //	e.g. ["folder",	"default-index"]
	public $method; //	e.g. "GET"

    public function __construct()
    {
        // Prepare route
        $this->route = key($_GET);

        // Split the endpoint into an array of paths
        $this->path = explode("/", $this->route);

		// Remove the blank first value
        array_shift($this->path);

		// Set the base variable and remove it from the path
		$this->base = $this->path[0];
        array_shift($this->path);

		// Set method
		$this->method = $_SERVER['REQUEST_METHOD'];
    }
}