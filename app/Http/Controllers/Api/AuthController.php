<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PasswordResetToken;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Validator;
use App\Notifications\ResetPasswordNotification;
use App\Mail\ContactUsMail;
use App\Models\Message;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    public function index()
    {
        $users = User::orderByRaw("CASE WHEN role = 'admin' THEN 0 ELSE 1 END")
            ->get();
        return response()->json([
            'users' => $users
        ]);
    }

    public function register(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'full_name' => 'required',
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ]);
        }

        $userexist = User::whereEmail($request->email)->first();
        if ($userexist) {
            return response()->json([
                'error' => 'Oops ! This email is alrady used'
            ]);
        }

        $user = User::create([
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
        ], 200);
    }

    //------------------------------------------------------------------------------------------
    public function login(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ]);
        }

        $user = User::whereEmail($request->email)->first();
        if (isset($user->id)) {
            if (hash::check($request->password, $user->password)) {
                $token = $user->createToken('Auth_token')->plainTextToken;
                // return (new AuthResource($user))->withToken($token);
                return response()->json([
                    'message' => 'Connected successfully',
                    'user' => $user,
                    'token' => $token,
                    "status" => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Wrong credentials',
                    "status" => 401
                ]);
            };
        } else {
            return response()->json([
                'message' => "User doesn't exists",
                "status" => 404
            ]);
        };
    }


    public function Profile()
    {
        // return new ProfileResource(auth()->user());
        return response()->json([
            'user' => auth()->user()
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout successfully',
            'status' => 200
        ]);
    }

    //--------------------------------------------------------------------------
    public function forget(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'error' => 'No user exists with this email. Please try again.',
            ]);
        }

        $numberToken = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        PasswordResetToken::updateOrCreate(
            ['email' => $user->email],
            ['token' => $numberToken]
        );

        $user->notify(new ResetPasswordNotification($numberToken));

        return response()->json([
            'message' => 'Check your email address.',
        ]);
    }

    //--------------------------------------------------------------------------
    public function reset(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'token' => 'required',
        ]);


        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'error' => $validateUser->errors()
            ]);
        }

        $resetRequest = PasswordResetToken::where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRequest) {
            return response()->json(['error' => 'Invalid token']);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Cannot find any user with this email']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $user->tokens()->delete();
        $resetRequest->delete();

        $token = $user->createToken('Auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Your password has been changed successfully',
            'user' => $user,
            'token' => $token
        ]);
    }



    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 401,
                'message' => 'validation error',
                'error' => $validator->errors()
            ]);
        }

        $user->update([
            'full_name' => $request->input('full_name', $user->full_name),
            'email' => $request->input('email', $user->email),
            'address' => $request->input('address', $user->address),
            'phone' => $request->input('phone', $user->phone),
            'role' => $request->input('role') ? 'admin' : 'user',
        ]);

        return response()->json([
            'status' => 200,
            'message' => ' Account updated successfully',
            'user' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = user::find($id);
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully',
            'data' => $user
        ]);
    }
    public function show($id)
    {
        $user = user::find($id);
        return response()->json([
            'user' => $user,
            'message' => 'user deleted seccessfully'
        ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'validation error',
                'error' => $validator->errors()
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
    }

    public function contactUs(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'message' => 'required|max:255',
        ]);
        if ($validatedData->fails()) {
            return response()->json([
                'status' => 401,
                'message' => 'validation error',
                'errors' => $validatedData->errors()
            ]);
        }
        $savedMessage = Message::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'message' => $request->message,
        ]);
        try {
            Mail::to($request->email)->send(new ContactUsMail($request->first_name, $request->last_name));
            return response()->json([
                'message' => 'Message sent successfully',
                'saved_message' => $savedMessage,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'status' => 500
            ]);
        }
    }
}
