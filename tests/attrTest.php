<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class AttrTest extends TestCase
{

    protected function setUp(): void {
        $p = new \Pejman\DomParser\Parser( '<span class="text-normal" data-menu-button-text>Stars</span><header data-light-theme=light data-dark-theme=dark></header><div id=test></div><div class="test">ss<span class="aa" test-attr="123">innnerssss</span><span class="aa">innnnn</span></div><div class="test1">eee</div>');
        $this->element =  $p->find(".aa", 0);

    }

    public function testSetAttrOnElementDirectly()
    {
        $element = $this->element;
        $element->{'test-attr'} = 321;
        $this->assertSame( 321, $element->attrs->{'test-attr'} );
    }


    public function testAttrMehtodOfElement() {
        $element = $this->element;
        $element->attr('aaa','bbb');
        $this->assertSame( 'bbb', $element->attrs->aaa );

    }
}