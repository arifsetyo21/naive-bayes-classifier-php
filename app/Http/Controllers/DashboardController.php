<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardController extends Controller
{
    public function index(){
        // TODO buat angka tabel confussion matrix
        return view('dashboard.index')->with('articles', Dashboard::with('category_prediction_nbc', 'category_prediction_modified', 'category_real_category')->paginate(10));
    }

    public function show($id){
        $history = Dashboard::findOrFail($id);
        return $history;   
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
