<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
	<!-- BEGIN: Head-->
	
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="author" content="">
		<title>ISP Billing Solution :: Login</title>
		<link rel="apple-touch-icon" href="">
		<link rel="shortcut icon" type="image/x-icon" href="">
		<link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">
		
		<!-- BEGIN: Vendor CSS-->
		<link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/vendors.min.css")}}">
		<link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/bootstrap.min.css")}}">
		<link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/bootstrap-extended.min.css")}}">
		<link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/components.min.css")}}">
		<link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/core/colors/palette-gradient.min.css") }}">
		<!-- END: Page CSS-->

	</head>
	<!-- END: Head-->
	
	<!-- BEGIN: Body-->
	<body class="vertical-layout vertical-menu 1-column  bg-full-screen-image blank-page blank-page" data-open="click" data-menu="vertical-menu" data-color="bg-gradient-x-purple-blue" data-col="1-column">
		<!-- BEGIN: Content-->
		<div class="app-content content">
			<div class="content-wrapper">
				<div class="content-wrapper-before"></div>
				<div class="content-header row">
				</div>
				<div class="content-body">
					<section class="flexbox-container">
						<div class="col-12 d-flex align-items-center justify-content-center">
							<div class="col-lg-4 col-md-6 col-11 box-shadow-2 p-0">
								<div class="card border-grey border-lighten-3 px-1 py-1 m-0">
									<div class="card-header border-0" style="padding: 0;">
										<div class="text-center mb-1">
											<img src="{{ asset("app-assets/images/company/default_company_logo.png") }}" alt="branding logo" width="100">
										</div>
										<div class="font-large-1  text-center">
											ISP Billing Solution
										</div>
									</div>
									<div class="card-content">
										
										<div class="card-body">
											<form method="post" class="form-horizontal" action="{{ route('authenticate') }}" novalidate>
												@csrf
												<fieldset class="form-group position-relative has-icon-left">
													<input type="text" class="form-control round @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" id="user-name" placeholder="Your Username" required>
													<div class="form-control-position">
														<i class="ft-user"></i>
													</div>
												</fieldset>
												<fieldset class="form-group position-relative has-icon-left">
													<input type="password" class="form-control round  @error('password') is-invalid @enderror" name="password" id="user-password" placeholder="Enter Password" required>
													<div class="form-control-position">
														<i class="ft-lock"></i>
													</div>
												</fieldset>
												<div class="form-group row">
													<label class="col-md-6 col-12 float-sm-left text-sm-left"><input type="checkbox" name="remember"> Remember me </label>
												</div>
												<div class="form-group text-center" style="margin-bottom: 0 !important;">
													<button style="margin-bottom: 0 !important;" type="submit" class="btn round btn-block btn-glow btn-bg-gradient-x-purple-blue col-12 mr-1 mb-1">Login</button>
												</div>
												
											</form>
										</div>
										<p class="card-subtitle text-muted text-right font-small-3 mx-2 my-1" style="margin-top: 0 !important;">
											<span>Don't have an account ? <a href="{{ route("register") }}" class="card-link">Sign Up</a></span>
										</p>
										<p class="card-subtitle text-muted text-center font-small-3 mx-2 my-1" style="margin-top: 0 !important;">
											<span>Powered By
												<a target="_blank" href="//techmakersbd.com" class="card-link">Tech Makers BD</a>
											</span>
										</p>
									</div>
								</div>
							</div>
						</div>
					</section>
					
				</div>
			</div>
		</div>
		<!-- END: Content-->
		
		
	</body>
	<!-- END: Body-->
</html>