<?php


namespace Nuclear\Synthesizer\Processors;


use Nuclear\Documents\Contract\Repositories\DocumentsRepository;

class DocumentsProcessor implements Processor {

    /** @var DocumentsRepository */
    protected $documentsRepository;

    /**
     * Constructor
     *
     * @param DocumentsRepository $documentsRepository
     */
    public function __construct(DocumentsRepository $documentsRepository)
    {
        $this->documentsRepository = $documentsRepository;
    }

    /**
     * Processes the given text
     *
     * @param string $text
     * @param array|null $args
     * @return string
     */
    public function process($text, $args = null)
    {
        $text = $this->processDocuments($text, $args);
        $text = $this->processGalleries($text, $args);

        return $text;
    }

    /**
     * Processes documents
     *
     * @param string $text
     * @param mixed $args
     * @return string
     */
    protected function processDocuments($text, $args)
    {
        list($matches, $ids) = $this->getDocumentMatches($text);

        $documents = $this->getDocuments($ids);

        return $this->makeDocuments($matches, $ids, $documents, $text);
    }

    /**
     * Finds all media in given string
     *
     * @param string $text
     * @return array
     */
    protected function getDocumentMatches($text)
    {
        preg_match_all('~\[document.+id="([1-9]\d*)"\]~', $text, $matches);

        return $matches;
    }

    /**
     * Finds the media with given ids
     *
     * @param array $ids
     * @return Collection
     */
    protected function getDocuments(array $ids)
    {
        return $this->documentsRepository->getDocuments($ids);
    }

    /**
     * Replaces the document tags with presenter renders
     *
     * @param array $matches
     * @param array $ids
     * @param Collection|null $documents
     * @param string $text
     * @return string
     */
    protected function makeDocuments(array $matches, array $ids, $documents, $text)
    {
        foreach ($ids as $key => $id)
        {
            $document = $documents->find($id);

            $parsedDocument = '';

            if ( ! is_null($document))
            {
                $parsedDocument = $document->present()->preview;
            }

            $text = str_replace($matches[$key], $parsedDocument, $text);
        }

        return $text;
    }

    /**
     * Processes galleries
     *
     * @param string $text
     * @param mixed $args
     * @return string
     */
    protected function processGalleries($text, $args)
    {
        list($matches, $idSets) = $this->getGalleryMatches($text);

        return $this->makeGalleries($matches, $idSets, $text);
    }

    /**
     * Finds all media in given string
     *
     * @param string $text
     * @return array
     */
    protected function getGalleryMatches($text)
    {
        preg_match_all('~\[gallery.+ids="(\d+(?:,\d+)*)"\]~', $text, $matches);

        list($matches, $gallery) = $matches;

        foreach ($gallery as $key => $ids)
        {
            $gallery[$key] = explode(',', $ids);
        }

        return [$matches, $gallery];
    }

    /**
     * Replaces the document tags with gallery render
     *
     * @param array $matches
     * @param array $idSets
     * @param string $text
     * @return string
     */
    protected function makeGalleries(array $matches, array $idSets, $text)
    {
        foreach ($idSets as $key => $ids)
        {
            $slides = get_reactor_gallery($ids);

            $gallery = (count($slides)) ? $this->makeGalleryHTML($slides) : '';

            $text = str_replace($matches[$key], $gallery, $text);
        }

        return $text;
    }

    /**
     * Makes gallery HTML
     *
     * @param Collection $slides
     * @return string
     */
    protected function makeGalleryHTML(Collection $slides)
    {
        if ( ! count($slides))
        {
            return '';
        }

        $html = '';

        foreach ($slides as $slide)
        {
            $html .= $this->makeSlideHTML($slide);
        }

        return $this->wrapSlidesHTML($html);
    }

    /**
     * Makes slide HTML
     *
     * @param Image $image
     * @return string
     */
    protected function makeSlideHTML(Image $image)
    {
        $translation = $image->translate();
        $caption = ($translation) ? $translation->caption : '';
        $description = ($translation) ? $translation->description : '';

        return '<li class="gallery__item">
            <figure data-original="' . $image->getPublicURL() . '">' .
                $image->present()->thumbnail .
                '<figcaption class="gallery-thumbnail__caption">' . $caption . '</figcaption>
                    <p class="gallery-thumbnail__description">' . $description . '</p>
            </figure>
        </li>';
    }

    /**
     * Wraps slides HTML
     *
     * @param string $slides
     * @return string
     */
    protected function wrapSlidesHTML($slides)
    {
        return '<ul class="gallery--inline gallery--lightbox">' . $slides . '</ul>';
    }
}