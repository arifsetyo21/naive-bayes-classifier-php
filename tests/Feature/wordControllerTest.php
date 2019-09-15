<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class wordControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function word_controller_convert_json_to_collection(){
        $jsonData = '{"0":"honda","1":"pcx","2":"listrik","3":"resmi","5":"armada","6":"transportasi","7":"bas","8":"aplikasi","9":"gojek","10":"ada","11":"unit","12":"libat","13":"astra","14":"honda","15":"motor","16":"ahm","18":"agen","19":"pegang","20":"merek","21":"motor","22":"honda","24":"tanah","25":"air","26":"quotitu","27":"guna","28":"pcx","29":"listrik","34":"titik","36":"jakarta","40":"ratus","41":"unitquot","43":"direktur","44":"pasar","45":"pt","46":"ahm","47":"thomas","48":"wijaya","50":"temu","52":"medan","57":"honda","58":"pcx","59":"listrik","61":"rilis","62":"pabrikan","64":"januari","67":"status","70":"pasar","74":"sewa","75":"ya","76":"ahm","78":"enggan","79":"niaga","81":"konsumen","82":"retail","84":"mula","85":"pabrikan","86":"coba","87":"riset","88":"pasar","90":"indonesia","92":"sewa","93":"pcx","94":"listrik","96":"korporasi","97":"quotmasih","98":"business","99":"to","100":"business","101":"bb","103":"sewa","105":"usaha","106":"sajaquot","107":"singkat","114":"honda","116":"jepang","118":"awal","119":"honda","120":"sewa","121":"pcx","122":"listrik","124":"bb","126":"lingkup","127":"indonesia","129":"sewa","131":"unit","132":"taksir","133":"rp","134":"juta","137":"thomas","138":"amin","140":"salah","142":"alas","143":"kuat","144":"pabrikan","145":"sayap","146":"epak","148":"lepas","150":"retail","152":"regulasi","153":"kendara","154":"listrik","158":"ketuk","159":"palu","161":"soal","162":"infrastruktur","164":"kesiap","165":"konsumen","166":"tunggang","167":"sepeda","168":"motor","169":"setrum","170":"quotpertama","174":"sesuai","176":"kirakira","177":"pres","178":"kendara","179":"listrik","188":"ajar","190":"ekosistem","191":"guna","193":"indonesia","194":"bagaimanaquot","197":"dukung","198":"operasional","199":"pcx","200":"listrik","202":"ahm","203":"sedia","204":"fasilitas","205":"isi","206":"daya","207":"listrik","210":"lokasi","212":"jakarta","213":"astra","214":"motor","215":"jakarta","216":"jalan","217":"dewi","218":"sartika","219":"jakarta","220":"timur","221":"wahana","222":"makmur","223":"sejati","224":"jalan","225":"gunung","226":"sahari","227":"jakarta","228":"pusat","230":"ahass","231":"tanah","232":"abang","233":"motor","234":"jalan","235":"kh","236":"mas","237":"mansyur","238":"soal","239":"jantung","240":"mekanis","242":"bagasi","243":"semat","245":"baterai","246":"lithiumion","249":"kapasitas","251":"ah","252":"daya","253":"baterai","254":"listrik","256":"salur","258":"motor","259":"listrik","262":"muntah","263":"tenaga","265":"dk","267":"torsi","268":"maksimum","269":"nm","272":"cepat","273":"isi","274":"daya","275":"baterai","276":"pcx","277":"listrik","279":"data","280":"teknis","282":"kondisi","283":"kosong","285":"ratus","286":"persen","287":"makan","289":"jam","291":"metode","292":"offboard","293":"alias","294":"baterai","295":"copot","297":"bagasi","299":"letak","301":"honda","302":"mobile","303":"power","304":"pack"}';

        $word = new \App\Http\Controllers\WordController;

        $collection = $word->setWordAndCount($jsonData);

        $this->assertInstanceOf($collection, Illuminate\Support\Collection );

    }
}
