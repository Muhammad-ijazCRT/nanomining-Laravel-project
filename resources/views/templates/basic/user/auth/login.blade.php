@php
    $content = getContent('login.content', true);
@endphp
@extends($activeTemplate . 'layouts.app')
@section('panel')
    <section class="register-section bg-overlay-primary bg_img" data-background="{{ getImage('assets/images/frontend/login/' . $content->data_values->image, '1920x1080') }}">
        <div class="container">
            <div class="go-to-home">
                <a class="text-white" href="{{ route('home') }}">
                    <i class="la la-times-circle fa-5x"></i>
                </a>
            </div>
            <div class="row register-area justify-content-center align-items-center">
                <div class="col-lg-5">
                    <div class="register-form-area">
                        <div class="register-logo-area text-center">
                            <a href="{{ route('home') }}"><img src="{{ siteLogo() }}" alt="@lang('logo')"></a>
                        </div>
                        @if (Route::has('user.register'))
                            <div class="account-header text-center">
                                <h2 class="title">{{ __(@$content->data_values->title) }}</h2>
                                <p class="sub-title">@lang('Don\'t Have An Account')? <a href="{{ route('user.register') }}">@lang('Create Now')</a></p>
                            </div>
                        @endif
                        <form class="register-form verify-gcaptcha" method="POST" action="{{ route('user.login') }}">
                            @csrf

                            <div class="form-group">
                                <label class="register-icon"><i class="fas fa-user"></i></label>
                                <input class="form-control" name="username" type="text" value="{{ old('username') }}" placeholder="@lang('Username')" required>
                            </div>

                            <div class="form-group">
                                <label class="register-icon"><i class="fas fa-key"></i></label>
                                <input class="form-control" name="password" type="password" placeholder="@lang('Password')" required autocomplete="new-password">
                            </div>

                            <x-captcha />

                            <div class="form-group d-flex justify-content-between flex-wrap gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" id="rem-me" name="remember" type="checkbox">
                                    <label class="form-check-label mb-0" for="rem-me">@lang('Remember Me')</label>
                                </div>
                                <div>
                                    <a class="text-base" href="{{ route('user.password.request') }}">@lang('Forgot Password')?</a>
                                </div>
                            </div>

                            <button class="submit-btn" id="recaptcha" type="submit">@lang('Login')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
