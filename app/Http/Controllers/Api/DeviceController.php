<?php

    namespace App\Http\Controllers\API;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class DeviceController extends Controller
    {
        public function register(Request $request)
        {
            $request->validate([
                'fcm_token' => 'required|string',
            ]);

            $user = Auth::user();
            $user->fcm_token = $request->fcm_token;
            $user->save();

            return response()->json(['message' => 'Device token registered successfully.']);
        }
    }
    
