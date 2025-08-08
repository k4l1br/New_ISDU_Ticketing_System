<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Define role constants
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username', // ✅ must be here
        'email',
        'password',
        'role',
        'unit', // Add unit field for admin assignment
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is admin or super admin
     */
    public function isAdminOrSuperAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SUPER_ADMIN]);
    }

    /**
     * Get tickets assigned to this user's unit
     */
    public function assignedTickets()
    {
        if ($this->isAdmin()) {
            return $this->hasMany(\App\Models\ticket::class, 'unitResponsible', 'unit');
        }
        return collect();
    }

    /**
     * Get all available roles
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_ADMIN => 'Admin',
        ];
    }

    /**
     * Get all available units
     */
    public static function getUnits(): array
    {
        return [
            'ISDU (INFORMATION SYSTEMS DEVELOPMENT UNIT)' => 'ISDU (INFORMATION SYSTEMS DEVELOPMENT UNIT)',
            'NMU (NETWORK MANAGEMENT UNIT)' => 'NMU (NETWORK MANAGEMENT UNIT)',
            'REPAIR' => 'REPAIR',
            'MB (MANAGEMENT BRANCH)' => 'MB (MANAGEMENT BRANCH)',
        ];
    }
}
