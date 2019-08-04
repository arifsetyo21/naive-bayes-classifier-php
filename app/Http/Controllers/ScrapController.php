<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\simple_html_dom;

class ScrapController extends Controller
{
    public function index(){
        $html = new simple_html_dom();

        // Load HTML from a URL
        $html->load_file('http://www.google.com/');
        $imgs = $html->find('img[src]');
        foreach ($imgs as $img) {
            echo $img->getAllAttributes()['src'];
            echo "<br>";
        }
        dd($imgs);
    }
}
