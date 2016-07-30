<?php

class HelpersTest extends TestBase
{

    /** @test */
    function it_synthesizes()
    {
        $this->assertEquals(
            '<p><strong>strong</strong></p>',
            synthesize('**strong**')
        );
    }

}