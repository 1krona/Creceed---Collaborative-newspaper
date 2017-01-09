<section class="row posts">
    <div class="col-md-6 col-md-offset-3">
        <header><h3>Whatever people say...</h3></header>

        @foreach($posts as $post)
                <!-- data-postid sets customized attribute, in this case to identify each post -->
        <article class="post" data-postid=" {{ $post->id }} ">
            <h2><a href="{{ URL::to('posts/' . $post->id) }}">{{ $post->title }}</a></h2>
            @if ($post->image)
                <img src="{{URL::to('images/' . $post->image)}}">
            @endif
            <p>{{ $post->body }}</p>

            <!-- SOURCES -->
            <div class="sources">
                <span class="show-sources">Show sources</span>
                </br>
                <b>Credibility score: {{$post->likes->where('post_id', $post->id)->filter(function ($item) {
                        return $item['source_id'] !== null;
                    })->avg('like') * 10}}
                </b>
                <div class="show-sources-interaction" style="display: none;">

                    @foreach($post->sources as $source)
                    <a>{{$source['link']}}</a>


                        @if (Auth::user())
                    <div class="interaction" data-sourceid=" {{ $source['id'] }} ">
                        <a href="#" class="like-source">{{ Auth::user()->likes()->where('source_id', $source['id'])->first() ? Auth::user()->likes()->where('source_id', $source['id'])->first()->like == 1 ? 'You like this source' : 'Like' : 'Like' }}</a> |
                        <a href="#" class="like-source">{{ Auth::user()->likes()->where('source_id', $source['id'])->first() ? Auth::user()->likes()->where('source_id', $source['id'])->first()->like == -1 ? 'You don\'t like this source' : 'Dislike' : 'Dislike' }}</a>


                    </div>
                        </br>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="info">
                Posted by {{$post->user->first_name}}  on {{ $post->created_at }}
            </div>
            <div class="score">
               <b>Attention score: {{$post->likes->filter(function ($item) {
                        return $item['source_id'] == null;
                    })->sum('like')}}</b>
            </div>

            @if (Auth::user())
            <div class="interaction">

                <a href="#" class="like">{{ Auth::user()->likes()->where('post_id', $post->id)->first() ? Auth::user()->likes()->where('post_id', $post->id)->first()->like == 1 ? 'You like this post' : 'Like' : 'Like' }}</a> |
                <a href="#" class="like">{{ Auth::user()->likes()->where('post_id', $post->id)->first() ? Auth::user()->likes()->where('post_id', $post->id)->first()->like == -1 ? 'You don\'t like this post' : 'Dislike' : 'Dislike' }}</a>
                @if(Auth::user() == $post->user) <!-- Auth helper function -->
                |
                <a href="#" class="edit">Edit</a> |
                <a href="{{ route('post.delete', ['post_id' => $post->id]) }}">Delete</a>
                @endif
            </div>
            <div class="comment">
                <form action="{{ route('comment.create') }}" method="post">
                    <div class="form-group">
                        <textarea class="form-control" name="body" id="new-comment" rows="1" placeholder="Your Comment"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Comment</button>
                    <input type="hidden" name="postId" value="{{ $post->id }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>

                @foreach($post->comments as $comment)
                <div class="comment-body">

                    <p>{{$comment['body']}}</p>

                    <!-- NO LOGIC SET UP -->
                    <div class="interaction">
                        <a href="#" class="like">{{ Auth::user()->likes()->where('post_id', $post->id)->first() ? Auth::user()->likes()->where('post_id', $post->id)->first()->like == 1 ? 'You like this post' : 'Like' : 'Like' }}</a> |
                        <a href="#" class="like">{{ Auth::user()->likes()->where('post_id', $post->id)->first() ? Auth::user()->likes()->where('post_id', $post->id)->first()->like == 0 ? 'You don\'t like this post' : 'Dislike' : 'Dislike' }}</a>
                        @if(Auth::user())

                        @endif
                        @if(Auth::user() == $post->user) <!-- Auth helper function -->
                        |
                        <a href="#" class="edit">Edit</a> |
                        <a href="{{ route('post.delete', ['post_id' => $post->id]) }}">Delete</a>
                        @endif

                        <span class="subcomment">Comment</span>

                        <!-- Display none Until onClick -->
                        <div class="subcomment-interaction" style="display: none;">
                            <form action="{{ route('comment.create') }}" method="post">
                                <div class="form-group">
                                    <textarea class="form-control" name="body" id="new-comment" rows="1" placeholder="Your Comment"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Create Comment</button>
                                <input type="hidden" name="postId" value="{{ $post->id }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </form>
                        </div>
                    </div>

                </div>
                @endforeach

            </div>

            @endif
        </article>

    @endforeach
    </div>

</section>

<div class="modal fade" tabindex="-1" role="dialog" id="edit-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit post</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="post-body">Edit the post</label>
                        <textarea class="form-control" name="post-body" id="post-body" rows="5"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="modal-save">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    var token = '{{ csrf_token() }}';
    var urlEdit = '{{ route('edit') }}';
    var urlLike = '{{ route('like') }}';
    var urlLikeSource = '{{ route('likesource') }}';
</script>
