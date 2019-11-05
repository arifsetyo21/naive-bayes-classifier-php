<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\ArticleController;

class PreprocessArticleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $articleController = new ArticleController;
        
        // if($this->id == null) {
        //     $articleController->preprocessAll();
        // } else {
        //     $articleController->preprocess($this->id);
        // }

        $articleController->preprocessArticle($this->id);
        $articleController->stemmingWord();

        $check_wordlist = $articleController->saveCleanedArticle($this->id);

        if($check_wordlist == false){
            throw new Exception($check_wordlist);
        }
    }
}
