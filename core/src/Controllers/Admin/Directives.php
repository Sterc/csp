<?php

namespace Sterc\CSP\Controllers\Admin;

use Illuminate\Database\Eloquent\Builder;
use Sterc\CSP\Models\Directive;
use Vesp\Controllers\ModelController;

class Directives extends ModelController
{
    protected $primaryKey = 'key';
    protected $model = Directive::class;
}