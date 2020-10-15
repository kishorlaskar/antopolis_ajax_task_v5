<?php

namespace App\Http\Controllers;

use App\Catagorey;
use App\Category;
use App\Subcategory;
use Illuminate\Http\Request;
use DataTables;
use Yajra\DataTables\Html\Editor\Fields\Select;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Catagorey::where('publication_status',1)->get();
         $subcategories = Subcategory::select('subcategory.id','subcategory.category_id','subcategory.subcategory_name','subcategory.subcategory_description','subcategory.publication_status')
             ->join('subcategories.category_id','=','category.id')->get();
         return view('admin.subcategory',
             [
                 'subcategories'=>$subcategories,
                 'categories' =>$categories
             ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id'        => 'required',
            'subcategory_name' => 'required',
            'subcategory_description' => 'required',
            'publication_status'   => 'required'
        ]);

        $subcategory = Category::updateOrCreate(['id' => $request->id], [
            'category_id' =>$request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_description' => $request->subcategory_description,
            'publication_status' => $request->publication_status
        ]);

        return response()->json(
            [      'code'=>200,
                'message'=>'Category Created successfully',
                'data' => $subcategory
            ],
            200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subcategory = Subcategory::find($id);

        return response()->json($subcategory);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function update(Request $request, $id)
//    {
//        //
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subcategory = Subcategory::find($id)->delete();

        return response()->json(
            ['success'=>'Subcategory Deleted successfully']
        );
    }
}
