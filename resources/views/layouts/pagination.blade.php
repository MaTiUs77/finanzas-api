<div>Viendo {{ $api->from }} a {{ $api->to }} de  {{ $api->total }} resultados</div>
<nav aria-label="Page navigation">
    <ul class="pagination">
        <li>
            <a href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo; Anterior</span>
            </a>
        </li>
        @for ($i = 1; $i < $api->last_page; $i++)
            @if($i<5)
                <li class="{{ $api->current_page==$i ? 'active' : '' }}">
                    <a href="?page={{ $i }}">{{ $i }}</a>
                </li>
            @endif
            @if($i==5)
                <li><a href="javascript:;">...</a></li>
            @endif
            @if(($api->last_page - $i) < 5)
                <li>
                    <a href="?page={{ $i }}">{{ $i }}</a>
                </li>
            @endif
        @endfor
        <li>
            <a href="#" aria-label="Next">
                <span aria-hidden="true">Siguiente &raquo;</span>
            </a>
        </li>
    </ul>
</nav>
