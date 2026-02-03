<table class="custom-table card-log-search-table">
    <thead>
        <tr>
            <th>{{ __("TRX ID") }}</th>
            <th>{{ __("Type") }}</th>
            <th>{{ __("User Name") }}</th>
            <th>{{ __("Amount") }}</th>
            <th>{{ __("Status") }}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($transactions as $item)
            <tr>
                <td><span>{{ $item->trx_id ?? '' }}</span></td>
                <td><span>{{ $item->type ?? '' }}</span></td>
                <td>{{ $item->user->fullname }}</td>
                <td>{{ get_amount($item->request_amount ,$item->request_currency,get_wallet_precision())  }}</td>
                <td><span class="{{ $item->stringStatus->class }}">{{ __($item->stringStatus->value) }}</span></td>
                <td>
                    <a href="{{ setRoute('admin.virtual.card.log.details',$item->id) }}" class="btn btn--base btn--primary"><i class="las la-info-circle"></i></a>
                </td>
            </tr>
        @empty
            @include('admin.components.alerts.empty',['colspan' => 5])
        @endforelse

    </tbody>
</table>
