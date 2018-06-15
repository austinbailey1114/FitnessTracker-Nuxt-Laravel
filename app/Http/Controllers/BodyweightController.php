<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\bodyweight;

class BodyweightController extends Controller
{
    public function getBodyweight(Request $request) {
        $bodyweights = Bodyweight::where('user', $request->id)->get();

        return response()->json($bodyweights);
    }

    public function postBodyweight(Request $request) {

        $date = strtotime('today midnight');
		$date = date("Y-m-d H:i:s", $date);

        try {
            Bodyweight::create([
                'user' => $request->user,
                'weight' => $request->weight,
                'date' => $date,
            ]);

            return response()->json([
                'success' => 'Bodyweight added successfully'
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'failure' => 'Sorry, we were unable to add that data'
            ], 400);
        }
    }

    public function deleteBodyweight(Request $request) {
        try {
            Bodyweight::where('id', $request->id)->delete();
            return response()->json([
                'success' => 'Bodyweight deleted successfully'
            ]);
        } catch(QueryException $e) {
            return response()->json([
                'failure' => 'Unable to delete bodyweight'
            ], 400);
        }
    }
}
