<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Libraries\simple_html_dom;

class ArticleModelsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function scrap_article_from_url(){
        $article = new \App\Http\Controllers\ArticleController;


    }
}
