<?php

namespace Crow\Faq\Exceptions;

use Exception;
use Crow\Faq\Models\FaqCategory;

class FaqException extends Exception
{
    public static function categoryNotEmpty(FaqCategory $model, ?int $code = null): FaqException
    {
        $visibleFields = [
            'id',
            'title',
            'slug',
        ];
        $text = __('faq.category.deleted_block_if_not_empty', $model->setVisible($visibleFields)->toArray());

        return new static($text, $code ?: 422);
    }
}
