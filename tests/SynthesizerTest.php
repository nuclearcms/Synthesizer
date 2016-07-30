<?php


class SynthesizerTest extends TestBase {

    /** @test */
    function it_synthesizes_text()
    {
        $synthesizer = $this->app->make('Nuclear\Synthesizer\SynthesizerContract');

        $synthesizer->setText('**strong**');

        $this->assertEquals(
            '<p><strong>strong</strong></p>',
            $synthesizer->synthesize()
        );

        $synthesizer->setText('**strong** ![READMORE]! *emphasize*');

        $this->assertEquals(
            '<p><strong>strong</strong>  <em>emphasize</em></p>',
            $synthesizer->synthesize()
        );

        $this->assertEquals(
            '<p><strong>strong</strong> </p>',
            $synthesizer->synthesize('before')
        );

        $this->assertEquals(
            '<p><em>emphasize</em></p>',
            $synthesizer->synthesize('rest')
        );

        $this->assertEquals(
            'strong  emphasize',
            $synthesizer->synthesize(null, 'striptags')
        );
    }

}