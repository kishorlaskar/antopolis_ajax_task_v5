<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Subcategory;
use App\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $sub        =  Subcategory::latest()->get();
        $categories  =  Category::where('publication_status',1)->get();
        $brands      = Brand::where('publication_status',1)->get();

        if ($request->ajax()) {
            return Datatables::of($sub,$categories,$brands)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';

                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);

        }
        return view('products', compact('data','categories','brands'));
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
        $rules = array(
            'category_id'    =>  'required',
            'subcategory_id' => 'required',
            'brand_id' => 'required',
            'product_name' => 'required',
            'product_size' => 'required',
            'product_color' => 'required',
            'product_price' => 'required',
            'product_quantity' => 'required',
            'product_short_description'     =>  'required',
            'product_long_description'      =>   'required',
            'product_image'         =>  'required|image|max:2048',
            'publication_status'  => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $image = $request->file('product_image');

        $new_name = rand() . '.' . $image->getClientOriginalExtension();

        $image->move(public_path('product-images'), $new_name);

        $form_data = array(
            'category_id'      =>  $request->category_id,
            'subcategory_id'   => $request->subcategory_id,
            'brnad_id'         => $request->brand_id,
            'product_name'     => $request->product_name,
            'product_size'     => $request->product_size,
            'product_color'    => $request->product_color,
            'product_price'    => $request->product_price,
            'product_quantity' => $request->product_quantity,
            'product_short_description' => $request->product_short_description,
            'product_long_description' => $request->product_long_description,
            'product_image'             =>  $new_name,
            'publication_status'      => $request->publication_status
        );

        Product::create($form_data);

        return response()->json(['success' => 'Product Added successfully.']);


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
        if(request()->ajax())
        {
            $data = Product::findOrFail($id);
            return response()->json(['data' => $data]);
        }
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
        $image_name = $request->hidden_image;
        $image = $request->file('product_image');
        if($image != '')
        {
            $rules = array(
                'category_id'    =>  'required',
                'subcategory_id'     =>  'required',
                'brand_id'         =>  'image|max:2048',
                'product_name'  => 'required',
                'product_price'  => 'required',
                'product_color'  => 'required',
                'product_size'  => 'required',
                'product_quantity'  => 'required',
                'product_short_description' => 'required',
                'product_long_description' => 'required',
                'product_image'=> 'required|image|max:2048',
                'publication_status' => 'required'
            );
            $error = Validator::make($request->all(), $rules);
            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('product-images'), $image_name);
        }
        else
        {
            $rules = array(
                'category_id'    =>  'required',
                'subcategory_id'     =>  'required',
                'brand_id'         =>  'image|max:2048',
                'product_name'  => 'required',
                'product_price'  => 'required',
                'product_color'  => 'required',
                'product_size'  => 'required',
                'product_quantity'  => 'required',
                'product_short_description' => 'required',
                'product_long_description' => 'required',
                'publication_status' => 'required'
            );

            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }
        }

        $form_data = array(
            'category_id'      =>  $request->category_id,
            'subcategory_id'   => $request->subcategory_id,
            'brnad_id'         => $request->brand_id,
            'product_name'     => $request->product_name,
            'product_size'     => $request->product_size,
            'product_color'    => $request->product_color,
            'product_price'    => $request->product_price,
            'product_quantity' => $request->product_quantity,
            'product_short_description' => $request->product_short_description,
            'product_long_description' => $request->product_long_description,
            'product_image'             =>  $image_name,
            'publication_status'      => $request->publication_status
        );
        Brand::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Product is successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Product::findOrFail($id);
        $data->delete();
    }
}
