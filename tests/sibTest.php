<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class sibTest extends TestCase
{

    protected function setUp(): void {
        $p = new \Pejman\DomParser\Parser( '<div class="test">ss<span class="aa">innnerssss</span><span class="bb">innnnn</span></div><div class="test1">eee</div>');
        $this->document = $p->document;
    }

    public function testNext()
    {
        $document = $this->document;
        $this->assertSame( 'bb', $document->find(".test span",0)->next->class );
    }

    public function testPrev()
    {
        $document = $this->document;
        $this->assertSame( 'aa', $document->find(".test span.bb",0)->prev->class );
    }

    public function testParent()
    {
        $document = $this->document;
        $this->assertSame( 'test', $document->find(".test span.bb",0)->parent->class );
    }

    public function testParentParent()
    {
        $document = $this->document;
        $this->assertSame( 'document', $document->find(".test span.bb",0)->parent->parent->tag );
    }

    public function testNextPrev()
    {
        $document = $this->document;
        $this->assertSame( 'aa', $document->find(".test span",0)->next->prev->class );
    }

    public function testOnHtmlChange()
    {
        $p = new \Pejman\DomParser\Parser( '<div class="test"><span class="aa">innnerssss</span></div>');

        $p->document->find(".aa",0)->html('<span class="cc"></span>');

        $this->assertSame( 'aa', $p->find(".cc",0)->parent->class );
    }
}