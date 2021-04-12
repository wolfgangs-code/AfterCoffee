<?php
class directoryList
{
    const version = '1.1';
    private function getFiles($dir)
    {
        $folder = $dir . "/*.md";
        foreach (glob($folder) as $filePath) {
            $fileName = substr($filePath, strpos($filePath, "/") + 1, -3);
            $md = file_get_contents($filePath);
            preg_match('/# (.*?)\n/', $md, $h1);
            $option .= "\t<option ";
            if (!isset($h1[1])) {
                $title = $fileName;
            } else {
                $title = $h1[1];
            }
            if ($fileName == $GLOBALS["page"]) {
                $option .= "selected ";
            }
            $option .= "value=\"?page=" . $fileName . "\">" . $title . "</option>";
        }
        return $option;
    }
    public function addInfo()
    {
        $txt .= "<select onchange=\"location = this.options[this.selectedIndex].value;\">";
        $txt .= $this->getFiles("pages");
        foreach (glob("pages/*", GLOB_ONLYDIR) as $dir) {
            $txt .= "<optgroup label=\"" . basename($dir) . "\">";
            $txt .= $this->getFiles($dir);
            $txt .= "</optgroup>";
        }
        $txt .= "</select>";
        print($txt);
    }
}
