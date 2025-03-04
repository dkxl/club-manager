<?php

namespace App\Models;

use App\Models\Role;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUlids, HasApiTokens;

    public $incrementing = false; // primary key is a ULID, not an integer sequence


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'roles',
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


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'roles' => AsCollection::class,
    ];


    // The available user roles
    public static $availableRoles = [
        'admin',
        'staff',
        'sales',
        'kiosk',    // used for card entry kiosk
        'member'
    ];


    // Helper to access the static attribute
    public function getAvailableRoles() : array
    {
        return self::$availableRoles;
    }


    /**
     * Assign this role to the User
     * @param $role
     * @return void
     */
    public function assignRole($role) {
        $this->roles->put($role, true);
    }


    /**
     * Does the User have this role?
     * @param $role
     * @return bool
     */
    public function hasRole($role) {
        return $this->roles->has($role) && $this->roles->get($role);
    }


    /**
     * Remove this role from the User
     * @param $role
     * @return void
     */
    public function revokeRole($role) {
        $this->roles->put($role, false);
    }

}
