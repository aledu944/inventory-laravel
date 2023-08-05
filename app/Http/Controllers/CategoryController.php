<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new CategoryCollection(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $request->validated();

        $request['slug'] = $this->createSlug($request['name']);
        Category::create($request->all());

        return response([
            'message' => 'Se creo la categoria'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $term)
    {
        return new CategoryResource(Category::where('id', $term)->orWhere('slug',$term)->get()[0]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);

        if( !$category ){
            return response()->json([
                'message' => 'No se encontro la categoria'
            ], 404);
        };


        $request['slug'] = $this->createSlug($request['name']);
        $category->update( $request->all() );
        return response()->json([
            'message' => 'Categoria actualizada'
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if( !$category ){
            return response()->json([
                'message' => 'No se encontro la categoria'
            ], 404);
        };

        $category->delete();
        return response()->json([
            'message' => 'Categoria eliminada'
        ], 200);
    }

    function createSlug($text)
    {
        $text = strtolower($text); 
        $text = preg_replace('/[^a-z0-9]+/','_',$text);
        $text = trim($text, '_');
        $text = preg_replace('/_+/','_',$text);

        return $text;   
    }

}
