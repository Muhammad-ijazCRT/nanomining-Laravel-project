<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $general->siteName(@$pageTitle ? __($pageTitle) : __('419 | Session has expired')) }}</title>
    <link type="image/png" href="{{ getImage(getFilePath('logoIcon') . '/favicon.png') }}" rel="shortcut icon">
    <!-- bootstrap 4  -->
    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- dashdoard main css -->
    <link href="{{ asset('assets/errors/css/main.css') }}" rel="stylesheet">
</head>

<body>

    <!-- error-404 start -->
    <div class="error">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 text-center">
                    <img src="{{ asset('assets/errors/images/error-419.png') }}" alt="@lang('image')">
                    <h2><b>@lang('419')</b> @lang('Sorry your session has expired.')</h2>
                    <p>@lang('Please go back and refresh your browser and try again')</p>
                    <a class="cmn-btn mt-4" href="{{ route('home') }}">@lang('Go to Home')</a>
                </div>
            </div>
        </div>
    </div>
    <!-- error-404 end -->

</body>

</html>
