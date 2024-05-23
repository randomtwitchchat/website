@props(['src'])

<style>
    /* The following CSS just makes sure the twitch video stays responsive */
    #player, .offline-placeholder {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    #video-player-container {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .offline-placeholder {
        background-color: rgba(51,65,85,.7);
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .offline-placeholder p {
        color: #fff;
    }
</style>
<div x-data="{ isPlayerLoading: true }" class="bg-transparent h-screen w-full">
    <div id="stream" class="relative size-full border border-transparent bg-transparent">
        <div x-show="isLoading"
             class="absolute inset-0 flex items-center justify-center bg-gray-800 flex-col gap-y-2 text-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="h-8 w-8 animate-spin">
                <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
            </svg>
            <p class="text-sm mt-1 text-white">Loading stream...</p>
        </div>
        <div x-show="!isLoading" id="video-player-container" class="size-full">
            <div id="player"></div>
            <div class="offline-placeholder flex bg-gray-800" id="offline-placeholder">
                <p>Streamer is currently offline, please check back later.</p>
                <div class="mt-4">
                    <button class="hover:underline text-lg bg-gray-700 rounded-md p-4" onclick="window.location.reload()">
                        <div class="flex items-center space-x-2">
                            <x-lucide-refresh-ccw class="w-4"/>
                            <span>{{__('Different Channel')}}</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://player.twitch.tv/js/embed/v1.js"></script>

<script>
    window.addEventListener('DOMContentLoaded', function () {
        var videoContainer = document.getElementById('stream');
        var playerEl = document.getElementById('player');
        var videoPlayerContainer = document.getElementById('video-player-container');
        var offlinePlaceholderEl = document.getElementById('offline-placeholder');
        var isPlayerLoading = true; // Moved isLoading variable to JavaScript scope

        // Initially, hide the player
        playerEl.style.display = 'none';

        // Simulate loading delay
        setTimeout(function () {
            var videoStreamer = @json($src); // Get the twitch source from the component parameter

            var options = {
                channel: videoStreamer, // channel name
                height: "100%",
                width: "100%",
                parent: "{{ str_replace(['http://', 'https://'], '', config('app.url')) }}" // site url
            };

            // Instantiate the Twitch Player
            var player = new Twitch.Player("player", options);

            player.addEventListener(Twitch.Player.READY, function () {
                // Once the player is ready, show it and hide the loading spinner
                videoPlayerContainer.style.display = 'block'; // Show the video container
                playerEl.style.display = 'block'; // Show the player
                videoContainer.querySelector('.text-secondary').style.display = 'none'; // Hide the loading spinner

                // Register Twitch Player Events
                player.addEventListener(Twitch.Player.ONLINE, handleOnline);
                player.addEventListener(Twitch.Player.OFFLINE, handleOffline);

                isPlayerLoading = false; // Update the isLoading flag to hide the preloader
            });

            function handleOnline() {
                playerEl.style.display = 'block'; // Show the player
                offlinePlaceholderEl.style.display = 'none'; // Hide the offline placeholder
                player.setMuted(false);
            }

            function handleOffline() {
                playerEl.style.display = 'none'; // Hide the player
                offlinePlaceholderEl.style.display = 'flex'; // Show the offline placeholder
                player.setMuted(true);
            }

        }, 2000); // Delay time for user experience enhancement
    });
</script>
