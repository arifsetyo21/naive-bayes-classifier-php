<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Word extends Model
{
    use softDeletes;

    protected $fillable = ['article_id', 'word_term'];

    public function article()
    {
        return $this->belongsTo('App\Models\Article');
    }
}
