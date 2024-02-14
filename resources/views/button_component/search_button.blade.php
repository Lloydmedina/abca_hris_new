<style>
@media (max-width: 420px) {
    .btn-sm-block {
        display: flex;
        width: 100%;
    }
}
</style>

@php
    $margin_top = (isset($margin_top) && $margin_top) ? $margin_top : '8.5';

    $button_search_id = (isset($button_search_id)) ? "id=$button_search_id" : '';
@endphp

<button type="submit" class="btn btn-dark float-right btn-sm-block" {{ $button_search_id }} style="margin-top: {{$margin_top}}%; border-radius: 6px">
    <i class="fa-solid fa-magnifying-glass m-1"></i> <span class="m-1">Search</span>
</button>