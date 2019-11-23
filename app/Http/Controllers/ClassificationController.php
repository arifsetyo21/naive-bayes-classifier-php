<?php

namespace App\Http\Controllers;

use Goutte\Client;
use App\Models\Article;
use App\Models\TestData;
use App\Models\Category;
use App\Models\Dashboard;
use Illuminate\Http\Request;
use App\Jobs\ScrapArticleJob;
use App\Jobs\ClassificationJob;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Jobs\ScrapArticleTestingJob;
use Illuminate\Support\Facades\Redis;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\ArticleController;

class ClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // for explode string with many parameter
    public function multiexplode ($delimiters,$string) {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }
    
    public function list(){

        $data_testing_detail = Category::withCount(['testDatas'])->get();

        $articles = TestData::with('category')->paginate(10);
        return view('classification.list', compact('articles', 'data_testing_detail'));
    }

    public function index()
    {
        $categories = Category::all('id', 'name');
        return view('classification.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('classification.create', compact('categories'));
    }

    public function storeDataTesting(Request $request){
              // list parameter for delimiter
        $delimiter = [PHP_EOL, ' ', ','];
        $real_category_id = (int) $request->real_category_id;
        
        // separate string dbto array
        $urls = (object) collect(
            array_filter(
                $this->multiexplode($delimiter, $request->url)
            )
        );

        // return dd($urls);

        $collection = $urls->map(function ($item, $key) use ($real_category_id){

            $url["url"] = trim($item);
            $url["real_category_id"] = $real_category_id;

            // return dd($url);

            $validation = \Validator::make($url, [
                "url" => "required|unique:testing_datas,url",
                "real_category_id" => "required"
            ])->validate();

            return collect ( [(object) [ 
                'url' => $url["url"],
                'real_category_id' => $url["real_category_id"]
            ]]); 
        });
        // return dd($collection);

        $collection->map(function ($item, $key) {
            // $scrapArticleTestingJob = new ScrapArticleTestingJob($item);
            ScrapArticleTestingJob::dispatch($item);
        });

        Alert::success('Sukses', 'Url Berhasil Ditambahkan di Antrian');
        return redirect()->route('classification.index');
     
    }

    public function scrapContentKumparan(Collection $collection){

        $collection->map(function ($item, $key) {

            $url = trim($item->url);

            $client = new Client();
            $crawler = $client->request('GET', $url);
            $title = $crawler->filter('h1')->each(function ($node) {return $node->text();});
    
            $content = $crawler->filter('div.clLKZY.components__NormalWidth-sc-1ukv6c0-0')->each(function ($node) {return $node->text();});
    
            unset($client);
    
            return $this->saveScrappedArticle([
                'title' => $title[0],
                'content' => $content,
                'url' => $item->url,
                'real_category_id' => $item->real_category_id
            ]);
        });
    }

    public function classificationAll(Request $request){
        // return dd($request);
        // return dd($request instanceof Collection);

        // Select article whereNotIn words, that mean, select article where not preprocess
        $articles = TestData::all();

        // foreach ($articles as $key => $article) {
        //     // return dd($article);
        //     try {
        //         ClassificationJob::dispatch($article);
        //         Alert::success('Preprocess Dimasukkan ke Antrian');
        //         return redirect()->back();
        //     } catch (\Exception $e) {
        //         Alert::error('Gagal Preprocessing', $e->getMessage());
        //         return redirect()->back();
        //     }
        // }

        try {
            $articles->map(function ($item, $key) use ($request){
                // $id = $item->id;
                $request->replace(['requestUri' => 'oke']);
                $request->request->add(['id' => $item->id]);
                $request->request->add(['articleTitle' => $item->title]);
                $request->request->add(['real_category' => $item->real_category_id]);
                $request->request->add(['articleText' => $item->content]);


                // return dd($request);
                // return dd((object) $item->id);
                // $this->direct((object) $item->id);
                return ClassificationJob::dispatch($request->all());
                // if($this->direct($item)){
                    
                // }
                // Alert::success('oke');
                // return redirect()->back();
            });
            Alert::success('Preprocess Dimasukkan ke Antrian');
            return redirect()->back();
            
        } catch (\Exception $e) {
            Alert::error('Gagal Preprocessing', $e->getMessage());
            return redirect()->back();
        }

    }

    public function classificationSingle($id){
        $request = new \Illuminate\Http\Request();

        $request->setMethod('POST');
        $request->request->add(['id' => $id]);
        $this->direct($request);
        return direct()->back();
    }

    // TODO Buat queue untuk klasifikasi
    public function direct(Request $request){

        $article_testing = TestData::findOrFail($request->id);
        // return dd($article_testing);
        $request->request->add(['articleTitle' => $article_testing->title]);
        $request->request->add(['real_category' => $article_testing->real_category_id]);
        $request->request->add(['articleText' => $article_testing->content]);

        try {
            // ClassificationJob::dispatch($request->all());
            $this->store($request);
            Alert::success('Data Selesai Diklasifikasi');
            $article_testing->delete();
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error($e->getMessage());
            return redirect()->back();
        }        
    }

    public function saveScrappedArticle(array $article){

        $validation = \Validator::make($article,[
            "url" => "unique:testing_datas,url"
        ])->validate();
        
        $article['content'] = json_encode($article['content']);

        return \App\Models\TestData::create($article)->id;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request){
        
        $modified = $this->nbcModified($request);
        $nbc = $this->nbc($request);

        // return dd($nbc['lower_value']);

        unset($nbc['lower_value']['words']);
        unset($modified['lower_value']['words']);
        unset($nbc['classprediction']['words']);
        unset($modified['classprediction']['words']);

        $document_count = new Article;

        try {
            $dashboard = Dashboard::create([
                'title' => json_encode($request->articleTitle),
                'classification_nbc_result' => json_encode($nbc['classprediction']->push($nbc['lower_value'])->toJson()),
                'classification_modified_result' => json_encode($modified['classprediction']->push($modified['lower_value'])->toJson()),
                'total_document' => $document_count->count(),
                'total_term' => $nbc['result']['total_words'],
                'real_category' => (int) $request->real_category,
                'prediction_nbc' => $nbc['classprediction']['category_id'],
                'prediction_modified' => $modified['classprediction']['category_id'],
            ]); 

            Redis::hSet('nbc', $dashboard->id, $nbc['total_time']);
            Redis::hSet('modified', $dashboard->id, $modified['total_time']);

            Alert::success('Berhasil DiKlasifikasi');
            // return redirect()->back();
        } catch (\Exception $e) {
            Alert::error($e->getMessage());
            return redirect()->back();
        }

        // return dd($nbc, $modified);
        return view('classification.result', ['result' => $nbc, 'result_modified' => $modified]);
    }

    public function nbc(Request $request)
    {

        $mtime = microtime(); 
        $mtime = explode(" ",$mtime); 
        $mtime = $mtime[1] + $mtime[0]; 
        $starttime = $mtime; 

        $messages = [
            'required' => ':attribute harus diisi.',
        ];

        \Validator::make($request->all(), [
            "articleTitle" => "required",
            "articleText" => "required",
        ], $messages)->validate();

        $article_text = $request->articleText;
        $article_title = $request->articleTitle;
        $token[] = $this->multiexplode([PHP_EOL, "\n", ' ', '-', '|', '/'], $article_text);
        
        foreach($token as $tkn){
            foreach($tkn as $index => $t){ 
                $k = strtolower(preg_replace('/[^a-zA-Z ]/', '', $t));
                if($k == ''){
                    unset($tkn[$index]);
                } else {
                    $string[] = $k;
                }
            }
        }

        $stopwords_list = collect(explode("\n", file_get_contents(\public_path('stopword_list_tala.txt'))));
        // stopword wordlist : http://hikaruyuuki.lecture.ub.ac.id/kamus-kata-dasar-dan-stopword-list-bahasa-indonesia/

        $this->wordlist = \collect($string);

        $this->wordlist = $this->wordlist->filter(function($value, $key){
            return strlen($value) > 1;
        });

        foreach($this->wordlist as $index => $s) {
            if(\in_array($s, $stopwords_list->toArray())) :
                unset($this->wordlist[$index]);
            endif;
        }

        $category_list = Category::all();

        $word_total = DB::table('words')->count();

        // Lakukan perulangan untuk collection $category_list
        $by_category = $category_list->map( function($category) use ($word_total){

            // Ambil total kata pada sebuah kelas
            $word_count_in_category = DB::table('words')
                                    ->join('articles', 'words.article_id', '=', 'articles.id')
                                    ->select('words.*', 'articles.category_id')
                                    ->where('category_id', '=', $category->id)->count();

            // Lakukan perulangan untuk collection di $this->wordlist
            $words = $this->wordlist->map(function ($value) use ($category, $word_total, $word_count_in_category){
                $word_stemmed = app('stemm')->stem($value);

                $word_count = DB::table('words')
                            ->join('articles', 'words.article_id', '=', 'articles.id')
                            ->select('words.*', 'articles.category_id')
                            ->where('word_term', '=', $word_stemmed)
                            ->where('category_id', '=', $category->id)->count();

                return [
                    'word' => $word_stemmed,
                    'word_count' => $word_count,
                    'word_count_in_category' => $word_count_in_category,
                    'word_total' => $word_total,
                    'nbc_value_per_word' => log(($word_count + 1) / ($word_count_in_category + $word_total), 10)
                ];
            });

            return [
                'category' => $category->name,
                'category_id' => $category->id,
                'words' => $words->toArray(),
                // Menampilkan jumlah kata pada sebuah kategori atau count(C)
                'words_count_in_category' => $word_count_in_category,
                'nbc_value_per_class' => $words->sum('nbc_value_per_word')
            ];
        });

        $result = collect([
            'category' => $by_category->keyBy('category')->sortBy('category'),
            // Menampilkan total words atau |V|
            'total_words' => $word_total,
        ]);

        $mtime = microtime(); 
        $mtime = explode(" ",$mtime); 
        $mtime = $mtime[1] + $mtime[0]; 
        $endtime = $mtime; 
        $totaltime = ($endtime - $starttime); 
        // echo "This page was created in ".$totaltime." seconds"; 

        // return dd($result['category']->keyBy('category')->sortBy('nbc_value_per_class')->first());
        // return view('classification.result', ['result' => $result->toArray(), 'class_prediction' => $result['category']->keyBy('category')->sortByDesc('nbc_value_per_class')->first()]);
        return collect(['result' => $result, 
                        'classprediction' => $result['category']->keyBy('category')->sortByDesc('nbc_value_per_class')->first(),
                        'lower_value' => $result['category']->keyBy('category')->sortBy('nbc_value_per_class')->first(),
                        'total_time' => $totaltime
                        ])->recursive();
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //  TODO Benahin klasifikasi yang modifikasi agar menyesuaikan data training yang 1/3 awal 
    public function nbcModified(Request $request)
    {
        $mtime = microtime(); 
        $mtime = explode(" ",$mtime); 
        $mtime = $mtime[1] + $mtime[0]; 
        $starttime = $mtime; 

        // dd($request);
        $messages = [
            'required' => ':attribute harus diisi.',
        ];
        \Validator::make($request->all(), [
            "articleTitle" => "required",
            "articleText" => "required",
        ], $messages)->validate();
        $article_text = $request->articleText;
        $article_title = $request->articleTitle;
        $token[] = $this->multiexplode([PHP_EOL, "\n", ' ', '-', '|', '/'], $article_text);
        foreach($token as $tkn){
            foreach($tkn as $index => $t){ 
                $word_token_url_removed = \preg_replace('(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})', '', $t);
                $word_token_case_folded = strtolower(preg_replace('/[^a-zA-Z ]/', '', $word_token_url_removed));
                if($word_token_case_folded == ''){
                    unset($tkn[$index]);
                } else {
                    $string[] = $word_token_case_folded;
                }
            }
        }
        $stopwords_list = collect(explode("\n", file_get_contents(\public_path('stopword_list_tala.txt'))));
        // stopword wordlist : http://hikaruyuuki.lecture.ub.ac.id/kamus-kata-dasar-dan-stopword-list-bahasa-indonesia/
        try {
            $this->wordlist = \collect($string);
        } catch (\Exception $e) {
            return back()->withError('hindari penggunaan link, tagar (#), et (@)');
        }
        $this->wordlist = $this->wordlist->filter(function($value, $key){
            return strlen($value) > 1;
        });
        foreach($this->wordlist as $index => $s) {
            if(\in_array($s, $stopwords_list->toArray())) :
                unset($this->wordlist[$index]);
            endif;
        }
        $category_list = Category::all();
        $word_total = DB::table('words')->count();
        // Lakukan perulangan untuk collection $category_list
        $by_category = $category_list->map( function($category) use ($word_total){
            // Ambil total kata pada sebuah kelas
            $word_count_in_category = DB::table('words')
                                    ->join('articles', 'words.article_id', '=', 'articles.id')
                                    ->select('words.*', 'articles.category_id')
                                    ->where('category_id', '=', $category->id)->count();
            // Lakukan perulangan untuk collection di $this->wordlist
            $words = $this->wordlist->map(function ($value, $key) use ($category, $word_total, $word_count_in_category){
                $word_stemmed = app('stemm')->stem($value);
                $word_count = DB::table('words')
                            ->join('articles', 'words.article_id', '=', 'articles.id')
                            ->select('words.*', 'articles.category_id')
                            ->where('word_term', '=', $word_stemmed)
                            ->where('category_id', '=', $category->id)->count();
                // Hitung pertiga posisi kata
                $pertiga = round(($this->wordlist->count() * (33.3/100)) + 1);
                if( $key < $pertiga ) {
                    $word_count *= 2;
                    return [
                        // 'word' => $word_stemmed,
                        'word_count' => $word_count,
                        'word_count_in_category' => $word_count_in_category,
                        'word_total' => $word_total,
                        'nbc_value_per_word' => log(($word_count + 1) / ($word_count_in_category + $word_total), 10)
                    ];
                } else {
                    return [
                        'word' => $word_stemmed,
                        'word_count' => $word_count,
                        'word_count_in_category' => $word_count_in_category,
                        'word_total' => $word_total,
                        'nbc_value_per_word' => log(($word_count + 1) / ($word_count_in_category + $word_total), 10)
                    ];
                }
            });
        
            return [
                'category' => $category->name,
                'category_id' => $category->id,
                'words' => $words->toArray(),
                // Menampilkan jumlah kata pada sebuah kategori atau count(C)
                'words_count_in_category' => $word_count_in_category,
                'nbc_value_per_class' => $words->sum('nbc_value_per_word')
            ];
        });
        
        $result = collect([
            'category' => $by_category->keyBy('category')->sortBy('category'),
            // Menampilkan total words atau |V|
            'total_words' => $word_total,
        ]);

        $mtime = microtime(); 
        $mtime = explode(" ",$mtime); 
        $mtime = $mtime[1] + $mtime[0]; 
        $endtime = $mtime; 
        $totaltime = ($endtime - $starttime); 
        // echo "This page was created in ".$totaltime." seconds"; 

        // 'category' => $by_category->keyBy('category')->sortByDesc('nbc_value_per_class'),
        // return dd($result['category']->keyBy('category')->sortByDesc('nbc_value_per_class')->first(), $result);
        // return view('classification.result', ['result' => $result->toArray(), 'class_prediction' => $result['category']->keyBy('category')->sortByDesc('nbc_value_per_class')->first()]);
        return collect([
            'result' => $result->toArray(), 
            'classprediction' => $result['category']->keyBy('category')->sortByDesc('nbc_value_per_class')->first(),
            'lower_value' => $result['category']->keyBy('category')->sortBy('nbc_value_per_class')->first(),
            'total_time' => $totaltime
            ])->recursive();
        
    }    

    // public function nbcModified(Request $request)
    // {

    //     $messages = [
    //         'required' => ':attribute harus diisi.',
    //     ];

    //     \Validator::make($request->all(), [
    //         "articleTitle" => "required",
    //         "articleText" => "required",
    //     ], $messages)->validate();

    //     $article_text = $request->articleText;
    //     $article_title = $request->articleTitle;
    //     $article_text = explode("\n", $article_text);
        
    //     foreach($article_text as $array) {
    //         $token[] = explode(" ", $array);
    //     }

    //     foreach($token as $tkn){
    //         foreach($tkn as $index => $t){ 
    //             $k = strtolower(preg_replace('/[^a-zA-Z ]/', '', $t));
    //             if($k == ''){
    //                 unset($tkn[$index]);
    //             } else {
    //                 $string[] = $k;
    //             }
    //         }
    //     }

    //     $stopwords_list = collect(explode("\n", file_get_contents(\public_path('stopword_list_tala.txt'))));
    //     // stopword wordlist : http://hikaruyuuki.lecture.ub.ac.id/kamus-kata-dasar-dan-stopword-list-bahasa-indonesia/

    //     $this->wordlist = \collect($string);

    //     $this->wordlist = $this->wordlist->filter(function($value, $key){
    //         return strlen($value) > 1;
    //     });

    //     foreach($this->wordlist as $index => $s) {
    //         if(\in_array($s, $stopwords_list->toArray())) :
    //             unset($this->wordlist[$index]);
    //         endif;
    //     }

    //     $category_list = Category::all();

    //     $this->category_list = \collect(Category::all());

    //     // return dd($this->category_list);

    //     $word_total = DB::table('words')->count();

    //     $this->word_total = DB::table('words')->count();

        // Mengubah nilai $word_total dan $word_count_in_category

        // $this->category_list = $this->category_list->map( function ($category) {

        //     // return dd($category);
        //     $count = DB::table('words')
        //             ->join('articles', 'words.article_id', '=', 'articles.id')
        //             ->select('words.*', 'articles.category_id')
        //             ->where('category_id', '=', $category->id)->count();

        //     $category->total_word_in_category = $count;

        //     // return dd($category);
            
        //     $words2 = $this->wordlist->map( function ($value, $key) use ($category) {
                
        //         // stemm word
        //         $word_stemmed = app('stemm')->stem($value);

        //         // return dd($this->wordlist->count());
                
        //         $word = \App\Models\Word::where('word_term', '=', $word_stemmed)
        //                                 ->with(['article' => function ($query) use ($category) {
        //                                     $query->where('category_id', '=', $category->id);
        //                                 }])->get(); 

        //         $word_count = $word->map(function ($item) use ($category, $key){

        //             // cek apakah ada word_term yang cocok
        //             if(\App\Models\Article::where('id', '=', $item->article_id)
        //                                 ->with('words')
        //                                 ->where('category_id', '=', $category->id)
        //                                 ->withCount('words')
        //                                 ->first() == null) {                            

        //                 // apabila tidak ada kembalikan null
        //                 return null;

        //             } else {

        //                 // $total_word_in_article = \App\Models\Article::where('id', '=', $item->article_id)
        //                 //                                             ->with('words')
        //                 //                                             ->where('category_id', '=', $category->id)
        //                 //                                             ->withCount('words')
        //                 //                                             ->first()->words_count;

        //                 // $id_word_start_in_article = \App\Models\Article::where('id', '=', $item->article_id)
        //                 //                                                 ->with('words')
        //                 //                                                 ->where('category_id', '=', $category->id)
        //                 //                                                 ->withCount('words')
        //                 //                                                 ->first()
        //                 //                                                 ->words->first()->id;

        //                 // $percentile_under_33 = round($total_word_in_article * (33.33/100)) + $id_word_start_in_article;

        //                 $percentile_under_33 = round($this->wordlist->count() * (33.33/100)) + 1;

        //                 // return dd($percentile_under_33, $key);

        //                 if( $key < ($percentile_under_33 - 2) ) {
                            
        //                     // $this->word_total += 1;

        //                     return [
        //                         'word_total' => 1,
        //                         'word_count_in_category' => 2,
        //                         'value' => 2,
        //                     ];
        //                 } else {
        //                     return [
        //                         'value' => 1,
        //                         'word_count_in_category' => 1
        //                     ]; 
        //                 }
        //             }
        //         });

        //             // return dd($word_count->sum('value'));
        //             // return dd($word_count->sum() - $word_count_original);
        //             // $different_word_count = ($word_count->sum() - $word_count_original);
        //             // $word_count_in_category = $word_count_in_category + $different_word_count;
        //             // $word_total = $word_total + $different_word_count;
                
        //             // return dd($word_count);
        //             // $category->total_word_in_category + ($word_count->sum('word_count_in_category') - $word_count->count())
        //             $extra_word = ($word_count->sum('word_count_in_category') != 0) ? $word_count->sum('word_count_in_category') - $word_count->count() : 0;
        //             $total_word_category = ($word_count->sum('word_count_in_category') != 0) ? $category->total_word_in_category + $extra_word : $category->total_word_in_category;
        //         // return dd($word_count);
        //         // FIXME Masih ada duplicate word_count_in_category di perhitungan
        //         return [
        //             'word' =>  $word_stemmed,
        //             'word_count' => $word_count->sum('value'),
        //             'total_word_category' => $total_word_category,
        //             'real_word_in_category' => $category->total_word_in_category,
        //             'extra_word' => $extra_word
        //             // 'plus_word' => $word_count->
        //             // 'nbc_value_per_word' => log(($word_count->sum() + 1) / ($word_count_in_category + $word_total))
        //         ];
        //     });
        //         // return dd($words2);
        //     return [
        //         'id' => $category->id,
        //         'name' => $category->name,
        //         'words' => $words2
        //     ];
                
        // });

        // return dd($this->category_list, $this->word_total );
        
    //     $word_total = $this->word_total;

    //     // convert array to collection instance
    //     $this->category_list = collect($this->category_list)->map(function ($voucher) {
    //         return (object) $voucher;
    //     });

    //     // return dd($this->category_list);

    //     // Lakukan perulangan untuk collection $category_list
    //     $by_category = $this->category_list->map( function($category) use ($word_total){

    //         // return dd($category->name, $word_total);

    //         // Ambil total kata pada sebuah kelas
    //         // Total kata pada sebuah kategori diambil dari $category->words->max('total_word_category')

    //         // $word_count_in_category = DB::table('words')
    //         //                             ->join('articles', 'words.article_id', '=', 'articles.id')
    //         //                             ->select('words.*', 'articles.category_id')
    //         //                             ->where('category_id', '=', $category->id)->count();

    //         // Lakukan perulangan untuk collection di $this->wordlist
    //         $words = $this->wordlist->map(function ($value) use ($category, $word_total){

    //             $word_count = 0;
    //             $word_stemmed = app('stemm')->stem($value);

    //         // DB::table('words')
    //         //             ->join('articles', 'words.article_id', '=', 'articles.id')
    //         //             ->select('words.*', 'articles.category_id')
    //         //             ->where('word_term', '=', $word_stemmed)
    //         //             ->where('category_id', '=', $category->id)->count();

    //             $word = \App\Models\Word::where('word_term', '=', $word_stemmed)
    //                                 ->with(['article' => function ($query) use ($category) {
    //                                     $query->where('category_id', '=', $category->id);
    //                                 }])->get();

    //             $word_count_original = DB::table('words')
    //                                     ->join('articles', 'words.article_id', '=', 'articles.id')
    //                                     ->select('words.*', 'articles.category_id')
    //                                     ->where('word_term', '=', $word_stemmed)
    //                                     ->where('category_id', '=', $category->id)->count();


    //             $word_count = $word->map(function ($item, $key) use ($category, $word_total){
                
    //                 if(\App\Models\Article::where('id', '=', $item->article_id)
    //                                     ->with('words')
    //                                     ->where('category_id', '=', $category->id)
    //                                     ->withCount('words')
    //                                     ->first() == null) 
    //                     {
    //                         // $total_word_in_article = null;                            
    //                         return null;
    //                     } else {
    //                         $total_word_in_article = \App\Models\Article::where('id', '=', $item->article_id)
    //                                                                     ->with('words')
    //                                                                     ->where('category_id', '=', $category->id)
    //                                                                     ->withCount('words')
    //                                                                     ->first()->words_count;

    //                         $id_word_start_in_article = \App\Models\Article::where('id', '=', $item->article_id)
    //                                                                         ->with('words')
    //                                                                         ->where('category_id', '=', $category->id)
    //                                                                         ->withCount('words')
    //                                                                         ->first()
    //                                                                         ->words->first()->id;

    //                         $percentile_under_33 = round($total_word_in_article * (33.33/100)) + $id_word_start_in_article;
                
    //                         if( $item->id <= $percentile_under_33  ) {
    //                             return [
    //                                 'word_total' => 1,
    //                                 'value' => 2
    //                             ];
    //                         } else {
    //                             return ['value' => 1];
    //                         }
    //                     }
    //                 });

    //                 // return dd($word_count->sum('value'));
    //                 // return dd($word_count->sum() - $word_count_original);
    //                 // $different_word_count = ($word_count->sum() - $word_count_original);
    //                 // $word_count_in_category = $word_count_in_category + $different_word_count;
    //                 // $word_total = $word_total + $different_word_count;
                
    //                 // return dd($category);

    //                 return [
    //                     'word' => $word_stemmed,
    //                     'word_count' => $word_count->sum('value'),
    //                     // 'plus_word' => $word_count->
    //                     // 'nbc_value_per_word' => log(($word_count->sum('value') + 1) / ($category->words->min('real_word_in_category') + $category->words->sum('extra_word')) + $word_total, 10),
    //                     'nbc_value_per_word' => log(($word_count->sum('value') + 1) / ($category->words->min('real_word_in_category')) + $word_total, 10),
    //                     'total_word' => $word_total,
    //                     // 'total_word_in_category' =>  $category->words->min('real_word_in_category') + $category->words->sum('extra_word')
    //                     'total_word_in_category' =>  $category->words->min('real_word_in_category')
    //                 ];
    //             });


    //         /*
    //             $category->name
    //             $category->id
    //             $category->total_word_per_category
    //         */

    //         // return dd($words);

    //         return [
    //             'category' => $category->name,
    //             'words' => $words->toArray(),
    //             // Menampilkan jumlah kata pada sebuah kategori atau count(C)
    //             'words_count_in_category' => $category->words->min('real_word_in_category') + $category->words->sum('extra_word'),
    //             'nbc_value_per_class' => $words->sum('nbc_value_per_word')
    //         ];
    //     });

    //     $result = collect([
    //         'category' => $by_category->keyBy('category')->sortBy('category'),
    //         // Menampilkan total words atau |V|
    //         'total_words' => $word_total
    //     ]);

    //     return dd($result);


    //     // 'category' => $by_category->keyBy('category')->sortByDesc('nbc_value_per_class'),

    //     // return dd($result['category']->keyBy('category')->sortByDesc('nbc_value_per_class')->first(), $result);

    //     // return dd($result->toArray());
    //     return view('classification.result', ['result' => $result->toArray(), 'class_prediction' => $result['category']->keyBy('category')->sortByDesc('nbc_value_per_class')->first()]);
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = TestData::findOrFail($id)->with('category')->first();

        return view('classification.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $article = TestData::findOrFail($id)->delete();
            Alert::success('Sukses Dihapus');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function deleteAll(){
        try {
            DB::table('testing_datas')->delete();
            Alert::success('Sukses Menghapus Semua');

            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Gagal', $e->getMessage());
            return redirect()->back();
        }
    }

    public function singleResult(Request $request){
        $text = $request->articleText;

    }

}
