@php
    $pages = App\Models\Page::where('is_default', Status::NO)
        ->where('tempname', $activeTemplate)
        ->get();
@endphp

<header class="header" id="header">
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
                <button class="navbar-toggler header-button" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span id="hiddenNav"><i class="las la-bars"></i></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('home') }}" href="{{ route('home') }}">@lang('Home')</a>
                    </li>
                    @foreach ($pages as $item)
                        <li class="nav-item">
                            <a class="nav-link {{ menuActive('pages', 1, ['slug', $item->slug]) }}" href="{{ route('pages', ['slug' => $item->slug]) }}">{{ __($item->name) }}</a>
                        </li>
                    @endforeach
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('plans') }}" href="{{ route('plans') }}">@lang('Plans')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('blog') }}" href="{{ route('blog') }}">@lang('Blogs')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('contact') }}" href="{{ route('contact') }}">@lang('Contact')</a>
                    </li>

                    <li class="header-right flex-align">
                        <div class="d-lg-block d-none">
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
                        <div class="account-buttons flex-align">
                            @guest
                                @if ($general->registration)
                                    <a href="{{ route('user.register') }}" class="btn btn-outline--base pill"> <span class="text--gradient">@lang('Register')</span> </a>
                                @endif
                                <a href="{{ route('user.login') }}" class="btn btn--base pill">@lang('Login')</a>
                            @else
                                @if (!request()->routeIs('user*') && !request()->routeIs('ticket*'))
                                    <a href="{{ route('user.home') }}" class="btn btn-outline--base pill"> <span class="text--gradient">@lang('Dashboard')</span> </a>
                                @endif
                                <a href="{{ route('user.logout') }}" class="btn btn--base pill">@lang('Logout')</a>
                            @endguest
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
