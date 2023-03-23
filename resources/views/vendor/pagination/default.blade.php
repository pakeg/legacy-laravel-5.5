@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        @if (Route::is('toplist-Picker') || Route::is('cc-livescore'))
                            <li><a href="{{ $url . '#toplist'}}">{{ $page }}</a></li>
                        @elseif (Request::query('sortBy') == 'start')
                            <li><a href="{{ $url . '&sortBy=start&orderBy=desc'}}">{{ $page }}</a></li>
                        @elseif ( (Request::query('sortBy') == 'cost') && Request::query('orderBy') == 'desc' )
                            <li><a href="{{ $url . '&sortBy=cost&orderBy=desc'}}">{{ $page }}</a></li>
                        @elseif ( (Request::query('sortBy') == 'cost') && Request::query('orderBy') == 'asc')
                            <li><a href="{{ $url . '&sortBy=cost&orderBy=asc'}}">{{ $page }}</a></li>
                        @elseif (Request::query('sortBy') == 'top')
                            <li><a href="{{ $url . '&sortBy=top&orderBy=desc'}}">{{ $page }}</a></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>    
                        @endif
                    @endif
                @endforeach
            @endif
        @endforeach

    </ul>
@endif
