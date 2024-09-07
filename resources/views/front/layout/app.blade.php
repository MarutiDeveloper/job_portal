<!DOCTYPE html>
<html class="no-js" lang="en_AU" />
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="{{ asset('assets/images/Software developer-1.png') }}" type="images/Software developer-1.png">
	<title>Job Portal | Find Best Jobs</title>
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />
	<meta name="HandheldFriendly" content="True" />
	<meta name="pinterest" content="nopin" />
    <meta name="csrf-token" content=" {{ csrf_token() }}" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@600&family=Lobster+Two:wght@700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css" integrity="sha512-Fm8kRNVGCBZn0sPmwJbVXlqfJmPC13zRsMElZenX6v721g/H7OukJd8XzDEBRQ2FSATK8xNF9UYvzsCtUpfeJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('css-2/main.css') }}" />

	<style>
  .social-icon {
    width: 50px;
    height: 50px;
    background-color: #3b5998; /* Change this to match each social icon's color */
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 5px;
    color: white;
    font-size: 20px;
  }

  .social-icon i {
    font-size: 24px;
  }
</style>

	
	<!-- Vendor CSS Files -->
	<!-- <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet" /> -->

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<!-- Fav Icon -->
	<link rel="shortcut icon" type="image/x-icon" href="#" />
</head>
<body data-instant-intensity="mousedown">
<header>
	<nav class="navbar navbar-expand-lg navbar-light bg-white shadow py-3">
		<div class="container">
		<a class="navbar-brand" href="{{ route('home') }}">
    
    Job Portal
</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav ms-0 ms-sm-0 me-auto mb-2 mb-lg-0 ms-lg-4">
					<li class="nav-item">
						<a class="nav-link" aria-current="page" href="{{ route('home') }}">
						Home</a>
					</li>	
					<li class="nav-item">
						<a class="nav-link" aria-current="page" href="{{ route('jobs') }}">
						Find Jobs</a>
					</li>										
				</ul>				
				@if (!Auth::check())
						<a class="btn btn-outline-primary me-2" href="{{ route('account.login') }}" type="submit">Login</a>
						@else
						
						@if (Auth::user()->role	==	'admin')
						<a class="btn btn-outline-primary me-2" href="{{ route('admin.dashboard') }}" type="submit">Admin</a>
						@endif
						
						<a class="btn btn-outline-primary me-2" href="{{ route('account.profile') }}" type="submit">Account</a>
				@endif

				<a class="btn btn-primary" href="{{ route('account.createJob') }}" type="submit">Post a Job</a>
			</div>
		</div>
	</nav>
</header>

@yield('main')

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pb-0" id="exampleModalLabel">Change Profile Picture</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="profilePicForm" name="profilePicForm" action="" method="post">
		@csrf
			
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Profile Image</label>
                <input type="file" class="form-control" id="image"  name="image">
				<p class="text-danger" id="image-error"></p>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary mx-3">Update</button>
				
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            
        </form>
      </div>
    </div>
  </div>
</div>

<footer class="bg-dark py-3 bg-2">
   <div class="container text-center">
      <div class="social-icons py-2">
	  <p class="text-center text-white pt-3 fw-bold fs-6">Follow Me On.</p>
	  <form id="subscribe-form" class="php-email-form">
              <div class="newsletter-form">
                <input type="email" name="email" placeholder="Enter your email" required>
                <input type="submit" value="Subscribe">
              </div>
            </form>
			<div class="d-flex justify-content-center">
  <a class="social-icon rounded-circle" href="https://www.facebook.com/people/Maruti-Developers/61563152392274/" target="_blank">
    <i class="fab fa-facebook-f"></i>
  </a>
  <a class="social-icon rounded-circle" href="https://x.com/Developers86" target="_blank">
    <i class="fab fa-twitter"></i>
  </a>
  <a class="social-icon rounded-circle" href="https://www.instagram.com/maritideveloper/" target="_blank">
    <i class="fab fa-instagram"></i>
  </a>
  <a class="social-icon rounded-circle" href="https://www.youtube.com/@Educational2020" target="_blank">
    <i class="fab fa-youtube"></i>
  </a>
  <a class="social-icon rounded-circle" href="https://www.linkedin.com/feed/?trk=public_profile_not-found-log-in-primary-cta" target="_blank">
    <i class="fab fa-linkedin-in"></i>
  </a>
</div>
      </div>
      <p class="text-center text-white pt-3 fw-bold fs-6"><a href="https://marutideveloper.github.io/Marutideveloper.io/" target="_blank" class="text-white mx-2">
	  <i class="fab fa-website"></i>© 2023 Maruti Developers, all rights reserved
	  </a></p>
   </div>
   <script>
      document.getElementById('subscribe-form').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the form from actually submitting
        window.location.href = "https://www.youtube.com/@Educational2020"; // Redirect to your YouTube channel
      });
    </script>
</footer>

<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
<script src="{{ asset('assets/js/instantpages.5.1.0.min.js') }}"></script>
<script src="{{ asset('assets/js/lazyload.17.6.0.min.js') }}" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/trumbowyg.min.js" integrity="sha512-YJgZG+6o3xSc0k5wv774GS+W1gx0vuSI/kr0E0UylL/Qg/noNspPtYwHPN9q6n59CTR/uhgXfjDXLTRI+uIryg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>

<script>
		$('.textarea').trumbowyg();

        $.ajaxSetup({
                                headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            }
	                        });	
							// $("#profilePicForm") .submit(function(e){
							// 		e.preventDefault();

							// 		var formData = new FormData(this);

							// 		$.ajax({
							// 			url: '{{ route("account.updateProfilePic") }}',
							// 			type: 'post',
							// 			data: formData,
							// 			dataType: 'json',
							// 			contentType: false,
							// 			processData: false,
							// 			success: function(response){
							// 				if(response.status == false){
							// 					var errors = response.errors;
							// 					if(errors.image){
							// 						$("#image-error").html(image-error)
							// 					}
							// 				}

							// 			}
							// 		});
							// });
</script>

@yield('customjs')
</body>
</html>