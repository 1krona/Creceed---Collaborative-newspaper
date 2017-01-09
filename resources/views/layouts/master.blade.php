<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title')</title>

       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" href="{{ URL::to('src/css/main.css') }}"> <!--Gets absolute path -->


	</head>
    <body>
		@include('includes.header')
		<div class="container">
			@yield('content')
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>﻿
		<script src="{{ URL::to('src/js/app.js?v=' . rand(1, 109)) }}"></script>
	</body>
</html>