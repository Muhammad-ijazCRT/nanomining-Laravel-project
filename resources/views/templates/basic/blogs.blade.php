@extends($activeTemplate . 'layouts.frontend')

@section('content')

    <!-- blog-section start -->
    <section class="blog-section ptb-120">
        <div class="container">
            <div class="row justify-content-center ml-b-30">
                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-6 col-sm-12 mrb-30">
                        <div class="blog-item">
                            <div class="blog-thumb">
                                <img src="{{ getImage('assets/images/frontend/blog/thumb_' . @$blog->data_values->blog_image, '318x212') }}" alt="@lang('Blog')">
                                <span class="overlay-date">{{ strtoupper(showDateTime($blog->created_at, 'd, M')) }}</span>
                            </div>
                            <div class="blog-content">
                                <h3 class="title"><a href="{{ route('blog.details', [slug($blog->data_values->title), $blog->id]) }}">{{ $blog->data_values->title }}</a></h3>
                                <p> @php echo strLimit(strip_tags($blog->data_values->description), 80) @endphp</p>
                                <div class="blog-btn">
                                    <a class="custom-btn" href="{{ route('blog.details', [slug($blog->data_values->title), $blog->id]) }}">@lang('Read More') <i class="fas fa-angle-double-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ paginateLinks($blogs) }}
        </div>
    </section>
    <!-- blog-section end -->

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection
