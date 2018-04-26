<?php

namespace LucasQuinnGuru\Entrust\Traits;

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package LucasQuinnGuru\Entrust
 */

use Illuminate\Support\Facades\Config;

trait EntrustPermissionTrait
{
    /**
     * Boot the permission model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the permission model uses soft deletes.
     *
     * @return void|bool
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($permission) {
            if (!method_exists(Config::get('entrust.permission'), 'bootSoftDeletes')) {
                $permission->roles()->sync([]);
            }

            return true;
        });
    }

    /**
     * Many-to-Many relations with role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->morphedByMany(Config::get('entrust.role'), "permissionable");
    }

    /**
     * Many-to-Many relations with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->morphedByMany(Config::get('auth.providers.users.model'), "permissionable");
    }
}
