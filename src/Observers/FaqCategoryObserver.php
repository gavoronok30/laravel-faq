<?php

namespace Crow\Faq\Observers;

use Crow\Faq\Helpers\FaqHelper;
use Crow\Faq\Models\Faq;
use Crow\Faq\Models\FaqCategoryRelation;
use Illuminate\Support\Str;
use Crow\Faq\Exceptions\FaqException;
use Crow\Faq\Models\FaqCategory;

class FaqCategoryObserver
{
    public function creating(FaqCategory $model): void
    {
        $this->slugGenerate($model);
        $this->generateRank($model);
    }

    public function updating(FaqCategory $model): void
    {
        $this->slugGenerate($model);
        $this->generateRank($model);
    }

    public function deleting(FaqCategory $model): void
    {
        if ($model->faqs()->count()) {
            if (config('faq.category.deleted_block_if_not_empty')) {
                throw FaqException::categoryNotEmpty($model);
            } elseif (config('faq.category.auto_delete_faqs')) {
                $model->faqs->each(function (Faq $faq) {
                    $faq->delete();
                });
            } elseif (config('faq.category.auto_unset_category')) {
                $model->faqs->each(function (Faq $faq) {
                    $faq->category_id = null;
                    $faq->save();
                });
            }
        }

        $model->relationModels->each(function (FaqCategoryRelation $relation) {
            $relation->delete();
        });
    }

    private function slugGenerate(FaqCategory $model): void
    {
        if (!$model->slug) {
            if (config('faq.slug_generator.function') && is_callable(config('faq.slug_generator.function'))) {
                $slug = config('faq.slug_generator.function')($model);
            } else {
                $slug = Str::slug($model->title, '-');
            }

            $model->slug = $slug;
        }
    }

    private function generateRank(FaqCategory $model): void
    {
        if (!$model->rank) {
            $model->rank = FaqHelper::rankNextForCategory();
        }
    }
}
