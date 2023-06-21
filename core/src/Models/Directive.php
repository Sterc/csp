<?php

namespace Sterc\CSP\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $group_id
 * @property int $key
 * @property ?array $value
 * @property ?string $description
 * @property bool $active
 *
 * @property-read Group $group
 */
class Directive extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'csp_directives';
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    protected $fillable = ['key', 'type', 'value', 'description', 'active'];
    protected $casts = [
        'active' => 'bool',
        'value' => 'array',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}