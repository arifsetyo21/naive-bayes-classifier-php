<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BeritaTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    // public function testExample()
    // {
    //     $this->assertTrue(true);
    // }

    /** @test */
    public function getter_setter(){
        $berita = new \App\Models\Berita('
                https://kumparan.com/@kumparanbola/tiba-di-hotel-persija-dapat-sambutan-meriah-dari-psm-1rboXdIfgaG
            ');
        $this->assertEquals('https:', $berita->protocol);
        $this->assertEquals('kumparan.com', $berita->domain);
        $this->assertEquals('@kumparanbola', $berita->category);
        $this->assertEquals('tiba-di-hotel-persija-dapat-sambutan-meriah-dari-psm-1rboXdIfgaG', $berita->slug);
    }

}
