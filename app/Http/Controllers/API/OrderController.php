<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use ApiHelpers;

    public function getAll(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)->get();

        $arr = [];
        for ($i = 0; $i < count($orders); $i++){
            $ord = explode(',',$orders[$i]->prod_and_count);
            $arr2 = [];
            for ($j = 0; $j < count($ord)-1; $j++) {
                $tt = explode('=>',$ord[$j]);
                $arr2[] = ['product_id' => $tt[0], 'count' => $tt[1]];
            }
            $arr[] = $arr2;
        }
        return response()->json(['data'=>$arr]);
    }

    public function create(Request $request)
    {
        $validate = Validator::make($request->all(),
            [
                'product' => 'present|array',
                'product.*.product_id' => 'required|integer',
                'product.*.count' => 'required|integer'
            ],
        );

        if ($validate->fails()) {
            return response()->json(['message' => $validate->errors()], 422);
        }

        $str = "";
        $error = false;

        for ($i = 0; $i < count($request->product); $i++) {
            if (Product::find($request->product[$i]['product_id']) === null) {
                $error = true;
                break;
            }
            $str .= $request->product[$i]['product_id'] . '=>' . $request->product[$i]['count'] . ',';
        }

        if ($error) {
            return response()->json(['message' => 'Товар не найден'], 404);
        }

        Order::create([
           'user_id'=>$request->user()->id,
           'prod_and_count'=>$str
        ]);

        return response()->json(['data'=>$request->product], 201);

    }
}
