<?php

namespace App\Jobs;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\ClassificationController;

class ClassificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $classificationController = new ClassificationController;
        // $classificationController->classificationSingle($this->id);
        $classificationController = new ClassificationController;
        // $classificationController->store($this->request);
        // $article_testing = TestData::findOrFail($request->id);
        // return dd($article_testing);
        // $request->request->add(['articleTitle' => $article_testing->title]);
        // $request->request->add(['real_category' => $article_testing->real_category_id]);
        // $request->request->add(['articleText' => $article_testing->content]);

        try {
            // ClassificationJob::dispatch($request->all());
            $classificationController->nbc($this->request);
            $classificationController->nbcModified($this->request);
            // $article_testing->delete();
            // return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }   
    }
}
