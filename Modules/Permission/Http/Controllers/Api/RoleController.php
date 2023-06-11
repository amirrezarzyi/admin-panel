<?php

namespace Modules\Permission\Http\Controllers\Api;


use Modules\Api\Http\Controllers\ApiController;
use Modules\Permission\Entities\Role;
use Modules\Permission\Fields\RoleFields;
use Modules\Permission\Transformers\Role\RoleResourceCollection;

class RoleController extends ApiController
{
    public function index()
    {
        $roles = Role::with('children')
            ->select([
                RoleFields::ID,
                RoleFields::NAME,
                RoleFields::TITLE
            ])
            ->whereNull(RoleFields::PARENT_ID)
            ->get();

        return $this->successResponse(
            new RoleResourceCollection($roles),
            __response()
        );
    }
}
