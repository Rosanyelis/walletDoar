@extends('admin.layouts.master')

@push('css')

@endpush

@section('page-title')
    @include('admin.components.page-title',['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("admin.dashboard"),
        ]
    ], 'active' => __($page_title)])
@endsection

@section('content')
    <div class="table-area">
        <div class="table-wrapper">
            <div class="table-header">
                <h5 class="title">{{ __($page_title) }}</h5>
                <div class="table-btn-area">
                    @include('admin.components.search-input',[
                        'name'  => 'virtual_card_log_search',
                    ])
                </div>
            </div>
            <div class="table-responsive">
                @include('admin.components.data-table.virtual-card-logs-table',[
                    'data'  => $transactions
                ])

            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    itemSearch($("input[name=virtual_card_log_search]"),$(".card-log-search-table"),"{{ setRoute('admin.virtual.card.log.search') }}",1);
</script>
@endpush
