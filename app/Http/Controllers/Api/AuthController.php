<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactUsMail;
use App\Models\Message;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function index()
    {
        $users = User::orderByRaw("CASE WHEN role = 'admin' THEN 0 ELSE 1 END")
            ->get();

        return response()->json([
            'users' => $users,
        ]);
    }

    public function register(Request $request)
    {
        $this->validate(
            $request,
            [
                'full_name' => ['bail', 'required', 'string', 'min:3', 'max:50', 'unique:users,username'],
                'email' => ['bail', 'required', 'string', 'min:3', 'max:50', 'unique:users,email'],
                'password' => ['bail', 'required', 'string', 'min:6'],
            ]
        );

        DB::beginTransaction();

        User::create([
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        DB::commit();

        return response()->json(true);
    }

    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (! auth()->attempt($data)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $tokenName = config('APP_NAME', 'TOKEN_NAME');
        $expiresAt = app()->isLocal() ? now()->addYear() : now()->addDay();
        $token = $request->user()->createToken($tokenName, ['*'], $expiresAt);

        return response()->json(['token' => $token->plainTextToken], 200);
    }

    public function logout()
    {
        $user = request()->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        return response()->json(true, 200);
    }

    public function forget(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $numberToken = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            PasswordResetToken::updateOrCreate(
                ['email' => $user->email],
                ['token' => $numberToken]
            );

            $user->notify(new ResetPasswordNotification($numberToken));
        }

        return response()->json(true);
    }

    public function reset(Request $request)
    {

        $data = $this->validate($request, [
            'email' => ['bail', 'required', 'string', 'min:3', 'max:50', 'unique:users,email'],
            'password' => ['bail', 'required', 'string', 'min:6'],
            'token' => ['bail', 'required', 'string', 'min:4'],
        ]);

        $resetRequest = PasswordResetToken::where('email', $data['email'])
            ->where('token', $data['token'])
            ->first();

        $user = User::where('email', $request->email)->first();

        if (! $user || ! $resetRequest) {
            return response()->json(['error' => 'wrong credentials']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $user->tokens()->delete();
        $resetRequest->delete();

        $tokenName = config('APP_NAME', 'TOKEN_NAME');
        $expiresAt = app()->isLocal() ? now()->addYear() : now()->addDay();
        $token = $request->user()->createToken($tokenName, ['*'], $expiresAt);

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function destroy($id)
    {
        $user = user::find($id);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
            'data' => $user,
        ]);
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'current_password' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $currentPasswordStatus = Hash::check($request->current_password, auth()->user()->password);
        if ($currentPasswordStatus) {

            User::findOrFail(Auth::user()->id)->update([
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'message' => 'Password updated successfully',
                'data' => auth()->user(),

            ]);
        } else {
            return response()->json([
                'error' => 'Current password is incorrect',
            ]);
        }
    }

    public function contactUs(Request $request)
    {
        $data = $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'message' => 'required|max:255',
        ]);

        DB::beginTransaction();

        $savedMessage = Message::create($data);
        Mail::to($data['email'])->send(new ContactUsMail($data['first_name'], $data['last_name']));

        DB::commit();

        return response()->json(true);

    }
}
