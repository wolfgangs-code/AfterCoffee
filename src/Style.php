<?php
class Style
{
    public static function colorPalette($paletteName = "default.palette")
    {
        if ($paletteName == "none") {return;} // No palette, no function
        $bufferArray = ["light" => "", "dark" => ""]; // Init. Color buffer
        $paletteColors = json_decode(file_get_contents(__DIR__ . "/../resource/color/{$paletteName}"), true);
        foreach (["light", "dark"] as $mode) {
			if (!isset($paletteColors[$mode])) {continue;}
			// For both light and dark modes, convert the array to CSS variables
            foreach ($paletteColors[$mode] as $color => $value) {
                $bufferArray[$mode] .= "--{$color}:{$value};";
            }
        }
        if (isset($paletteColors["dark"])) {
			// If there exits a dark mode in the palette, include it in full.
			// If not, include nothing about it at all to save space.
            $dark = "@media (prefers-color-scheme: dark){:root{{$bufferArray["dark"]}}";
        } else {
            $dark = null;
        }
        return "<style>:root{{$bufferArray["light"]}}{$dark}</style>\n";
    }

    public static function linkStylesheet($location, $external = false)
    {
        $global = $external ?: "./resource/css/";
        return "<link rel='stylesheet' href='{$global}{$location}'>\n";
    }
}
