<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * Получение Students привязанных к Groups
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function students()
    {
        return $this->hasMany('App\Student');
    }

    /**
     * Получение Files, привязанных к Group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->belongsToMany('App\File', 'groups_files', 'group_id', 'files_id');
    }
}
