@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="card custom--card">
        <h5 class="card-header">
            {{ __($pageTitle) }}
        </h5>

        <div class="card-body">
            <form action="" class="profile-form" enctype="multipart/form-data" method="post">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="InputFirstname">@lang('First Name')</label>
                            <input class="form--control" id="InputFirstname" name="firstname" placeholder="@lang('First Name')" required type="text" value="{{ old('firstname', $user->firstname) }}" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="lastname">@lang('Last Name')</label>
                            <input class="form--control" id="lastname" name="lastname" placeholder="@lang('Last Name')" required type="text" value="{{ $user->lastname }}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label">@lang('Username')</label>
                            <input class="form--control" placeholder="Username" readonly type="text" value="{{ $user->username }}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="email">@lang('E-mail Address')</label>
                            <input class="form--control" id="email" name="email" placeholder="@lang('E-mail Address')" readonly type="email" value="{{ $user->email }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input id="track" name="country_code" type="hidden">
                            <label class="form-label" for="phone">@lang('Mobile Number')</label>
                            <input class="form--control" id="phone" name="mobile" placeholder="@lang('Your Contact Number')" readonly type="tel" value="{{ $user->mobile }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="country">@lang('Country')</label>
                            <input class="form--control" id="country" readonly type="text" value="{{ @$user->address->country }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="address">@lang('Address')</label>
                            <input class="form--control" id="address" name="address" placeholder="@lang('Address')" type="text" value="{{ @$user->address->address }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="state">@lang('State')</label>
                            <input class="form--control" id="state" name="state" placeholder="@lang('state')" type="text" value="{{ @$user->address->state }}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="zip">@lang('Zip Code')</label>
                            <input class="form--control" id="zip" name="zip" placeholder="@lang('Zip Code')" type="text" value="{{ @$user->address->zip }}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="city">@lang('City')</label>
                            <input class="form--control" id="city" name="city" placeholder="@lang('City')" type="text" value="{{ @$user->address->city }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn--base w-100" type="submit">@lang('Submit')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
