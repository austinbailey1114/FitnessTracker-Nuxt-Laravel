<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Goals;

class GoalsController extends Controller
{
    public function getFoodGoals(Request $request) {
        $goals = Goals::where('user', $request->id)->first();

        if (!$goals) {
            $goals = Goals::create([
                'user' => $request->id,
                'calories' => 2000,
                'fat' => 30,
                'carbohydrate' => 40,
                'protein' => 50,
            ]);
        }

        return response()->json($goals);
    }

    public function postFoodGoals(Request $request) {
        if (Goals::where('user', $request->user)->update([
            'calories' => $request->cals,
            'fat' => $request->fat,
            'carbohydrate' => $request->carbs,
            'protein' => $request->protein,
        ])) {
            return response()->json([
                'success' => 'Goals updated successfully'
            ]);
        }

        return response()->json([
            'failure' => 'Sorry, we were unable to update that data'
        ], 400);


    }
}
