<?php


class DocumentsProcessorTest extends TestBase {

    /** @test */
    function it_is_instantiatable()
    {
        $this->assertInstanceOf(
            'Nuclear\Synthesizer\Processors\DocumentsProcessor',
            $this->app->make('Nuclear\Synthesizer\Processors\DocumentsProcessor')
        );
    }

}