<?php

namespace Crow\Faq\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Crow\Faq\Models\FaqCategory;
use Crow\Faq\Models\Faq;

/**
 * @method static void syncModelAndCategories(Model $model, array|Collection $categories)
 * @method static void attachModelAndCategory(Model $model, int|FaqCategory $category)
 * @method static void detachModelAndCategory(Model $model, int|FaqCategory $category)
 * @method static void syncModelAndFaqs(Model $model, array|Collection $faqs)
 * @method static void attachModelAndFaq(Model $model, int|Faq $faq)
 * @method static void detachModelAndFaq(Model $model, int|Faq $faq)
 * @method static void resortCategories()
 * @method static void resortFaqs(array $categoryIds = [])
 * @method static int rankNextForCategory()
 * @method static int rankNextForFaq(?int $categoryId = null)
 */
class FaqHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FaqHelperHandler::class;
    }
}
