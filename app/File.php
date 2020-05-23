<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'extension'
    ];

    /**
     * Получение Groups, привязанных к File.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }

    /**
     * Accessor возвращающий время загрузки файла в виде timestamp
     *
     * @param $value
     * @return int timestamp
     */
    public function getCreatedAtAttribute($value)
    {
        $date = $this->asDateTime($value);
        return $date->getTimestamp();
    }
}
