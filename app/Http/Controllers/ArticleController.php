<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url as UrlModels;
use App\Libraries\simple_html_dom as simple_html_dom;
use App\Models\Article as Article;
use Illuminate\Support\Collection;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Category;
class ArticleController extends Controller
{
    protected $url,
              $title,
              $content = [],
              $cleaned_content = [],
              $article,
              $wordlist,
              $stemmed,
              $article_id;


    public function __construct(){
        
    }

   public function __set($property, $value){
      if( property_exists($this, $property)){
          $this->{$property} = $value;
      }
   }

   public function __get($property){
      if( property_exists($this, $property)){
         return $this->{$property};
      } else {
         throw new \Exception('Property doesnt exist');
      }
   }
    
   public function index(){
      return Article::all();
   } 

   public function show($id){
      $article = Article::findOrFail($id)->where('id', '=', $id)->with(['words', 'url', 'category'])->first();
      return view('article.show', ['article' => $article]);
   }

   public function addUrl(){
      $category = Category::all();
      return view('training.addUrl', ['categories' => $category]);
   }

   public function storeUrl(Request $request){

      \Validator::make($request->all(), [
         "url" => "unique:urls,url",
      ])->validate();

      $url = \App\Models\Url::create([
         'domain' => 'kumparan.com',
         'url' => $request->url,         
      ]);

      $request->url_id = $url->id;

      $this->scrapContentKumparan($request);
      Alert::success('Success Message', 'Url Berhasil ditambahkan dan discrap');
      return redirect()->route('training.index');
   }

   public function setUrlfromDB(){
      $url_list = Url::all();
      foreach ($url_list as $url) {
         $this->url = $url->url;
      }
   }

   public function scrap(){
      $urls = \App\Models\Url::all();
      return view('training.scrap', ['urls' => $urls]);
   }

   public function scrapContentKumparan(Request $request){

      $simple_html_dom = new simple_html_dom();
      
      $simple_html_dom->load_file($request->url);
      $title = trim($simple_html_dom->find('h1', 0)->plaintext);
      $content = [];
      foreach ($simple_html_dom->find('div[class=components__NormalWidth-sc-1ukv6c0-0 clLKZY]')
               as $paragraph) {
                  array_push($content, trim($paragraph->plaintext));
               }

      return $this->saveScrappedArticle([
         'title' => $title,
         'content' => $content,
         'url_id' => $request->url_id,
         'category_id' => $request->category_id
      ]);      
   }

   public function getTitle(){
      return $this->title;
   }

   public function saveArticleWithoutUrl($article) {
      $article = json_encode($article[]);
   }

   public function saveScrappedArticle(array $article){

         $validation = \Validator::make($article,[
            "url_id" => "unique:articles"
         ])->validate();
        
         $article['content'] = json_encode($article['content']);

         return \App\Models\Article::create($article)->id;
   }

   public function preprocess($id){
       $this->preprocessArticle($id);
       $this->stemmingWord();
       $status = $this->saveCleanedArticle();
       return redirect()->route('training.index')->with('status', 'oke');
   }

   public function preprocessArticle($id){
        $article = \App\Models\Article::findOrFail($id);
        $this->article_id = $article->id;
        $content_article = \json_decode($article->content);
        
        foreach($content_article as $array) {
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
            // filter this->wordlist less than 2 character and if the this->wordlist is 'dan', 'dengan', and 'serta' (Menggabungkan biasa)
            // if (strlen($s) < 2 || in_array( $s, ['dan','dengan','serta'])) :
            //     unset($this->wordlist[$index]);
            // endif;
            // // filter this->wordlist if this->wordlist is 'atau' (Menggabungkan memilih)
            // if (\in_array($s, ['atau'])):
            //     unset($this->wordlist[$index]);
            // endif;
            // // filter (Menggabungkan mempertentangkan)
            // if (\in_array($s, ['tetapi', 'namun', 'sedangkan', 'sebaliknya'])):
            //     unset($this->wordlist[$index]);
            // endif;
            // // filter (Menggabungkan membetulkan)
            // if (\in_array($s, ['melainkan', 'hanya'])):
            //     unset($this->wordlist[$index]);
            // endif;
            // // filter (Menggabungkan menegaskan)
            // if (\in_array($s, ['bahkan', 'malah', 'malahan', 'lagipula', 'apalagi', 'jangankan'])):
            //     unset($this->wordlist[$index]);
            // endif;
            // // filter (Menggabungkan membatasi)
            // if (\in_array($s, ['kecuali', 'hanya'])):
            //     unset($this->wordlist[$index]);
            // endif;
            // // filter (Menggabungkan mengurutkan)
            // if (\in_array($s, ['lalu', 'kemudian', 'selanjutnya'])):
            //     unset($this->wordlist[$index]);
            // endif;
            // // filter (Menggabungkan menyamakan)
            // if (\in_array($s, ['yaitu', 'yakni', 'bahwa', 'adalah', 'ialah'])):
            //     unset($this->wordlist[$index]);
            // endif;
            // // filter (Menggabungkan menyimpulkan)
            // if (\in_array($s, ['jadi', 'karena', 'itu', 'oleh', 'sebab'])):
            //     unset($this->wordlist[$index]);
            // endif;
            // // filter (Menyatakan syarat)
            // if (\in_array($s, ['kalau', 'jikalau', 'jika', 'bila', 'apalagi', 'asal'])):
            //     unset($this->wordlist[$index]);
            // endif;
            // // filter (Menyatakan tujuan)
            // if (\in_array($s, ['agar', 'supaya'])):
            //     unset($this->wordlist[$index]);
            // endif;
            // // filter (Menyatakan waktu)
            // if (\in_array($s, ['ketika', 'sewaktu', 'sebelum', 'sesudah', 'tatkala'])):
            //     unset($this->wordlist[$index]);
            // endif;
            // // filter (Menyatakan akibat)
            // if (\in_array($s, ['sampai', 'hingga', 'sehingga'])):
            //     unset($this->wordlist[$index]);
            // endif;
            // // filter (Menyatakan sasaran)
            // if (\in_array($s, ['untuk', 'guna'])):
            //     unset($this->wordlist[$index]);
            // endif;
            // // filter (Menyatakan perbandingan)
            // if (\in_array($s, ['seperti', 'sebagai', 'laksana'])):
            //     unset($this->wordlist[$index]);
            // endif;
            // if(strlen($s) < 2) :
            //     unset($this->wordlist[$index]);
            // endif;
            if(\in_array($s, $stopwords_list->toArray())) :
                unset($this->wordlist[$index]);
            endif;
            // if (\in_array($s, ['di', 'ke', 'ini',])):
            //     unset($this->wordlist[$index]);
            // endif;
        }
        return $this->wordlist;
   }

   public function stemmingWord(){
        return $this->stemmed = $this->wordlist->map(function ($value) {
            return app('stemm')->stem($value);
        });
    }   

   public function saveCleanedArticle(){
       $update_article = \App\Models\Article::findOrFail($this->article_id);
       $update_article->content_cleaned = $this->stemmed->toJson();
       return $update_article->save();
   }

   public function deleteArticle($id = null){
        $delete_article = ($id == null) ? \App\Models\Article::findOrFail($this->article_id) : \App\Models\Article::findOrFail($id);   
        return $delete_article->delete();
   }

   public function cleanContentCleaned(){
        $article = \App\Models\Article::findOrFail($this->article_id);
        $article->content_cleaned = null;
        return $article->save();
   }

   public function deleteArticlePermanent($id = null){
        $delete_article = ($id == null) ? \App\Models\Article::withTrashed()->findOrFail($this->article_id) : \App\Models\Article::withTrashed()->findOrFail($id);   
        return $delete_article->forceDelete();
   }
}