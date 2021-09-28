![AfterCoffee](https://wolfgang.space/assets/github/aftercoffee.png)

# AfterCoffee
![MIT License](https://img.shields.io/github/license/wolfgang-degroot/AfterCoffee) ![Code Size](https://img.shields.io/github/languages/code-size/wolfgang-degroot/AfterCoffee) ![Release Version](https://img.shields.io/github/v/release/wolfgang-degroot/AfterCoffee)
## A fast, low-power, scriptless blogging/notetaking CMS ☕
It needs no formal installation and is self-contained- Letting you place as many installations you want in as many different folders as you need.

It has a pleasantly low footprint in both size and bandwidth, making hosting on slow networks easy. *A page without images will use just about **3.5 KB** of bandwidth.¹*
AfterCoffee stores everything locally and uses no database- guaranteeing portability if you need to move your build around.

###### 1: Using default plugins and a 1000 word *Lorem ipsum*
### Features

- Drag-and-Drop Installation
  - Works out-of-the-box, plugins included. Updating your build and installing new plugins is as simple as overwriting.
  - Uses relative everything, letting you place it anywhere on your website, in as many different directories as you want.
- Lightweight Web Editor
  - Edit, create, and delete existing and new pages, using native HTML form editing for increased accessibility.
  - Works using Markdown, Markdown Extra, and placing HTML tags as you please.
  - Plugins can document and demonstrate their custom markdown in the editor
- Translation and i18n
  - Simple translation format, allowing any custom one to be made with ease
  - **Default Languages:**
  - English
  - Japanese
  - Russian
  - Simplified Chinese
- Small but Ready to Grow
  - AfterCoffee only comes with the bare essentials, allowing full control to how your build performs
  - Plugins can extend features to infinity and beyond
- SEO Ready
  - Full, automatically generated meta tags
  - [Open Graph](https://ogp.me/) ready
  - Includes image thumbnail support
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

Replace `"([a-z]*)": "(#\w*)",?` with `--$1: $2;\n`

#### Potential Issues
Remember to give AfterCoffee appropriate write permissions, or else critical functions such as Auth and the Editor will not function.

###### *Coffee Bag model by Zdenko Roman*
