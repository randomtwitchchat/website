<?php

namespace App\Console\Commands;

use GhostZero\Tmi\Client;
use GhostZero\Tmi\ClientOptions;
use Illuminate\Console\Command;

class TwitchConnectCommand extends Command
{
    // The name and description of the console command.
    protected $signature = 'twitch:connect';
    protected $description = 'Create new connection to Twitch';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $tmiclient = new Client(new ClientOptions([
            'options' => ['debug' => true],
            'connection' => [
                'secure' => true,
                'reconnect' => true,
                'rejoin' => true,
            ],
            'identity' => [
                'username' => env('TWITCH_BOT_USERNAME'),
                'password' => 'oauth:' . env('TWITCH_BOT_TOKEN'),
            ],
            'channels' => ['kingsman265_twitch']
        ]));

        $tmiclient->connect();
    }
}
