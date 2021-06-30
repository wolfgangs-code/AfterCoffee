<?php
class directoryList
{
    const version = '5.2';
    private function getFiles($dir = "pages")
    {
        $pages = [];
        $option = "";
        $indexPath = __DIR__ . "/../pages/index.json";

        #=========#
        # Indexer #
        #=========#

        # Check if an index isn't already built
        if (!file_exists($indexPath)) {
            $glob = glob("{$dir}/*", GLOB_ONLYDIR);
            array_push($glob, $dir);
            foreach ($glob as $folder) {
                foreach (glob("{$folder}/*.md") as $filePath) {
                    $fileName = substr($filePath, strpos($filePath, "/") + 1, -3);
                    $md = file_get_contents($filePath);
                    $tags = preg_match("/<!--(.* NOINDEX .*)-->/", $md);
                    if ($tags && $fileName) {
                        continue;
                    }

                    preg_match('/# (.*?)\n/', $md, $h1);
                    $title = trim($h1[1] ?? $fileName);
                    if ($folder === $dir) {
                        $pages[$folder][$fileName] = $title;
                    } else {
                        $pages[$folder][substr($fileName, strpos($fileName, "/") + 1)] = $title;
                    }

                }}
            $indexJSON = fopen($indexPath, 'w');
            fwrite($indexJSON, json_encode($pages));
            fclose($indexJSON);
        } else {
            $indexJSON = file_get_contents($indexPath);
            $pages = json_decode($indexJSON, true);
        }

        #===================#
        # Directory builder #
        #===================#
        $isVisible = false;
        arsort($pages);
        foreach ($pages as $folder => $content) {
            # Uncommenting the below line will sort each folder's directory by title
            // asort($content);

            # Subgroup subfolders 1/2
            ($folder === $dir) ?: $option .= "\t<optgroup label=\"" . ucfirst(basename($folder)) . "\">{$n}";

            # Listing constructor
            foreach ($content as $fileName => $title) {
                $option .= $dir === "pages" ? "\t" : "\t\t";
                $option .= "<option ";

                # Select the listing for the current page
                if ($fileName == $GLOBALS["page"]) {
                    $option .= "selected ";
                    $isVisible = true;
                }

                # Subgroup subfolders 2/2
                if ($folder === $dir) {
                    $option .= "value=\"?page={$fileName}\">{$title}</option>\n\t\t";
                } else {
                    $folder = substr($folder, strpos($folder, "/") + 1);
                    $option .= "value=\"?page={$folder}/{$fileName}\">{$title}</option>\n\t\t";
                    $option .= "\t</optgroup>{$n}";
                }
            }

        }
        ($isVisible) ?: $option .= "<option selected>" . $GLOBALS["page"] ." ". USERLANG["ac_hidden"] . "</option>\n";
        return $option;
    }
    public function addInfo()
    {
        $n = "\n\t\t";
        $txt = "{$n}<select onchange=\"location = this.options[this.selectedIndex].value;\">{$n}";
        $txt .= $this->getFiles();
        $txt .= "</select>\n";
        print($txt);
    }
}
