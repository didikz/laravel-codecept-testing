<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * user relation
     */
    public function user()
    {
        $this->belongsTo(User::class);
    }
}
