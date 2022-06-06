<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class FeedbackController extends Controller
{
    use ApiHelpers;

    public function create(Request $request)
    {
//        $validate = Validator::make($request->all(), [
//            'fio' => 'required|string|min:5',
//            'phone' => 'required|string|min:8',
//        ]);
//
//        if ($validate->fails()) {
//            return response()->json(['message' => $validate->errors()], 422);
//        }

        $feedback = Feedback::create([
            'fio' => 'эфыв',
            'phone' => '342323'
        ]);

        return $this->onSuccess($feedback);
    }
}
