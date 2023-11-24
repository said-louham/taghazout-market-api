<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileSettingController extends Controller
{
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $data = $request->validated();

        if (! Hash::check($data['current_password'], auth()->user()->password)) {
            return response()->json(false, 401);
        }
        User::query()->where('id', auth()->id())->update([
            'password' => Hash::make($data['password']),
        ]);

        $request->user()->tokens()->delete();

        return response()->json(true);
    }
}
