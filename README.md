# PHP-DOM-Parser
Fast PHP HTML DOM parser
- All PHP version work properly without extension
- No need PHP extension
- No Regex used
- Can embed to another languages like JS
- Can load XML file like HTML
- PQuery like JQuery

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

# PQuery Like JQuery :

```php
<?php
include __dir__.'/../vendor/autoload.php';
$p = e( '<div id="test"><div class="test"><span>aaa</span>bbb<span>ccc</span></div></div><div class="test1">eee</div>');

pq("#test span")->each(function( $elm ) {
	echo pq( $elm )->html()."\n";
});

print_r( pq("#test")->next()->attr('class') );
echo "\n";
pq("#test")->addClass("added-class");
echo pq("#test")->outerHtml();
echo "\n";
pq("#test")->removeClass("added-class");
echo pq("#test")->outerHtml();
echo "\n";
pq("#test")->addClass("added-class");
echo pq("#test")->outerHtml();
echo "\n";

```