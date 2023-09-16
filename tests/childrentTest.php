<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ChildrentTest extends TestCase
{

    protected function setUp(): void {
        $p = new \Pejman\DomParser\Parser( '<div class="test">ss<span class="aa">innnerssss</span><span class="bb">innnnn</span></div><div class="test1">eee</div>');
        $this->element = $p->find(".test", 0);
    }

    public function testChildrenIndex()
    {
        $element = $this->element;
        $this->assertSame( 'aa', $element->children(0)->class );
    }


    public function testChildrens() {
        $element = $this->element;
        $cls = [];
        foreach( $element->children() as $childs ) {
            $cls[] = $childs->class;
        }
        $this->assertSame( ['aa','bb'], $cls );

    }
}