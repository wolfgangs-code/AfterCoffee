# Plugin Hooks
**Plugin Hooks** are a vital part of AfterCoffee's plugin system, giving them most, if not all of the functionality plugins need. Hooks are used as to organize and specialize what each plugin is capable of, so they may easily perform a variety of actions. They are meant to allow as much flexibility as possible.

----

### addFooter
**addFooter** is called after the main text is called, below the page right before the end of the `</body>` tag.
This is primarily used to insert JavaScript to have it load after the body.

##### Example (tweetEmbed)
```php
public function addFooter($html) {
	$hasTweet = strpos($html, "twitter-tweet");
	!$hasTweet ?: print("<script async src=\"https://platform.twitter.com/widgets.js\" charset=\"utf-8\"></script>");
}
```

----

### addHead
**addHead** is called at the top of the `<head>` tag, before any other tag is set.
This is primarily used to insert external stylesheets or critical JavaScript.

##### Example (googleAnalytics)
```php
public function addHead() {
	print ("
		<script async src=\"https://www.googletagmanager.com/gtag/js?id=" . $this->tag . "\"></script>
		<script>
	  	window.dataLayer = window.dataLayer || [];
	  	function gtag(){dataLayer.push(arguments);}
	  	gtag('js', new Date());
	  	gtag('config', '" . $this->tag . "');
		</script>
	");
}
```

----

### addInfo
**addInfo** is called within the Info bar within the page, between the _Date Modified_ block and the _Control Panel_ block (if logged in).
This is primarily used to add in-page controls, statuses, or to display relevant metadata.

##### Example (directoryList)
```php
public function addInfo() {
    $n = "\n\t\t";
    $txt = "{$n}<select onchange=\"document.getElementById('body').className = ' pageOut'; location = this.options[this.selectedIndex].value;\">{$n}";    $txt .= $this->getFiles();
    $txt .= "</select>\n";
    print($txt);
}
```

----

### addSetting

**addSetting** allows your plugins to define their configuration through the Settings page. AfterCoffee handles managing and saving them from there.

* ⚠ Be sure to use a _null coalescing operator_[^nco] when parsing settings to account for unset values!

[^nco]: *e.g.,* `$form = USERSET["dateFormat"] ?? "F jS, Y";` sets `$form` to `F jS, Y` if the `dateFormat` setting is unset or does not exist.

##### Example (googleAnalytics)
```php
public function addSetting() {
	$settings = ["gtag"];
	return $settings;
}
```

----

### changeText
**changeText** modifies **the HTML** compiled by ParsedownExtra from the Markdown file source. You must return your changed HTML.

* ℹ You are given the HTML _after_ markdown, you do not receive raw markdown. Expect HTML tags galore.
* ⚠ _Remember:_ You **must** return the modified HTML/text

This is the 'main' use of plugins, due to it directly modifying text.

##### Example (dateFormat)
```php
public function changeText($html) {
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
```

----

### editorGuide
**editorGuide** allows you to give hints in the Editor as to how to use your plugins. editorGuide is called within a `<ul>` tag, with each plugin being automatically placed within its own `<li>` tag.

##### Example (vanillaTilt)
```php
public function editorGuide() {
	$guide = "<b>vanilla-tilt:</b> [card]image.png[/card]";
	return $guide;
}
```

----

### onSave
**onSave** runs whenever the Editor publishes or saves a page file.
This is best used to do things such as caching, metadata generation, etc.

##### Example (directoryList)
```php
public function onSave() {
    # Delete the directory index so it may be rebuilt automatically
    unlink($this->indexPath);
}
```
