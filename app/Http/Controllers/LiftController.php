<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\LiftResource;
use App\Lift;
use App\Lifttype;

class LiftController extends Controller
{
    public function getLift(Request $request) {
        $lifts = Lift::where('user', $request->id)->orderBy('date', 'asc')->get();

        return LiftResource::collection($lifts);
    }

    public function postLift(Request $request) {

        $this->validate($request, [
            'weight' => 'required|numeric',
            'reps' => 'required|numeric',
        ]);

        if ($request->date == "") {
			$date = strtotime('today midnight');
		} else {
			$date = strtotime($request->date);
		}

		$date = date("Y-m-d H:i:s", $date);

        if ($request->newType) {
            $type = $request->newType;

            Lifttype::create([
                'user' => $request->user,
                'name' => $type,
            ]);
        } else {
            $type = $request->type;
        }

        $created = Lift::create([
            'weight' => $request->weight,
            'reps' => $request->reps,
            'date' => $date,
            'type' => $type,
            'user' => $request->user,
        ]);

        if ($created) {
            return response()->json([
                'success' => 'New lift added successfully'
            ]);
        }
        return response()->json([
            'failure' => 'Sorry, we were unable to add that data'
        ], 400);

    }

    public function deleteLift(Request $request) {
        if (Lift::where('id', $request->id)->delete()) {
            return response()->json([
                'Success' => 'Lift deleted successfully'
            ]);
        }
        return response()->json([
            'failure' => 'Unable to delete that lift'
        ]);


    }
}
