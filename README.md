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

# Fast Use :
```php
<?php
include __dir__.'/../vendor/autoload.php';
$p = e( '<div><div class="test">ss<span class="aa">innnerssss</span><span class="aa">innnnn</span></div></div><div class="test1">eee</div>');
dump( $p->document );
```

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

# PQeury Like JQuery :

```php
<?php
include __dir__.'/../vendor/autoload.php';
$p = new \Pejman\DomParser\Parser( '<div id="test"><div class="test"><span>aaa</span>bbb<span>ccc</span></div></div><div class="test1">eee</div>');
\Pejman\DomParser\PQuery::$document = $p->document;


pq("#test span")->each(function( $elm ) {
	echo pq( $elm )->html()."\n";
});

print_r( pq("#test")->next()->attr('class') );

```