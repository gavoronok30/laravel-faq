<?php

namespace Crow\Faq\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Config;

/**
 * @property-read int $id
 * @property string $title
 * @property string $description
 * @property string $slug
 * @property int $rank
 * @property bool $is_active
 * @property array $properties
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Faq[]|Collection $faqs
 * @property Faq[]|Collection $faqsActive
 * @property FaqCategoryRelation[]|Collection $relationModels
 *
 * @method static Builder|static query()
 * @method static Builder|static find(int $id, array $columns = ['*'])
 * @method static Builder|static findOrFail(int $id, array $columns = ['*'])
 * @method static Builder|static first(array $columns = ['*'])
 * @method static Builder|static firstOrFail(array $columns = ['*'])
 * @method static Collection|static[] get(array $columns = ['*'])
 */
class FaqCategory extends Model
{
    protected $fillable = [
        'title',
        'description',
        'slug',
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
        $this->table = Config::get('faq.table.category');

        parent::__construct($attributes);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class, 'category_id', 'id');
    }

    public function faqsActive(): HasMany
    {
        return $this->hasMany(Faq::class, 'category_id', 'id')
            ->where('is_active', '=', true);
    }

    public function relationModels(): HasMany
    {
        return $this->hasMany(FaqCategoryRelation::class, 'category_id', 'id');
    }
}
