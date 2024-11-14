<?php

namespace Crow\Faq\Observers;

use Crow\Faq\Helpers\FaqHelper;
use Crow\Faq\Models\Faq;
use Crow\Faq\Models\FaqRelation;
use Illuminate\Support\Str;

class FaqObserver
{
    public function creating(Faq $model): void
    {
        $this->slugGenerate($model);
        $this->generateRank($model);
    }

    public function updating(Faq $model): void
    {
        $this->slugGenerate($model);
        $this->generateRank($model);
    }

    public function deleting(Faq $model): void
    {
        $model->relationModels->each(function (FaqRelation $relation) {
            $relation->delete();
        });
    }

    private function slugGenerate(Faq $model): void
    {
        if (!$model->slug) {
            if (config('faq.slug_generator.function') && is_callable(config('faq.slug_generator.function'))) {
                $slug = config('faq.slug_generator.function')($model);
            } else {
                $slug = Str::slug($model->question, '-');
            }

            $model->slug = $slug;
        }
    }

    private function generateRank(Faq $model): void
    {
        if (!$model->rank) {
            $model->rank = FaqHelper::rankNextForFaq($model->category_id);
        }
    }
}
