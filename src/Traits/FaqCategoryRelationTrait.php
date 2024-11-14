<?php

namespace Crow\Faq\Traits;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\Relation;
use Crow\Faq\Models\FaqCategory;
use Crow\Faq\Models\FaqCategoryRelation;

/**
 * @property FaqCategory[] $faqCategories
 * @property FaqCategory[] $faqCategoriesActive
 */
trait FaqCategoryRelationTrait
{
    public function faqCategories(): HasManyThrough
    {
        return $this->hasManyThrough(
            FaqCategory::class,
            FaqCategoryRelation::class,
            'model_id',
            'id',
            null,
            'category_id'
        )->where(
            'model_type',
            array_search(static::class, Relation::morphMap()) ?: static::class
        )->orderBy('rank', 'ASC');
    }

    public function faqCategoriesActive(): HasManyThrough
    {
        return $this->hasManyThrough(
            FaqCategory::class,
            FaqCategoryRelation::class,
            'model_id',
            'id',
            null,
            'category_id'
        )->where(
            'model_type',
            array_search(static::class, Relation::morphMap()) ?: static::class
        )->where('is_active', '=', true)->orderBy('rank', 'ASC');
    }
}
