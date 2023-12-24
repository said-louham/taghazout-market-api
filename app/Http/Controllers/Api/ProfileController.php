<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{


    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'phone' => ['required'],
            'adress' => ['required', 'string', 'max:499'],
        ]);
        $user = User::findOrFail(auth()->id());
        if ($user) {

            $user->update([
                'name' => $request->username,
            ]);

            $user->Profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $request->phone,
                    'adress' => $request->adress,
                ]
            );

            return response()->json([
                'status' => true,
                'message' => 'Profile Updated Successfully',
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'user not found'
            ]);
        }
    }
}
