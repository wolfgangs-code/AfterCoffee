<?php
#=====================================================================#
# This plugin for AfterCoffee formats dates to be more human-readable #
#=====================================================================#
class dateFormat
{
    const version = '6.0';
    public function changeText($html)
    {
        $form = USERSET["dateFormat"] ?? "F jS, Y";
        $html = preg_replace_callback(
			// Accounts for shortened ISO dates (2021-09-09),
			// But also accepts full-length ISO 8601 and RFC 3339/ATOM formats as well.
			// Apologies for the nightmare.
            "(\d{4}(-\d+)+(T(\d+:)+\d+\+(\d+:*\d+))*)",
            function ($m) use ($form) {
                return date($form, strtotime($m[0]));
            },
            $html
        );
        return $html;
    }
}
