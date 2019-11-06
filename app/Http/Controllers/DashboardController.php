<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dashboard;
use Illuminate\Http\Request;
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

        return view('dashboard.index')
                ->with('categories2', Category::all())
                ->with('categories', Category::all())
                ->with('dashboard', $dashboard)
                ->with('total', ['modified' => collect($total_modified), 'nbc' => collect($total_nbc)])
                ->with('articles', Dashboard::with('category_prediction_nbc', 'category_prediction_modified', 'category_real_category')->paginate(10));
    }

    public function show($id){
        $history = Dashboard::findOrFail($id);
        return $history;   
    }

    public function destroyAll(){

        DB::table('classification_history')->delete();
        Alert::success('Sukses Membersihkan Data Articles dan Urls');
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
}
