<?php

namespace Crow\Faq\Providers;

use Crow\Faq\Models\Faq;
use Crow\Faq\Observers\FaqObserver;
use Illuminate\Support\ServiceProvider;
use Crow\Faq\Console\Commands\FaqPublishCommand;
use Crow\Faq\Console\Commands\FaqResortCommand;
use Crow\Faq\Models\FaqCategory;
use Crow\Faq\Observers\FaqCategoryObserver;
use Crow\Faq\Services\FaqService;

class FaqProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadObservers();
    }

    public function register(): void
    {
        $this->loadCustomCommands();
        $this->loadCustomConfig();
        $this->loadCustomPublished();
        $this->loadCustomClasses();
        $this->loadCustomLexicon();
    }

    private function loadCustomCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                [
                    FaqPublishCommand::class,
                    FaqResortCommand::class
                ]
            );
        }
    }

    private function loadCustomConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/faq.php', 'faq');
    }

    private function loadCustomPublished(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__ . '/../../config' => app('path.config')
                ],
                'config'
            );
            $this->publishes(
                [
                    __DIR__ . '/../../migration' => database_path('migrations')
                ],
                'migration'
            );
            $this->publishes(
                [
                    __DIR__ . '/../../lang' => app('path.lang')
                ],
                'lang'
            );
        }
    }

    private function loadCustomClasses(): void
    {
        $this->app->singleton(FaqService::class);
    }

    private function loadCustomLexicon(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'faq');
    }

    private function loadObservers(): void
    {
        Faq::observe(FaqObserver::class);
        FaqCategory::observe(FaqCategoryObserver::class);
    }
}
