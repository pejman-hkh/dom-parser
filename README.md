# dom-parser
Fast php HTML DOM parser
- All php version work properly without extension
- No need php extension
- No Regex use
- Can embed to another languages like js

# Install :
```
composer require pejman/dom-parser
```


in this project I haven't used Regex, I just parsed with pure php

Yet I'm working on it

Now we can find in html with class and id and attributes

```
$p->find();
$p->find(".test");
$p->find("#test");
$p->find("input[type=button]");
```

I wrote somethings like next,prev,text,... that i'll complete it at the future