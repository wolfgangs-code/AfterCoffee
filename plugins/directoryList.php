<?php
class directoryList
{
    const version = '8.0';
    private $indexPath = __DIR__ . "/../pages/index.json";

    private function isHidden($string, $isMarkdownNotFilePath = true)
    {
		$filePath = __DIR__ . "/../pages/{$string}.md";
        // Evaluates whether or not a page should be hidden. It uses two modes:
        // Markdown, for directly accessing the string to search, or a filename
        // which allows the Markdown to be loaded from disk.
		if ($isMarkdownNotFilePath) {
			$text = $string;
		} elseif (file_exists($filePath)) {
			// Checks if the file exists before getting.
			$text = file_get_contents($filePath);
		} else {
			// If this is met, index.json is either corrupted or outdated.
			// Make a new one and get out of here (Could cause problems?)
			$this->buildIndex($this->readFiles(true));
			return false;
		}
        return preg_match("/<!--(.* NOINDEX .*)-->/", $text);
    }

    private function readFiles($skipHiddenPages = true)
    {
        // If we are skipping pages, and an index is already build, we load and
        // then return that instead of performing a lot of reads onto the disk.
        if ($skipHiddenPages && file_exists($this->indexPath)) {
            return json_decode(file_get_contents($this->indexPath), true);
        }

        // Reads all files in the pages directory, and then returns an array.
        $pages = [];
        $dir = "pages";
        $glob = glob("{$dir}/*", GLOB_ONLYDIR);
        array_push($glob, $dir);
        foreach ($glob as $folder) {
            foreach (glob("{$folder}/*.md") as $filePath) {
                $fileName = substr($filePath, strpos($filePath, "/") + 1, -3);
                $md = file_get_contents($filePath);
                if ($skipHiddenPages && $this->isHidden($md, true)) {continue;}

                // Find the first H1 in the Markdown, and when making the array
                // use it as the title.
                preg_match('/# (.*?)\n/', $md, $h1);
                $title = trim($h1[1] ?? $fileName);
                if ($folder === $dir) {
                    $pages[$folder][$fileName] = $title;
                } else {
                    $pages[$folder][substr($fileName, strpos($fileName, "/") + 1)] = $title;
                }
            }
        }

        if ($skipHiddenPages && !file_exists($this->indexPath)) {
            $this->buildIndex($pages);
        }

        return $pages;
    }

    private function buildIndex($pages)
    {
        // Creates and saves a JSON file of the page index, with all the hidden
        // pages removed before writing the file to disk.
        $indexJSON = fopen($this->indexPath, 'w');
        fwrite($indexJSON, json_encode($pages));
        fclose($indexJSON);
    }

    private function buildList($pages, $dir = "pages")
    {
        $option = "";
        arsort($pages);
        foreach ($pages as $folder => $folderContent) {
			$explode = explode("/", $GLOBALS["page"]);
            if (isset($pages[$folder."/".$explode[0]][@$explode[1]])) {
                $pages[$folder."/".$explode[0]][$explode[1]] = USERLANG["ac_hidden"];
            }

            // Create <optgroup> for folders
            ($folder === $dir) ?: $option .= "\t\t<optgroup label=\"" . ucfirst(basename($folder)) . "\">\n\t\t";

            // Create all the pages for each file within folders. This is a BIG
            // chunk of code, but it gets the job done.
            foreach ($folderContent as $fileName => $title) {
                $path = ($folder === $dir) ? $fileName : substr($folder, strpos($folder, "/") + 1) . "/{$fileName}";
                $option .= (strpos($path, "/") ? "\t\t\t" : "\t\t") . "<option ";
                if ($path === $GLOBALS["page"]) {
                    $option .= "selected ";
                }
                $hiddenMark = ($this->isHidden($path, false)) ? " " . USERLANG["ac_hidden"] : null;
                $option .= "value=\"{$path}\">{$title}{$hiddenMark}</option>\n\t\t";
            }
        }
        ($folder === $dir) ?: $option .= "\t\t</optgroup>\n\t\t";
        return $option;
    }

    public function addInfo()
    {
		$n = "\n\t\t";
        $txt = "{$n}\t<select name=\"page\" onchange=\"document.getElementsByTagName('article')[0].className = ' pageOut'; location = '?page=' + this.options[this.selectedIndex].value;\" form=\"directoryList\">{$n}";
        $txt .= $this->buildList($this->readFiles(!Auth::isAuthed())) . "\t</select>\n";
        $txt .= "\t\t\t<noscript><form id=\"directoryList\" action=\".\" method=\"get\"><input type=\"submit\" value=\"Go\"></form></noscript>{$n}";
        print($txt);
    }

    public function onSave()
    {
        $this->buildIndex($this->readFiles(true));
    }
}
