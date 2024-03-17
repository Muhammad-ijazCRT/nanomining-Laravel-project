<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $general->siteName(@$pageTitle ? __($pageTitle) : __('404 | page not found')) }}</title>
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
                    <img src="{{ asset('assets/errors/images/error-404.png') }}" alt="@lang('image')">
                    <h2><b>@lang('404')</b> @lang('Page not found')</h2>
                    <p>@lang('page you are looking for doesn\'t exit or an other error ocurred') <br> @lang('or temporarily unavailable.')</p>
                    <a class="cmn-btn mt-4" href="{{ route('home') }}">@lang('Go to Home')</a>
                </div>
            </div>
        </div>
    </div>
    <!-- error-404 end -->

</body>

</html>
