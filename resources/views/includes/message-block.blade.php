@if(count($errors) > 0) <!-- $errors is a variable which always gets passed when validate method is used in controllers -->
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="alert alert-danger">
					<ul>
						@foreach($errors->all() as $error)
							<li>{{$error}}</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
@endif
@if(Session::has('message')) <!-- Laravel helper function "has" looks for message which we defined in PostController -->
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="alert alert-success">
					{{ Session::get('message') }}
				</div>
			</div>
		</div>
@endif