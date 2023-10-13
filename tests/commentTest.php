<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class commentTest extends TestCase
{

    protected function setUp(): void {
        $p = new \Pejman\DomParser\Parser( '<div class="alert alert-info">یک اشتراک را انتخاب کنید <!-- ( کمک مالی اختیاری میباشد. )  --></div>');
        $this->document = $p->document;
    }

    public function testNext()
    {
        $this->assertSame( $this->document->html, '<div class="alert alert-info">یک اشتراک را انتخاب کنید</div>');
       
    }

}