@php
    $contactCaption = getContent('contact_us.content', true);
    $pages = App\Models\Page::where('is_default', Status::NO)
        ->where('tempname', $activeTemplate)
        ->get();
    $socials = getContent('social_icon.element');
@endphp

<header class="header-section">
    <div class="header">
        <div class="header-bottom-area">
            <div class="container">
                <div class="header-menu-content">
                    <nav class="navbar navbar-expand-lg justify-content-between p-0">
                        <a class="site-logo site-title" href="{{ route('home') }}"><img src="{{ siteLogo() }}" alt="@lang('site-logo')"></a>

                        <button class="navbar-toggler header-button ml-auto shadow-none" data-bs-target="#navbarSupportedContent" data-bs-toggle="collapse" type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fas fa-bars"></span>
                        </button>
                        <div class="navbar-collapse justify-content-lg-center collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav main-menu ml-auto mr-auto">
                                <li><a class="active" href="{{ route('home') }}">@lang('Home')</a></li>

                                @if (!request()->routeIs('user.*') && (!auth()->user() || (!request()->routeIs('ticket.index') && !request()->routeIs('ticket.open') && !request()->routeIs('ticket.view'))))
                                    @if ($pages->count())
                                        @foreach ($pages as $item)
                                            <li><a href="{{ route('pages', ['slug' => $item->slug]) }}">{{ __($item->name) }}</a></li>
                                        @endforeach
                                    @endif
                                    <li><a href="{{ route('plans') }}">@lang('Mining Plans')</a></li>
                                    <li><a href="{{ route('blog') }}">@lang('Blog')</a></li>
                                    <li><a href="{{ route('contact') }}">@lang('Contact')</a></li>
                                @else
                                    <li class="menu_has_children"><a href="#0">@lang('Withdraw')</a>
                                        <ul class="sub-menu">
                                            <li><a href="{{ route('user.withdraw') }}">@lang('Withdraw Now')</a></li>
                                            <li><a href="{{ route('user.withdraw.history') }}">@lang('My Withdrawals')</a></li>
                                        </ul>
                                    </li>

                                    <li class="menu_has_children"><a href="#0">@lang('Mining')</a>
                                        <ul class="sub-menu">
                                            <li><a href="{{ route('user.plans') }}">@lang('Start Mining')</a></li>
                                            <li><a href="{{ route('user.plans.purchased') }}">@lang('Mining Tracks')</a></li>
                                        </ul>
                                    </li>

                                    @if ($general->referral_system)
                                        <li class="menu_has_children"><a href="#0">@lang('Referral')</a>
                                            <ul class="sub-menu">
                                                <li><a href="{{ route('user.referral') }}">@lang('My Referral')</a></li>
                                                <li><a href="{{ route('user.referral.log') }}">@lang('Referral Bonus Logs')</a></li>
                                            </ul>
                                        </li>
                                    @endif

                                    <li class="menu_has_children"><a href="#0">@lang('Support Ticket')</a>
                                        <ul class="sub-menu">
                                            <li><a href="{{ route('ticket.index') }}">@lang('All Tickets')</a></li>
                                            <li><a href="{{ route('ticket.open') }}">@lang('Open Ticket')</a></li>
                                        </ul>
                                    </li>

                                    <li class="menu_has_children"><a href="#0">@lang('My Account')</a>
                                        <ul class="sub-menu">
                                            <li><a href="{{ route('user.profile.setting') }}">@lang('Profile Setting')</a></li>
                                            <li><a href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
                                            <li><a href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
                                            <li><a href="{{ route('user.wallets') }}">@lang('Wallets')</a></li>
                                            <li><a href="{{ route('user.payment.history') }}">@lang('Payments Log')</a></li>
                                            <li><a href="{{ route('user.transactions') }}">@lang('Transactions')</a></li>
                                            <li><a href="{{ route('user.logout') }}">@lang('Logout')</a></li>
                                        </ul>
                                    </li>
                                @endif

                                <li class="d-flex justify-content-between d-lg-none d-block flex-wrap">
                                    @if ($general->multi_language)
                                        @php
                                            $language = App\Models\Language::all();
                                        @endphp
                                        <div class="select-language d-lg-none d-block">
                                            <select class="select-bar nic-select langSel">
                                                @foreach ($language as $lang)
                                                    <option value="{{ $lang->code }}">@lang($lang->name)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    @auth
                                        <div class="dashboard-btn d-lg-none d-block">
                                            <a class="cmn-btn" href="{{ route('user.home') }}">@lang('Dashboard')</a>
                                            <a class="cmn-btn-danger" href="{{ route('user.logout') }}">@lang('Logout')</a>
                                        </div>
                                    @else
                                        <div class="dashboard-btn d-lg-none d-block">
                                            <a class="cmn-btn" href="{{ route('user.register') }}">@lang('Register')</a>
                                            <a class="cmn-btn" href="{{ route('user.login') }}">@lang('Login')</a>
                                        </div>
                                    @endauth
                                </li>

                            </ul>
                        </div>
                        @if ($general->multi_language)
                            @php
                                $language = App\Models\Language::all();
                            @endphp
                            <div class="select-language d-lg-block d-none">
                                <select class="select-bar nic-select langSel">
                                    @foreach ($language as $lang)
                                        <option value="{{ $lang->code }}">@lang($lang->name)</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="header-right-info d-lg-block d-none ms-lg-2">
                            <div class="header-action">
                                @guest
                                    <a class="cmn-btn" href="{{ route('user.register') }}">@lang('Register')</a>
                                    <a class="cmn-btn-active" href="{{ route('user.login') }}">@lang('Login')</a>
                                @else
                                    <a class="cmn-btn-active" href="{{ route('user.home') }}">@lang('Dashboard')</a>
                                @endguest
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
