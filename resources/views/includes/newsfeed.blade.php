<section class="row posts">
    <div class="col-sm-8">
        <header><h4 style="color: #444;">Top stories...</h4></header>

        @foreach($posts->where('published', 1) as $post)
                <!-- data-postid sets customized attribute, in this case to identify each post -->
        <article class="post" data-postid=" {{ $post->id }} ">
            <h2><a class="post-title" href="{{ URL::to('posts/' . $post->id) }}">{{ $post->title }}</a></h2>

            @if($post->video_link)
                <iframe width="560" height="315" src="http://www.youtube.com/embed/{{$post->video_link}}" frameborder="0" allowfullscreen></iframe>
                <br>
            @else
                @if ($post->image)
                    <img class="post-image" src="{{URL::to('images/' . $post->image)}}">
                @endif
                <p class="post-body">{{ str_limit($post->body, 350) }}</p>
                <br>
            @endif

            <!-- INTERACTION-->
                    <a href="{{ URL::to('posts/' . $post->id) }}" style="color: #00B1B3;">Read more...</a>
            <button type="button" class="btn btn-default" aria-label="Left Align">
                    <span class="glyphicon glyphicon-circle-arrow-up" aria-hidden="true">
                    </span>
                <b> | {{$post->likes->filter(function ($item) { return $item['source_id'] == null; })->sum('like')}}</b>
            </button>
            <button type="button" class="btn btn-default" aria-label="Left Align">
                <span class="glyphicon glyphicon-circle-arrow-down" aria-hidden="true"></span>
            </button>
            <button class="btn btn-primary">Comment</button>
            <button class="btn btn-primary btn-sources">
                Credibility |
                <b> {{round($post->likes->where('post_id', $post->id)->filter(function ($item) { return $item['source_id'] !== null; })->avg('like') * 10)}}</b>
            </button>

            @if (Auth::user())
                <div class="interaction">

                    <a href="#" class="like" data-value="1" style="color: #00B1B3;">{{ Auth::user()->likes()->where('post_id', $post->id)->first() ? Auth::user()->likes()->where('post_id', $post->id)->first()->like == 1 ? 'You like this post' : 'Like' : 'Like' }}</a> |
                    <a href="#" class="like" data-value="-1" style="color: #00B1B3;">{{ Auth::user()->likes()->where('post_id', $post->id)->first() ? Auth::user()->likes()->where('post_id', $post->id)->first()->like == -1 ? 'You don\'t like this post' : 'Dislike' : 'Dislike' }}</a>
                </div>
            @endif


        </article>

    @endforeach
    </div>

</section>



<script>
    var token = '{{ csrf_token() }}';
    var urlLike = '{{ route('like') }}';
    var urlLikeSource = '{{ route('likesource') }}';
</script>
