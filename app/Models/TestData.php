<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestData extends Model
{
    protected $table = 'testing_datas';

    protected $fillable = [
        'title',
        'url',
        'content',
        'real_category_id'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'real_category_id');
    }
}
