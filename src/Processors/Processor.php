<?php


namespace Nuclear\Synthesizer\Processors;


interface Processor {

    /**
     * Processes the given text
     *
     * @param string $text
     * @param array|null $args
     * @return string
     */
    public function process($text, $args = null);

}