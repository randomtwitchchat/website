<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
use App\Models\User;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    public function update(Request $request)
    {
        $username = $request->input('username');
        $channelName = $request->input('channel_name');

        if(!$request->input('key') == env('TWITCH_BOT_API_TOKEN') ){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Find the exact user using username
        $user = User::where('name', $username)->first();

        // If the user is found
        if($user) {
            // Find or Create a new blacklist entry
            $blacklist = Blacklist::firstOrCreate([
                'user_id' => $user->id,
                'channel_name' => $channelName
            ]);

            // Toggle the status
            $blacklist->status = !$blacklist->status;
            $blacklist->save();

            // Return response with 'blacklisted' field
            return response()->json([
                'blacklisted' => $blacklist->status,
            ]);
        }

        // If the user is not found
        return response()->json([
            'error' => 'User not found',
        ]);
    }
}
