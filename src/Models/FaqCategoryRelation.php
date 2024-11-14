<?php

namespace Crow\Faq\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;

/**
 * @property-read int $id
 * @property string $model_type
 * @property int $model_id
 * @property int $category_id
 * @property FaqCategory $category
 * @property Carbon $created_at
 */
class FaqCategoryRelation extends Model
{
    public const UPDATED_AT = null;
    protected $fillable = [
        'model_type',
        'model_id',
        'category_id',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('faq.table.category_relation');

        parent::__construct($attributes);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'category_id', 'id');
    }
}
