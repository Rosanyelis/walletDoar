@extends('admin.layouts.master')


@push('css')
    <style>
        .fileholder {
            min-height: 194px !important;
        }

        .fileholder-files-view-wrp.accept-single-file .fileholder-single-file-view,.fileholder-files-view-wrp.fileholder-perview-single .fileholder-single-file-view{
            height: 150px !important;
        }
    </style>
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
    ], 'active' => __("Setup Virtual Card Api")])
@endsection

@section('content')
    <div class="table-area">
        <div class="table-wrapper">
            <div class="table-header">
                <h5 class="title">{{ __("Provider's List") }}</h5>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{ __("Provider") }}</th>
                            {{-- <th>{{ __("Status") }}</th> --}}
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($apis as $api)
                            <tr>
                                <td>
                                    <ul class="user-list">
                                        <li><img src="{{ get_image($api->provider_image,'card-providers-images') }}" alt="image"></li>
                                    </ul>
                                </td>
                                <td>{{ $api->provider_title }}</td>
                                {{-- <td>
                                    @include('admin.components.form.switcher',[
                                        'name'          => 'status',
                                        'data_target'   => $api->id,
                                        'value'         => $api->status,
                                        'options'       => [__('Enable') => 1, __('Disable') => 0],
                                        'onload'        => true,
                                        'permission'    => "admin.payment.gateway.status.update",
                                    ])
                                </td> --}}
                                <td>
                                    @include('admin.components.link.edit-default',[
                                        'href'          => setRoute('admin.virtual.card.edit',$api->provider_slug),
                                        'permission'    => "admin.virtual.card.edit",
                                    ])
                                </td>
                            </tr>
                        @empty
                            @include('admin.components.alerts.empty',['colspan' => 6])
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('script')
    {{-- <script>
        $(document).ready(function(){
            openModalWhenError("automatic-payment-method","#p-gateway-automatic-add");
            switcherAjax("{{ setRoute('admin.payment.gateway.status.update') }}");
        });
    </script> --}}
@endpush
