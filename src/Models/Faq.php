<?php

namespace Crow\Faq\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Config;

/**
 * @property-read int $id
 * @property string $slug
 * @property int $category_id
 * @property FaqCategory $category
 * @property string $question
 * @property string $answer
 * @property int $rank
 * @property bool $is_active
 * @property array $properties
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection|static[] $relationModels
 *
 * @method static Builder|static query()
 * @method static Builder|static find(int $id)
 * @method static Builder|static findOrFail(int $id, $columns = ['*'])
 * @method static Builder|static first(array $columns = ['*'])
 * @method static Builder|static firstOrFail(array $columns = ['*'])
 * @method static Collection|static[] get(array $columns = ['*'])
 */
class Faq extends Model
{
    protected $fillable = [
        'slug',
        'category_id',
        'question',
        'answer',
        'rank',
        'is_active',
        'properties',
    ];
    protected $casts = [
        'rank' => 'int',
        'is_active' => 'bool',
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('faq.table.faq');

        parent::__construct($attributes);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'category_id', 'id');
    }

    public function relationModels(): HasMany
    {
        return $this->hasMany(FaqRelation::class, 'faq_id', 'id');
    }
}
