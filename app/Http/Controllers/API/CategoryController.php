<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use ApiHelpers;

    public function prodInCategory($id): JsonResponse
    {
        if (Category::find($id) === null) {
            return response()->json(['massage'=>'Категория не найдена'], 404);
        }
        $prod = Product::where('category_id', $id)->get();

        for ($i = 0; $i < count($prod); $i++) {
            $cat = Category::find($prod[$i]->category_id);
            $prod[$i]->category_id = $cat->name;
        }
        return $this->onSuccess($prod);
    }

    // получение всех категорий
    public function getAll(Request $request): JsonResponse
    {
        return $this->onSuccess(DB::table('categories')->get());
    }

    // добавление категории
    public function create(Request $request): JsonResponse
    {
        // проверка на одмина
        if ($this->isAdmin($request->user())) {
            // валидации
            $validate = Validator::make($request->all(),
                [
                    'name' => 'required|string|min:2',
                ],
                [
                    'required' => 'Необходимо указать :attribute.',
                    'string' => 'Поле :attribute должно содержать только символы.',
                    'min' => 'Поле :attribute должно содержать минимум :min символов',
                ]);
            // вернет ошибку если не прошла валидация
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 422);
            }
            // добавление
            $cat = Category::create([
                'name' => $request->name
            ]);

            return $this->onSuccess($cat);
        }
        // нет доступа
        return $this->forbidden();
    }

    public function update(Request $request, $id)
    {
        if ($this->isAdmin($request->user())) {

            $validate = Validator::make($request->all(),
                [
                    'name' => 'required|string|min:5',
                ],
                [
                    'required' => 'Необходимо указать :attribute.',
                    'string' => 'Поле :attribute должно содержать только символы.',
                    'min' => 'Поле :attribute должно содержать минимум :min символов',
                ]
            );

            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 422);
            }

            $cat = Category::find($id);

            if ($cat === null) {
                return $this->notFound();
            }

            $cat->name = $request->name;
            $cat->save();

            return $this->onSuccess($cat);
        }
        return $this->forbidden();
    }

    public function delete(Request $request, $id)
    {
        if ($this->isAdmin($request->user())) {
            $cat = Category::find($id);

            if ($cat === null) {
                return $this->notFound();
            }
            $cat->delete();

            return response()->json(['message'=>'Успешно удален']);
        }
        return $this->forbidden();
    }
}
