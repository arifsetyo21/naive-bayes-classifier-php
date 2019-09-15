<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article as Article;
use Alert;

class WordController extends Controller
{
    protected $wordCollection;
    
    public function setWordFromJson(string $jsonWord){
        return $this->wordCollection = \collect(json_decode($jsonWord));
    }
    
    public function getWord(){
        return $this->wordCollection; 
    }

    public function saveWordFromArticleClass($id){
        $article = \App\Models\Article::findOrFail($id);
        $article_id = $article->id;
        $this->wordCollection = \collect(json_decode($article->content_cleaned));
        if (\App\Models\Word::where('article_id', '=', $article_id)->exists()) {
            Alert::error('Data Gagal Disimpan', 'Words Sudah Pernah di Simpan');
            return back();
        } else {
            Alert::success('Sukses di Simpan', 'Words sukses disimpan di database');
            return $this->wordCollection->map(function ($item) use ($article_id){
                return \App\Models\Word::create(['word_term' => $item, 'article_id' => $article_id]);
            });
        }
    }

    public function deleteWordsArticle($id){
        $article = \App\Models\Article::findOrFail($id);
        $article_id = $article->id;
        return \App\Models\Word::all()->where('article_id', $article_id)->forceDelete();
    }
}
