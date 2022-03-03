<?php
class Router
{
    public $endpoint; //    e.g. "/page/default-index"
    public $paths; //       e.g. ["page", "default-index"]

    public function __construct()
    {
        // Prepare Endpoint
        $this->endpoint = key($_GET);
        // Split the endpoint into an array of paths
        $this->paths = explode("/", $this->endpoint);
        array_shift($this->paths); // Remove the blank first value
        print_r($this->paths);
    }
}
$Router = new Router();
