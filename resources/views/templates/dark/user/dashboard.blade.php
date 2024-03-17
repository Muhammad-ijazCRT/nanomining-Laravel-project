@extends($activeTemplate . 'layouts.master')
@section('content')


    <style>
        .dash-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 3px 8px;
            background-color: rgb(var(--main));
            font-family: "Exo 2", sans-serif;
            color: #fff !important;
            font-size: 10px;
            font-weight: 700;
            border-radius: 3px;
            background: #055ade;
        }
    </style>


    <style>
        .damagesnone {
            display: none !important;
        }

        .damagesblock {
            display: flex !important;
        }
    </style>



    @if ($general->kv && auth()->user()->kv != Status::KYC_VERIFIED)
        @php
            $kycInstruction = getContent('kyc_instruction.content', true);
        @endphp
        <div class="row mb-3">
            <div class="container">
                <div class="row">
                    @if (auth()->user()->kv == Status::KYC_UNVERIFIED)
                        <div class="col-12">
                            <div class="alert alert-info mb-0" role="alert">
                                <h5 class="alert-heading m-0">@lang('KYC Verification Required')</h5>
                                <hr>
                                <p class="mb-0"> {{ __($kycInstruction->data_values->verification_instruction) }} <a
                                        class="text--base" href="{{ route('user.kyc.form') }}">@lang('Click Here to Verify')</a></p>
                            </div>
                        </div>
                    @elseif(auth()->user()->kv == Status::KYC_PENDING)
                        <div class="col-12">
                            <div class="alert alert-warning mb-0" role="alert">
                                <h5 class="alert-heading m-0">@lang('KYC Verification pending')</h5>
                                <hr>
                                <p class="mb-0"> {{ __($kycInstruction->data_values->pending_instruction) }} <a
                                        class="text--base" href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a></p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    
    <div class="row mb-3">
        <div class="container">
            <div class="row">

                <div class="col-12">
                    <div class="alert alert-info mb-0" role="alert">
                        <h5 class="alert-heading m-0">World-class security</h5>
                        <hr>
                        <p class="mb-0">As a wholly owned subsidiary of Digital Currency Group, we offer clients the
                            opportunity to tap into our ecosystem.</p>
                        <p class="mb-0">Easy Mining has entered into a deep strategic partnership agreement with Coinbase,
                            the largest cryptocurrency exchange in the United States.</p>
                        <p class="mb-0">Easy Mining already supports direct transfers from Coinbase exchange accounts to
                            Easy Mining accounts! If you are also a client of the Coinbase exchange, you can choose Coinbase
                            Payments when making payments. The funds are supervised by Coinbase, a third-party listed
                            company.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- user wallets -->
    <div class="row gy-4 justify-content-center dashboard-card-wrapper">
        @php
            $session = Session()->get('mining_servers');
            $btc = 0;
            $ltc = 0;
            // dd($session);
            if ($session) {
                foreach ($session as $item) {
                    // dd($item['miner_machine_id']);
                    if ($item['miner_currency_id'] == 1) {
                        $btc = 1;
                    }

                    if ($item['miner_currency_id'] == 2) {
                        $ltc = 1;
                    }
                }
            }
        @endphp

        @if ($ActiveMining->isNotEmpty())
        @else
        @endif


        {{-- {{ dd($item) }} --}}
        @foreach ($miners as $item)
            <div class="col-xl-4 col-sm-6">
                <div class="dashboard-card border-bottom-violet">
                    <div class="dashboard-card__thumb-title">
                        <a href="/user/withdraw" class="dash-btn">Withdraw Now</a>
                        <div class="dashboard-card__thumb">
                            <img src="{{ getImage(getFilePath('miner') . '/' . $item->coin_image, getFileSize('miner')) }}"
                                alt="@lang('Image')">
                        </div>
                        <h5 class="dashboard-card__title"> <span>{{ strtoupper($item->coin_code) }}</span>
                            @lang('Wallet')
                        </h5>
                    </div>
                    <div class="dashboard-card__content">
                        <h4 class="dashboard-card__Status">
                            <span id="User{{ $item->id }}Balance" class="userCoinBalances mammu_{{ $item->id }}"
                                planId="{{ $item->id }}" data-plan-id="{{ $item->id }}"
                                data-CoinBalances="{{ $item->userCoinBalances->balance }}">{{ showAmount($item->userCoinBalances->balance, 8, exceptZeros: true) }}
                            </span>
                            {{ strtoupper($item->coin_code) }}
                        </h4>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Referral + Balance start -->
        <div class="col-xl-4 col-sm-6">
            <div class="dashboard-card border-bottom-info border-bottom-violet">
                <div class="dashboard-card__thumb-title">
                    <a href="/user/deposit" class="dash-btn">@lang('Deposit')</a>
                    <div class="dashboard-card__thumb rounded-0 border-0">
                        <i class="las la-money-bill fa-4x"></i>
                    </div>
                    <h5 class="dashboard-card__title"> @lang('Balance')</h5>
                </div>
                <div class="dashboard-card__content">
                    <h4 class="dashboard-card__Status">{{ showAmount(auth()->user()->balance) }}
                        {{ __($general->cur_text) }}</h4>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6">
            <div class="dashboard-card border-bottom-violet">
                <div class="dashboard-card__thumb-title">
                    <a href="/user/my-referral" class="dash-btn">@lang('My Referrals')</a>
                    <div class="dashboard-card__thumb rounded-0 border-0">
                        <i class="las la-wallet fa-4x"></i>
                    </div>
                    <h5 class="dashboard-card__title"> @lang('Referral Bonus')</h5>
                </div>
                <div class="dashboard-card__content">
                    <h4 class="dashboard-card__Status">{{ showAmount($referralBonus) }} {{ __($general->cur_text) }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6">
            <div class="dashboard-card border-bottom-violet">
                <a href="/user/mining-plans" class="dash-btn">@lang('Start Mining')</a>
                <div class="dashboard-card__thumb-title">
                    <div class="dashboard-card__thumb rounded-0 border-0">
                        <i class="las la-hammer fa-4x"></i>
                    </div>
                    <h5 class="dashboard-card__title">@lang('BTC Mining')</h5>
                </div>
                <div class="dashboard-card__content">
                    <h4 class="dashboard-card__Status">@lang('Start your Miner')</h4>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6">
            <div class="dashboard-card border-bottom-violet">

                <div class="dashboard-card__thumb-title">

                </div>
                <div class="dashboard-card__content" style="height: 88px;">
                    <h4 class="dashboard-card__Status">
                        <div style="text-align: center;">
                            <a href="#" class="btn--base" onclick="showpopup()"><i
                                    class="las la-download fa-1x"></i>APP Download</a>
                        </div>
                    </h4>
                </div>
            </div>
        </div>

    </div>
    </div>
    <!-- dashboard-section end -->
    @if (isset($mining_servers))
        <div class="pt-40">
            <h5>@lang('Active Miner')</h5>
            <div class="dashboard-table">
                @include($activeTemplate . 'partials.maining_machine_table', [
                    'mining_servers' => $mining_servers,
                ])
            </div>
        </div>
    @endif
@endsection


<script>
    function showpopup() {
        alert('Sorry! App is Still in Development Mode!')
    }
</script>



<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API = Tawk_API || {},
        Tawk_LoadStart = new Date();
    (function() {
        var s1 = document.createElement("script"),
            s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/659ac1788d261e1b5f507998/1hji87fvs';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
<!--End of Tawk.to Script-->
