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

```php
$element = $p->find(".test",0);
$element->next;
$element->next();

$element->prev;
$element->prev();

$element->parent;
$element->parent();

//all childs
$element->children();

//first child
$element->children(0);

//all attributes
$element->attrs;

//find in tag
$element->find("span");

//set attr
$element->attr( $key, $value );

//set attr
$element->href='test';
//get attr
$element->href;

$element->getElementById("test");
$element->getElementByTagName("span");
$element->getElementsByTagName("span");

$element->html();
$element->text();
$element->outerHtml();

```

