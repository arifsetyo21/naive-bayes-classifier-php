<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('classification.index');
    }

    public function indexModified()
    {
        return view('classification.index-modified');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        $article_text = explode("\n", $article_text);
        
        foreach($article_text as $array) {
            $token[] = explode(" ", $array);
        }

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

        $collection_result = collect();

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
                    'nbc_value_per_word' => log(($word_count + 1) / ($word_count_in_category + $word_total))
                ];
            });

            return [
                'category' => $category->name,
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

        // dd($result);

        // 'category' => $by_category->keyBy('category')->sortByDesc('nbc_value_per_class'),

        // return dd($result['category']->keyBy('category')->sortByDesc('nbc_value_per_class')->first(), $result);

        // return dd($result->toArray());
        return view('classification.result', ['result' => $result->toArray(), 'class_prediction' => $result['category']->keyBy('category')->sortByDesc('nbc_value_per_class')->first()]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeModified(Request $request)
    {
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
        $article_text = explode("\n", $article_text);
        
        foreach($article_text as $array) {
            $token[] = explode(" ", $array);
        }

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

        $collection_result = collect();

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

                $word_count = 0;
                $word_stemmed = app('stemm')->stem($value);

            // DB::table('words')
            //             ->join('articles', 'words.article_id', '=', 'articles.id')
            //             ->select('words.*', 'articles.category_id')
            //             ->where('word_term', '=', $word_stemmed)
            //             ->where('category_id', '=', $category->id)->count();

                $word = \App\Models\Word::where('word_term', '=', $word_stemmed)
                                    ->with(['article' => function ($query) use ($category) {
                                        $query->where('category_id', '=', $category->id);
                                    }])->get();

                $word_count_original = DB::table('words')
                                        ->join('articles', 'words.article_id', '=', 'articles.id')
                                        ->select('words.*', 'articles.category_id')
                                        ->where('word_term', '=', $word_stemmed)
                                        ->where('category_id', '=', $category->id)->count();

                $word_count = $word->map(function ($item, $key) use ($category, $word_total, $word_count_in_category){
                
                    if(\App\Models\Article::where('id', '=', $item->article_id)
                                        ->with('words')
                                        ->where('category_id', '=', $category->id)
                                        ->withCount('words')
                                        ->first() == null) 
                        {
                            // $total_word_in_article = null;                            
                            return null;
                        } else {
                            $total_word_in_article = \App\Models\Article::where('id', '=', $item->article_id)
                                                                        ->with('words')
                                                                        ->where('category_id', '=', $category->id)
                                                                        ->withCount('words')
                                                                        ->first()->words_count;

                            $id_word_start_in_article = \App\Models\Article::where('id', '=', $item->article_id)
                                                                            ->with('words')
                                                                            ->where('category_id', '=', $category->id)
                                                                            ->withCount('words')
                                                                            ->first()
                                                                            ->words->first()->id;

                            $percentile_under_33 = round($total_word_in_article * (33.33/100)) + $id_word_start_in_article;
                
                            if( $item->id <= $percentile_under_33  ) {
                                return [
                                    'word_total' => 1,
                                    'word_count_in_category' => $word_count_in_category + 1,
                                    'value' => 2
                                ];
                            } else {
                                return ['value' => 1];
                            }
                        }
                    });

                    // return dd($word_count->sum('value'));
                    // return dd($word_count->sum() - $word_count_original);
                    $different_word_count = ($word_count->sum() - $word_count_original);
                    $word_count_in_category = $word_count_in_category + $different_word_count;
                    $word_total = $word_total + $different_word_count;
                
                return dd($word_count);

                return [
                    'word' => $word_stemmed,
                    'word_count' => $word_count->sum('value'),
                    // 'plus_word' => $word_count->
                    // 'nbc_value_per_word' => log(($word_count->sum() + 1) / ($word_count_in_category + $word_total))
                ];
            });

            return [
                'category' => $category->name,
                'words' => $words->toArray(),
                // Menampilkan jumlah kata pada sebuah kategori atau count(C)
                'words_count_in_category' => $word_count_in_category,
                'nbc_value_per_class' => $words->sum('nbc_value_per_word')
            ];

        });

        $temp_word_total = $by_category->map( function ($item, $key) {
            // $item[''];
        });

        $result = collect([
            'category' => $by_category->keyBy('category')->sortBy('category'),
            // Menampilkan total words atau |V|
            'total_words' => $word_total,
        ]);


        // 'category' => $by_category->keyBy('category')->sortByDesc('nbc_value_per_class'),

        // return dd($result['category']->keyBy('category')->sortByDesc('nbc_value_per_class')->first(), $result);

        // return dd($result->toArray());
        return view('classification.result', ['result' => $result->toArray(), 'class_prediction' => $result['category']->keyBy('category')->sortByDesc('nbc_value_per_class')->first()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    public function singleResult(Request $request){
        $text = $request->articleText;

    }

    public function classification($word, $category){
        // count(Wic) : Jumlah kata w dalam sebuah kelas
        // DB::table('words')->join('articles', 'words.article_id', '=', 'articles.id')->select('words.*', 'articles.category_id')->where('word_term', '=', 'motor')->where('category_id', '=', '11')->count();

        // Count(C) : Jumlah total kata pada sebuah kelas
        // DB::table('words')->join('articles', 'words.article_id', '=', 'articles.id')->select('words.*', 'articles.category_id')->where('category_', '=', 'motor')->count();
        
        // |V| : Total kata semua kelas
        // DB::table('words')->count();
        // Rumus
        // P(C|d) = hasil probabilitas di kalikan semua, dan diambil hasil paling besar
        // P(Wi | C) = (count(Wi, C) + 1) / (count(C) + |V|)
        
    }

}
