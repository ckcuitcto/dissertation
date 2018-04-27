<?php

namespace App\Http\Controllers\Information;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InformationController extends Controller
{
    public function show($id){
        return view();
    }

    public function update($id, Request $request){
        $inform = User::find($id);
        if (!empty($inform)) {
            $inform->name = $request->name;
            $inform->email = $request->email;
            $inform->gender = $request->gender;
            $inform->address = $request->address;
            $inform->phone_number = $request->phone_number;
            $inform->birthday = $request->birthday;
            $inform->Permissions()->sync($request->permission);
            $inform->save();
            return response()->json([
                'inform' => $inform,
                'status' => true
            ], 200);
        }
        return response()->json([
            'status' => false
        ], 200);
    }
}
