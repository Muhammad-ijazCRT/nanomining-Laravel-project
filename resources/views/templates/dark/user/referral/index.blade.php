@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="card custom--card">
        <div class="card-body">
            <div class="form-group mb-4">

                {{-- My Commission --}}
                <label class="d-flex justify-content-between">
                    <span>@lang('My Commission')</span>
                </label>
                <form action="{{ route('user.withdraw.referral.commission') }}" method="post">
                    @csrf
                    <div class="input-group">

                        <div class="input-group-text append-icon--btn">
                            <div class="input-group-text">USDT</div>
                        </div>

                        <input type="text" value="{{ $user->referral_commission ?? '0' }}"
                            class="form-control form--control" readonly="">
                        <div class="input-group-text append-icon--btn">

                            <input type="hidden" name="amount" value="{{ $user->referral_commission ?? '0' }}">
                            <input type="hidden" name="referral_commission" value="1">

                            <button type="submit" class="input-group-text bg--info border--light text--white withdrawBtn">
                                Withdrawal
                            </button>


                            {{-- <button type="button" data-commissionbalance="0.0000" data-commissionwallet=""
                                data-withdrawl_fee="10.0000" data-toggle="modal" data-target="#exampleModal"
                                class="input-group-text bg--info border--light text--white withdrawBtn"> Withdrawal</button> --}}
                        </div>

                    </div>
                </form>

                {{-- USDT Wallet --}}
                <label class="d-flex justify-content-between mt-3">
                    <span>@lang('USDT Wallet Address (Require TRC20)')</span>
                </label>
                <form action="{{ route('user.user-trc20-address') }}" method="post">
                    @csrf
                    <div class="input-group">

                        <input type="text" name="trc20_address" value="{{ auth()->user()->trc20_address ?? '' }}"
                            placeholder="TRC20 USDT Wallet Address" class="form-control form--control">



                        <div class="input-group-text append-icon--btn">
                            @if (auth()->user()->trc20_address)
                                <button type="submit"
                                    class="input-group-text bg--info border--light text--white withdrawBtn">
                                    Update Wallet Address
                                </button>
                            @else
                                <button type="submit"
                                    class="input-group-text bg--info border--light text--white withdrawBtn">
                                    Save Wallet Address
                                </button>
                            @endif

                        </div>
                    </div>
                </form>

                {{-- Referal Link --}}
                <label class="d-flex justify-content-between mt-3">
                    <span>@lang('Referral Link')</span>
                    @if (auth()->user()->referrer)
                        <span class="text--info">@lang('You are referred by') {{ auth()->user()->referrer->fullname }}</span>
                    @endif
                </label>
                <div class="input-group">
                    <input class="form-control form--control referralURL" name="text" type="text"
                        value="{{ route('home') }}?ref={{ auth()->user()->username }}" readonly="">
                    <button class="input-group-text copytext copyBoard" id="copyBoard"> <i class="fa fa-copy"></i> </button>
                </div>

                <span class="mt-3">Earn USDT by referring new users, join our Affiliate Program (Partner Program), and
                    earn a lifetime 10% commission!</span>
                <span>1. Put your affiliate link on your blog or any website you may have.</span>
                <span>2.New users register with us. You will get 10% of the top-up amount. For example, You recommend user
                    A, you can get 7% of the referral plan, A recommends B to buy plan, you can get 2% B recommends C to buy
                    plan, you can get 1%</span>
                <span>3. Mention Easy Mining. in your newsletter and use your affiliate link.</span>
                <span>4. Invite your friends and earn USDT benefits when they complete their purchases. Keep an eye on how
                    much you earn each week, get paid in USDT, and each of your affiliates will generate lifetime
                    commissions.</span>
                <span>5. We allow you to earn commissions by referring friends without purchasing any mining plans.</span>

            </div>

            @if ($user->allReferrals->count() > 0 && $maxLevel > 0)
                <label>@lang('My Referrals')</label>
                <div class="treeview-container">
                    <ul class="treeview">
                        <li class="items-expanded"> {{ $user->fullname }} ( {{ $user->username }} )
                            @include($activeTemplate . 'partials.under_tree', [
                                'user' => $user,
                                'layer' => 0,
                                'isFirst' => true,
                            ])
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
@endsection
@push('style')
    <link type="text/css" href="{{ asset('assets/global/css/jquery.treeView.css') }}" rel="stylesheet">
@endpush
@push('script')
    <script src="{{ asset('assets/global/js/jquery.treeView.js') }}"></script>
    <script>
        (function($) {
            "use strict";

            $('.treeview').treeView();
            $('.copyBoard').click(function() {
                var copyText = document.getElementsByClassName("referralURL");
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);

                /*For mobile devices*/
                document.execCommand("copy");
                notify('success', "Copied: " + copyText.value);
            });
        })(jQuery);
    </script>
@endpush
