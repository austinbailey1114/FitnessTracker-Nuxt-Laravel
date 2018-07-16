<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Foods;
use Carbon\Carbon;

class FoodController extends Controller
{
    public function postFood(Request $request) {
        $created = Foods::create([
            'user' => $request->user,
            'name' => $request->name,
            'fat' => $request->fat,
            'carbohydrate' => $request->carbohydrate,
            'calories' => $request->calories,
            'protein' => $request->protein,
        ]);

        if ($created) {
            return response()->json([
                'success' => 'Successfully added food.'
            ]);
        }

        return response()->json([
            'error' => 'Sorry, we were unable to save that.'
        ]);
    }

    public function recentFoods(Request $request) {
        $foods = Foods::where('user', $request->user)
                      ->orderBy('created_at', 'desc')
                      ->take(10)
                      ->get();

        return response()->json($foods);
    }

    public function getTotals(Request $request) {
        $foods = Foods::where('user', $request->user)
                      ->whereDate('created_at', Carbon::today());

        return response()->json([
            'calories' => round($foods->sum('calories')),
            'fat' => round($foods->sum('fat')),
            'carbohydrate' => round($foods->sum('carbohydrate')),
            'protein' => round($foods->sum('protein')),
        ]);
    }
}
