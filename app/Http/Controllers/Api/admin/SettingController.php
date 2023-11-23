<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $data = Setting::first();

        return response()->json($data);
    }

    public function store(SettingRequest $request)
    {
        $data = $request->validated();

        Setting::first()->updateOrfail($data);

        return response()->json(true);
    }
}
