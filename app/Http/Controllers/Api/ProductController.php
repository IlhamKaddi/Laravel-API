<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{

// private array $products =[
//     [
//      'id'=> 1,
//      'name' => 'laptop',
//      'price' => 100000
//     ],
//     [
//      'id'=> 2,
//      'name' => 'desktop',
//      'price' => 200000
//     ],
//     [
//      'id'=> 3,
//      'name' => 'tablet',
//      'price' => 150000
//     ]
// ];
//display all products
  public function index()
{
    $products=Product::all();

    return ProductResource::collection($products);
    // return response()->json($products);
    // return response()->json($this->products);
}

//dislay single product
public function show($id)
{
    
// $product= collect($this->products)->firstWhere('id',(int)$id)  ;  
$product= Product::find($id);
if(!$product){
    return response()->json(
        [    'success'=>false,    
            'message'=>'product not found'
        ],
    404);
}
// return response()->json($product);
return new ProductResource ($product);
}


// create new product
public function store(Request $request){
    // $product=Product::create([
    //     'name'=>$request->name,
    //     'price'=>$request->price,
    //     'category_id'=>$request->category_id
    // ]);
    $validated=$request->validate([
        'name'=>'required|string|max:255',
        'price'=>'required|numeric',
        'category_id'=>'required|exists:categories,id'
    ]);
    $product=Product::create($validated);
    return response()->json(
        [
            'message' => 'product created successfully',
            'data'=>$product
        ],201
    );
}



// update product
public function update (Request $request,$id){
    $product=Product::find($id);
    if(!$product){
        return response()->json(
            [
                'success'=>false,
                'message'=>'product not found'
            ]
        ,404);
    }
    // $product->update([
    //     'name'=>$request->name,
    //     'price'=>$request->price,
    //     'category_id'=>$request->category_id
    // ]);
       $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'category_id' => 'required|exists:categories,id'
    ]);

    $product->update($validated);
    return response()->json(
        [
            'message'=>'product updated successfully',
            'data'=>$product
        ]
    );
    // return response()->json(
    //     [
    //         'message'=> 'Update endpoint reached',
    //         'id'=>(int)$id,
    //         'data'=>$request->all(),
    //     ],200
    // );
    
}

// delete product
public function destroy($id){

    $product=Product::find($id);

    if(!$product){
        return response()->json([
            'sucess'=>false,
            'message'=>'product not found'
        ],404);
    }

    $product->delete();

    return response()->json([
        'message'=>'product deleted successfully',
    ]);


    // return response()->json(
    //     [
    //         "message"=> 'Product deleted',
    //         "id"=>(int) $id,
    //     ],200
    // );
}


}
