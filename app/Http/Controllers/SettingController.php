<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Url;
use App\Models\Word;
use Alert;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index(){
        return view('setting.index');
    }

    public function destroy(){

        DB::table('articles')->delete();
        DB::table('urls')->delete();
        DB::table('classification_history')->delete();
        DB::table('testing_datas')->delete();
        
        Alert::success('Sukses Membersihkan Data Articles dan Urls');
        return redirect()->route('setting.index');
    }
}
