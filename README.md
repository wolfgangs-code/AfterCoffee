![AfterCoffee](https://wolfgang.space/assets/github/aftercoffee.png)

# AfterCoffee
![MIT License](https://img.shields.io/github/license/wolfgang-degroot/AfterCoffee) ![Code Size](https://img.shields.io/github/languages/code-size/wolfgang-degroot/AfterCoffee) ![Release Version](https://img.shields.io/github/v/release/wolfgang-degroot/AfterCoffee)
## A fast, low-power, scriptless flat-file CMS ☕
It needs no installation and is self-contained- Letting you put as many installations you want in as many different folders as you need.

It has a pleasantly low footprint in both size and bandwidth, making hosting on slow networks easy. *A page without images will use just about **10 KB** of bandwidth.*
Being flat-file, means it uses no database- guaranteeing portability if you need to move your build around.

### Features

- Drag-and-Drop Installation
  - Works out-of-the-box, plugins included. Updating your build and installing new plugins is as simple as overwriting.
  - Uses relative everything, letting you place it anywhere on your website, in as many different directories as you want.
- Lightweight Web Editor
  - Edit, create, and delete existing and new pages, using native HTML form editing for increased accessibility.
  - Works using Markdown, Markdown Extra, and placing HTML tags as you please.
  - Plugins can document and demonstrate their custom markdown in the editor
- Small but Ready to Grow
  - AfterCoffee only comes with the bare essentials, allowing full control to how your build performs
  - Plugins can extend features to infinity and beyond
- SEO Ready
  - Full, automatically generated meta tags
  - [Open Graph](https://ogp.me/) ready
  - Includes image thumbail support
- Page Indexing
  - Shrinks disk I/O
  - Simple JSON structure
  - Generates automatically
- Natively Hidden/Unlisted Pages
  - Does not show itself in the directory
  - `noindex` for robots to skip indexing
- Simple drag-and-drop plugin system
  - **Default Plugins:**
  - customMarkdown
    - Enables ==highlighting==
    - A starting point to make your own custom Markdown rules
  - dateFormat
    - Formats YYYY-MM-DD dates to human-readable formats
    - Formatted based on the `dateFormat` setting
  - directoryList
    - Adds a dropdown menu of all posts to your pages
- Named after [a song](https://open.spotify.com/track/7EaL8Zt8UAabmP6sQydgx9 "a song") I was listening to at the time

### Color Pallete Swapping
To tune colors in the default CSS, a Windows Terminal-like structure is used.
To quickly turn [Windows Terminal theme](https://windowsterminalthemes.dev) JSON into valid CSS, use this Regex Replace-with:

Replace `"  "([a-z]*)": "(#\w*)",?"` with `"--$1: $2;"`

#### Potential Issues
If your installation only outputs a blank page, check if `mb_strlen()` is enabled.
If not, Parsedown cannot run, and AfterCoffee cannot run at all. Enable it.

If you cannot save new pages, create folders, or change settings- Recursively allow all folders to have the write permission, or `0777`.

#### Credits
*Coffee Bag model by Zdenko Roman*
