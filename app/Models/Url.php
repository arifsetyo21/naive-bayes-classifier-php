<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = ['protocol', 'domain', 'category', 'slug', 'url'];
    protected $urls = [];

    public function setUrlsFromBulkListUrl(string $file){
        $this->urls = explode("\n", trim(file_get_contents($file)));
    }

    public function getUrls(){
        return $this->urls;
    }

    public function getUrlWithIndex(int $index){
        return $this->urls[$index];
    }

    public function explodeUrl(string $url){
        $urlAttribute = explode('/', $url);

        $data['protocol'] = trim($urlAttribute[0]);
        $data['domain'] = trim($urlAttribute[2]);
        $data['category'] = trim($urlAttribute[3]);
        $data['slug'] = trim($urlAttribute[4]);
        $data['url'] = trim($url);

        return $data;
    }

    public function saveToDB(string $url){
        if ($this->checkNotDuplicateUrl($url)) :
            $data = $this->explodeUrl($url);
            if (Url::create($data)) :
                return true;
            else : 
                return false;
            endif;
        else :
            throw new \Exception("Url is already in database");
            return false;
        endif;
    }

    public function checkNotDuplicateUrl(string $url){
        return Url::where('url', $url)->get()->isEmpty();        
    }
    
    public function article()
    {
        return $this->HasOne('App\Models\   ');
    }
}
