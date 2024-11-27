<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class Stats extends Controller
{
    public function getStatsDoc($id){
        
        $doctor = Doctor::find($id);
        if(!$doctor)
            return response()->json([
                'message' => 'El doctor no esta registrado en el sistema.'
            ],404);
        
        return response()->json([

        ]);

    }
}
