@if($paginator->lastPage() > 1)
    <?php
        $half_total_links = 4;
    ?>
    <div class="nav col-auto ml-auto pagination">
        <a href="{{ $paginator->url($paginator->currentPage() - 1) }}" class="btn pagination-btn prev {{ $paginator->currentPage() == '1' ? 'disabled' : '' }}">
            <img src="/images/chevron-icon-left.svg" alt="Chevron icon left">
        </a>
        <?php

        ?>
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <?php
                $from = $paginator->currentPage() - $half_total_links;
                $to = $paginator->currentPage() + $half_total_links;
                if ($paginator->currentPage() < $half_total_links) {
                    $to += $half_total_links - $paginator->currentPage();
                }
                if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
                    $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
                }
            ?>

            @if($from < $i && $i < $to)
                <a href="{{ $paginator->url($i) }}" class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">{{ $i }}</a>
            @endif
        @endfor
        <a href="{{ $paginator->url($paginator->currentPage() + 1) }}" class="btn pagination-btn prev {{ $paginator->currentPage() == $paginator->lastPage() ? 'disabled' : '' }}">
            <img src="/images/chevron-icon-right.svg" alt="Chevron icon right">
        </a>
    </div>
@endif
