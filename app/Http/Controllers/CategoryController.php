<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('category.index', ['categories' => Category::withTrashed()->paginate(10)]);
    }

            /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return \redirect()->route('category.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:categories'
        ])->validate();

        $new_category = new \App\Models\Category;
        $new_category->name = $request->name;
        $new_category->save();
        return \redirect()->route('category.index')->with('status', 'Successfully Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        
        return view('category.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = \App\Models\Category::findOrFail($id);

        try {
            $category->name = $request->name;
            $category->save();
            Alert::success('Sukses Diubah', 'Nama Kategori Berhasil Diubah');

            return redirect()->route('category.index');

        } catch (Exception $e) {
            Alert::error( 'Gagal', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $category = \App\Models\Category::findOrFail($id);
        $category->delete();
        return \redirect()->route('category.index')->with('status', 'successfully deleted');
    }

    public function deletePermanent($id)
    {
        $category = \App\Models\Category::withTrashed()->findOrFail($id);
        $category->forceDelete();
        return redirect()->route('category.index');
    }
}
