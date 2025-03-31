# parsedown-extension_toc-toctop
This is an extension to an extension of an extension of parsedown - specifically to [parsedown-extension_table-of-contents](https://github.com/KEINOS/parsedown-extension_table-of-contents) 

## Installation, Dependencies and Misc
This is an extension to [parsedown-extension_table-of-contents](https://github.com/KEINOS/parsedown-extension_table-of-contents) which is itself an extension to [Markdown-extra](https://michelf.ca/projects/php-markdown/extra/) which is an extension to [parsedown](https://github.com/erusev/parsedown)

So you need to ensure you have each of these. I don't have this set up for composer etc, so this is going to be the "raw" install assuming you go to the relevant git hubs and grab their main PHP files
### get the files
```bash
wget https://github.com/erusev/parsedown/blob/master/Parsedown.php
wget https://github.com/erusev/parsedown-extra/blob/master/ParsedownExtra.php
wget https://github.com/KEINOS/parsedown-extension_table-of-contents/blob/master/Extension.php
wget https://github.com/DigitalSorceress/parsedown-extension_toc-toctop/blob/main/ParsedownExtraTocTocTop.php
```

### Include each of these in your php 
```php
<?php
require_once('Parsedown.php');
require_once('ParsedownExtra.php');
require_once('Extension.php');
require_once('ParsedownExtraTocTocTop.php');
?>
```

### In your php to instantiate and use
```php
<?php

$Parsedown = new ParsedownExtraTocTocTop();

// Enables the delay toc feature and sets the id that triggers it
$Parsedown->setEnableDelayToc(true, 'toctop');

// causes links to external sites (http[s]://example.com) to have _blank target so they open in new
$Parsedown->setExtLinksInNewWindow(true);

// if you prefer ALL links that way
// $Parsedown->setExtLinksInNewWindow(true);

// if you don't want this feature leave it unset
?>
```

## finally put some ids /tags into your markdown
Note that you want to put {\#toctop} on the last header line you want to HIDE from TOC
- It must be on a #, ##, ###, #####, #####, or ###### line
- The TOC will ignore all heads up to and including the one that has TocTop
```markdown
# Title
### yyyy mon dd

## Table of Contents {#toctop}
[toc] 

## Some head
- fnord
- foo

## some other head
### Some subhead
- bar
-baz
```



## Extension to extension to extension?
OK so I started with [parsedown](https://github.com/erusev/parsedown).

Really liked it but I had this little thing I wanted to change: I wanted to make it so I could decide if hyperlinks to exteral sites would have \_blank target so they'd open in a new window  or so that all links did

I ended up forking parsedown and my code worked and it was fine for my needs

However, I had reason/need to redo things and I reallized a couple things.. First of all, I really wanted access to the [Markdown-extra](https://michelf.ca/projects/php-markdown/extra/) features like tables etc. I realized that the original craator of parsedown happily did an extension:

[parsedown-extra](https://github.com/erusev/parsedown-extra)

Except I also happened to find [parsedown-extension_table-of-contents](https://github.com/KEINOS/parsedown-extension_table-of-contents) which brilliantly extends Parsedown OR ParsedownExtra seamlessly

So, I liked the Table of contents features a lot but there was a tiny thing I wanted to fix.

I originally just started hacking my local copy of paresdown-extension_table-of-contents and wold likely have left it at that, but I left an issue / enhancement suggestion for KEINOS, the author and they came back and said they loved my idea. I happily said take my code / idea please

However, in the meantime it occurred to me that I really just want to do it right and all this patching my changes directly in was really not The Way.

So, I rolled back to plain jane copies off the current github versions and made this new extension.

If /when KEINOS adds the feature directly to their addon I'll remove that part but I'll leave this extension to their extenion so I can patch in my other desired fixs (the extLinksInNewWindow / allLinksInNewWindow) feature I really wanted.

Sorry for the hot mess

I'm making this public as a way to give back

## Thank you  to 
[Emanuil Rusev](https://github.com/erusev/) for parsedown and parsedown-extra
and to [KEINOS](https://github.com/KEINOS) for parsedown-extension_table-of-contents

