<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    protected $table = 'classification_history';

    protected $fillable = [
        'title', 
        'url', 
        'content', 
        'classification_nbc_result', 
        'classification_modified_result', 
        'total_document', 
        'total_term', 
        'real_category',
        'prediction_nbc',
        'prediction_modified'
    ];

    public function category_prediction_nbc()
    {
        return $this->belongsTo('App\Models\Category', 'prediction_nbc');
    }

    public function category_prediction_modified()
    {
        return $this->belongsTo('App\Models\Category', 'prediction_modified');
    }
    public function category_real_category()
    {
        return $this->belongsTo('App\Models\Category', 'real_category');
    }

}
