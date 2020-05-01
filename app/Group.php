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
}
