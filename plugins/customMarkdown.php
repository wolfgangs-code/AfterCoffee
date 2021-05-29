<?php
	#===========================================================#
	# This plugin for AfterCoffee adds custom Markdown features #
	# on top of what ParsedownExtra provides. To work off of    #
	# this, You should copy this example and make your own,     #
	# As updates may overwrite this file as it is default.      #
	#===========================================================#
class customMarkdown {
	const version = '2.0';
	function changeText($html) {
		# Allows highlighting text by surrounding it with double-equals,
		# e.g. ==I'm highlighted==
		$html = preg_replace("/(==)(.*?)(==)/", "<span class=mark>$2</mark>", $html);
		return $html;
	}
	function editorGuide() {
		$guide = "<b>AfterCoffee Custom Markdown:</b> ==highlighted text==";
		return $guide;
	}
}
?>