<?php


class SynthesizerServiceProviderTest extends TestBase {

    /** @test */
    function it_registers_documents_processor()
    {
        $this->assertInstanceOf(
            'Nuclear\Synthesizer\Processors\DocumentsProcessor',
            $this->app->make('synthesizer.processors.documents')
        );
    }

    /** @test */
    function it_registers_markdown_processor()
    {
        $this->assertInstanceOf(
            'Nuclear\Synthesizer\Processors\MarkdownProcessor',
            $this->app->make('synthesizer.processors.markdown')
        );
    }

    /** @test */
    function it_registers_synthesizer()
    {
        $this->assertInstanceOf(
            'Nuclear\Synthesizer\Synthesizer',
            $this->app->make('Nuclear\Synthesizer\SynthesizerContract')
        );
    }

}