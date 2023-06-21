<?php

namespace Sterc\CSP\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $key
 * @property string $title
 * @property ?string $description
 * @property bool $active
 *
 * @property-read Directive[] $directives
 */
class Group extends Model
{
    public $timestamps = false;
    protected $table = 'csp_groups';
    protected $fillable = ['key', 'title', 'description', 'active'];
    protected $casts = ['active' => 'bool'];

    public function directives(): HasMany
    {
        return $this->hasMany(Directive::class);
    }
}