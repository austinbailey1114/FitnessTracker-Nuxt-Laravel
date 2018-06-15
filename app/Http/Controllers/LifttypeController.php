<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lifttype;

class LifttypeController extends Controller
{
    public function getLifttypes(Request $request) {
        $lifttypes = LiftType::where('user', $request->id)->get();

        return response()->json($lifttypes);
    }
}
