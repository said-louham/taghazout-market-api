<?php

namespace App\Http\Controllers\Api\admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::firstOrFail();

        return response()->json([
            'status' => 'success',
            'message' => 'Settings retrieved successfully',
            'data' => $settings
        ], 200);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'website_name' => ['required', 'string', 'max:255'],
            'website_url' => ['required'],
            'adress' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'facebook' => ['nullable'],
            'instagram' => ['nullable'],
            'twitter' => ['nullable'],
            // 'linkden' => ['nullable'],
        ]);

        $setting = Setting::updateOrCreate([], $validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Settings created or updated successfully',
            'data' => $setting
        ], 201);
    }

    public function update(Request $request, Setting $setting)
    {

        $setting->website_name = $request->input('website_name', $setting->website_name);
        $setting->website_url = $request->input('website_url', $setting->website_url);
        $setting->adress = $request->input('adress', $setting->adress);
        $setting->phone = $request->input('phone', $setting->phone);
        $setting->email = $request->input('email', $setting->email);
        $setting->facebook = $request->input('facebook', $setting->facebook);
        $setting->instagram = $request->input('instagram', $setting->instagram);
        $setting->twitter = $request->input('twitter', $setting->twitter);
        // $setting->linkden = $request->input('linkden', $setting->linkden);
        $setting->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Settings updated successfully',
            'data' => $setting
        ], 200);
    }
}
