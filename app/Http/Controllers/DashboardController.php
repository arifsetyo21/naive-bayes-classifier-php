<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use RealRashid\SweetAlert\Facades\Alert;
class DashboardController extends Controller
{
    public function index(){
        // TODO buat angka tabel confussion matrix
        $dashboard = Dashboard::all();
        $category = Category::all();

        $total_modified = $category->map(function ($item, $key) use ($dashboard) {
            return $total_modified = $dashboard->where('real_category', $item->id)
                                        ->where('prediction_nbc', $item->id)
                                        ->count();
                                    });
                                    
        $total_nbc = $category->map(function ($item, $key) use ($dashboard) {
            return $total_nbc = $dashboard->where('real_category', $item->id)
                                  ->where('prediction_nbc', $item->id)
                                  ->count();
        });

        $modified = Redis::hvals('modified');
        $nbc = Redis::hvals('nbc');
        $labels = collect(Redis::hkeys('nbc'));

        // $label = $labels->map(function($item, $key) {
        //     return str_replace('"', '', strtok(Dashboard::findOrFail($item)->title, " ")) . "...";
        // });

        // return dd( "'" . implode("', '", $label->all()) . "'");
        // return dd(htmlspecialchars_decode(htmlspecialchars(implode(',', $label->all()))));
        // return dd($label->all());

        return view('dashboard.index')
                ->with('categories2', Category::all())
                ->with('categories', Category::all())
                ->with('dashboard', $dashboard)
                ->with('modified', implode(',', $modified))
                ->with('nbc', implode(',', $nbc))
                ->with('labels', $labels->all())
                ->with('avg_performance', collect($modified)->avg() - collect($nbc)->avg())
                ->with('total', ['modified' => collect($total_modified), 'nbc' => collect($total_nbc)])
                ->with('articles', Dashboard::with('category_prediction_nbc', 'category_prediction_modified', 'category_real_category')->paginate(10));
    }

    public function show($id){
        $history = Dashboard::findOrFail($id);
        return $history;   
    }

    public function destroyAll(){

        DB::table('classification_history')->delete();
        Alert::success('Sukses', 'Membersihkan Data Hasil Training');
        return redirect()->route('dashboard.index');
    }

    public function destroy($id){

        $history = Dashboard::findOrFail($id);

        // return dd($id);
        try {
            $title = $history->title;
            $history->delete();
            Alert::success('Sukses Dihapus', 'Artikel ' . $title . ' telah Dihapus');
            return redirect()->back();

        } catch (\Exception $e) {
            Alert::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function articleListChart(){
        $labels = collect(Redis::hkeys('nbc'));
        
        return $labels->map(function($item, $key) {
            return $this->limit_text(str_replace('"', '', Dashboard::findOrFail($item)->title), 4);
        });
    }

    public function limit_text($text, $limit) {
        if (str_word_count($text, 0) > $limit) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);
            $text = substr($text, 0, $pos[$limit]) . '...';
        }
        return $text;
      }
}
