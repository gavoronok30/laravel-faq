<?php

namespace Crow\Faq\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Crow\Faq\Models\FaqCategory;
use Crow\Faq\Models\FaqCategoryRelation;
use Crow\Faq\Models\Faq;
use Crow\Faq\Models\FaqRelation;

class FaqService
{
    public function syncModelAndCategories(Model $model, array|Collection $categories): void
    {
        if (!is_array($categories)) {
            $categories = $categories->map(function (int|FaqCategory $value) {
                return is_a($value, FaqCategory::class) ? $value->id : $value;
            })->toArray();
        }

        $activeCategories = $model->faqCategories->map(function (FaqCategory $value) use ($categories, $model) {
            if (!in_array($value->id, $categories)) {
                $this->detachModelAndCategory($model, $value);
            }

            return $value->id;
        })->toArray();

        foreach (array_diff($categories, $activeCategories) as $category) {
            $this->attachModelAndCategory($model, $category);
        }
    }

    public function attachModelAndCategory(Model $model, int|FaqCategory $category): void
    {
        $modelId = $model->getKey();
        $categoryId = is_a($category, FaqCategory::class) ? $category->id : $category;

        FaqCategoryRelation::query()
            ->firstOrCreate(
                [
                    'model_type' => get_class($model),
                    'model_id' => $modelId,
                    'category_id' => $categoryId,
                ]
            );
    }

    public function detachModelAndCategory(Model $model, int|FaqCategory $category): void
    {
        $modelId = $model->getKey();
        $categoryId = is_a($category, FaqCategory::class) ? $category->id : $category;

        FaqCategoryRelation::query()
            ->where('model_type', '=', get_class($model))
            ->where('model_id', '=', $modelId)
            ->where('category_id', '=', $categoryId)
            ->first()
            ?->delete();
    }

    public function syncModelAndFaqs(Model $model, array|Collection $faqs): void
    {
        if (!is_array($faqs)) {
            $faqs = $faqs->map(function (int|Faq $value) {
                return is_a($value, Faq::class) ? $value->id : $value;
            })->toArray();
        }

        $activeFaqs = $model->faqs->map(function (Faq $value) use ($faqs, $model) {
            if (!in_array($value->id, $faqs)) {
                $this->detachModelAndFaq($model, $value);
            }

            return $value->id;
        })->toArray();

        foreach (array_diff($faqs, $activeFaqs) as $faq) {
            $this->attachModelAndFaq($model, $faq);
        }
    }

    public function attachModelAndFaq(Model $model, int|Faq $faq): void
    {
        $modelId = $model->getKey();
        $faqId = is_a($faq, Faq::class) ? $faq->id : $faq;

        FaqRelation::query()
            ->firstOrCreate(
                [
                    'model_type' => get_class($model),
                    'model_id' => $modelId,
                    'faq_id' => $faqId,
                ]
            );
    }

    public function detachModelAndFaq(Model $model, int|Faq $faq): void
    {
        $modelId = $model->getKey();
        $faqId = is_a($faq, Faq::class) ? $faq->id : $faq;

        FaqRelation::query()
            ->where('model_type', '=', get_class($model))
            ->where('model_id', '=', $modelId)
            ->where('faq_id', '=', $faqId)
            ->first()
            ?->delete();
    }

    public function resortCategories(): void
    {
        /** @var FaqCategory[] $rows */
        $rows = FaqCategory::query()
            ->orderBy('rank', 'ASC')
            ->orderBy('id', 'ASC')
            ->cursor();

        $rank = 1;

        foreach ($rows as $row) {
            $row->update(['rank' => $rank]);
            $rank++;
        }
    }

    public function resortFaqs(array $categoryIds = []): void
    {
        if (empty($categoryIds)) {
            $categoryIds = Faq::query()
                ->groupBy('category_id')
                ->pluck('category_id');
        }

        foreach ($categoryIds as $categoryId) {
            $rows = Faq::query()
                ->where('category_id', '=', $categoryId)
                ->orderBy('rank', 'ASC')
                ->orderBy('id', 'ASC')
                ->cursor();

            $rank = 1;

            foreach ($rows as $row) {
                $row->update(['rank' => $rank]);
                $rank++;
            }
        }
    }

    public function rankNextForCategory(): int
    {
        return (int)FaqCategory::query()
            ->max('rank') + 1;
    }

    public function rankNextForFaq(?int $categoryId = null): int
    {
        $query = Faq::query();
        if ($categoryId) {
            $query->where('category_id', '=', $categoryId);
        } else {
            $query->whereNull('category_id');
        }

        return (int)$query->max('rank') + 1;
    }
}
