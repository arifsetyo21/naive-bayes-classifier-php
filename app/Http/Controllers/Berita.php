<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Exceptions\Handler as Exception;

class Berita extends Controller
{
    private $domain,
            $protocol,
            $category,
            $slug;

    public function __construct(string $url){
        $berita_attr = explode('/', $url);

        $this->protocol = trim($berita_attr[0]);
        $this->domain = trim($berita_attr[2]);
        $this->category = trim($berita_attr[3]);
        $this->slug = trim($berita_attr[4]);
    }

    public function __set($property, $value){
        if( property_exists($this, $property)){
            $this->{$property} = $value;
        }
    }

    public function __get($property){
        if( property_exists($this, $property)){
            return $this->{$property};
        } else {
            throw new \Exception('Property doesnt exist');
        }
    }

    
}
