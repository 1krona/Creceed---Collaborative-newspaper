<header>	
	<nav class="navbar navbar-default">
	  <div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<a href="{{ route('dashboard')}}"><img src="{{URL::to('LogoWithText80x381.jpg')}}" style="height: 80px; width: 381px;"></a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		  <ul class="nav navbar-nav navbar-right">
			  @if(Auth::user())
			<li><a href="{{ route('account')}}">Account</a></li>
			<li><a href="{{ route('logout')}}">Logout</a></li>
			  @else
			  <li><a href="{{ route('login-modal')}}">Sign in</a></li>
			  @endif
		  </ul>
		</div><!-- /.navbar-collapse -->

	  </div><!-- /.container-fluid -->
		<nav class="navbar navbar-default">
			<div class="container-fluid-bottom">
				<!-- Brand and toggle get grouped for better mobile display -->

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li><a href="{{ route('home.type', ['type' => 'News article']) }}">News</a></li>
						<li><a href="{{ route('home.type', ['type' => 'Opinion']) }}">Opinion</a></li>
						<li><a href="{{ route('home.type', ['type' => 'Debunk']) }}">Debunk</a></li>
						<li><a href="{{ route('home.type', ['type' => 'User story']) }}">User stories</a></li>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
	</nav>


</header>