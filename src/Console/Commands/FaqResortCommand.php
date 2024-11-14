<?php

namespace Crow\Faq\Console\Commands;

use Illuminate\Console\Command;
use Crow\Faq\Helpers\FaqHelper;

class FaqResortCommand extends Command
{
    private const TYPE_CATEGORY = 'category';
    private const TYPE_FAQ = 'faq';

    protected $signature = 'faq:resort {type}';
    protected $description = 'Resort rows for categories or faqs, types: category, faq';

    public function handle(): void
    {
        switch ($this->argument('type')) {
            case self::TYPE_CATEGORY:
                FaqHelper::resortCategories();
                break;
            case self::TYPE_FAQ:
                FaqHelper::resortFaqs();
                break;
        }
    }
}
