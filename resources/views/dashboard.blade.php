@extends('layouts.master')

@section('content')
	@include('includes.message-block')

	<section class="row new-post">
		@include('includes.draft-sidebar')
		<div class="col-md-6 ">
			<header> 
				<h3>What do you have to say?</h3>
			</header>

			<form action="{{ route('post.create') }}" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label for="title">Title</label>
					<input type="text" name="title" class="form-control" placeholder="Your title" id="title" value="@if($draft_id){{ Auth::user()->posts()->where('id', $draft_id)->first()->title }}@endif">
				</div>
				<div class="form-group">
					<textarea class="form-control" name="body" id="new-post" rows="15" placeholder="Your Post">@if($draft_id){{ Auth::user()->posts()->where('id', $draft_id)->first()->body }}@endif</textarea>
				</div>
				<div class="form-group col-sm-6">
					<label for="image">Upload image</label>
					<input type="file" name="image" class="form-control" id="image">
				</div>
				<div class="form-group col-sm-6">
					<label for="video_link">Video link</label>
					<input type="text" name="video_link" class="form-control" placeholder="https://youtube.com/example-video" value="@if($draft_id){{ "https://www.youtube.com/watch?v=" . Auth::user()->posts()->where('id', $draft_id)->first()->video_link }}@endif">
				</div>

				<div class="form-group"> <!-- blade if statements which adds bootstrap class 'has-error' if field has error -->
					<label for="category">Categories</label>
					<input class="form-control" type="text" name="categories" id="categories" placeholder="Categories, separate using comma." value="">
				</div>
				<div>
					<label for="type">Article type</label>
					<select name="type" class="form-control" id="type">
						<option value="News article">News article</option>
						<option value="Opinion">Opinion</option>
						<option value="Debunk">Debunk</option>
						<option value="User story">User story</option>

					</select>
				</div>



				<span class="add-sources">Add sources</span>

				<!-- Hide until Click -->
				<div class="add-sources-interaction" style="display: none;">
					<div class="form-group">
						<label for="source-1">Sources</label>
						<input type="text" name="source-1" class="form-control" placeholder="Source #1" id="source-1">
						<input type="text" name="source-2" class="form-control" placeholder="Source #2" id="source-2">
						<input type="text" name="source-3" class="form-control" placeholder="Source #3" id="source-3">
					</div>
				</div>
				<br>


				<button type="submit" class="btn btn-primary" name="publish" value="1">Create Post</button>
				<button type="submit" class="btn btn-primary btn-draft" name="publish" value="0">Save Draft</button>


				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				<!-- Sends $draft_id to controller if it's NULL it'll save the post instead of update -->
				<input type="hidden" name="draft_id" value="{{$draft_id}}">
			</form>

		</div>
	</section>

	<div class="col-md-offset-2">
	@include('includes.newsfeed')
	</div>
@endsection