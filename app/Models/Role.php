<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    const ADMIN = 1;
    const EMPLOYEE = 2; // Also used for 'holo tech'
    const STUDENT = 3;

    // You can add helper methods here if needed
}
