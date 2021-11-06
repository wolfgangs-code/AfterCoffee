<?php
class MetaTagger
{
    private $tags = [];
    public function __construct($title = null, $desc = null, $author = null)
    {
        $author = $author ?? USERSET["author"];
        // Names
        $this->changeSetting("name", "author",		$author);
        $this->changeSetting("name", "description",	$desc);
        $this->changeSetting("name", "generator",	"AfterCoffee");
        $this->changeSetting("name", "viewport",	"width=device-width, initial-scale=1");
        // Properties
        $this->changeSetting("property", "article:author",	$author);
        $this->changeSetting("property", "og:description",	$desc);
        $this->changeSetting("property", "og:title",		$title);
        $this->changeSetting("property", "og:type", 		"website");
    }
    public function changeSetting($space, $key, $value = null)
    {
        $this->tags[$space][$key] = $value;
        if ($value === null) {
            unset($this->tags[$space][$key]);
        }

    }
    public function render($tab = 0)
    {
        $tags = $this->tags;
        ksort($tags);
		$first = true;
        foreach ($tags as $space) {
			ksort($space);
            foreach ($space as $key => $value) {
				$type = key($tags);
                echo str_repeat($first ? null : "\t", $tab);
                echo "<meta {$type}=\"{$key}\"\tcontent=\"{$value}\">\n";
				$first = false;
            }
			next($tags);
        }
    }
}
