<!-- ======= Footer ======= -->
<footer id="footer">
    <div class="footer-top mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 footer-contact">
                    <h3><a href="{{ route('index') }}"><img src="{{ asset('assets/img/footer-logo.svg') }}" alt=""></a>
                    </h3>
                    <p>The Fastest and Easiest Data Prediction <br>tool in the world.</p>
                    <p class="mt-3">©2022 DoleUpp.</p>
                    <div class="social-links mt-3">
                        <a href="javascript:" class="twitter"><i class="bx bxl-twitter"></i></a>
                        <a href="javascript:" class="facebook"><i class="bx bxl-facebook"></i></a>
                        <a href="javascript:" class="instagram"><i class="bx bxl-instagram"></i></a>
                        <a href="javascript:" class="linkedin"><i class="bx bxl-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Users DoleUpp Request</h4>
                    <ul>
                        @foreach(App\Models\Category::get() as $category)
                            <li>
                                <a href="{{ route('fundraisers.show', ['slug' => $category->slug]) }}">{{ $category->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 footer-links">
                    <h4 style="background:#9BCB20;">Learn more</h4>
                    <ul>
                        <li><a href="{{ route('how-it-works') }}">How DoleUpp works</a></li>
                        <li><a href="{{ route('subscription') }}">Pricing</a></li>
                        <li><a href="{{ route('news') }}">DoleUpp News</a></li>
                        <li><a href="{{ route('corporate.categories') }}">Corporate DoleUpp</a></li>
                        {{--<li><a href="javascript:">Common questions</a></li>
                        <li><a href="javascript:">Success stories</a></li>
                        <li><a href="javascript:">Supported countries</a></li>--}}
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 footer-links">
                    <h4 style="background:#6F6F6F;">Resources</h4>
                    <ul>
                        <li><a href="{{ route('faq') }}">FAQ's</a></li>
                        <li><a href="{{ route('about-us') }}">About Us</a></li>
                        <li><a href="{{ route('contact-us') }}">Contact Us</a></li>
                        <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('terms-and-conditions') }}">Terms and Conditions</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer><!-- End Footer -->
