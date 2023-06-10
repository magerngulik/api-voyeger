<?php

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});


Route::group(['prefix' => 'api'], function () {
 
    Route::get('/products', function () {
        return DB::table("products")->paginate(10);
    });
    Route::get('/products/{id}', function () {
        $products = DB::table("products")->where('id', request()->id)->get();
        if ($products->isEmpty()) {
            return response()->json([
                "status" => "gagal",
                "data" => $products,
                "message" => "product tidak di temukan",
            ],404);
        } else {
            return response()->json([
                "status" => "berhasil",
                "data" => $products->first()        
            ],200);
        }
    });
    
    Route::post('/products', function(){
        $post = request()->post();
        $validator = Validator::make($post, [
            'product_name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'image' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' =>'gagal',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 400);
        }
        $insetTable = DB::table('products')->insert([
            'product_name' => $post['product_name'],
            'description' => $post['description'],
            'price' => $post['price'],
            'stock' => $post['stock'],
            'image' => $post['image'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Data berhasil disimpan'], 200);
    });

    Route::post('/products/{id}', function(){
        $id = request()->id;
        $post = request()->post();
        $validator = Validator::make($post, [
            'product_name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'image' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' =>'gagal',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 400);
        }
        $insetTable = DB::table('products')
            ->where('id', $id)
            ->update([
            'product_name' => $post['product_name'],
            'description' => $post['description'],
            'price' => $post['price'],
            'stock' => $post['stock'],
            'image' => $post['image'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return response()->json(['message' => 'Data berhasil di update'], 200);
    });

    Route::delete('/products/{id}', function(){
        $id = request()->id;
        $insetTable = DB::table('products')
            ->where('id', $id)
            ->delete();
        return response()->json(['message' => 'Data berhasil di hapus'], 200);
    });
});


Route::get('/products/action/', [ProductController::class, 'index']);



