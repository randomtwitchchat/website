<div class="bg-gray-800">
    @if ($paginator->hasPages())
        <nav class="flex items-center justify-between border-t px-4 sm:px-0 border-gray-600">
            <div class="-mt-px flex w-0 flex-1">
                @if (!$paginator->onFirstPage())
                    <button wire:click="previousPage('{{ $paginator->getPageName() }}')" class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium hover:border-gray-500 hover:text-gray-300 text-gray-300">
                        <svg class="mr-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a.75.75 0 01-.75.75H4.66l2.1 1.95a.75.75 0 11-1.02 1.1l-3.5-3.25a.75.75 0 010-1.1l3.5-3.25a.75.75 0 111.02 1.1l-2.1 1.95h12.59A.75.75 0 0118 10z" clip-rule="evenodd" />
                        </svg>
                        Previous
                    </button>
                @endif
            </div>

            <div class="hidden md:-mt-px md:flex">
                @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center border-t-2 border-indigo-500 px-4 pt-4 text-sm font-medium text-purple-400" aria-current="page">{{ $page }}</span>
                    @else
                        <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium hover:border-gray-500 hover:text-gray-300 text-gray-300">
                            {{ $page }}
                        </button>
                    @endif
                @endforeach
            </div>

            <div class="-mt-px flex w-0 flex-1 justify-end">
                @if ($paginator->hasMorePages())
                    <button wire:click="nextPage('{{ $paginator->getPageName() }}')" class="inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium hover:border-gray-500 hover:text-gray-300 text-gray-300">
                        Next
                        <svg class="ml-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M2 10a.75.75 0 01.75-.75h12.59l-2.1-1.95a.75.75 0 111.02-1.1l3.5 3.25a.75.75 0 010 1.1l-3.5 3.25a.75.75 0 11-1.02-1.1l2.1-1.95H2.75A.75.75 0 012 10z" clip-rule="evenodd" />
                        </svg>
                    </button>
                @endif
            </div>
        </nav>
    @endif
</div>
