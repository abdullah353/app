<!doctype html>
<html>
<head>
	<title>Look at me Login</title>
</head>
<body>
	<!-- To display the flash message, I've first used a Blade if statement to check if we have a flash message to display. Our flash message will be available in the Session under message. So we can use the Session::has() method to check for that message. If that evaluates to true, we create a paragraph with the Twitter bootstrap class of alert and we call the Session::get() method to display the message's value. -->	
	<div class="container">
		@if(Session::has('message'))
			<p class="alert">{{ Session::get('message') }}</p>
		@endif
	</div>

	{{ Form::open(array('url'=>'users/signin')) }}
		<h1>Login</h1>

	<!-- if there are login errors, show them here -->
	@if (Session::get('loginError'))
	<div class="alert alert-danger">{{ Session::get('loginError') }}</div>
	@endif

	<p>
		{{ $errors->first('email') }}
		{{ $errors->first('password') }}
	</p>

	<p>
		{{ Form::label('email', 'Email Address') }}
		{{ Form::text('email', Input::old('email'), array('placeholder' => 'awesome@awesome.com')) }}
	</p>

	<p>
	{{ Form::label('password', 'Password') }}
	{{ Form::password('password') }}
	</p>

	<p>{{ Form::submit('Submit!') }}</p>
{{ Form::close() }}

</body>
</html>