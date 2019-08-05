<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\simple_html_dom;

class ScrapController extends Controller
{
    public function index(){
        $html = new simple_html_dom();

        $urls = file_get_contents(public_path('log.scrap.@kumparanoto.with.l1.filtered'));
        $urls = explode("\n", "$urls");
        array_pop($urls);   
        foreach ($urls as $url) {
            $matched = explode("/", $url);
            unset($matched[1]);
            print_r($matched);
        }
        // Load HTML from a URL
        // $html->load_file('http://www.google.com/');
        // $imgs = $html->find('img[src]');
        // foreach ($imgs as $img) {
        //     echo $img->getAllAttributes()['src'];
        //     echo "<br>";
        // }
        // dd($imgs);
    }
}
