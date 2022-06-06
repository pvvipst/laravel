<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Models\Feedbacks;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class FeedbackController extends Controller
{
    use ApiHelpers;

    public function create(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'fio' => 'required|string|min:5',
            'phone' => 'required|string|min:8',
        ]);

        if ($validate->fails()) {
            return response()->json(['message' => $validate->errors()], 422);
        }

        $feedback = Feedbacks::create([
            'fio' => $request->fio,
            'phone' => $request->phone
        ]);

        return $this->onSuccess($feedback);
    }

    public function getAll(Request $request): JsonResponse
    {
        if ($this->isAdmin($request->user())) {
            return $this->onSuccess(DB::table('feedbacks')->get());
        }
        return $this->forbidden();
    }

    public function changeStatus(Request $request, $id): JsonResponse
    {
        if ($this->isAdmin($request->user())) {
            $feed = Feedbacks::find($id);

            if ($feed === null) {
                return response()->json(['message' => 'Не найден feedback'], 404);
            }

            $feed->status = 1;
            $feed->save();

            return response()->json(['message'=>'Успешно выполненно']);
        }
        return $this->forbidden();
    }
}
