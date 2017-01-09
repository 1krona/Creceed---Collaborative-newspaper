// jQuery is basically just DOM-shit(Get element by class, id, parentNode,childNode[#]), event-listening and jQuery-internal functions like AJAX, not that hard really.

var postId = 0;
var postBodyElement = null;

$('.post').find('.interaction').find('.edit').on('click', function(event){
	event.preventDefault();
	
	postBodyElement = event.target.parentNode.parentNode.childNodes[1];
	var postBody = postBodyElement.textContent; // event is argument passed through, target find .post, childNodes[1] find index 1
	postId = event.target.parentNode.parentNode.dataset['postid']; //access data-postid element within dashboard
	
	
	$('#post-body').val(postBody); //Sets post-body textarea value to var postBody					
	$('#edit-modal').modal(); // Calls modal function within jQuery
}); 

$('#modal-save').on('click', function() {
	$.ajax({   //ajax function within jQuery, url and token gets specified within the dashboard.blade.php page.
		method: 'POST',
		url: urlEdit,
		data: { body:$('#post-body').val(), postId: postId, _token: token} //Data from textarea
	}) 
	.done(function (msg){
		$(postBodyElement).text(msg['new_body']); //How is msg and postController return connected?
		$('#edit-modal').modal('hide');
	});
});

$('.like').on('click', function(event){
	event.preventDefault();
	var el = $(this);
	var postId = +el.parents('.post').data('postid').trim();
	var isLike = +el.data('value') === 1;


	$.ajax({
		method: 'POST',
		url: urlLike,
		data: {isLike: isLike, postId: postId, _token: token}
	})
	.done(function(){
		//Change the page when .ajax has been executed.
		el.text(isLike ? el.text() == 'Like' ? 'You like this post' : 'Like' : el.text() == 'Dislike' ? 'You don\'t like this post' : 'Dislike');
        el.addClass('active');
		//Make sure you can't dislike and like at the same time.
		if(isLike){
			el.next().text('Dislike');
            el.next().removeClass('active');
		} else {
			 el.prev().text('Like');
            el.prev().removeClass('active');
		}
	});
});

/* Opens subcomment-interaction */

$('.subcomment').click(function(){
	$('.subcomment-interaction').slideToggle("fast");
});


/* Opens add-sources-interaction */
$('.add-sources').click(function(){
	$('.add-sources-interaction').slideToggle("fast");
});

$('.show-sources').click(function(){
	$('.show-sources-interaction').slideToggle("fast");
});

/* LIKE SOURCE */

$('.like-source').on('click', function(event){
	event.preventDefault();
	sourceId = event.target.parentNode.dataset['sourceid'];
	postId = event.target.parentNode.parentNode.parentNode.parentNode.dataset['postid'];
	var isLike = event.target.previousElementSibling == null; //Checks if it's a like or dislike.

	$.ajax({
		method: 'POST',
		url: urlLikeSource,
		data: {isLike: isLike, sourceId: sourceId, postId: postId, _token: token}
	})

		.done(function(){
			//Change the page when .ajax has been executed.
			event.target.innerText = isLike ? event.target.innerText == 'Like' ? 'You like this source' : 'Like' : event.target.innerText == 'Dislike' ? 'You don\'t like this source' : 'Dislike';

			//Make sure you can't dislike and like at the same time.
			if(isLike){
				event.target.nextElementSibling.innerText = 'Dislike';
			} else {
				event.target.previousElementSibling.innerText = 'Like';
			}
		});
});