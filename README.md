![AfterCoffee](https://wolfgang.space/assets/github/aftercoffee.png)

# AfterCoffee
### A barebones Markdown CMS designed for simple, quick and dirty deployment
#### Features and Perks

- Drag-and-drop installation
  - Comes with all two dependencies
  - Uses relative everything, letting you place it anywhere in your website
- Web Editor
  - Edit and create new pages from the browser
  - Displays markdown guides
  - Plugins can document and demonstrate their custom markdown in the editor
- Simple plugin system
  - Also drag-and-drop, with no installation needed
  - Comes with the customMarkdown plugin
    - Enables ==highlighting==
    - A starting point to make your own custom Markdown rules
- Named after [a song](https://open.spotify.com/track/7EaL8Zt8UAabmP6sQydgx9 "a song") I was listening to at the time

#### Themes
To for colors in the default CSS, a Windows Terminal-like structure is used.
To quickly turn [Windows Terminal theme](https://windowsterminalthemes.dev) JSON into valid CSS, use this Regex Replace-with:

Replace `"  "([a-z]*)": "(#\w*)",?"` with `"--$1: $2;"`

#### Common Problems
If your installation only outputs a blank page, check if `mb_strlen()` is enabled.
If not, Parsedown cannot run, and AfterCoffee cannot run at all. Enable it.
