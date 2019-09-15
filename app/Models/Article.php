<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    use SoftDeletes;
    protected $fillable = ['category_id', 'url_id', 'title', 'content', 'url', 'content_cleaned'];
    
    public function url(){
        return $this->belongsTo('App\Models\Url');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function words()
    {
        return $this->hasMany('App\Models\Word');
    }
}
