<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>DoleUpp</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/fonts.css" rel="stylesheet">

  <link rel="stylesheet" href="assets/css/intlTelInput.css">

</head>

<body>
  <!-- ======= Header ======= -->
  <header id="header" class="d-flex align-items-center">
    <div class="container-fluid d-flex align-items-center justify-content-between">

      <h1 class="logo"><a href="index"><img src="assets/img/brand.svg" alt=""></a></h1>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index" class="logo"><img src="assets/img/logo.png" alt=""></a>-->

      <nav id="navbar" class="navbar">
        <ul>
            <li class="nav-item d-flex">
                <div class="collapse fade" id="searchForm">
                    <input id="collapseExample" type="search" class="form-control border-0" placeholder="search" />
                </div>
                <a class="nav-link ml-auto" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <img src="assets/img/search.svg" class="" alt="">&nbsp; Search
                </a>
            </li>
          <li><a class="nav-link scrollto active" href="how-it-works">How it works</a></li>
          <li><a class="nav-link scrollto" href="fundraisers">Fundraise for</a></li>
          <li class="dropdown"><a href="#"><span>Drop Down</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="donation-request">DoleUpp Request</a></li>
              <li><a href="my-account">My Account</a></li>
              <li><a href="contact">Contact Us</a></li>
              <li><a href="privacy-policy">Privacy Policy</a></li>
            </ul>
          </li>
          <li><a class="nav-link scrollto sign-in" href="login">Sign in</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->


  <main id="main" class="login-pg">
  <form class="modal-content animate" action="/action_page.php" method="post">
    <div class="container">
      <div class="section-title">
          <h3>Sign Up</h3>
        </div>
      <input id="phone" name="phone" placeholder="Enter mobile number" type="tel">
      <button onclick="location.href='verify'" type="button">Continue</button>
      <p class="d-block text-center mt-3">or</p>
      <a href="" class="fb-login"><img src="assets/img/facebook.svg" alt="">&nbsp; Continue with Facebook</a>
      <a href="" class="fb-login mt-3"><img src="assets/img/google.svg" alt="">&nbsp; Continue with Google</a>
      <a href="" class="fb-login mt-3"><img src="assets/img/email.svg" alt="">&nbsp; Sign In With Email</a>
      <div class="alrdy-act">Already have an account? <a href="login">Sign In</a></div>
    </div>

  </form>

  </main><!-- End #main -->



  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/purecounter/purecounter.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>


  <script src="assets/js/intlTelInput.js"></script>
  <script>
    var input = document.querySelector("#phone");
    window.intlTelInput(input, {
      // allowDropdown: false,
      // autoHideDialCode: false,
      // autoPlaceholder: "off",
      // dropdownContainer: document.body,
      // excludeCountries: ["us"],
      // formatOnDisplay: false,
      // geoIpLookup: function(callback) {
      //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
      //     var countryCode = (resp && resp.country) ? resp.country : "";
      //     callback(countryCode);
      //   });
      // },
      // hiddenInput: "full_number",
      // initialCountry: "auto",
      // localizedCountries: { 'de': 'Deutschland' },
      // nationalMode: false,
      // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
      // placeholderNumberType: "MOBILE",
      // preferredCountries: ['cn', 'jp'],
      // separateDialCode: true,
      utilsScript: "assets/js/utils.js",
    });
  </script>
</body>

</html>
