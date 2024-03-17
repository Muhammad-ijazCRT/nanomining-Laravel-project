@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="">
        <div class="dashboard-table">
            @include($activeTemplate . 'partials.transaction_table', ['transactions' => $transactions])
        </div>
    </div>
@endsection
