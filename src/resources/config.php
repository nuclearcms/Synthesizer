<?php

return [

    /*
	|--------------------------------------------------------------------------
	| Default processors
	|--------------------------------------------------------------------------
	|
	| The processors which synthesizer use for processing documents and markdown
	|
	*/
    'processors' => [
        'documents' => 'Nuclear\Synthesizer\Processors\DocumentsProcessor',
        'markdown' => 'Nuclear\Synthesizer\Processors\MarkdownProcessor'
    ],

];