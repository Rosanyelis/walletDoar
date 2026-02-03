
 <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Subscribe
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="subscribe-section ptb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-10">
                <div class="subscribe-area">
                    <div class="subscribe-content">
                        <h2 class="title">{{ __('Subscribe To Our Newsletter') }}</h2>
                    </div>
                    <form action="{{ setRoute('frontend.subscribe') }}" method="post" class="subscribe-form">
                        @csrf
                        <input type="email" name="email" class="form--control" placeholder="{{ __("Enter Your Email") }}...">
                        <div class="pt-20">
                            <x-security.google-recaptcha-field />
                        </div>
                        <button type="submit"><i class="las la-arrow-right"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Subscribe
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
