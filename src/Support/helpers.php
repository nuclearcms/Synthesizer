<?php

if ( ! function_exists('synthesizer'))
{
    /**
     * Synthesizes the given text
     *
     * @param string $text
     * @param string $part
     * @param array|string $filters
     * @param mixed $args
     * @return string
     */
    function synthesize($text, $part = null, $filters = [], $args = null)
    {
        return app()->make('Nuclear\Synthesizer\SynthesizerContract')
            ->setText($text)->synthesize($part, $filters, $args);
    }
}