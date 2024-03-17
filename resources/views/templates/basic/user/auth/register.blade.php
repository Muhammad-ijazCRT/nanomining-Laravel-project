@php
    $policyPages = getContent('policy_pages.element', false, null, true);
    $content = getContent('register.content', true);
@endphp
@extends($activeTemplate . 'layouts.app')
@section('panel')
    <section class="register-section bg-overlay-primary bg_img" data-background="{{ getImage('assets/images/frontend/register/' . $content->data_values->image, '1920x1080') }}">
        <div class="container">
            <div class="go-to-home">
                <a class="text-white" href="{{ route('home') }}">
                    <i class="la la-times-circle fa-5x"></i>
                </a>
            </div>
            <div class="row register-area justify-content-center align-items-center">
                <div class="col-lg-8">
                    <div class="register-form-area">

                        <div class="register-logo-area text-center">
                            <a href="{{ route('home') }}"><img src="{{ siteLogo() }}" alt="@lang('logo')"></a>
                        </div>

                        <div class="account-header text-center">
                            <h2 class="title">{{ __(@$content->data_values->title) }}</h2>
                            <p class="sub-title">@lang('Already Have An Account')? <a href="{{ route('user.login') }}">@lang('Login Now')</a></p>
                        </div>

                        <form class="register-form verify-gcaptcha" action="{{ route('user.register') }}" method="POST">
                            @csrf
                            <div class="row justify-content-center ml-b-20">
                                @if (session()->get('reference') != null)
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label" for="referenceBy">@lang('Reference by')</label>
                                            <input class="form-control" id="referenceBy" name="referBy" type="text" value="{{ session()->get('reference') }}" readonly>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="register-icon"><i class="fas fa-user"></i></label>
                                        <input class="form-control checkUser" name="username" type="text" value="{{ old('username') }}" placeholder="@lang('Enter Username')" required>
                                        <small class="text-danger usernameExist"></small>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="register-icon"><i class="fas fa-envelope"></i></label>
                                        <input class="form-control checkUser" name="email" type="email" value="{{ old('email') }}" placeholder="@lang('Enter Email')" required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="register-icon"><i class="fas fa-globe"></i></label>
                                        <select class="form-control country" name="country">
                                            @foreach ($countries as $key => $country)
                                                <option data-code="{{ $key }}" data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}">{{ __($country->country) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input name="mobile_code" type="hidden">
                                        <input name="country_code" type="hidden">
                                        <label class="register-icon">
                                            <span class="mobile-code"></span>
                                        </label>
                                        <input class="form-control checkUser" name="mobile" type="number" value="{{ old('mobile') }}" placeholder="@lang('Enter Mobile Number')" required>
                                        <small class="text-danger mobileExist"></small>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="register-icon"><i class="fas fa-key"></i></label>
                                        <input class="form-control @if ($general->secure_password) secure-password @endif" name="password" type="password" placeholder="@lang('Enter Password')" required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="register-icon"><i class="fas fa-key"></i></label>
                                        <input class="form-control" name="password_confirmation" type="password" placeholder="@lang('Confirm Password')" required>
                                    </div>
                                </div>

                                <x-captcha class="col-lg-12" customCaptchaMarginBottom="mrb-20" googleMarginBottom="mrb-20" />

                                @if ($general->agree)
                                    <div class="col-lg-12 text-center">
                                        <div class="form-group">
                                            <div class="checkbox-wrapper d-flex align-items-center mt-0 flex-wrap">
                                                <div class="checkbox-item">
                                                    <input id="agree" name="agree" type="checkbox" required>
                                                    <label for="agree">@lang('I agree with')</label>
                                                    <span>
                                                        @foreach ($policyPages as $policy)
                                                            <a href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}" target="_blank">{{ __($policy->data_values->title) }}</a>
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-12 mb-0">
                                    <div class="form-group">
                                        <button class="submit-btn" type="submit">{{ __(@$content->data_values->button_text) }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="existModalCenter" role="dialog" aria-hidden="true" aria-labelledby="existModalCenterTitle" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <strong>@lang('You already have an account please Login ')</strong>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-icon btn-sm" href="{{ route('user.login') }}">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <style>
        .country-code .input-group-text {
            background: #fff !important;
        }

        .country-code select {
            border: none;
        }

        .country-code select:focus {
            border: none;
            outline: none;
        }

        #referenceBy {
            padding-left: 10px;
        }

        .country {
            padding: 8px 20px 8px 45px;
        }
    </style>
@endpush

@if ($general->secure_password)
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
@push('script')
    <script>
        "use strict";
        (function($) {
            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif

            $('select[name=country]').change(function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {
                        mobile: mobile,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'email') {
                    var data = {
                        email: value,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response.data != false && response.type == 'email') {
                        $('#existModalCenter').modal('show');
                    } else if (response.data != false) {
                        $(`.${response.type}Exist`).text(`${response.type} already exist`);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
