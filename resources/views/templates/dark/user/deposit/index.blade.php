@extends($activeTemplate . 'layouts.master')
@section('content')
    <table class="table--responsive--lg table">
        <thead>
            <tr>
                <th scope="col">@lang('No')</th>
                <th scope="col">@lang('Currency')</th>
                <th scope="col">@lang('Amount')</th>
                <th scope="col">@lang('Rate')</th>
                <th scope="col">@lang('Balance')</th>
                <th scope="col">@lang('Status')</th>
                <th scope="col">@lang('Date')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($deposits as $deposit)
                <tr class="mining-server-row">
                    <td>
                        {{ strtoupper($loop->iteration) }}
                    </td>
                    <td>
                        {{ $deposit->method_currency }}
                    </td>
                    <td>
                        {{ $deposit->amount }}
                    </td>
                    <td>
                        {{ $deposit->rate }}
                    </td>
                    <td>
                        {{ $deposit->final_amo }}
                    </td>
                    <td class="budget">
                        @if ($deposit->status == 1)
                            <span class="badge badge--success">Success</span>
                        @elseif($deposit->status == 2)
                            <span class="badge badge--warning">Pending</span>
                        @elseif($deposit->status == 3)
                            <span class="badge badge--cancel">Cancel</span>
                        @elseif($deposit->status == 0)
                            <span class="badge badge--warning">Not Processed</span>
                        @else
                            <span class="badge badge--unknown">Unknown Status</span>
                        @endif
                    </td>

                    <td>
                        {{ $deposit->created_at->format('Y-m-d H:i:s') }}
                    </td>


                </tr>
            @empty
                <tr>
                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{-- @if ($paginate) --}}
    {{ paginateLinks($deposits) }}
    {{-- @endif --}}
@endsection
