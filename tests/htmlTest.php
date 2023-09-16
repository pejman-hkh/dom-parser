<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class HtmlTest extends TestCase
{

    protected function setUp(): void {
        $p = new \Pejman\DomParser\Parser( '<div id="first"><div class="test"><span>aaa</span>bbb<span></span></div></div><div class="test1">eee</div>');
        $this->document = $p->document;
    }

    public function testGetHtml()
    {
        $document = $this->document;
        $this->assertSame( '<div class="test"><span>aaa</span>bbb<span></span></div>', $document->find("#first",0)->html );
    }

    public function testSetHtml()
    {
        $document = $this->document;
        $test = $document->find(".test", 0);
        $test->html('<span>ddd</span>');
        $this->assertSame( '<span>ddd</span>', $test->html );
    }

    public function testParentHtml()
    {
        $document = $this->document;
        $test = $document->find(".test", 0);
        $test->html('<span>ddd</span>');
        $this->assertSame( '<div id="first"><div class="test"><span>ddd</span></div></div><div class="test1">eee</div>', $document->html );
    }

    public function testHtmlOnRemoveSomeElement()
    {
        $document = $this->document;
        $test = $document->find(".test", 0)->remove();
        $this->assertSame( '<div id="first"></div><div class="test1">eee</div>', $document->html );
    }
}