<?php
class directoryList
{
    const version = '2.0';
    private function getFiles($dir = "pages")
    {
        foreach (glob($dir . "/*.md") as $filePath) {
            $fileName = substr($filePath, strpos($filePath, "/") + 1, -3);
            $md = file_get_contents($filePath);
            if (preg_match("/<!--(.* NOINDEX .*)-->/", $md)) {
                continue;
            }
            preg_match('/# (.*?)\n/', $md, $h1);
            $option .= $dir === "pages" ? "\t" : "\t\t";
            $option .= "<option ";
            $title = trim($h1[1] ?? $fileName);
            if ($fileName == $GLOBALS["page"]) {
                $option .= "selected ";
            }
            $option .= "value=\"?page={$fileName}\">{$title}</option>\n\t\t";
        }
        return $option;
    }
    public function addInfo()
    {
        $n = "\n\t\t";
        $txt = "{$n}<select onchange=\"location = this.options[this.selectedIndex].value;\">{$n}";
        $txt .= $this->getFiles();
        foreach (glob("pages/*", GLOB_ONLYDIR) as $dir) {
            $txt .= "\t<optgroup label=\"" . basename($dir) . "\">{$n}";
            $txt .= $this->getFiles($dir);
            $txt .= "\t</optgroup>{$n}";
        }
        $txt .= "</select>\n";
        print($txt);
    }
}
