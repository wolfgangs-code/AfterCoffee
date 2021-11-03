<?php
class directoryList
{
    const version = '7.1';
    private $indexPath = __DIR__ . "/../pages/index.json";

    private function isHidden($string, $mode = "markdown")
    {
        // Evaluates whether or not a page should be hidden. It uses two modes:
		// Markdown, for directly accessing the string to search, or a filename
		// which allows the Markdown to be loaded from disk.
        switch ($mode) {
            case "filePath":
                $text = file_get_contents(__DIR__ . "/../pages/{$string}.md");
                break;
            case "markdown":
            default:
                $text = $string;
                break;
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

        // Reads all files in the pages directory, and then returns a completed
		// array of them.
        $pages = [];
        $dir = "pages";
        $glob = glob("{$dir}/*", GLOB_ONLYDIR);
        array_push($glob, $dir);
        foreach ($glob as $folder) {
            foreach (glob("{$folder}/*.md") as $filePath) {
                $fileName = substr($filePath, strpos($filePath, "/") + 1, -3);
                $md = file_get_contents($filePath);
                if ($skipHiddenPages && $this->isHidden($md, "markdown")) {continue;}

                // Find the first Header 1 in the Markdown, and when making the
				// array, make that the set title of the page if it's possible.
                // If not, the filename of the Markdown file.
                preg_match('/# (.*?)\n/', $md, $h1);
                $title = trim($h1[1] ?? $fileName);
                if ($folder === $dir) {
                    $pages[$folder][$fileName] = $title;
                } else {
                    $pages[$folder][substr($fileName, strpos($fileName, "/") + 1)] = $title;
                }
            }
        }
        // When skipping hidden pages, try and use the cached version. If there
		// is none, make one for the next load and return that.
        if ($skipHiddenPages && !file_exists($this->indexPath)) $this->buildIndex($pages);
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

        foreach ($pages as $folder => $folderContent)
		{
            // Create <optgroup> for folders
            ($folder === $dir) ?: $option .= "\t<optgroup label=\"" . ucfirst(basename($folder)) . "\">\n\t\t";

            // Create all the pages for each file within folders. This is a BIG
			// chunk of code, but it gets the job done.
            foreach ($folderContent as $fileName => $title) {
                $path = ($folder === $dir) ? $fileName : substr($folder, strpos($folder, "/") + 1) . "/{$fileName}";
                $option .= ($dir === "pages" ? "\t\t" : "\t\t") . "<option ";
                if ($path === $GLOBALS["page"]) $option .= "selected ";
                $hiddenMark = ($this->isHidden($path, "filePath")) ? " " . USERLANG["ac_hidden"] : null;
                $option .= "value=\"{$path}\">{$title}{$hiddenMark}</option>\n\t\t";
            }
        }
        ($folder === $dir) ?: $option .= "\t</optgroup>\n\t\t";
        return $option;
    }

    public function addInfo() //	(Hook)

    {
        $n = "\n\t\t";
        $txt = "{$n}<select name=\"page\" onchange=\"document.getElementsByTagName('article')[0].className = ' pageOut'; location = '?page=' + this.options[this.selectedIndex].value;\" form=\"directoryList\">{$n}";
        $txt .= $this->buildList($this->readFiles(!Auth::isAuthed()));
        $txt .= "</select>\n";
        $txt .= "<noscript><form id=\"directoryList\" action=\".\" method=\"get\"><input type=\"submit\" value=\"Go\"></form></noscript>";
        print($txt);
    }

    public function onSave() //		(Hook)

    {
        // This function overwrites the previous index file with the up-to-date
		// version after saving or deleting any page.
        $this->buildIndex($this->readFiles(true));
    }
}
