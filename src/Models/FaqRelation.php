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
 * @property int $faq_id
 * @property Faq $faq
 * @property Carbon $created_at
 */
class FaqRelation extends Model
{
    public const UPDATED_AT = null;
    protected $fillable = [
        'model_type',
        'model_id',
        'faq_id',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('faq.table.faq_relation');

        parent::__construct($attributes);
    }

    public function faq(): BelongsTo
    {
        return $this->belongsTo(Faq::class, 'faq_id', 'id');
    }
}
