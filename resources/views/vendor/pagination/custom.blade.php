@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div>
            @if ($paginator->onFirstPage())
                <span class="cursor-not-allowed bg-gray-300 text-gray-500 px-4 py-2 rounded">Previous</span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="bg-white text-black border border-gray-300 px-4 py-2 rounded">Previous</button>
            @endif
        </div>
        <div>
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="bg-gray-300 text-gray-500 px-4 py-2 rounded">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="bg-black text-white px-4 py-2 rounded">{{ $page }}</span>
                        @else
                            <button wire:click="gotoPage({{ $page }})" class="bg-white text-black border border-gray-300 px-4 py-2 rounded">{{ $page }}</button>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>
        <div>
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="bg-white text-black border border-gray-300 px-4 py-2 rounded">Next</button>
            @else
                <span class="cursor-not-allowed bg-gray-300 text-gray-500 px-4 py-2 rounded">Next</span>
            @endif
        </div>
    </nav>
@endif
