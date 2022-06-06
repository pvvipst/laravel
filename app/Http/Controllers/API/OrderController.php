<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use ApiHelpers;


    // один энпоинт на 2 функции
    public function getAll(Request $request): JsonResponse
    {
        if ($this->isAdmin($request->user())) {
            // получение всех заказов админом
            $orders = DB::table('orders')->get();
        } else {
            // получение заказов только обычному юзеру и только его
            $orders = Order::where('user_id', $request->user()->id)->get();
        }
        $arr = [];
        for ($i = 0; $i < count($orders); $i++) {
            $ord = explode(',', $orders[$i]->prod_and_count);
            $arr2 = [];
            for ($j = 0; $j < count($ord) - 1; $j++) {
                $tt = explode('=>', $ord[$j]);
                $arr2[] = ['product' => Product::find($tt[0]), 'count' => $tt[1]];
            }
            $arr[] = ['id'=> $orders[$i]->id,'items' => $arr2, 'status' => $orders[$i]->status];
        }
        return response()->json(['data' => $arr]);
    }

    public function create(Request $request): JsonResponse
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
            'user_id' => $request->user()->id,
            'prod_and_count' => $str
        ]);

        return response()->json(['data' => $request->product], 201);

    }

    public function changeStatus(Request $request, $id): JsonResponse
    {
        if ($this->isAdmin($request->user())) {

            $validate = Validator::make($request->all(), [
                'status' => 'required|integer'
            ]);

            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 422);
            }

            $orders = Order::find($id);
            if ($orders === null) {
                return response()->json(['message' => 'Не найден заказ'], 404);
            }

            $orders->status = $request->status;
            $orders->save();
            $ord = explode(',', $orders->prod_and_count);
            $arr2 = [];
            for ($j = 0; $j < count($ord) - 1; $j++) {
                $tt = explode('=>', $ord[$j]);
                $arr2[] = ['product_id' => $tt[0], 'count' => $tt[1]];
            }
            $arr = ['items' => $arr2, 'status' => $orders->status];
            return response()->json(['data' => $arr]);
        }
        return $this->forbidden();
    }
}
