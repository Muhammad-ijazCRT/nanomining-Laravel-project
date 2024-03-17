@php
    $policyPages = getContent('policy_pages.element', false, null, true);
    $content = getContent('register.content', true);
@endphp
@extends($activeTemplate . 'layouts.app')
@section('panel')
    <section class="account bg-img" data-background-image="{{ getImage('assets/images/frontend/register/' . @$content->data_values->image, '1235x980') }}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8 col-md-10">
                    <div class="account-form">
                        <div class="account-form__logo text-center">
                            <a href="{{ route('home') }}"><img src="{{ siteLogo() }}" alt="@lang('image')"></a>
                        </div>
                        <h4 class="account-form__title"> {{ __(@$content->data_values->title) }} </h4>
                        <form class="verify-gcaptcha" action="{{ route('user.register') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="row">
                                @if (session()->get('reference') != null)
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form--label">@lang('Referrer')</label>
                                            <div class="position-relative">
                                                <input class="form--control" name="referBy" type="text" value="{{ session()->get('reference') }}" required>
                                                <span class="input-icon"><i class="fa fa-user-friends"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form--label" for="Username">@lang('Username')</label>
                                        <div class="position-relative">
                                            <input class="form--control checkUser" id="Username" name="username" type="text" value="{{ old('username') }}" required>
                                            <span class="input-icon"><i class="far fa-user"></i></span>
                                        </div>
                                        <small class="text-danger usernameExist"></small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form--label" for="email">@lang('Email Address')</label>
                                        <div class="position-relative">
                                            <input class="form--control checkUser" id="email" name="email" type="text" value="{{ old('email') }}" required>
                                            <span class="input-icon"><i class="far fa-envelope"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form--label" for="country">@lang('Country')</label>
                                        <div class="position-relative">
                                            <select class="select form--control" id="mySelect" name="country">
                                                @foreach ($countries as $key => $country)
                                                    <option data-mobile_code="{{ $country->dial_code }}" data-code="{{ $key }}" value="{{ $country->country }}">{{ __($country->country) }}</option>
                                                @endforeach
                                            </select>
                                            <span class="input-icon"><i class="far fa-envelope"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form--label" for="phone">@lang('Mobile Number')</label>
                                        <div class="select-phone">
                                            <div class="input-group">
                                                <div class="input-group-text p-0">
                                                    <span class="input-group-text mobile-code select"></span>
                                                    <span class="input-icon"><span class="icon-phone-fill"></span></span>
                                                    <input name="mobile_code" type="hidden">
                                                    <input name="country_code" type="hidden">
                                                </div>
                                                <input class="form-control form--control checkUser ps-0" name="mobile" type="number" value="{{ old('mobile') }}" required>
                                            </div>
                                        </div>
                                        <small class="text-danger mobileExist"></small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form--label" for="your-password">@lang('Password')</label>
                                        <div class="position-relative">
                                            <input class="form-control form--control @if ($general->secure_password) secure-password @endif" id="your-password" name="password" type="password" required>
                                            <span class="input-icon"><span class="icon-Lock"></span></span>
                                            <span class="password-show-hide icon-eye toggle-password icon-eye-off" id="#your-password"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form--label" for="confirm-password">@lang('Confirm Password')</label>
                                        <div class="position-relative">
                                            <input class="form-control form--control" id="confirm-password" name="password_confirmation" type="password" required>
                                            <span class="input-icon"><span class="icon-Lock"></span></span>
                                            <span class="password-show-hide icon-eye toggle-password icon-eye-off" id="#confirm-password"></span>
                                        </div>
                                    </div>
                                </div>
                                <x-captcha />

                                @if ($general->agree)
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="form--check">
                                                <input class="form-check-input" id="remember" name="agree" type="checkbox" required>
                                                <div class="form-check-label">
                                                    <label for="remember">@lang('I agree with the')</label>
                                                    @foreach ($policyPages as $policy)
                                                        <a class="text--base" href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}" target="_blank">@lang($policy->data_values->title) </a>
                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button class="btn btn-outline--base w-100" type="submit"> <span class="text--gradient">@lang('Submit')</span> </button>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="have-account text-center">
                                        <p class="have-account__text">@lang('Already Have An Account')? <a class="have-account__link text--gradient fw-bold" href="{{ route('user.login') }}">@lang('Login Now')</a></p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade custom--modal" id="existModalCenter" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="mb-0 text-center">@lang('You already have an account. Please Login. ')</h6>
                </div>
                <div class="modal-footer">
                    <button class="btn btn--danger btn--sm" data-bs-dismiss="modal" type="button">@lang('Close')</button>
                    <a class="btn btn--base btn--sm" href="{{ route('user.login') }}">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>
@endsection

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
            @if ($general->secure_password)
                $('input[name=password]').on('input', function() {
                    secure_password($(this));
                });

                $('[name=password]').focus(function() {
                    $(this).closest('.input-group').addClass('hover-input-popup');
                });

                $('[name=password]').focusout(function() {
                    $(this).closest('.input-group').removeClass('hover-input-popup');
                });
            @endif

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
