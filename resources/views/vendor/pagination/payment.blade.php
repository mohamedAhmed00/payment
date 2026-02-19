@if ($paginator->hasPages())
    <div class="fixed-table-pagination">
        <div class="float-right pagination">
            <ul class="pagination">
                @if ($paginator->onFirstPage())
                    <li class="disabled page-item page-pre"><a class="page-link" aria-label="@lang('pagination.previous')" href="javascript:void(0)">›</a></li>
                @else
                    <li class="page-item page-pre">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                    </li>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true">
                            <a class="page-link" aria-label="to page 1" href="javascript:void(0)">{{ $element }}</a>
                        </li>
                    @endif
                        @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-disabled="true">
                                    <a class="page-link" aria-label="{{ $page }}" href="javascript:void(0)">{{ $page }}</a>
                                </li>
                                @else
                                <li class="page-item"><a class="page-link"  href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
                @if ($paginator->hasMorePages())
                    <li class="page-item page-next"><a class="page-link" aria-label="@lang('pagination.next')" href="{{ $paginator->nextPageUrl() }}">›</a></li>
                @else
                    <li class="disabled"><a class="page-link" aria-label="@lang('pagination.next')" href="javascript:void(0)">›</a></li>
                @endif
            </ul>
        </div>
    </div>
@endif

