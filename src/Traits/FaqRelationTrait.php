<?php

namespace Crow\Faq\Traits;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\Relation;
use Crow\Faq\Models\Faq;
use Crow\Faq\Models\FaqRelation;

/**
 * @property Faq[] $faqs
 * @property Faq[] $faqsActive
 */
trait FaqRelationTrait
{
    public function faqs(): HasManyThrough
    {
        return $this->hasManyThrough(
            Faq::class,
            FaqRelation::class,
            'model_id',
            'id',
            null,
            'faq_id'
        )->where(
            'model_type',
            array_search(static::class, Relation::morphMap()) ?: static::class
        )->orderBy('rank', 'ASC');
    }

    public function faqsActive(): HasManyThrough
    {
        return $this->hasManyThrough(
            Faq::class,
            FaqRelation::class,
            'model_id',
            'id',
            null,
            'faq_id'
        )->where(
            'model_type',
            array_search(static::class, Relation::morphMap()) ?: static::class
        )->where('is_active', '=', true)->orderBy('rank', 'ASC');
    }
}
