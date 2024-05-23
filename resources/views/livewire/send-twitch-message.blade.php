<div wire:poll.15s>
    @if($isBlacklisted)
        <div class="flex flex-col flex-grow justify-end">
            <div class="bg-gray-900/40 pb-4 pt-4 rounded-md mt-auto">
                <div class="text-center items-center">
                    <div class="bg-gray-800/40 rounded-full p-3 inline-block">
                        <x-lucide-message-circle-off class="mx-auto h-6 w-6 text-gray-400"/>
                    </div>
                    <h3 class="mt-2 text-sm font-semibold text-gray-100">Chat Unavailable</h3>
                    <p class="mt-2 text-sm font-semibold text-gray-500">You have been blacklisted from chatting in this
                        channel</p>
                </div>
            </div>
        </div>
    @else
        <form id="messageForm" class="flex flex-col flex-grow" wire:submit.prevent="sendMessage">
            <!-- Tooltip -->
            <div class="bg-yellow-400 text-gray-900 px-2 py-1 rounded-md mb-2 text-center text-sm">
                All messages sent are filtered and are sent with your Twitch username
            </div>
            <textarea id="messageInput" placeholder="Type your message..." wire:model="message"
                      class="flex-grow rounded-md bg-gray-900 p-2 text-white"></textarea>

            @error('message') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            <button type="submit"
                    class="bg-blue-600 px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-400 mt-2">
                Send
            </button>
        </form>
    @endif

    @script
    <script>
        $wire.on('profanity-detected', () => {
            alert('Your message was not sent due to profanity');
        });

        $wire.on('blacklisted', () => {
            alert('Your message was not sent due to being blacklisted from this channel');
        });
    </script>
    @endscript
</div>
