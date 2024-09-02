<?php

namespace Sterc\CSP\Controllers\Admin;

use Illuminate\Database\Eloquent\Builder;
use Sterc\CSP\Models\Directive;
use Vesp\Controllers\ModelController;

class Directives extends ModelController
{
    protected string|array $primaryKey = 'key';
    protected string $model = Directive::class;
}