<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class ToolController extends Controller
{
    public function index(){

        $categories = Category::all();
        return view('tool.index', compact('categories'));
    }

    public function convertToArrayJson(Request $request){

        $category = Category::findOrFail($request->category_id);
        $article = json_encode(explode(PHP_EOL, $request->article));

        return view('tool.convert-result', compact('article', 'category'));        
    }
}
