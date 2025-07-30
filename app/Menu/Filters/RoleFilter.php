<?php

namespace App\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Illuminate\Support\Facades\Auth;

class RoleFilter implements FilterInterface
{
    public function transform($item)
    {
        if (isset($item['can'])) {
            $user = Auth::user();
            
            if (!$user) {
                return false;
            }

            $allowedRoles = is_array($item['can']) ? $item['can'] : [$item['can']];
            
            // Check if user's role is in allowed roles
            if (!in_array($user->role, $allowedRoles)) {
                return false;
            }
        }

        return $item;
    }
}
