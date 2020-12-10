<?php

namespace App\Models;

use Backpack\PermissionManager\app\Models\Permission as OriginalPermission;

class Permission extends OriginalPermission
{
	use \Venturecraft\Revisionable\RevisionableTrait;
    use \App\Models\Traits\RevisionableInitTrait;
}
