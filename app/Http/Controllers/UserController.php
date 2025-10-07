<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $age = $request->age;

        $insert = Users::create([
            'name' => $name,
            'email' => $email,
            'age' => $age,
        ]);
        if ($insert) {
            return response()->json(['status' => true, 'message' => 'inserted succcessfully']);
        } else {
            return response()->json(['status' => false, 'message' => ' not inserted ']);
        }
    }
    public function getdetails(Request $request)
    {

        $users = Users::all();
        if ($users) {
            return response()->json(['status' => true, 'message' => $users]);
        } else {
            return response()->json(['status' => false, 'message' => 'data not found']);
        }
    }

    public function deletedetails(Request $request)
    {
        $userId = $request->id;
        $delete = Users::where('id', $userId)->update([
            'deleted_at' => Carbon::now()
        ]);
    }

    public function editdetails(Request $request)
    {
        $userId = $request->id;
        $details = Users::where('id', $userId)->first();
        if ($details) {
            return response()->json(['status' => true, 'message' => $details]);
        } else {
            return response()->json(['status' => false, 'message' => "not edited"]);
        }
    }

    public function updatedetails(Request $request)
    {

        $id = $request->id;
        $name = $request->name;
        $email = $request->email;
        $age = $request->age;

        $update = Users::where('id', $id)->update([
            'name' => $name,
            'email' => $email,
            'age' => $age,

        ]);
        if ($update) {
            return response()->json(['status' => true, 'message' => 'updated succcessfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'not inserted']);
        }
    }
}
