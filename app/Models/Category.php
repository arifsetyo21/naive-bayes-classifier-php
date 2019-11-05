<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use softDeletes;

    public function articles()
    {
        return $this->hasMany('App\Models\Article');
    }
    
    public function words()
    {
        return $this->hasManyThrough('App\Models\Word', 'App\Models\Article');
    }

    public function testDatas(){
        return $this->hasMany('App\Models\TestData', 'real_category_id');
    }

}
