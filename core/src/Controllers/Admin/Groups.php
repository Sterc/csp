<?php

namespace Sterc\CSP\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ResponseInterface;
use Sterc\CSP\Models\Directive;
use Sterc\CSP\Models\Group;
use Vesp\Controllers\ModelController;

class Groups extends ModelController
{
    protected $model = Group::class;

    public function prepareRow(Model $object): array
    {
        /** @var Group $object */
        $array = $object->toArray();
        $array['directives'] = $object->directives()->get()->toArray();

        return $array;
    }

    protected function beforeSave(Model $record): ?ResponseInterface
    {
        /** @var Group $record */
        if ($record->isDirty('active')) {
            Directive::query()
                ->where('group_id', $record->id)
                ->update(['active' => $record->active]);
        }

        return null;
    }
}