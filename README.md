# parsedown-extension_toc-toctop
This is an extension to an extension of an extension of parsedown - specifically to [parsedown-extension_table-of-contents](https://github.com/KEINOS/parsedown-extension_table-of-contents) 

## Extension to extension to extension?
OK so I started with [parsedown](https://github.com/erusev/parsedown).

Really liked it but I had this little thing I wanted to change: I wanted to make it so I could decide if hyperlinks to exteral sites would have _blank target so they'd open in a new window  or so that all links did

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
[Emanuil Rusev ](https://github.com/erusev/) for parsedown and parsedown-extra
and to [KEINOS](https://github.com/KEINOS) for parsedown-extension_table-of-contents

