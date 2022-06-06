<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ApiHelpers;

    public function getAll() {
        $prod = DB::table('products')->get();

        for ($i = 0; $i < count($prod); $i++) {
            $cat = Category::find($prod[$i]->category_id);
            $prod[$i]->category_id = $cat->name;
        }
        return $this->onSuccess($prod);
    }

    public function create(Request $request) {
        // проверка на одмина
        if ($this->isAdmin($request->user())) {
            // валидации
            $validate = Validator::make($request->all(),
                [
                    'category_id' => 'required|integer',
                    'name' => 'required|string|min:5',
                    'price' => 'required|integer',
                    'description' => 'required|string|min:15',
                    'count' => 'required|integer',
                    'size' => 'required|string',
                ],
                [
                    'required' => 'Необходимо указать :attribute.',
                    'string' => 'Поле :attribute должно содержать только символы.',
                    'integer' => 'Поле :attribute должно содержать только цифры.',
                    'min' => 'Поле :attribute должно содержать минимум :min символов',
                ]);
            // вернет ошибку если не прошла валидация
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 422);
            }

            if (Category::find($request->category_id) === null) {
                return response()->json(['message' => ['category_id'=>'Не найдена категория']], 404);
            }

            // добавление
            $prod = Product::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'count' => $request->count,
                'size' => $request->size,
            ]);

            $cat = Category::find($prod->category_id);
            $prod->category_id = $cat->name;

            return $this->onSuccess($prod);
        }
        // нет доступа
        return $this->forbidden();
    }

    public function update(Request $request, $id) {
        if ($this->isAdmin($request->user())) {
            // валидации
            $validate = Validator::make($request->all(),
                [
                    'category_id' => 'required|integer',
                    'name' => 'required|string|min:5',
                    'price' => 'required|integer',
                    'description' => 'required|string|min:15',
                    'count' => 'required|integer',
                    'size' => 'required|string',
                ],
                [
                    'required' => 'Необходимо указать :attribute.',
                    'string' => 'Поле :attribute должно содержать только символы.',
                    'integer' => 'Поле :attribute должно содержать только цифры.',
                    'min' => 'Поле :attribute должно содержать минимум :min символов',
                ]);
            // вернет ошибку если не прошла валидация
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 422);
            }

            if (Category::find($request->category_id) === null) {
                return response()->json(['message' => 'Не найдена категория'], 404);
            }

            // добавление

            $prod = Product::find($id);
            if ($prod === null) {
                return response()->json(['message' => 'Не найден такой товар'], 404);
            }

            $prod->category_id = $request->category_id;
            $prod->name = $request->name;
            $prod->price = $request->price;
            $prod->description = $request->description;
            $prod->count = $request->count;
            $prod->size = $request->size;
            $prod->save();

            $cat = Category::find($prod->category_id);
            $prod->category_id = $cat->name;

            return $this->onSuccess($prod);
        }
        return $this->forbidden();
    }

    public function delete(Request $request, $id) {
        if ($this->isAdmin($request->user())) {
            $prod = Product::find($id);

            if ($prod === null) {
                return response()->json(['message'=>'Товар не найден'], 404);
            }

            $prod->delete();

            return response()->json(['message'=>'Успешно удален']);
        }
        return $this->forbidden();
    }
}
