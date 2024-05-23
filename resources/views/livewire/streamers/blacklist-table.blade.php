<div wire:poll.visible.15s>
    @php
        $isSearchPerformed = $searchTerm !== null;
    @endphp

    @if($blacklists->isNotEmpty() || $isSearchPerformed)
        <form wire:submit.prevent="submit" class="mb-4">
            <x-text-input wire:model="searchTerm" type="text" class="form-input w-full bg-gray-800" placeholder="Search by username..."/>
            <button type="submit" class="btn btn-primary hidden">Search</button>
        </form>
    @endif

    @if($blacklists->isNotEmpty())
        <div class="relative shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-400">
                <thead class="text-xs uppercase bg-gray-800/20 text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        {{__('User')}}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{('Status')}}
                    </th>
                    <th scope="col" class="px-6 py-3  text-right">
                        {{('Actions')}}
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($blacklists as $blacklist)
                    <tr class="border-b bg-gray-800 border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap text-white">
                            <a class="hover:underline cursor-pointer" href="https://twitch.tv/{{ $blacklist->user->name }}">
                            {{ $blacklist->user->name }}</a>
                        </th>
                        <td class="px-6 py-4">{{ $blacklist->status ? 'Blacklisted' : 'Allowed' }}</td>
                        <td class="px-6 py-4 text-right">
                            <button
                                class="font-medium text-blue-500 hover:underline"
                                wire:click.prevent="toggleBlacklistStatus({{ $blacklist->user->id }})">
                                Change Status
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="mt-4">
            {{ $blacklists->links() }}
        </div>
    @else
        @if($isSearchPerformed)
            <div class="flex flex-col flex-grow justify-end">
                <div class="bg-gray-900/40 pb-4 pt-4 rounded-md mt-auto">
                    <div class="text-center items-center">
                        <div class="bg-gray-800/40 rounded-full p-3 inline-block">
                            <x-lucide-search-x class="mx-auto h-6 w-6 text-gray-400"/>
                        </div>
                        <h3 class="mt-2 text-sm font-semibold text-gray-100">No Results Found</h3>
                        <p class="mt-2 text-sm font-semibold text-gray-500">No results were found for your search query</p>
                    </div>
                </div>
            </div>
        @else
        <div class="flex flex-col flex-grow justify-end">
            <div class="bg-gray-900/40 pb-4 pt-4 rounded-md mt-auto">
                <div class="text-center items-center">
                    <div class="bg-gray-800/40 rounded-full p-3 inline-block">
                        <x-lucide-users-2 class="mx-auto h-6 w-6 text-gray-400"/>
                    </div>
                    <h3 class="mt-2 text-sm font-semibold text-gray-100">No Users</h3>
                    <p class="mt-2 text-sm font-semibold text-gray-500">No users are currently in your blacklist</p>
                </div>
            </div>
        </div>
        @endif
    @endif
</div>
