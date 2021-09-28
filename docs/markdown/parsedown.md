# Changes made to Parsedown for AfterCoffee
For best use, AfterCoffee uses a modified version of Parsedown.[^rusev]
All changes made to the original (1.7.4) are listed below:

## All images lazy-load by default
**⚠ For *most* browsers, this only works with Javascript enabled!** This is an anti-tracking feature- See MDN for more information why. This is not something AfterCoffee can change.

## Fewer dependencies
`mb_strlen`, a non-default PHP module, has been switched to use built-in functions.
This also brings a miniscule performance improvement, but that is not the focus of this change.

## URI Scheme Safe Whitelist Changes
* ***Additions***
	* ➕ gopher
	* ➕ ipfs
	* ➕ magnet
	* ➕ tel
	* ➕ bitcoin
	* ➕ bitcoincash
	* ➕ ethereum
* *Removals*
	* ➖ irc
	* ➖ ircs
	* ➖ git
	* ➖ ssh
	* ➖ news
	* ➖ steam

## Misc.
* Code length reduced by nearly 200 lines

[^rusev]: Parsedown is created by **Emanuil Rusev**