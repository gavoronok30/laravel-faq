<?php

use Crow\Faq\Models\Faq;

return [
    /*
    |--------------------------------------------------------------------------
    | Tables name
    |--------------------------------------------------------------------------
    | Tables name for models
    */
    'table' => [
        'category' => 'faq_categories',
        'category_relation' => 'faq_category_relations',
        'faq' => 'faqs',
        'faq_relation' => 'faq_relations'
    ],

    /*
    |--------------------------------------------------------------------------
    | For category model
    |--------------------------------------------------------------------------
    */
    'category' => [
        /*
        | if there is a faq in the category then the category cannot be deleted
        */
        'deleted_block_if_not_empty' => true,
        /*
        | If there is a FAQ in the category being deleted, all FAQs from that
        | category will be deleted before the category is deleted
        */
        'auto_delete_faqs' => false,
        /*
        | If there is a FAQ in the category being deleted, then the category
        | field of all FAQs from this category will be reset to null
        */
        'auto_unset_category' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom slug generator
    |--------------------------------------------------------------------------
    */
    'slug_generator' => [
        /*
        | If null, then the built-in generator from Laravel will be used.
        | To connect a custom generator, you must use the closure function
        | Example:
        | 'function' => function (Illuminate\Database\Eloquent\Model $model) {
        |    if (is_a($model, \Crow\Faq\Models\FaqCategory::class)) {
        |        return \Illuminate\Support\Str::slug($model->title, '-');
        |    } else {
        |        return \Illuminate\Support\Str::slug($model->question, '-');
        |    }
        | },
        */
        'function' => null
    ],
];
