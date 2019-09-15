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

        $cleanArticle = $article->scrapContentKumparan('https://kumparan.com/@kumparanoto/pemuda-di-as-curi-listrik-demi-mobil-tesla-nya-1ratFdzUoPP');

        $this->assertArrayHasKey('url', $cleanArticle);
        $this->assertArrayHasKey('title', $cleanArticle);
        $this->assertArrayHasKey('content', $cleanArticle);

        $this->assertEquals('Pemuda di AS ‘Curi’ Listrik Demi Mobil Tesla-nya', $cleanArticle['title']);
    }

    /** @test */
    // public function save_article_to_db_with_faker_data(){
    //     $article = new \App\Http\Controllers\ArticleController;
        
    //     $faker = \Faker\Factory::create();

    //     $data['title'] = $faker->realText($maxNbChars = 50, $indexSize = 1);
    //     $data['content'] = [
    //         0 => $faker->realText($maxNbChars = 200, $indexSize = 1),
    //         1 => $faker->realText($maxNbChars = 200, $indexSize = 1)
    //     ];

    //     $data['url'] = $faker->url;

    //     $article->saveScrappedArticle($data);

    //     $checkSaved = \App\Models\Article::where('title', '=', $data['title'])->exists();

    //     $this->assertTrue($checkSaved);

    // }

    /** @test */
    public function save_article_to_db(){
        $article = new \App\Http\Controllers\ArticleController;
        
        $url = \App\Models\Url::inRandomOrder()->first()->url;

        $articleToSave = $article->scrapContentKumparan($url);

        $article->saveScrappedArticle($articleToSave);

        $checkSaved = \App\Models\Article::where('title', '=', $article->title)->exists();

        $this->assertTrue($checkSaved);
    }
    // TODO create save to article table feature with checking duplicate article
    
    /** @test */
    // public function save_article_with_checking_duplicate_article_url_from_db(){

        // $data = [
        //     'url' => 'http://abernathy.com/',
        //     'title' => 'It WAS when she soon as she got up.',
        //     'content' => '',
        //     'url_id' => 23
        // ];

        // $data = \App\Models\Url::first()->toArray();

        // $article = new \App\Http\Controllers\ArticleController;

        // $new_article = new \App\Models\Article;
        // $new_article->url = $data['url'];
        // $new_article->title = $data['title'];
        // $new_article->url_id = $data['url_id'];
        // $new_article->content = $data['content'];
        // $new_article->save();

        // $this->expectException();
    //     $checkSaved = $article->saveScrappedArticle($data);

    //     $this->assertFalse($checkSaved);
    // }

    /** @test */
    public function check_cleaned_stemmed_and_save_article_which_cleaned(){
        $data = [
            'url' => 'https://kumparan.com/@kumparanoto/pemuda-di-as-curi-listrik-demi-mobil-tesla-nya-1ratFdzUoPP',
            'title' => 'Pemuda di AS ‘Curi’ Listrik Demi Mobil Tesla-nya',
            'content' => [
                "Tren penggunaan mobil listrik memang sudah meningkat di beberapa negara, salah satunya tent
           u negara Paman Sam. Di negara ini, mobil listrik seperti Tesla sudah cukup banyak berlalu-lalang 
           di jalanan.",
                "Penggunaan daya listrik pada mobil Tesla, tentu membuatnya menjadi jauh lebih efisien, rama
           h lingkungan dan juga praktis. Praktis, karena sang pemilik Tesla tersebut dapat melakukan pengisian daya mobilnya di mana saja selama ada colokan listrik.",
                "Meskipun dapat mencolok listrik di mana saja, tetapi sebaiknya jangan mencontoh seperti yan
           g dilakukan seorang pemuda di Florida, Amerika Serikat ini.",
                "Dikutip dari Carscoops, pemuda tersebut dengan sengaja mencolokkan kabel pengisian daya Tes
           la Model 3 miliknya di stop kontak rumah orang tanpa izin terlebih dahulu. Bahkan, pemuda tersebu
           t melakukan pengisian daya mobilnya selama kurang lebih 12 jam.",
                "Kejadian tersebut pertama kali diungkap oleh tukang kebun dari pemilik rumah tersebut. Saat
            itu, tukang kebun tersebut membangunkan pemilik rumah yang diketahui bernama Fraumeni dan memint
           anya untuk memindahkan Tesla Model 3 tersebut.",
                "Fraumeni yang merasa tidak memiliki mobil Tesla Model 3 pun merasa kebingungan dan segera m
           enghubungi pihak kepolisian. Setelah beberapa jam menunggu, ternyata pemuda pemilik mobil tersebu
           t pun muncul di depan rumah Fraumeni.",
                "Pemuda itu mengatakan, bahwa malam sebelumnya dirinya berencana berkunjung ke rumah temanny
           a di sekitar wilayah situ. Namun, mobil Tesla Model 3 yang dia kemudikan, ternyata mengalami mogo
           k karena kehabisan daya baterai.",
                "Tanpa berpikir panjang, pemuda yang tidak diketahui identitasnya itu langsung mengambil kab
           el pengisian daya mobilnya dan mencolokkan ke soket yang berada di luar rumah Fraumeni.",
              ],
            'url_id' => 52
        ];

        $article = new \App\Http\Controllers\ArticleController;        

        $id = $article->saveScrappedArticle($data);
        $new_article = \App\Models\Article::findOrFail($id);
        
        $this->assertInstanceOf('\App\Models\Article', $new_article);

        $word = $article->preprocessArticle($new_article);
        $word_stemmed = $article->stemmingWord();
        $check_store_to_db = $article->saveCleanedArticle();
        $check_clean_clear_content = $article->cleanContentCleaned();
        $check_softdelete_from_db = $article->deleteArticle();

        // $check_softdelete_from_db = $article->deleteArticle();

        // - ubah jadi huruf kecil
        // - Hapus kata dalam tanda kurung
        // - hilangkan tanda khusus ( ',', '.', ':', ';', '/', '\', '''', '""', )
        $content_cleaned_json = '{"0":"tren","1":"penggunaan","2":"mobil","3":"listrik","6":"meningkat","9":"negara","10":"salah","11":"satunya","12":"tent","14":"negara","15":"paman","16":"sam","18":"negara","20":"mobil","21":"listrik","23":"tesla","27":"berlalulalang","29":"jalanan","30":"penggunaan","31":"daya","32":"listrik","34":"mobil","35":"tesla","37":"membuatnya","41":"efisien","42":"rama","44":"lingkungan","47":"praktis","48":"praktis","50":"sang","51":"pemilik","52":"tesla","56":"pengisian","57":"daya","58":"mobilnya","64":"colokan","65":"listrik","68":"mencolok","69":"listrik","76":"mencontoh","78":"yan","82":"pemuda","84":"florida","85":"amerika","86":"serikat","88":"dikutip","90":"carscoops","91":"pemuda","94":"sengaja","95":"mencolokkan","96":"kabel","97":"pengisian","98":"daya","99":"tes","100":"la","101":"model","102":"miliknya","104":"stop","105":"kontak","106":"rumah","107":"orang","109":"izin","113":"pemuda","114":"tersebu","117":"pengisian","118":"daya","119":"mobilnya","123":"jam","124":"kejadian","127":"kali","128":"diungkap","130":"tukang","131":"kebun","133":"pemilik","134":"rumah","138":"tukang","139":"kebun","141":"membangunkan","142":"pemilik","143":"rumah","146":"bernama","147":"fraumeni","149":"memint","150":"anya","152":"memindahkan","153":"tesla","154":"model","156":"fraumeni","160":"memiliki","161":"mobil","162":"tesla","163":"model","166":"kebingungan","170":"enghubungi","172":"kepolisian","175":"jam","176":"menunggu","178":"pemuda","179":"pemilik","180":"mobil","181":"tersebu","184":"muncul","187":"rumah","188":"fraumeni","189":"pemuda","193":"malam","196":"berencana","197":"berkunjung","199":"rumah","200":"temanny","204":"wilayah","205":"situ","207":"mobil","208":"tesla","209":"model","212":"kemudikan","214":"mengalami","215":"mogo","218":"kehabisan","219":"daya","220":"baterai","222":"berpikir","224":"pemuda","228":"identitasnya","230":"langsung","231":"mengambil","232":"kab","233":"el","234":"pengisian","235":"daya","236":"mobilnya","238":"mencolokkan","240":"soket","245":"rumah","246":"fraumeni"}';
        $content_stemmed_json = '{"0":"tren","1":"guna","2":"mobil","3":"listrik","6":"tingkat","9":"negara","10":"salah","11":"satu","12":"tent","14":"negara","15":"paman","16":"sam","18":"negara","20":"mobil","21":"listrik","23":"tesla","27":"berlalulalang","29":"jalan","30":"guna","31":"daya","32":"listrik","34":"mobil","35":"tesla","37":"buat","41":"efisien","42":"rama","44":"lingkung","47":"praktis","48":"praktis","50":"sang","51":"milik","52":"tesla","56":"isi","57":"daya","58":"mobil","64":"colok","65":"listrik","68":"colok","69":"listrik","76":"contoh","78":"yan","82":"pemuda","84":"florida","85":"amerika","86":"serikat","88":"kutip","90":"carscoops","91":"pemuda","94":"sengaja","95":"colok","96":"kabel","97":"isi","98":"daya","99":"tes","100":"la","101":"model","102":"milik","104":"stop","105":"kontak","106":"rumah","107":"orang","109":"izin","113":"pemuda","114":"sebu","117":"isi","118":"daya","119":"mobil","123":"jam","124":"jadi","127":"kali","128":"ungkap","130":"tukang","131":"kebun","133":"milik","134":"rumah","138":"tukang","139":"kebun","141":"bangun","142":"milik","143":"rumah","146":"nama","147":"fraumeni","149":"memint","150":"anya","152":"pindah","153":"tesla","154":"model","156":"fraumeni","160":"milik","161":"mobil","162":"tesla","163":"model","166":"bingung","170":"enghubungi","172":"polisi","175":"jam","176":"tunggu","178":"pemuda","179":"milik","180":"mobil","181":"sebu","184":"muncul","187":"rumah","188":"fraumeni","189":"pemuda","193":"malam","196":"rencana","197":"kunjung","199":"rumah","200":"temanny","204":"wilayah","205":"situ","207":"mobil","208":"tesla","209":"model","212":"kemudi","214":"alami","215":"mogo","218":"habis","219":"daya","220":"baterai","222":"pikir","224":"pemuda","228":"identitas","230":"langsung","231":"ambil","232":"kab","233":"el","234":"isi","235":"daya","236":"mobil","238":"colok","240":"soket","245":"rumah","246":"fraumeni"}';

        // $this->assertCount(228, $word->all());
        $this->assertFalse($word->search('di', true));
        $this->assertEquals($content_cleaned_json, $word->toJson());
        $this->assertEquals($content_stemmed_json, $word_stemmed->toJson());

        $check_deleteArticlePermanent_from_db = $article->deleteArticlePermanent();
        $this->assertTrue($check_store_to_db);
        $this->assertTrue($check_clean_clear_content);
        $this->assertTrue($check_softdelete_from_db);
        $this->assertTrue($check_deleteArticlePermanent_from_db);
        

        // TODO 
        // - tokenizing (ok)
        // - stemming
        // - menghilangkan url (ok)
        // - menghilangkan mention username '@...' (ok)
        // - menghilangkan hastag '#...' (ok)
        // - Pecah kalimat menjadi kata tunggal (ok)
        // - Pengecekan sinonim 
        // - Menghapus kata tertentu (ok)
        // - menghapus kata dengan 1 karakter (ok)
        // - Menghapus stopword (ok)
        // - Gabung kalimat ()
        // - Simpan data
    }

    /** @test */
    // public function stemming_from_article_and_save_to_db(){
    //     // $
    // }
    
}
