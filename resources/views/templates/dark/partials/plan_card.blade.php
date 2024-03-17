@php
    if (!@$class) {
        $class = 'col-xl-3 col-md-6 col-sm-8';
    }
@endphp
  <div class="row mb-3">
        <div class="container">
            <div class="row">

                <div class="col-12">
                    <div class="alert alert-info mb-0" role="alert">
                        <h5 class="alert-heading m-0">Your selected mining contract Is activated automatically once your payment Is confirmed.</h5>
                        <hr>
                        <p class="mb-0"> Mining income is released once a day. You can withdraw the output at any time (without waiting for the end of the contract) There is no limit to the number of withdrawals                                <br>   <br>
                            You can have the fastest bitcoin miner in 5 minutes:  <br>
                            For example if you activate ECONOMY miner with 728 USD, after 30 days you can withdraw 0.00336 BTC x 30 = 0.1007 BTC<br>
                            1- Choose one of the below miners<br>
                            2- Click on "Buy Now" button and pay the miner price<br>
                            3- Your miner is launched and adds bitcoin to your balance every second (until 1 year)<br>
                            4- Your bitcoin increase every minute and you can withdraw it or buy a new bigger miner<br><br>

                            USDT. The profit of USDT Plans comes from intelligent quantitative trading strategies. Daily earnings may fluctuate based on Binance trading depth. The contract period is only one day, so you can withdraw all your funds the next day. There will be no automatic re-investment after the contract expires. If you need to re-invest, you need to manually purchase the plan again.</a>




                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
<div class="plan-tab">
    @if ($miners?->count() > 1)
        <ul class="nav custom--tab nav-pills mb-3" id="pills-tab" role="tablist">
            @foreach ($miners as $item)
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if ($loop->first) active @endif" id="tabName{{ $loop->iteration }}" data-bs-toggle="pill" data-bs-target="#pills-{{ $loop->iteration }}" type="button" role="tab" aria-controls="pills-{{ $loop->iteration }}" aria-selected="@if ($loop->first) true @else false @endif">{{ $item->name }}</button>
                </li>
            @endforeach
        </ul>
    @endif
    <div class="tab-content" id="pills-tabContent">
        @foreach ($miners as $item)
            <div class="tab-pane fade @if ($loop->first) show active @endif" id="pills-{{ $loop->iteration }}" role="tabpanel" aria-labelledby="tabName{{ $loop->iteration }}">
                <div class="row gy-4 justify-content-center">
                    @foreach ($item->activePlans as $plan)
                        <div class="{{ $class }}">
                            <div class="price-item">
                                <div class="price-item__header">
                                    <h5 class="price-item__title">{{ __($plan->title) }}</h5>
                                    <h2 class="price-item__price"> {{ $general->cur_sym }}{{ showAmount($plan->price) }}<span class="price-item__price-month"> / {{ $plan->period . ' ' . $plan->periodUnitText }}</span> </h2>
                                </div>
                                <div class="price-item__content">
                                    <div class="price-item__body">
                                        <ul class="text-list">
                                            <li class="text-list__item">@lang('Return per day:')
                                                {{ showAmount($plan->min_return_per_day) }} {{ strtoupper($item->coin_code) }}
                                                @if ($plan->max_return_per_day)
                                                    - {{ showAmount($plan->max_return_per_day) }} {{ strtoupper($item->coin_code) }}
                                                @endif
                                            </li>
                                            <li class="text-list__item">{{ getAmount($plan->maintenance_cost) }}% @lang('Maintenance Cost Per Day')</li>
                                            @foreach ($plan->features ?? [] as $feature)
                                                <li class="text-list__item">{{ $feature }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="price-item__button">
                                        @guest
                                            <a class="btn--base" href="{{ route('user.login') }}">@lang('Buy Now')</a>
                                        @else
                                            <button class="btn--base buy-plan" data-id="{{ $plan->id }}" data-title="{{ $plan->title }}" data-price="{{ showAmount($plan->price) }}" type="button">@lang('Buy Now')</button>
                                        @endguest
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

@auth
    @include($activeTemplate . 'partials.buy_plan_modal')
@endauth
