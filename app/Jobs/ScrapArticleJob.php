<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\ArticleController;
use Illuminate\Support\Collection;

class ScrapArticleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $collection;
    private $fromController;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ArticleController = new ArticleController;

        $ArticleController->scrapContentKumparan($this->collection);
    }
}
