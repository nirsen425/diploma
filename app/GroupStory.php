<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupStory extends Model
{
    /**
     * Получение Group привязанного к GroupStory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('App\Group');
    }
}
