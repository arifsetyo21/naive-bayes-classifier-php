<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UrlModelsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function url_set_and_get_method(){
        $url = new \App\Models\Url;
        $url->setUrlsFromBulkListUrl(public_path('log.scrap.@kumparanoto.with.l1.filtered'));
        
        $this->assertCount(10, $url->getUrls());
    }

    /** @test */
    public function get_url_with_index_for_test(){
        $url = new \App\Models\Url;
        $url->setUrlsFromBulkListUrl(public_path('log.scrap.@kumparanoto.with.l1.filtered'));

        $this->assertEquals('https://kumparan.com/@kumparanoto/bali-jadi-tuan-rumah-royal-enfield-jamboree-2019-1rbB9efo1aW', $url->getUrlWithIndex(0));
    }

    /** @test */
    public function check_url_to_database(){
        $url = new \App\Models\Url;
        $url->setUrlsFromBulkListUrl(public_path('log.scrap.@kumparanoto.with.l1.filtered'));

        $urlGenerator = \Faker\Factory::create();
        $newUrl = $urlGenerator->url;
        $this->assertTrue($url->checkNotDuplicateUrl($newUrl));
    }

    /** @test */
    // public function url_null_from_database(){
    //     $exampleUrl = \App\Models\Url::all()->first();
    //     $this->assertNull($exampleUrl);
    // }
    

    /** @test */
    public function explode_url(){
        $url = new \App\Models\Url;
        $url->setUrlsFromBulkListUrl(public_path('log.scrap.@kumparanoto.with.l1.filtered'));

        $urlAttribute = $url->explodeUrl($url->getUrlWithIndex(0));
        $this->assertArrayHasKey('protocol', $urlAttribute);
        $this->assertArrayHasKey('domain', $urlAttribute);
        $this->assertArrayHasKey('category', $urlAttribute);
        $this->assertArrayHasKey('slug', $urlAttribute);
        $this->assertArrayHasKey('url', $urlAttribute);

        $this->assertEquals('https:', $urlAttribute['protocol']);
        $this->assertEquals('kumparan.com', $urlAttribute['domain']);
        $this->assertEquals('@kumparanoto', $urlAttribute['category']);
        $this->assertEquals('bali-jadi-tuan-rumah-royal-enfield-jamboree-2019-1rbB9efo1aW', $urlAttribute['slug']);
        $this->assertEquals('https://kumparan.com/@kumparanoto/bali-jadi-tuan-rumah-royal-enfield-jamboree-2019-1rbB9efo1aW', $urlAttribute['url']);
    }

    /** @test */
    public function success_save_to_database(){
        $url = new \App\Models\Url;
        $url->setUrlsFromBulkListUrl(public_path('log.scrap.@kumparanoto.with.l1.filtered'));

        $urlGenerator = \Faker\Factory::create();
        $urlGenerator->imageUrl($urlGenerator->phoneNumber, $urlGenerator->phoneNumber, 'cats');
        $this->assertTrue($url->saveToDB($urlGenerator->imageUrl($urlGenerator->phoneNumber, $urlGenerator->phoneNumber, 'cats')));
    }

    /** @test */
    public function fail_save_to_db(){
        $url = new \App\Models\Url;
        $url_data = \App\Models\Url::all()->first();

        $this->expectException(\Exception::class);
        $url->saveToDB($url_data->url);
    }

    // /** @test */
    // public function check_throw_duplicate_url(){

    // }
}
