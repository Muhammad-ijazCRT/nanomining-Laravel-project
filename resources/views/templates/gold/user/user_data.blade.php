@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="py-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-6">
                    <div class="card custom--card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('user.data.submit') }}">
                                @csrf
                                <div class="row gy-4">
                                    <div class="col-sm-6">
                                        <input class="form--control" name="firstname" type="text" value="{{ old('firstname') }}" placeholder="@lang('First Name')" required>
                                    </div>

                                    <div class="col-sm-6">
                                        <input class="form--control" name="lastname" type="text" value="{{ old('lastname') }}" placeholder="@lang('Last Name')" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form--control" name="address" type="text" value="{{ old('address') }}" placeholder="@lang('Address')">
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form--control" name="state" type="text" value="{{ old('state') }}" placeholder="@lang('State')">
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form--control" name="zip" type="text" value="{{ old('zip') }}" placeholder="@lang('Zip Code')">
                                    </div>

                                    <div class="col-sm-6">
                                        <input class="form--control" name="city" type="text" value="{{ old('city') }}" placeholder="@lang('City')">
                                    </div>

                                    <div>
                                        <button class="btn btn--base w-100" type="submit">
                                            @lang('Submit')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
