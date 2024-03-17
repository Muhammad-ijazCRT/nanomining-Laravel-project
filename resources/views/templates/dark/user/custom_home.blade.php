@php
    $policyPages = getContent('policy_pages.element', false, null, true);
    $content = getContent('register.content', true);
@endphp
@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="account section-bg py-100">

        <section class="banner bg-img bg-overlay-one"
            style="background-image: url(assets/images/frontend/banner/63709b553403c1668324181.png);">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-7">
                        <div class="banner-content">
                            <h1 class="banner-content__title">EASY TO START BTC MINING . SIGN UP TO GET $10 BONUS</h1>
                            <p class="banner-content__desc">Join over 2,000,000 people with the world’s leading hashpower
                                provider. Finally, a truly institutional grade mining pool brought to you by CoinGPT. Built
                                from the ground up, we are geared towards providing best in class service with a focus on
                                large miners.</p>
                            <a class="btn--base" href="user/register">Let&#039;s Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="services py-100">
            <div class="container">
                <div class="row">
                    <div class="section-heading">
                        <h3 class="section-heading__title">Why CoinGPT</h3>
                        <p class="section-heading__desc">We provide the best services to our miners, be connected with us,
                            and get profited.</p>
                    </div>
                </div>
                <div class="row gy-4">
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="service-card">
                            <div class="service-card__icon"> <i class="fas fa-random"></i> </div>
                            <div class="service-card__content">
                                <h4 class="service-card__title">24/7 Support</h4>
                                <p class="service-card__desc">We are ready to answer all your questions and advise you
                                    24/7. Feel free to reach us anytime.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="service-card">
                            <div class="service-card__icon"> <i class="fab fa-connectdevelop"></i> </div>
                            <div class="service-card__content">
                                <h4 class="service-card__title">Instant Connect</h4>
                                <p class="service-card__desc">Our team of experts always available and feels happy to help
                                    you. Please mail if you have issue</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="service-card">
                            <div class="service-card__icon"> <i class="las la-star-and-crescent"></i> </div>
                            <div class="service-card__content">
                                <h4 class="service-card__title">Easy Withdrawal</h4>
                                <p class="service-card__desc">Our withdrawal process takes only 24 hours. We are highly
                                    transparent about transactions.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="service-card">
                            <div class="service-card__icon"> <i class="las la-ribbon"></i> </div>
                            <div class="service-card__content">
                                <h4 class="service-card__title">Detailed Statistics</h4>
                                <p class="service-card__desc">We make detailed statistics of your transaction, also you
                                    will get all the mining logs.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="service-card">
                            <div class="service-card__icon"> <i class="fab fa-bitcoin"></i> </div>
                            <div class="service-card__content">
                                <h4 class="service-card__title">Cloud Mining</h4>
                                <p class="service-card__desc">We provide the best cloud mining service and give rewards to
                                    our miners on a daily basis.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="service-card">
                            <div class="service-card__icon"> <i class="fab fa-accusoft"></i> </div>
                            <div class="service-card__content">
                                <h4 class="service-card__title">Data Protection</h4>
                                <p class="service-card__desc">We constantly work on improving our system and the level of
                                    our security to minimize any risks.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="about pt-100 pb-100 section-bg">
            <div class="container">
                <div class="row gy-4 flex-wrap-reverse">
                    <div class="col-lg-6 pe-lg-5">
                        <div class="about-thumb">
                            <img src="{{ asset('assets/images/frontend/about/63709b4023e591668324160.png') }}"
                                alt="">
                            <div class="about-thumb__coin">
                                <img src="{{ asset('assets/images/frontend/about/63709b4020d991668324160.png') }}"
                                    alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="section-heading style-two">
                            <h3 class="section-heading__title">About CoinGPT</h3>
                            <p class="section-heading__desc"> CoinGPT was created to meet the institutional demand for
                                better capital access, efficiency, and transparency in the digital currency mining and
                                staking industry.As a Digital Currency Group company, Braiins taps unparalleled
                                institutional expertise, capital, and market intelligence to provide global bitcoin miners
                                and global manufacturers with the resources to build, maintain, and secure decentralized
                                networks.</p>
                        </div>
                        <div class="about-item-wrapper">
                            <div class="about-item">
                                <div class="about-item__icon"><i class="las la-database"></i></div>
                                <div class="about-item__content">
                                    <h5 class="about-item__title">Performance</h5>
                                    <p class="about-item__desc">Ultimate performance at low cost</p>
                                </div>
                            </div>
                            <div class="about-item">
                                <div class="about-item__icon"><i class="fas fa-globe"></i></div>
                                <div class="about-item__content">
                                    <h5 class="about-item__title">World Wide Service</h5>
                                    <p class="about-item__desc">Servicing over customers from 100+ countries</p>
                                </div>
                            </div>
                            <div class="about-item">
                                <div class="about-item__icon"><i class="fas fa-coins"></i></div>
                                <div class="about-item__content">
                                    <h5 class="about-item__title">Multiple Cryptocurrencies</h5>
                                    <p class="about-item__desc">We are offering 3+ minable cryptocurrencies.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="work py-100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="section-heading">
                            <h3 class="section-heading__title">HOW CoinGPT WORKS?</h3>
                            <p class="section-heading__desc">Learn about our work process. You need to follow the steps
                                below to start your first mining.</p>
                        </div>
                    </div>
                </div>
                <div class="row gy-4">
                    <div class="col-xl-3 col-sm-6">
                        <div class="work-item">
                            <span class="work-item__border"></span>
                            <span class="work-item__number"> 1</span>
                            <span class="work-item__icon"><i class="las la-user-edit"></i></span>
                            <h4 class="work-item__title">Create An Account</h4>
                            <p class="work-item__desc">Create a user profile using the register option and get ready for
                                earning</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="work-item">
                            <span class="work-item__border"></span>
                            <span class="work-item__number"> 2</span>
                            <span class="work-item__icon"><i class="fas fa-clipboard-list"></i></span>
                            <h4 class="work-item__title">Choose Plans</h4>
                            <p class="work-item__desc">Top up your balance and buy the Bots plan at the most reasonable
                                price.</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="work-item">
                            <span class="work-item__border"></span>
                            <span class="work-item__number"> 3</span>
                            <span class="work-item__icon"><i class="las la-coins"></i></span>
                            <h4 class="work-item__title">Start Earnining</h4>
                            <p class="work-item__desc">Increase the mining power on the fly for all the coins using
                                CoinGPT.</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="work-item">
                            <span class="work-item__border"></span>
                            <span class="work-item__number"> 4</span>
                            <span class="work-item__icon"><i class="las la-wallet"></i></span>
                            <h4 class="work-item__title">Withdraw Profits</h4>
                            <p class="work-item__desc">You will periodically receive mining output in your designated
                                wallet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="chooseus py-100 section-bg-two">
            <div class="container">
                <div class="row">
                    <div class="section-heading">
                        <h3 class="section-heading__title">Our Special Features</h3>
                        <p class="section-heading__desc">We are combining all the key aspects of conducting an efficient
                            cryptocurrency mining activity. From building a highly efficient data center to providing a
                            robust mining system for our users.</p>
                    </div>
                </div>
                <div class="row align-items-lg-center gy-4">
                    <div class="col-xl-4 col-md-6">
                        <div class="chooseus-card style-two">
                            <span class="chooseus-card__icon">
                                <i class="fas fa-globe-africa"></i> </span>
                            <div class="chooseus-card__content">
                                <h5 class="chooseus-card__title">Multilingual</h5>
                                <p class="chooseus-card__desc">As we run our business in 100+ countries we have a
                                    multilingual feature in your system.</p>
                            </div>
                        </div>
                        <div class="chooseus-card style-two">
                            <span class="chooseus-card__icon">
                                <i class="fab fa-paypal"></i> </span>
                            <div class="chooseus-card__content">
                                <h5 class="chooseus-card__title">Easy Payment System</h5>
                                <p class="chooseus-card__desc">We have 20+ payment methods in our system. You can easily
                                    complete your payment.</p>
                            </div>
                        </div>
                        <div class="chooseus-card style-two">
                            <span class="chooseus-card__icon">
                                <i class="las la-wallet"></i> </span>
                            <div class="chooseus-card__content">
                                <h5 class="chooseus-card__title">Daily Get Profit</h5>
                                <p class="chooseus-card__desc">Our system will automatically add your daily profit to your
                                    account. Also, you are able to withdraw that amount.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 d-xl-block d-none px-5">
                        <div class="chooseus-thumb">
                            <img src="{{ asset('assets/images/frontend/feature/63709c10cbaa41668324368.png') }}"
                                alt="">
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="chooseus-card">
                            <span class="chooseus-card__icon">
                                <i class="las la-lock"></i> </span>
                            <div class="chooseus-card__content">
                                <h5 class="chooseus-card__title">Secure and Private</h5>
                                <p class="chooseus-card__desc">We support cryptocurrencies that promote privacy, so we try
                                    to keep user data collected to a minimum and will only require information.</p>
                            </div>
                        </div>
                        <div class="chooseus-card">
                            <span class="chooseus-card__icon">
                                <i class="las la-chart-bar"></i> </span>
                            <div class="chooseus-card__content">
                                <h5 class="chooseus-card__title">Intuitive Dashboard</h5>
                                <p class="chooseus-card__desc">Our system dashboard contains all your crypto trading data
                                    and charts.</p>
                            </div>
                        </div>
                        <div class="chooseus-card">
                            <span class="chooseus-card__icon">
                                <i class="las la-hammer"></i> </span>
                            <div class="chooseus-card__content">
                                <h5 class="chooseus-card__title">Smart Quantitative Trading Strategy</h5>
                                <p class="chooseus-card__desc">CoinGPT firmly believes in a scientific, hypothesis-driven
                                    and repeatable risk-managed process for carrying out systematic trading research and
                                    implementation.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="testimonails py-100 section-bg">
            <div class="container">
                <div class="row">
                    <div class="section-heading">
                        <h3 class="section-heading__title">What people says about us</h3>
                        <p class="section-heading__desc">What people says about us</p>
                    </div>
                </div>
                <div class="row gy-4 testimonails-item-wrapper">
                    <div class="col-lg-4">
                        <div class="testimonails-item">
                            <div class="testimonails-item__content">
                                <div class="testimonails-item__icon"><i class="fas fa-quote-left"></i></div>
                                <p class="testimonails-item__desc">CoinGPT is very easy to use, the withdrawal speed is
                                    fast, the function is powerful, and it is very cool to use. I highly recommend it</p>
                            </div>
                            <div class="testimonails-item__info">
                                <div class="testimonails-item__thumb">
                                    <img src="{{ asset('assets/images/frontend/testimonial/63709db8ea6781668324792.png') }}"
                                        alt="Client">
                                </div>
                                <h6 class="testimonails-item__name">Jonathon Smith</h6>
                                <span class="testimonails-item__designation"> Civil Servant</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="testimonails-item">
                            <div class="testimonails-item__content">
                                <div class="testimonails-item__icon"><i class="fas fa-quote-left"></i></div>
                                <p class="testimonails-item__desc">i start earning more profit since i know this
                                    application and i invested with this.You guys can try</p>
                            </div>
                            <div class="testimonails-item__info">
                                <div class="testimonails-item__thumb">
                                    <img src="{{ asset('assets/images/frontend/testimonial/63709db12dbb51668324785.png') }}"
                                        alt="Client">
                                </div>
                                <h6 class="testimonails-item__name">Mrr. Beezey</h6>
                                <span class="testimonails-item__designation"> Engineer</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="testimonails-item">
                            <div class="testimonails-item__content">
                                <div class="testimonails-item__icon"><i class="fas fa-quote-left"></i></div>
                                <p class="testimonails-item__desc">I have successfully made investments and withdrawals, I
                                    feel very confident with this platform</p>
                            </div>
                            <div class="testimonails-item__info">
                                <div class="testimonails-item__thumb">
                                    <img src="{{ asset('assets/images/frontend/testimonial/63709da8d05061668324776.png') }}"
                                        alt="Client">
                                </div>
                                <h6 class="testimonails-item__name">Nami</h6>
                                <span class="testimonails-item__designation"> University English Teacher</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="testimonails-item">
                            <div class="testimonails-item__content">
                                <div class="testimonails-item__icon"><i class="fas fa-quote-left"></i></div>
                                <p class="testimonails-item__desc">I have worked with this platform and got good feedback.
                                    I recommend this site to all people. Really trustworthy.</p>
                            </div>
                            <div class="testimonails-item__info">
                                <div class="testimonails-item__thumb">
                                    <img src="{{ asset('assets/images/frontend/testimonial/63709da1829861668324769.png') }}"
                                        alt="Client">
                                </div>
                                <h6 class="testimonails-item__name">Jonathon Smith</h6>
                                <span class="testimonails-item__designation"> Businessman</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="testimonails-item">
                            <div class="testimonails-item__content">
                                <div class="testimonails-item__icon"><i class="fas fa-quote-left"></i></div>
                                <p class="testimonails-item__desc">I received my withdrawal from this company in less than
                                    4 hours. A really good start. Now I can refer it to people</p>
                            </div>
                            <div class="testimonails-item__info">
                                <div class="testimonails-item__thumb">
                                    <img src="{{ asset('assets/images/frontend/testimonial/63709dc6ca6da1668324806.png') }}"
                                        alt="Client">
                                </div>
                                <h6 class="testimonails-item__name">Ms Anna</h6>
                                <span class="testimonails-item__designation"> Fashion Blogger</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal custom--modal fade" id="existModalCenter" role="dialog"
            aria-labelledby="existModalCenterTitle" aria-hidden="true" tabindex="-1">
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
                        <button class="btn btn--danger btn--sm" data-bs-dismiss="modal"
                            type="button">@lang('Close')</button>
                        <a class="btn btn--base btn--sm outline" href="{{ route('user.login') }}">@lang('Login')</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@if ($general->secure_password)
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
