<?php
#=====================================================================#
# This plugin for AfterCoffee formats dates to be more human-readable #
#=====================================================================#
class dateFormat
{
    const version = '1.0';
    public function changeText($html)
    {
        if (!isset(USERSET["dateFormat"])) {
            $form = "F jS, Y";
        } else {
            $form = USERSET["dateFormat"];
        }
        $html = preg_replace_callback(
            "(\d{4}(-\d{2}){2})",
            function ($m) use ($form) {
                return date($form, strtotime($m[0]));
            },
            $html
        );
        return $html;
    }
}
