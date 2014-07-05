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

{{ Form::open(array('url'=>'users/create', 'class'=>'form-signup')) }}
<h2 class="form-signup-heading">Please Register</h2>

<ul>
@foreach($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>

{{ Form::text('firstname', null, array('class'=>'input-block-level', 'placeholder'=>'First Name')) }}
{{ Form::text('lastname', null, array('class'=>'input-block-level', 'placeholder'=>'Last Name')) }}
{{ Form::text('email', null, array('class'=>'input-block-level', 'placeholder'=>'Email Address')) }}
{{ Form::password('password', array('class'=>'input-block-level', 'placeholder'=>'Password')) }}
{{ Form::password('password_confirmation', array('class'=>'input-block-level', 'placeholder'=>'Confirm Password')) }}

{{ Form::submit('Register', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}

</body>
</html>