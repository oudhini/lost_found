@if ($paginator->hasPages())
    <ul class="pagination justify-content-center mb-5">
        {{-- Lien vers la première page --}}
        @if ($paginator->onFirstPage())
            <li class="disabled page-item"><span class="page-link">&laquo;</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">&laquo;</a></li>
        @endif

        {{-- Lien vers la page précédente --}}
        @if ($paginator->onFirstPage())
            <li class="disabled page-item"><span class="page-link">&lsaquo;</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">&lsaquo;</a></li>
        @endif

        {{-- Liens vers les pages --}}
        @foreach ($elements as $element)
            {{-- "Trois points" --}}
            @if (is_string($element))
                <li class="disabled page-item"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Liens vers les pages --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active page-item"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Lien vers la page suivante --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">&rsaquo;</a></li>
        @else
            <li class="disabled page-item"><span class="page-link">&rsaquo;</span></li>
        @endif

        {{-- Lien vers la dernière page --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">&raquo;</a></li>
        @else
            <li class="disabled page-item"><span class="page-link">&raquo;</span></li>
        @endif
    </ul>
@endif
