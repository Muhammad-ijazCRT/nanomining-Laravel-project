<header class="header dashboard-header" id="header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand logo" href="{{ route('home') }}"><img src="{{ siteLogo() }}" alt="@lang('image')"></a>
            <div class="flex-align">
                <div class="d-lg-none d-block">
                    <div class="language flex-align">
                        @if ($general->multi_language)
                            @php
                                $language = App\Models\Language::all();
                            @endphp
                            <div class="language__icon flex-center"><span class="icon-world-1-1"></span></div>
                            <select class="select langSel">
                                @foreach ($language as $lang)
                                    <option value="{{ $lang->code }}">@lang($lang->name)</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
                <button class="navbar-toggler header-button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span id="hiddenNav"><i class="las la-bars"></i></span>
                </button>
            </div>

            <div class="navbar-collapse collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu align-items-lg-center ms-auto">

                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"> @lang('Mining') <span class="nav-item__icon"><i class="las la-angle-down"></i></span></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link" href="{{ route('user.plans') }}">@lang('Start Mining')</a></li>
                            <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link" href="{{ route('user.plans.purchased') }}">@lang('Mining Tracks')</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"> @lang('Withdraw') <span class="nav-item__icon"><i class="las la-angle-down"></i></span></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link" href="{{ route('user.withdraw') }}">@lang('Withdraw Now')</a></li>
                            <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link" href="{{ route('user.withdraw.history') }}">@lang('My Withdrawals')</a>
                            </li>
                        </ul>
                    </li>
                    @if ($general->referral_system)
                        <li class="nav-item dropdown">
                            <a class="nav-link" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"> @lang('Referral') <span class="nav-item__icon"><i class="las la-angle-down"></i></span></a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link" href="{{ route('user.referral') }}">@lang('My Referral')</a></li>
                                <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link" href="{{ route('user.referral.log') }}">@lang('Referral Bonus Logs')</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"> @lang('Support Ticket') <span class="nav-item__icon"><i class="las la-angle-down"></i></span></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link" href="{{ route('ticket.open') }}">@lang('Open Ticket')</a>
                            <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link" href="{{ route('ticket.index') }}">@lang('All Tickets')</a></li>
                    </li>
                </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">@lang('My Account') <span class="nav-item__icon"><i class="las la-angle-down"></i></span></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link" href="{{ route('user.wallets') }}">@lang('Wallets')</a></li>
                        <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link" href="{{ route('user.payment.history') }}">@lang('Payments Log')</a></li>
                        <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link" href="{{ route('user.transactions') }}">@lang('Transactions')</a></li>
                        <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link" href="{{ route('user.profile.setting') }}">@lang('Profile Setting')</a></li>
                        <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link" href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
                        <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link" href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
                        <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link" href="{{ route('user.logout') }}">@lang('Logout')</a></li>
                    </ul>
                </li>
                <li class="header-right flex-align">
                    <div class="d-lg-block d-none">
                        <div class="language flex-align">
                            @if ($general->multi_language)
                                <div class="language__icon flex-center"><span class="icon-world-1-1"></span></div>
                                <select class="select langSel">
                                    @foreach ($language as $lang)
                                        <option value="{{ $lang->code }}">@lang($lang->name)</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                    <div class="account-buttons flex-align">
                        <a class="btn btn--base pill" href="{{ route('user.home') }}">@lang('Dashboard')</a>
                    </div>
                </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
