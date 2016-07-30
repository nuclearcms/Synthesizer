<?php


use Nuclear\Synthesizer\Processors\MarkdownProcessor;

class MarkdownProcessorTest extends TestBase {

    protected function getMarkdownProcessor()
    {
        return new MarkdownProcessor();
    }

    /** @test */
    function it_processes_subscripts_and_strikethroughs()
    {
        $processor = $this->getMarkdownProcessor();

        $text = 'H~2~O ~~strike~~';
        $this->assertEquals(
            '<p>H<sub>2</sub>O <del>strike</del></p>',
            $processor->process($text)
        );
    }

    /** @test */
    function it_processes_superscripts()
    {
        $processor = $this->getMarkdownProcessor();

        $text = '2^10^ equals to 1024';
        $this->assertEquals(
            '<p>2<sup>10</sup> equals to 1024</p>',
            $processor->process($text)
        );
    }

    /** @test */
    function it_processes_links_and_definitions_and_blank_links()
    {
        $processor = $this->getMarkdownProcessor();

        $text = '[link](http://example.com) [link](http://example.com){blank}';
        $this->assertEquals(
            '<p><a href="http://example.com">link</a> <a href="http://example.com" title="" target="_blank">link</a></p>',
            $processor->process($text)
        );

        $text = "[reference][id]\n\n[id]: http://example.com \"Hey there\"";
        $this->assertEquals(
            '<p><a href="http://example.com" title="Hey there">reference</a></p>',
            $processor->process($text)
        );
    }

}