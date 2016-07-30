<?php


namespace Nuclear\Synthesizer;


class Synthesizer implements SynthesizerContract {

    /** @const string */
    const separator = '![READMORE]!';

    /** @var Processor */
    protected $documentsProcessor;
    protected $markdownProcessor;

    /** @var string */
    protected $text;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->documentsProcessor = app('synthesizer.processors.documents');
        $this->markdownProcessor = app('synthesizer.processors.markdown');
    }

    /**
     * Setter for the text
     *
     * @param string $text
     * @return self
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Synthesizes text
     *
     * @param string $part
     * @param array|string $filters
     * @param mixed $args
     * @return string
     */
    public function synthesize($part = null, $filters = [], $args = null)
    {
        $text = $this->splitText($part);

        $text = $this->documentsProcessor->process($text, $args);
        $text = $this->markdownProcessor->process($text, $args);

        if (! is_array($filters))
        {
            $filters = [$filters];
        }

        return $this->applyFilters($text, $filters, $args);
    }

    /**
     * Splits the text into given part
     *
     * @param string $part
     * @return string
     */
    protected function splitText($part)
    {
        if ($part === 'before' || $part === 'rest')
        {
            $parts = explode(static::separator, $this->text);

            return ($part === 'before') ? current($parts) : end($parts);
        }

        return str_replace(static::separator, '', $this->text);
    }

    /**
     * Applies filters
     *
     * @param string $text
     * @param array $filters
     * @param mixed $args
     * @return string
     */
    protected function applyFilters($text, array $filters, $args)
    {
        foreach ($filters as $filter)
        {
            $text = $this->{'apply' . ucfirst($filter) . 'Filter'}($text, $args);
        }

        return $text;
    }

    /**
     * Applies strip tag filter
     *
     * @param string $text
     * @param mixed $args
     * @return string
     */
    protected function applyStriptagsFilter($text, $args)
    {
        return strip_tags($text);
    }

}