<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        $this->stemmed = $this->wordlist->map(function ($value) {
            return app('stemm')->stem($value);
        });
        

        return view('classification.result', ['words' => $this->stemmed]);
        
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

    public function classification($word, $category){
        // count(Wic) : Jumlah kata w dalam sebuah kelas
        // DB::table('words')->join('articles', 'words.article_id', '=', 'articles.id')->select('words.*', 'articles.category_id')->where('word_term', '=', 'motor')->where('category_id', '=', '11')->count();

        // Count(C) : Jumlah total kata pada sebuah kelas
        // DB::table('words')->join('articles', 'words.article_id', '=', 'articles.id')->select('words.*', 'articles.category_id')->where('word_term', '=', 'motor')->count();

        // |V| : Total kata semua kelas
        // DB::table('words')->count();

        // Rumus
        // P(C|d) = hasil probabilitas di kalikan semua, dan diambil hasil paling besar
        // P(Wi | C) = (count(Wi, C) + 1) / (count(C) + |V|)
        
    }

    public function singleResult(Request $request){
        $text = $request->articleText;

    }


}
