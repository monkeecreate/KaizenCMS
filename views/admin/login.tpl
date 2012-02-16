<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<!-- iPhone -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<!-- /iPhone -->
	<!-- IE -->
	<meta name="application-name" content="{getSetting tag="site-title"} Admin">
	<meta name="msapplication-tooltip" content="Website Admin Area">
	<meta name="msapplication-starturl" content="/?iePinned=true">
	<!-- /IE -->
	
	<title>{getSetting tag="site-title"} Log In</title>
	
	<link rel="shortcut icon" href="/images/favicon.ico">
	<link rel="apple-touch-icon" href="/images/apple-touch-icon.png">
	<link rel="author" href="/humans.txt">
	<link rel="dns-prefetch" href="//ajax.googleapis.com">
	
	<link href="/css/admin/style.css?v1" rel="stylesheet">
	<link href="/js/ui-themes/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet">
	
	<script src="/js/modernizr-2.0.6.min.js"></script>
</head>
<body>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<img src="/images/logo.png" alt="logo" class="pull-left" style="margin-right: 15px;">
				<a class="brand" href="/">{getSetting tag="site-title"}</a>
			</div>
		</div>
	</div>
	
	<div class="container">
		<div class="row">		
			<div class="span4 offset4">
				<div class="row">
			  		{include file="inc_alerts.tpl"}
			  		
			  		<form name="login" class="form-horizontal{if $smarty.get.state == "password"} hide{/if}" method="post" action="/admin/login/">
			  			<fieldset>
			  				<legend>Login</legend>
			  				
							<div class="control-group">
								<label class="control-label" for="form-username">Username</label>
								<div class="controls">
									<input type="text" name="username" class="input-large" id="form-username">
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="form-passwordd">Password</label>
								<div class="controls">
									<input type="password" name="password" class="input-large" id="form-password">
									<p class="help-block"><a href="#" class="toggleForm">Forgot password?</a></p>
								</div>
							</div>
							
							<button type="submit" class="btn btn-primary pull-right"><i class="icon-chevron-right icon-white"></i> Log In</button>
				  		</fieldset>
			  		</form>
			  		
			  		<form name="forgot-password" class="form-horizontal{if $smarty.get.state != "password"} hide{/if}" method="post" action="/admin/passwordReset/">
			  			<fieldset>
			  				<legend>Forgot Password</legend>
			  				
			  				<div class="control-group">
			  					<label class="control-label" for="form-email">Email</label>
			  					<div class="controls">
			  						<input type="text" name="email" class="input-large validate[required,custom[email]]" id="form-email">
			  						<p class="help-block"><a href="#" class="toggleForm">Back to Login</a></p>
			  					</div>
			  				</div>
			  				
			  				<button type="submit" class="btn btn-primary pull-right"><i class="icon-chevron-right icon-white"></i> Reset Password</button>
			  			</fieldset>
			  		</form>
				</div><!--/row-->
			</div><!--/span-->
		</div><!--/row-->
		<hr>
		<footer>
			<p class="pull-left">&copy; {$smarty.now|formatDate:"Y"} Crane | West Advertising Agency, All Rights Reserved.</p>
			<p class="pull-right">Powered by <strong>cwCMS</strong> v{$cmsVersion}</p>
		</footer>
	</div><!--/.fluid-container-->
	
	<script src="/js/jquery-1.7.1.min.js"></script>
	<script src="/js/bootstrap.js"></script>
	<script src="/js/validationEngine/jquery.validationEngine-en.js"></script>
	<script src="/js/validationEngine/jquery.validationEngine.js"></script>
	<script src="/js/common_admin.js"></script>
	<script>
	$(function(){
		$('.toggleForm').click(function(event) {
			if($('form[name=login]').is(':visible') == true) {
				$('form[name=login]').slideUp();
				$('form[name=forgot-password]').slideDown();
			} else {
				$('form[name=forgot-password]').slideUp();
				$('form[name=login]').slideDown();
			}
			event.preventDefault();
		});

		jQuery("form[name=login]").validationEngine();
		jQuery("form[name=forgot-password]").validationEngine();
		
		{if $smarty.get.error && $smarty.get.state != "password"}$("form[name=login]").effect("shake", { times:1 }, 100);{/if}
	});
	</script>
</body>
</html>