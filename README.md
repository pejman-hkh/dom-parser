# dom-parser
Fast php HTML DOM parser
- All php version work properly without extension
- No need php extension
- No Regex used
- Can embed to another languages like js
- Can load xml file like html

# Install :
```
composer require pejman/dom-parser
```


in this project I haven't used Regex, I just parsed with pure php


# Usage :

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


//remove element
$element->remove();

//update html
$element->html('ddd');

//get html
$element->html();
$element->html;
```

