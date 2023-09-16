<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class FindTest extends TestCase
{

    protected function setUp(): void {
        $p = new \Pejman\DomParser\Parser( '<div><div class="test">ss<span class="aa">innnerssss</span><span class="bb">innnnn</span></div></div><div class="test1">eee</div>');
        $this->element = $p->document;
    }

    public function testFindCommaInQuery()
    {
        $element = $this->element;
        $cls = [];
        foreach( $element->find(".test,.test1") as $childs ) {
            $cls[] = $childs->class;
        }
        $this->assertSame( ['test','test1'], $cls);
    }

    public function testFindChildren()
    {
        $element = $this->element;
        $cls = [];
        foreach( $element->find(".test span") as $childs ) {
            $cls[] = $childs->class;
        }
        $this->assertSame( ['aa','bb'], $cls);
    }
}