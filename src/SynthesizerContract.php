<?php


namespace Nuclear\Synthesizer;


interface SynthesizerContract {

    /**
     * Synthesizes the given text
     *
     * @param string $part
     * @param array|string $filters
     * @param mixed $args
     * @return string
     */
    public function synthesize($part = null, $filters = null, $args = null);

}