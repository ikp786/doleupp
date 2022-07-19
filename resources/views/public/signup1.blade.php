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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css">
  <!-- Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/fonts.css" rel="stylesheet">
  <style>
/* Full-width input fields */

</style>
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
  <form class="modal-content animate px-5" action="/action_page.php" method="post">
    <div class="container">
      <div class="section-title">
          <h3>Sign In</h3>
        </div>
        <div class="sign-up-tabs">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Personal Details</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Security Questions</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Banking Information</button>
          </li>
        </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

          <div class="avatar-upload">
              <div class="avatar-edit">
                  <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" />
                  <label for="imageUpload"></label>
              </div>
              <div class="avatar-preview">
                  <div id="imagePreview" style="background-image: url(assets/img/profile-pic.png);">
                  </div>
              </div>
          </div>

          <input type="text" class="mb-0" placeholder="Name" name="uname" >
          <span>Note: “This information must match the banking information”</span>
          <input type="text" class="mb-0" placeholder="Username" name="uname" >
          <span>Note: “This can be any name you choose under 16 characters, no spaces”</span>
          <input type="text" class="mb-0" placeholder="University" name="uname" >
          <span>Note: Optional</span>
          <input type="text" placeholder="Occupation" name="uname" >
          <input type="text" class="mb-0" placeholder="City & State Location" name="uname" >
          <span>Note: Example- Atlanta, GA</span>
          <input type="text" placeholder="Email" name="uname" >
          <input type="text" placeholder="DOB" name="uname" >

          <div class="cal-main">
          	<input type="text" id="dp1" class="datepicker" placeholder="Pick Date" name="date" readonly>
          	<a href="" class="cal-icn">
          		<img src="assets/img/calendar.svg" alt="">
          	</a>
          </div>

          <textarea class="mb-0" placeholder="Tell us about yourself " rows="5"></textarea>
          <span>Note: Up to 1000 characters. No special characters</span>

      <button type="submit">Continue</button>
      <div class="alrdy-act">Already have an account? <a href="signin">Sign In</a></div>

        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        	<select class="form-select mb-0 mt-5" aria-label="Default select example">
			  <option selected>Mothers maiden name?</option>
			  <option value="1">One</option>
			  <option value="2">Two</option>
			  <option value="3">Three</option>
			</select>
			<textarea class="mb-0" style="background: #F9F9F9;" placeholder="Answer" rows="3"></textarea>

			<select class="form-select mb-0" aria-label="Default select example">
			  <option selected>City you met your spouse?</option>
			  <option value="1">One</option>
			  <option value="2">Two</option>
			  <option value="3">Three</option>
			</select>
			<textarea class="mb-0" style="background: #F9F9F9;" placeholder="Answer" rows="3"></textarea>

			<select class="form-select mb-0" aria-label="Default select example">
			  <option selected>First pets name?</option>
			  <option value="1">One</option>
			  <option value="2">Two</option>
			  <option value="3">Three</option>
			</select>
			<textarea class="mb-0" style="background: #F9F9F9;" placeholder="Answer" rows="3"></textarea>

			<select class="form-select mb-0" aria-label="Default select example">
			  <option selected>Name of your elementary school?</option>
			  <option value="1">One</option>
			  <option value="2">Two</option>
			  <option value="3">Three</option>
			</select>
			<textarea class="mb-0" style="background: #F9F9F9;" placeholder="Answer" rows="3"></textarea>

			<select class="form-select mb-0" aria-label="Default select example">
			  <option selected>Favorite color?</option>
			  <option value="1">One</option>
			  <option value="2">Two</option>
			  <option value="3">Three</option>
			</select>
			<textarea class="mb-0" style="background: #F9F9F9;" placeholder="Answer" rows="3"></textarea>

			<button type="submit">Continue</button>
      		<div class="alrdy-act">Already have an account? <a href="signin">Sign In</a></div>

        </div>

        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
        	<input class="mt-5" type="text" placeholder="Bank Name" name="uname" required>
      		<input type="text" placeholder="Routing Number" name="psw" required>
      		<input type="text" placeholder="Account Number" name="psw" required>

      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
        <label class="form-check-label" for="flexCheckDefault" style="font-size: 12px;">Accept Terms & Conditions | User Agreement</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault1">
        <label class="form-check-label" for="flexCheckDefault1" style="font-size: 12px;">Accept Privacy Policy</label>
      </div>
      <button onclick="location.href='add-card'" type="button">Submit</button>
      <div class="alrdy-act">Already have an account? <a href="signin">Sign In</a></div>
        </div>
      </div>
    </div>


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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script type="text/javascript">
    function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#imageUpload").change(function() {
    readURL(this);
});
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>
  <script type="text/javascript">
  	$(document).ready(function(){

$('.datepicker').datepicker({
format: 'dd-mm-yyyy',
autoclose: true,
startDate: '0d'
});

$('.cell').click(function(){
$('.cell').removeClass('select');
$(this).addClass('select');
});

});
  </script>

</body>

</html>
