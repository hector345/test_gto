@if ($paginator->hasPages())
    <nav class="d-flex justify-items-center justify-content-between">

        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
            <div>
                <p class="small text-muted">
                    Mostrar
                    <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                    a
                    <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                    de
                    <span class="fw-semibold">{{ $paginator->total() }}</span>
                    resultados
                </p>
            </div>

            <div>
                <ul class=" pagination pagination-round justify-content-end">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true">
                            <a class="page-link"><i class="ti ti-chevron-left ti-xs"></i></a>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}"
                                rel="prev"><i class="ti ti-chevron-left ti-xs"></i></a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="page-item disabled" aria-disabled="true"><a
                                    class="page-link">{{ $element }}</a></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active" aria-current="page"><a
                                            class="page-link">{{ $page }}</a></li>
                                    {{-- se establece el rango de 3 antes de $paginator->currentPage() a 3 despues de $paginator->currentPage()  --}}
                                @elseif($page >= $paginator->currentPage() - 3 && $page <= $paginator->currentPage() + 3)
                                    <li class="page-item"><a class="page-link"
                                            href="{{ $url }}">{{ $page }}</a></li>
                                @else
                                    <li class="page-item d-none d-md-block"><a class="page-link"
                                            href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item next">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"
                                aria-label="@lang('pagination.next')"><i class="ti ti-chevron-right ti-xs"></i></a>
                        </li>
                    @else
                        <li class="page-item disabled next" aria-disabled="true" aria-label="@lang('pagination.next')">
                            <a class="page-link" aria-hidden="true"><i class="ti ti-chevron-right ti-xs"></i></a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
