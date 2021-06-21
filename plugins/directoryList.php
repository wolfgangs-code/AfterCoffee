<?php
class directoryList
{
    const version = '4.0';
    private function getFiles($dir = "pages")
    {
		$option = "";
        foreach (glob($dir . "/*.md") as $filePath) {
            $fileName = substr($filePath, strpos($filePath, "/") + 1, -3);
            $md = file_get_contents($filePath);
			$tags = preg_match("/<!--(.* NOINDEX .*)-->/", $md);
            if ($tags && $fileName != $GLOBALS["page"]) continue;
            preg_match('/# (.*?)\n/', $md, $h1);
            $option .= $dir === "pages" ? "\t" : "\t\t";
            $option .= "<option ";
            $title = trim($h1[1] ?? $fileName);
			if ($tags) $title .= " [Hidden]";
            if ($fileName == $GLOBALS["page"]) $option .= "selected ";
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
            $txt .= "\t<optgroup label=\"" . ucfirst(basename($dir)) . "\">{$n}";
            $txt .= $this->getFiles($dir);
            $txt .= "\t</optgroup>{$n}";
        }
        $txt .= "</select>\n";
        print($txt);
    }
}
