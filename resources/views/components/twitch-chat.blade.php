@props(['channel'])

<style>
    /* The following CSS just makes sure the twitch video stays responsive */
    iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>

<div x-data="{ isChatLoading: true }" class="bg-gray-700 h-3/4 w-full relative">
    <div id="chat" x-show="isChatLoading"
         class="absolute inset-0 flex items-center justify-center bg-gray-700 flex-col gap-y-2 text-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="h-8 w-8 animate-spin">
            <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
        </svg>
        <p class="text-sm mt-1 text-white">Loading chatroom...</p>
    </div>
    <iframe x-show="!isChatLoading"
        id="obschat"
        src="https://nightdev.com/hosted/obschat/?theme=none&amp;channel={{ $channel }}&amp;fade=30&amp;bot_activity=true&amp;prevent_clipping=false"
        onload="chatLoaded(this)"
        frameborder="0"
    ></iframe>
</div>

<script>
    // Make sure you add the "chat" id to your main div
    var chatContainer = document.querySelector('#chat');
    var iframe = document.querySelector('#obschat');
    iframe.style.display = 'none';
    var loadingText = chatContainer.querySelector('.text-secondary');

    function chatLoaded() {
        var min = 3;
        var max = 5;
        var delayInSeconds = Math.floor(Math.random() * (max - min + 1) + min) * 1000; // Convert to milliseconds

        setTimeout(function(){
            chatContainer.style.display = 'none';
            iframe.style.display = 'block';
            Alpine.store('isChatLoading', false);
        }, delayInSeconds);
    }
</script>
