<?php


namespace Nuclear\Synthesizer\Processors;


use ParsedownExtra;

class MarkdownProcessor extends ParsedownExtra implements Processor {

    /**
     * Processes the given text
     *
     * @param string $text
     * @param array|null $args
     * @return string
     */
    public function process($text, $args = null)
    {
        return $this->text($text);
    }

    #
    # Inline Elements
    #
    protected $InlineTypes = array(
        '"' => array('SpecialCharacter'),
        '!' => array('Image'),
        '&' => array('SpecialCharacter'),
        '*' => array('Emphasis'),
        ':' => array('Url'),
        '<' => array('UrlTag', 'EmailTag', 'Markup', 'SpecialCharacter'),
        '>' => array('SpecialCharacter'),
        '[' => array('Link'),
        '_' => array('Emphasis'),
        '`' => array('Code'),
        '~' => array('Strikethrough', 'Subscript'),
        '^' => array('Superscript'),
        '\\' => array('EscapeSequence'),
    );

    # ~
    protected $inlineMarkerList = '!"*_&[:<>`~^\\';

    /**
     * Processor for subscript element
     *
     * @param $Excerpt
     * @return array|void
     */
    protected function inlineSubscript($Excerpt)
    {
        if ($Excerpt['text'][0] === '~' and preg_match('/^[~]((?:\\\\\~|[^~\n\r])+?)[~](?![~])/', $Excerpt['text'], $matches))
        {
            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'sub',
                    'text' => $matches[1],
                    'handler' => 'line',
                ),
            );
        }
    }

    /**
     * Processor for superscript element
     *
     * @param $Excerpt
     * @return array|void
     */
    protected function inlineSuperscript($Excerpt)
    {
        if ($Excerpt['text'][0] === '^' and preg_match('/^[\^]((?:\\\\\^|[^\^\n\r])+?)[\^]/', $Excerpt['text'], $matches))
        {
            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'sup',
                    'text' => $matches[1],
                    'handler' => 'line',
                ),
            );
        }
    }

    protected function inlineLink($Excerpt)
    {
        $Element = array(
            'name' => 'a',
            'handler' => 'line',
            'text' => null,
            'attributes' => array(
                'href' => null,
                'title' => null
            ),
        );

        $extent = 0;

        $remainder = $Excerpt['text'];

        if (preg_match('/\[((?:[^][]|(?R))*)\]/', $remainder, $matches))
        {
            $Element['text'] = $matches[1];

            $extent += strlen($matches[0]);

            $remainder = substr($remainder, $extent);
        }
        else
        {
            return;
        }

        /**
         * This part is modified in order to be able to
         * add blank target link capability.
         */
        if (preg_match('/^[(]((?:[^ ()]|[(][^ )]+[)])+)(?:[ ]+("[^"]*"|\'[^\']*\'))?[)]({blank})?/', $remainder, $matches))
        {
            $Element['attributes']['href'] = $matches[1];

            if (isset($matches[2]))
            {
                $Element['attributes']['title'] = substr($matches[2], 1, - 1);
            }

            if (isset($matches[3]))
            {
                $Element['attributes']['target'] = '_blank';
            }

            $extent += strlen($matches[0]);
        }
        else
        {
            if (preg_match('/^\s*\[(.*?)\]/', $remainder, $matches))
            {
                $definition = strlen($matches[1]) ? $matches[1] : $Element['text'];
                $definition = strtolower($definition);

                $extent += strlen($matches[0]);
            }
            else
            {
                $definition = strtolower($Element['text']);
            }

            if ( ! isset($this->DefinitionData['Reference'][$definition]))
            {
                return;
            }

            $Definition = $this->DefinitionData['Reference'][$definition];

            $Element['attributes']['href'] = $Definition['url'];
            $Element['attributes']['title'] = $Definition['title'];
        }

        $Element['attributes']['href'] = str_replace(array('&', '<'), array('&amp;', '&lt;'), $Element['attributes']['href']);

        return array(
            'extent' => $extent,
            'element' => $Element,
        );
    }

}