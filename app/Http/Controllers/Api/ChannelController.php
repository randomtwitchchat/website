<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChannelController extends Controller
{
    public function addChannel(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'channel' => 'required|string|unique:channels,channel|max:255',
            'key' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        if(!$request->input('key') == env('TWITCH_BOT_API_TOKEN') ){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Create a new channel
        $channel = Channel::create(['channel' => $request->input('channel')]);

        return response()->json(['message' => 'Channel added successfully!', 'channel' => $channel], 201);
    }

    public function removeChannel(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'channel' => 'required|string|exists:channels,channel',
            'key' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        if(!$request->input('key') == env('TWITCH_BOT_API_TOKEN') ){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Find and delete the channel
        $channel = Channel::where('channel', $request->input('channel'))->first();
        $channel->delete();

        return response()->json(['message' => 'Channel removed successfully!'], 200);
    }
}
