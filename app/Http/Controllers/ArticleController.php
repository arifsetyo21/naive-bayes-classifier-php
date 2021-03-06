<?php

namespace App\Http\Controllers;

use Goutte\Client;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Jobs\ScrapArticleJob;
use App\Imports\ArticleImport;
use App\Exports\ArticleExport;
use App\Models\Url as UrlModels;
use App\Models\Article as Article;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Jobs\PreprocessArticleJob;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\ClassificationController;
use App\Libraries\simple_html_dom as simple_html_dom;

class ArticleController extends Controller
{
    protected $url,
              $title,
              $content = [],
              $cleaned_content = [],
              $article,
              $wordlist,
              $stemmed,
              $article_id,
              $wordCollection;



   public function __construct(){
      
   }

   // for explode string with many parameter
   public function multiexplode ($delimiters,$string) {
      $ready = str_replace($delimiters, $delimiters[0], $string);
      $launch = explode($delimiters[0], $ready);
      return  $launch;
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

      // list parameter for delimiter
      $delimiter = [PHP_EOL, ' ', ','];
      $category_id = $request->category_id;

      // separate string to array
      $urls = (object) collect(
         array_filter(
            $this->multiexplode($delimiter, $request->url)
         )
      );

      $collection = $urls->map(function ($item, $key) use ($category_id){

         $url["url"] = $item;
         $url["category_id"] = $category_id;
         // $item["url"] = $item;

         $validation = \Validator::make($url, [
            "url" => "required|unique:urls,url",
            "category_id" => "required"
         ])->validate();

         $id = \App\Models\Url::create([
            'domain' => 'kumparan.com',
            'url' => $url["url"],
         ]);

         return collect ( [(object) [ 
            'url' => $item,
            'url_id' => $id->id,
            'category_id' => $category_id
         ]]); 
      });

      $collection->map(function ($item, $key) {
         ScrapArticleJob::dispatch($item);
         // $this->scrapContentKumparan($item);
      });

      Alert::success('Success Message', 'Url Berhasil Ditambahkan dan Discrap');
      return redirect()->route('training.index');
   }

   public function saveUrl($request){

      $collection = $request->map(function ($item, $key){
         $id = \App\Models\Url::create([
            'domain' => 'kumparan.com',
            'url' => $item->url,
         ]);

         return collect ( [(object) [ 
            'url' => $item->url,
            'url_id' => $id->id,
            'category_id' => $item->category_id,
         ]]); 
      });

      $collection->map(function ($item, $key) {
         ScrapArticleJob::dispatch($item);
         // $this->scrapContentKumparan($item);
      });
   }

   public function scrapContentKumparan(Collection $collection){
      
      $collection->map(function ($item, $key) {

         $client = new Client();
         $crawler = $client->request('GET', trim($item->url));
         $title = $crawler->filter('h1')->each(function ($node) {return $node->text();});

         $content = $crawler->filter('div.clLKZY.components__NormalWidth-sc-1ukv6c0-0')->each(function ($node) {return $node->text();});

         unset($client);

         return $this->saveScrappedArticle([
            'title' => $title[0],
            'content' => $content,
            'url_id' => $item->url_id,
            'category_id' => $item->category_id
         ]);

      });
   }
   
   // function for export as Excel
   public function export(){
      $file_name = "articles_training_(".date("Y-m-d",time()). ").xlsx";
      return Excel::download(new ArticleExport(), $file_name);
   }

   public function import(Request $request){
      
      $articles = Excel::toCollection(new ArticleImport(), $request->file('import_article'));

      $articles = $articles[0]->map(function($item, $key){
         $container = collect();
         $container->url = trim($item['url']);
         $container->category_id = (int) $item['category_id'];

         return $container;
      });

      // return dd($articles);

      try {
         $this->saveUrl($articles);
         // $this->scrapContentKumparan($articles);
         Alert::success('URL Berhasil Ditambahkan dan Discrap');
         return redirect()->back();

      } catch (\Exception $e) {
         Alert::error('URL Gagal Ditambahkan', $e->getMessage());
         return redirect()->back();
      }
   
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

   public function preprocessAll(){
      
      // Select article whereNotIn words, that mean, select article where not preprocess
      $articles = DB::table('articles')
                  ->select('*')
                  ->whereNotIn('id', function($query) {
                     $query->select('article_id')->from('words');
                  })->get();

      if($this->preprocess($articles, true)){

         Alert::success('Preprocess Dimasukkan ke Antrian');
         return redirect()->back();
      } else {

         Alert::error('Gagal Preprocessing');
         return redirect()->back();
      }

   }

   public function preprocess($id, $many = false){

      // for single preprocess 
      if( $many == false ) {
         try {
            PreprocessArticleJob::dispatch($id);
            Alert::success('Preprocess Dimasukkan ke Antrian');
            return redirect()->back();
         } catch (Exception $e) {
            Alert::error('Gagal Preprcessing', $e->getMessage());
            return redirect()->back();
         }
         // for many preprocess
      } else {
         try {
            $id->map(function ($item, $key) {
               PreprocessArticleJob::dispatch($item->id);
            });
            return true;
         } catch (\Exception $e) {
            Alert::error('Gagal Melakukan Preprocess', $e->getMessage());
            return redirect()->back();
         }
      }
   }

   public function preprocessArticle($id){

        $article = \App\Models\Article::findOrFail($id);
        $this->article_id = $article->id;
        $content_article = \json_decode($article->content);

        $tokenizerFactory  = new \Sastrawi\Tokenizer\TokenizerFactory();
        $tokenizer = $tokenizerFactory->createDefaultTokenizer();
        
        foreach($content_article as $array) {
            $token[] = $tokenizer->tokenize($array);
        }

        foreach($token as $tkn){
            foreach($tkn as $index => $t){ 
                $k = strtolower($t);
                $word_token_url_removed = \preg_replace('(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})', '', $k);
                $word_token_bracket_removed = \preg_replace('/\([^)]+\)/', '', $word_token_url_removed);
                if($word_token_bracket_removed == ''){
                    unset($tkn[$index]);
                } else {
                    $string[] = $word_token_bracket_removed;
                }
            }
        }

        $stopwords_list = collect(explode("\n", file_get_contents(\public_path('stopword_list_tala.txt'))));
        // stopword wordlist : http://hikaruyuuki.lecture.ub.ac.id/kamus-kata-dasar-dan-stopword-list-bahasa-indonesia/

        $this->wordlist = \collect($string);

        $this->wordlist = $this->wordlist->filter(function($value, $key){
                  $value = preg_replace('/[^a-zA-Z ]/', '', $value);
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

   public function saveCleanedArticle($article_id){

      $this->wordCollection = $this->stemmed;
      if (\App\Models\Word::where('article_id', '=', $article_id)->exists()) {
         // Alert::error('Data Gagal Disimpan', 'Words Sudah Pernah di Simpan');
         return false;
      } else {
         // Alert::success('Sukses di Simpan', 'Words sukses disimpan di database');
         return $this->wordCollection->map(function ($item) use ($article_id){
               return \App\Models\Word::create(['word_term' => $item, 'article_id' => $article_id]);
         });
      }
   }

   public function deleteArticle(Request $request){
      $delete_article = ($request->id == null) ? \App\Models\Article::findOrFail($this->article_id) : \App\Models\Article::findOrFail($request->id);   
      $delete_url = \App\Models\Url::findOrFail($delete_article->url_id);
      $delete_url->delete();
      $delete_article->delete();

      Alert::success('Sukses di Hapus', 'Words sukses dihapus dari database');
      return redirect()->route('training.index');
   }

   public function cleanContentCleaned(){
        $article = \App\Models\Article::findOrFail($this->article_id);
        $article->content_cleaned = null;
        return $article->save();
   }

   public function deleteArticlePermanent(Request $request){
        $delete_article = ($request->id) ? \App\Models\Article::withTrashed()->findOrFail($this->article_id) : \App\Models\Article::withTrashed()->findOrFail($request->id);   
        return $delete_article->forceDelete();
   }
}