<?php
namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepository;

class AdminSystemPermissionRepository extends BaseRepository
{
    public function generatePermissionHash($permissionAction)
    {
        return md5($permissionAction);
    }
}
