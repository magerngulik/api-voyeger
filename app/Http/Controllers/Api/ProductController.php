<?php

namespace App\Http\Controllers\Api;

use DB;
use Carbon\Carbon;
// use Illuminate\Validation\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;


class ProductController extends BaseController
{
    
    public function index(){
        return DB::table("products")->paginate(10);
       
    }
    public function singleItem(){
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
    }

    public function create(){
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
    }

    public function update(){
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
    }

    public function delete(){
        $id = request()->id;
        $insetTable = DB::table('products')
            ->where('id', $id)
            ->delete();
        return response()->json(['message' => 'Data berhasil di hapus'], 200);
    }
}
