
@if ($paginator->lastPage() > 1)
    <div class="pagination">
        <div class="align-items-center" style="width: 50%;margin: 0 auto;">
            <a href="{{ $paginator->url(1) }}" class="prevposts-link"><i class="fa fa-caret-left"></i></a>
            @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                <a class="{{ ($paginator->currentPage() == $i) ? ' current-page' : '' }}" href="{{ $paginator->url($i) }}">{{ $i }}</a>
            @endfor
            <a {{ $paginator->url($paginator->currentPage()+1) }} class="nextposts-link"><i class="fa fa-caret-right"></i></a>
        </div>
    </div>
@endif
