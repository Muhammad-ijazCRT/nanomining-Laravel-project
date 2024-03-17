@extends($activeTemplate . 'layouts.master')
@section('content')
    @if ($general->kv && $user->kv != Status::KYC_VERIFIED)
        @php
            $kycInstruction = getContent('kyc_instruction.content', true);
        @endphp
        <div class="row mb-5">
            @if ($user->kv == Status::KYC_UNVERIFIED)
                <div class="col-12">
                    <div class="alert alert-info mb-0" role="alert">
                        <h5 class="alert-heading m-0">@lang('KYC Verification Required')</h5>
                        <hr>
                        <p class="mb-0"> {{ __($kycInstruction->data_values->verification_instruction) }} <a href="{{ route('user.kyc.form') }}">@lang('Click Here to Verify')</a></p>
                    </div>
                </div>
            @elseif($user->kv == Status::KYC_PENDING)
                <div class="col-12">
                    <div class="alert alert-warning mb-0" role="alert">
                        <h5 class="alert-heading m-0">@lang('KYC Verification pending')</h5>
                        <hr>
                        <p class="mb-0"> {{ __($kycInstruction->data_values->pending_instruction) }} <a href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a></p>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <div class="row gy-4 justify-content-center">
        <div class="col-lg-4 col-sm-6 col-xsm-6">
            <div class="dashboard-widget flex-align">
                <span class="dashboard-widget__icon flex-center before-shadow"><span class="icon-Money"></span></span>
                <div class="dashboard-widget__content">
                    <span class="dashboard-widget__text">@lang('Balance')</span>
                    <h4 class="dashboard-widget__title">{{ showAmount(auth()->user()->balance) }} {{ __($general->cur_text) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-xsm-6">
            <div class="dashboard-widget flex-align">
                <span class="dashboard-widget__icon flex-center before-shadow"><span class="icon-Wallet_light"></span></span>
                <div class="dashboard-widget__content">
                    <span class="dashboard-widget__text">@lang('Referral Bonus')</span>
                    <h4 class="dashboard-widget__title">{{ showAmount($referralBonus) }} {{ __($general->cur_text) }}</h4>
                </div>
            </div>
        </div>
        @foreach ($miners as $item)
            <div class="col-lg-4 col-sm-6 col-xsm-6">
                <div class="dashboard-widget flex-align">
                    <span class="dashboard-widget__icon flex-center before-shadow">
                        <img alt="@lang('Image')" src="{{ getImage(getFilePath('miner') . '/' . $item->coin_image, getFileSize('miner')) }}">
                    </span>
                    <div class="dashboard-widget__content">
                        <span class="dashboard-widget__text">{{ strtoupper($item->coin_code) }} @lang('Wallet')</span>
                        <h4 class="dashboard-widget__title">{{ showAmount($item->userCoinBalances->balance, 8, exceptZeros: true) }} {{ strtoupper($item->coin_code) }}</h4>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="dashboard__content pt-80">
        <h5>@lang('Latest Transactions')</h5>
        @include($activeTemplate . 'partials.transaction_table', ['transactions' => $transactions])
    </div>
@endsection
