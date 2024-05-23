<?php

namespace App\Livewire;

use App\Models\Blacklist;
use Filament\Notifications\Notification;
use GhostZero\Tmi\ClientOptions;
use GhostZero\Tmi\Events\Twitch\MessageEvent;
use GhostZero\Tmi\Client as TMIClient;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SendTwitchMessage extends Component
{
    public $message = '';
    public $channel;

    public function mount($channel)
    {
        $this->channel = $channel;
    }

    public function updatedMessage()
    {
        $this->validate(['message' => 'required|max:255']);
    }

    public function sendMessage()
    {
        // Check if the user is blacklisted in the channel
        $blacklist = Blacklist::where('user_id', Auth::id())
            ->where('channel_name', $this->channel)
            ->first();

        if ($blacklist && (bool)$blacklist->status === true) {
            // Don't send the message if the user is blacklisted
            $this->dispatch('blacklisted');
            return;
        }

        $client = new GuzzleClient();
        $res = $client->request('POST', 'https://vector.profanity.dev', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => ['message' => $this->message]
        ]);

        $responseBody = json_decode($res->getBody()->getContents());

        if ($responseBody->isProfanity) {
            // Profanity detected, do not send message
            $this->dispatch('profanity-detected');

        } else {

            // URL of the Express Server
            $url = env("TWITCH_BOT_API_URL") . '/send';

            // Secret Key
            $secretKey = env("TWITCH_BOT_API_TOKEN");

            $message = "(" . Auth::user()->name . "): " . $this->message;

            // Send the request
            $response = Http::post($url, [
                'key' => $secretKey,
                'message' => $message,
                'channel' => $this->channel,
            ]);

            if ($response->successful()) {
               // success
            } else {
                // fail
                Log::debug($response);
            }
            $this->message = ''; // Clear the message box
        }
    }

    public function render()
    {
        // Try to get the user's blacklist record for this channel, or create it with default status
        $blacklist = Blacklist::firstOrCreate(
            ['user_id' => Auth::id(), 'channel_name' => $this->channel],
            ['status' => 0]
        );

        // Using Laravel's dd() to dump the status. It will stop execution after this.
        // dd($blacklist->status);

        // Since we use firstOrCreate, isBlacklisted will always be a boolean
        $isBlacklisted = (bool)$blacklist->status;

        return view('livewire.send-twitch-message', [
            'isBlacklisted' => $isBlacklisted,
        ]);
    }
}
