<?php
class directoryList
{
    const version = '5.0';
    private function getFiles($dir = "pages")
    {
        $pages = [];
        $option = "";
        $indexPath = __DIR__."/../{$dir}/index.json";
        # Indexer
        if (!file_exists($indexPath)) {
            foreach (glob($dir . "/*.md") as $filePath) {
                $fileName = substr($filePath, strpos($filePath, "/") + 1, -3);
                $md = file_get_contents($filePath);
                $tags = preg_match("/<!--(.* NOINDEX .*)-->/", $md);
                if ($tags && $fileName) {
                    continue;
                }

                preg_match('/# (.*?)\n/', $md, $h1);
                $title = trim($h1[1] ?? $fileName);
                $pages[$fileName] = $title;
            }
			$indexJSON = fopen($indexPath, 'w');
			fwrite($indexJSON, json_encode($pages));
			fclose($indexJSON);
        } else {
			$indexJSON = file_get_contents($indexPath);
			$pages = json_decode($indexJSON, true);
		}
		// $pages[$GLOBALS["page"]] = $pages[$GLOBALS["page"]] ?: "[Hidden]";

        # Index builder
        foreach ($pages as $fileName => $title) {
            $option .= $dir === "pages" ? "\t" : "\t\t";
            $option .= "<option ";
            if (empty($pages[$fileName])) {
                $title .= " " . USERLANG["ac_hidden"];
            }

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
            $txt .= "\t<optgroup label=\"" . ucfirst(basename($dir)) . "\">{$n}";
            $txt .= $this->getFiles($dir);
            $txt .= "\t</optgroup>{$n}";
        }
        $txt .= "</select>\n";
        print($txt);
    }
}
