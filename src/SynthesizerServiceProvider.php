<?php


namespace Nuclear\Synthesizer;


use Illuminate\Support\ServiceProvider;

class SynthesizerServiceProvider extends ServiceProvider {

    const version = '0.9.2';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'Nuclear\Synthesizer\SynthesizerContract',
            'synthesizer.processors.documents',
            'synthesizer.processors.markdown'
        ];
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerDocumentsProcessor();
        $this->registerMarkdownProcessor();
        $this->registerSynthesizer();
    }

    /**
     * Registers the markdown processor
     */
    protected function registerDocumentsProcessor()
    {
        $modelName = config('synthesizer.processors.documents', 'Nuclear\Synthesizer\Processors\DocumentsProcessor');

        $this->app->singleton('synthesizer.processors.documents', $modelName);
    }

    /**
     * Registers the markdown processor
     */
    protected function registerMarkdownProcessor()
    {
        $modelName = config('synthesizer.processors.markdown', 'Nuclear\Synthesizer\Processors\MarkdownProcessor');

        $this->app->singleton('synthesizer.processors.markdown', $modelName);
    }

    /**
     * Registers the synthesizer
     */
    protected function registerSynthesizer()
    {
        $this->app->bind(
            'Nuclear\Synthesizer\SynthesizerContract',
            'Nuclear\Synthesizer\Synthesizer'
        );
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        if ( ! $this->app->environment('production'))
        {
            $this->publishes([
                dirname(__DIR__) . '/resources/config.php' => config_path('synthesizer.php')
            ]);
        }
    }
}