<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ public_path('favicon.ico') }}" type="image/x-icon">
    <meta name="description" content="Chat with random streamers on {{ config('app.name') }}">
    <title>{{ config('app.name') }}</title>
    <!-- Styles -->
    @filamentStyles
    @filamentScripts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body,
        html {
            height: 100%;
        }

        .full-height {
            height: 100%;
        }

    </style>
</head>

<body class="bg-gray-900 text-white font-sans">
<!-- Header -->
<header class="flex items-center justify-between bg-gray-800 px-4 py-2 text-white">
    {{-- left content --}}
    <div class="flex gap-4 items-center">
        <a href="#" class="text-xl font-bold">{{ config('app.name') }}</a>
        <div x-data="{ open: false }" class="lg:hidden">
            <!-- Hamburger button -->
            <button @click="open = !open" class="text-lg">
                <svg x-show="!open" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
                <svg x-show="open" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <!-- Mobile navigation -->
            <div x-show="open" class="absolute top-0 left-0 p-5 mt-12 bg-gray-800 w-full h-full">
                <button data-modal-target="commands-modal" data-modal-toggle="commands-modal"
                        class="block text-white mb-4">
                    <div class="flex items-center space-x-2">
                        <x-lucide-bot-message-square class="w-4"/>
                        <span>{{__('Commands')}}</span>
                    </div>
                </button>
                <button data-modal-target="about-modal" data-modal-toggle="about-modal"
                        class="block text-white mb-4">
                    <div class="flex items-center space-x-2">
                        <x-lucide-badge-info class="w-4"/>
                        <span>{{__('About')}}</span>
                    </div>
                </button>
                <a href="mailto:{{ env('APP_CONTACT_EMAIL') }}?subject={{config('app.name')}}&body=Let%20us%20know%20what%20you'd%20like%20to%20talk%20about%20here."
                   class="hover:underline text-lg">
                    <div class="flex items-center space-x-2">
                        <x-lucide-mail class="w-4"/>
                        <span>{{__('Contact')}}</span>
                    </div>
                </a>
            </div>
        </div>
        <!-- Desktop navigation -->
        <div class="hidden lg:flex gap-4 items-center">
            <button class="hover:underline text-lg" data-modal-target="commands-modal"
                    data-modal-toggle="commands-modal">
                <div class="flex items-center space-x-2">
                    <x-lucide-bot-message-square class="w-4"/>
                    <span>{{__('Commands')}}</span>
                </div>
            </button>
            <button class="hover:underline text-lg" data-modal-target="about-modal" data-modal-toggle="about-modal">
                <div class="flex items-center space-x-2">
                    <x-lucide-info class="w-4"/>
                    <span>{{__('About')}}</span>
                </div>
            </button>
            <a href="mailto:{{ env('APP_CONTACT_EMAIL') }}?subject={{config('app.name')}}&body=Let%20us%20know%20what%20you'd%20like%20to%20talk%20about%20here."
               class="hover:underline text-lg">
                <div class="flex items-center space-x-2">
                    <x-lucide-mail class="w-4"/>
                    <span>{{__('Contact')}}</span>
                </div>
            </a>
        </div>
    </div>

    {{-- middle content --}}
    <div id="countdown"><span id="time">5:00</span> left on this channel</div>

    <!-- Dropdown -->
    @auth
        <div x-data="{ open: false }" class="relative inline-block text-left">
            <!-- Button -->
            <div>
                <button @click="open = !open" type="button"
                        class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-100 shadow-sm ring-1 ring-inset ring-gray-800 hover:bg-gray-900 dro-shadow-md"
                        aria-expanded="true" aria-haspopup="true">
                    {{ \Illuminate\Support\Facades\Auth::user()->name }}
                    <svg class="-mr-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                         aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <!-- Dropdown menu -->
            <div x-show="open" @click.away="open = false"
                 class="absolute right-0 z-10 mt-2 w-56 origin-top-right divide-y divide-gray-700 rounded-md bg-gray-800 border border-gray-700 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                 role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                <div class="px-4 py-3" role="none">
                    <p class="text-sm text-gray-200" role="none">Signed in as</p>
                    <p class="truncate text-sm font-medium text-gray-100"
                       role="none">{{ \Illuminate\Support\Facades\Auth::user()->name }}
{{--                        <span--}}
{{--                            class="ml-1 inline-flex items-center rounded-md bg-yellow-400/10 px-2 py-1 text-xs font-medium text-yellow-500 ring-1 ring-inset ring-yellow-400/20">Supporter</span>--}}
                    </p>
                </div>
                <div class="py-1" role="none">
                    <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                    <button aria-label="Manage your channel blacklist" data-modal-target="blacklist-modal"
                            data-modal-toggle="blacklist-modal"
                            class="text-gray-100 block px-4 py-2 text-sm" role="menuitem" tabindex="-1"
                            id="menu-item-0">
                        <div class="flex items-center space-x-2">
                            <x-lucide-table-properties class="w-4"/>
                            <span>{{__('Manage Blacklist')}}</span>
                        </div>
                    </button>
                    <button aria-label="Changes the current Twitch channel" onclick="window.location.reload()"
                            class="text-gray-100 block px-4 py-2 text-sm" role="menuitem" tabindex="-1"
                            id="menu-item-0">
                        <div class="flex items-center space-x-2">
                            <x-lucide-refresh-ccw class="w-4"/>
                            <span>{{__('Different Channel')}}</span>
                        </div>
                    </button>
                    {{--                                <a href="#" class="text-gray-100 block px-4 py-2 text-sm" role="menuitem" tabindex="-1"--}}
                    {{--                                   id="menu-item-1">Support</a>--}}
                    {{--                                <a href="#" class="text-gray-100 block px-4 py-2 text-sm" role="menuitem" tabindex="-1"--}}
                    {{--                                   id="menu-item-2">License</a>--}}
                </div>
                <div class="py-1" role="none">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button onclick="window.location.reload()" class="text-gray-100 block px-4 py-2 text-sm"
                                role="menuitem" tabindex="-1" id="menu-item-0">
                            <div class="flex items-center space-x-2">
                                <x-lucide-log-out class="w-4"/>
                                <span>Logout</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div>
            <a href="{{ route('oauth.redirect', "twitch") }}"
               class="cursor-pointer inline-flex w-full justify-center gap-x-1.5 rounded-md bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-100 shadow-sm ring-1 ring-inset ring-gray-800 hover:bg-gray-900 dro-shadow-md"
               aria-expanded="true" aria-haspopup="true">
                Login With Twitch
                <svg class="-mr-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="#9146FF"
                     aria-hidden="true">
                    <path
                        d="M3.857 0 1 2.857v10.286h3.429V16l2.857-2.857H9.57L14.714 8V0zm9.714 7.429-2.285 2.285H9l-2 2v-2H4.429V1.143h9.142z"/>
                    <path d="M11.857 3.143h-1.143V6.57h1.143zm-3.143 0H7.571V6.57h1.143z"/>
                </svg>
            </a>
        </div>
    @endauth
</header>

<!-- Main Content -->
<main class="flex flex-col lg:flex-row full-height">
    <div class="lg:w-3/4 sm:h-full sm:w-full bg-gray-900 lg:h-screen sm:h-screen">
        <!-- Embed Twitch stream -->
        <x-twitch-player class="h-screen w-screen" src="{{ $randomChannel }}"/>
    </div>

    <div class="lg:w-1/4 bg-gray-700 p-4 relative flex flex-col">
        <!-- Embed OBSChat iframe -->
{{--        <iframe--}}
{{--            src="https://nightdev.com/hosted/obschat/?theme=none&channel={{ $randomChannel }}&fade=30&bot_activity=true&prevent_clipping=false"--}}
{{--            frameborder="0" class="h-3/4 w-full mb-4"></iframe>--}}
        <x-twitch-chat channel="{{$randomChannel}}" class="h-3/4 w-full mb-4"/>
        <!-- Message input -->
        @auth
            <div class="flex flex-col flex-grow justify-end">
                <livewire:send-twitch-message channel="{{$randomChannel}}"/>
            </div>
        @else
            <div class="flex flex-col flex-grow justify-end">
                <div class="bg-gray-900/40 pb-4 pt-4 rounded-md mt-auto">
                    <div class="text-center items-center">
                        <div class="bg-gray-800/40 rounded-full p-3 inline-block">
                            <x-lucide-message-circle-off class="mx-auto h-6 w-6 text-gray-400"/>
                        </div>
                        <h3 class="mt-2 text-sm font-semibold text-gray-100">Chat Unavailable</h3>
                        <div class="pl-4 pr-4 mt-2">
                            <a href="{{ route('oauth.redirect', "twitch") }}"
                               class="cursor-pointer inline-flex w-full justify-center gap-x-1.5 rounded-md bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-100 shadow-sm ring-1 ring-inset ring-gray-800 hover:bg-gray-900 dro-shadow-md"
                               aria-expanded="true" aria-haspopup="true">
                                Login With Twitch
                                <svg class="-mr-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="#9146FF"
                                     aria-hidden="true">
                                    <path
                                        d="M3.857 0 1 2.857v10.286h3.429V16l2.857-2.857H9.57L14.714 8V0zm9.714 7.429-2.285 2.285H9l-2 2v-2H4.429V1.143h9.142z"/>
                                    <path d="M11.857 3.143h-1.143V6.57h1.143zm-3.143 0H7.571V6.57h1.143z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endauth
    </div>
</main>

{{-- modals --}}
<!-- Commands modal -->
<div id="commands-modal" tabindex="-1" aria-hidden="true"
     class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-7xl max-h-full">
        <!-- Modal content -->
        <div class="relative rounded-lg shadow bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-600">
                <h3 class="text-xl font-semibold text-white">
                    {{__('Bot Commands')}}
                </h3>
                <button type="button"
                        class="text-gray-400 bg-transparent rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center hover:bg-gray-600 hover:text-white"
                        data-modal-hide="commands-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">


                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-400">
                        {{--                        <caption--}}
                        {{--                            class="p-5 text-lg font-semibold text-left rtl:text-right text-white bg-gray-800">--}}
                        {{--                            Our products--}}
                        {{--                            <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Browse a list of--}}
                        {{--                                Flowbite products designed to help you work and play, stay organized, get answers, keep--}}
                        {{--                                in touch, grow your business, and more.</p>--}}
                        {{--                        </caption>--}}
                        <thead class="text-xs uppercase bg-gray-800/20 text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                {{__('Command name')}}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{__('Description')}}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{__('Arguments')}}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{('Permission level')}}
                            </th>
                            {{--                            <th scope="col" class="px-6 py-3">--}}
                            {{--                                <span class="sr-only">Edit</span>--}}
                            {{--                            </th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b bg-gray-800 border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium whitespace-nowrap text-white">
                                !join
                            </th>
                            <td class="px-6 py-4">
                                Has the bot join your channel. ( must be run in the bot's chat )
                            </td>
                            <td class="px-6 py-4">
                                None
                            </td>
                            <td class="px-6 py-4">
                                None
                            </td>
                            {{--                            <td class="px-6 py-4 text-right">--}}
                            {{--                                <a href="#"--}}
                            {{--                                   class="font-medium text-blue-500 hover:underline">Edit</a>--}}
                            {{--                            </td>--}}
                        </tr>
                        <tr class="border-b bg-gray-800 border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium whitespace-nowrap text-white">
                                !leave
                            </th>
                            <td class="px-6 py-4">
                                Has the bot leave your channel. ( must be run in the bot's chat )
                            </td>
                            <td class="px-6 py-4">
                                None
                            </td>
                            <td class="px-6 py-4">
                                None
                            </td>
                            {{--                            <td class="px-6 py-4 text-right">--}}
                            {{--                                <a href="#"--}}
                            {{--                                   class="font-medium text-blue-500 hover:underline">Edit</a>--}}
                            {{--                            </td>--}}
                        </tr>
                        <tr class="border-b bg-gray-800 border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium whitespace-nowrap text-white">
                                !blacklist
                            </th>
                            <td class="px-6 py-4">
                                Blacklists a user from using {{config('app.name')}} in your channel
                            </td>
                            <td class="px-6 py-4">
                                Username
                            </td>
                            <td class="px-6 py-4">
                                Moderator OR Broadcaster
                            </td>
                            {{--                            <td class="px-6 py-4 text-right">--}}
                            {{--                                <a href="#"--}}
                            {{--                                   class="font-medium text-blue-500 hover:underline">Edit</a>--}}
                            {{--                            </td>--}}
                        </tr>
                        <tr class="bg-gray-800">
                            <th scope="row"
                                class="px-6 py-4 font-medium whitespace-nowrap text-white">
                                !unblacklist
                            </th>
                            <td class="px-6 py-4">
                                Un-blacklists a user from using {{config('app.name')}} in your channel
                            </td>
                            <td class="px-6 py-4">
                                Username
                            </td>
                            <td class="px-6 py-4">
                                Moderator OR Broadcaster
                            </td>
                            {{--                            <td class="px-6 py-4 text-right">--}}
                            {{--                                <a href="#"--}}
                            {{--                                   class="font-medium text-blue-500 hover:underline">Edit</a>--}}
                            {{--                            </td>--}}
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <!-- Modal footer -->
            {{--            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">--}}
            {{--                <button data-modal-hide="default-modal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I accept</button>--}}
            {{--                <button data-modal-hide="default-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Decline</button>--}}
            {{--            </div>--}}
        </div>
    </div>
</div>
{{-- modal divider --}}
<!-- About modal -->
<div id="about-modal" tabindex="-1" aria-hidden="true"
     class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-7xl max-h-full">
        <!-- Modal content -->
        <div class="relative rounded-lg shadow bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-600">
                <h3 class="text-xl font-semibold text-white">
                    {{__('About ' . config('app.name'))}}
                </h3>
                <button type="button"
                        class="text-gray-400 bg-transparent rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center hover:bg-gray-600 hover:text-white"
                        data-modal-hide="about-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4 text-white">
                <p>Thanks for checking out {{ config('app.name') }}! <br>
                    {{config(('app.name'))}} is a experiment created similar to Omegle, allowing you to chat in random streams that have opted in. </p>
                <p>{{config('app.name')}} is open source! Check it out at <a class="hover:underline"
                                                                             href="https://github.com/randomtwitchchat">https://github.com/randomtwitchchat</a></p>
                <p>If you want to donate to help fund {{ config('app.name') }}, please visit <a class="hover:underline"
                                                                                                href="https://superdev.one/donate">https://superdev.one/donate</a>
                </p>
                <p>Made with love by <a class="hover:underline"
                                        href="https://github.com/supernova3339">Supernova3339</a> ❤️</p>
                <br/>
            </div>
            <!-- Modal footer -->
            {{--            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">--}}
            {{--                <button data-modal-hide="default-modal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I accept</button>--}}
            {{--                <button data-modal-hide="default-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Decline</button>--}}
            {{--            </div>--}}
        </div>
    </div>
</div>
{{-- modal  divider --}}
@auth
    <!-- Manage Blacklist modal -->
    <div id="blacklist-modal" tabindex="-1" aria-hidden="true"
         class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-7xl max-h-full">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-600">
                    <h3 class="text-xl font-semibold text-white">
                        {{__('Your ' . config('app.name') . ' Blacklist')}}
                    </h3>
                    <button type="button"
                            class="text-gray-400 bg-transparent rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center hover:bg-gray-600 hover:text-white"
                            data-modal-hide="blacklist-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4 text-white">
                    <livewire:streamers.blacklist-table/>
                </div>
                <!-- Modal footer -->
                {{--            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">--}}
                {{--                <button data-modal-hide="default-modal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I accept</button>--}}
                {{--                <button data-modal-hide="default-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Decline</button>--}}
                {{--            </div>--}}
            </div>
        </div>
    </div>
@endauth


<script>
    // Function to handle sending messages
    function sendMessage() {
        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value.trim();
        if (message !== '') {
            // clear message
            messageInput.value = '';
        }
    }

    // Submit message on form submit
    const messageForm = document.getElementById('messageForm');
    messageForm.addEventListener('submit', function (event) {
        event.preventDefault();
        sendMessage();
    });

    const refreshInterval = 5 * 60 * 1000;
    let countdownTime = 5 * 60;

    function updateCountdown() {
        const minutes = Math.floor(countdownTime / 60);
        const seconds = countdownTime % 60;
        document.getElementById('time').textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        countdownTime--;
        if (countdownTime < 0) {
            clearInterval(countdownInterval);
            location.reload();
        }
    }

    const countdownInterval = setInterval(updateCountdown, 1000);

    setTimeout(() => {
        location.reload();
    }, refreshInterval);
</script>
</body>

</html>
