<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(){
        return view('products.index', ['products'=>Product::get()]);  
    }
    
    public function create(){
        return view('products.create');
    }

    public function store(Request $request){

        //Todo Imporve this function with breaking Validation into class and encapsulating product repository

        // Validation of the form 
        $request-> validate([
            'name'=> 'required',
            'description'=> 'required',
            'image'=> 'required|mimes:jpeg,jpeg,png,gif|max:10000'
        ]);

        //Store in the DB
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('products'), $imageName);

        $product = new Product();
        $product->image = $imageName;
        $product->description = $request->description;
        $product->name = $request->name;

        $product->save();
        return back()->withSuccess('Product Created');
    }

    public function edit($id){
        $product = Product::find($id);
        return view('products.edit', ['product' => $product]);
    }

    public function update(Request $request, $id){
        // Validation of the form 
        $request-> validate([
            'name'=> 'required',
            'description'=> 'required',
            'image'=> 'nullable|mimes:jpeg,jpeg,png,gif|max:10000'
        ]);

        $product = Product::where('id',$id)->first();

        if(isset($request->image)){
        //Store in the DB
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('products'), $imageName);
        $product->image = $imageName;
        }
        $product->description = $request->description;
        $product->name = $request->name;

        $product->save();
        return back()->withSuccess('Product Updated');
    }

    public function destroy(Request $request, $id){
        $product = Product::find($id);
    
        if (!$product) {
            return back()->withError('Product not found.');
        }
    
        $product->delete();
        return back()->with('deleted', 'Product Deleted successfully.');
    }

    public function show($id){
        $product = Product::where('id',$id)->first();
        return view('products.show', ['product' => $product]);
    }
    
}
