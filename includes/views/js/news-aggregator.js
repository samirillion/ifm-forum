window.onload = function(){

  }
jQuery(document).ready( function($) {

   jQuery("#aggregator-container").on( "click", ".upvote_entry", function() {
      upvoter = jQuery(this)
      post_id = jQuery(this).attr("data-post_id")
      nonce = jQuery(this).attr("data-nonce")

      jQuery.ajax({
         type : "post",
         dataType : "json",
         url : myAjax.ajaxurl,
         data : {action: "add_entry_karma", post_id : post_id, nonce: nonce},
         success: function(response) {
            if(response.redirect) {
              window.location.href = response.redirect;
            }
            if(response.type == "success") {
              upvoter.toggleClass('bottom-left bottom-right')
            if (response.upvoted == 1) {
              upvoter.html('++')
            } else {
              upvoter.html('unvote')
              }
            if (response.entry_karma == 1) {
              upvoter.prev().html(response.entry_karma + " point")
            } else {
            upvoter.prev().html(response.entry_karma + " points")
          }
          }
         }
      })

   });

//'paging' function for posts
   var aggregatorPPP = 9;
   var aggregatorPageNumber = 1;
   $loader = $("#aggregator-container");
   function load_posts(){
     aggregatorPageNumber++;
     jQuery.ajax({
       type: "POST",
       dataType: "html",
       url: myAjax.ajaxurl,
       data: {action: "more_aggregator_posts", ppp: aggregatorPPP, pageNumber: aggregatorPageNumber},
       success: function(data){
         var $data = jQuery(data);
           if(data.length){
               jQuery("#aggregator-container").append($data);
               jQuery("#more_aggregator_posts").attr("disabled",false);
           } else {
               jQuery("#more_aggregator_posts").html('No More Posts');
               jQuery("#more_aggregator_posts").attr("disabled",true);
           }
       },
       error : function(jqXHR, textStatus, errorThrown) {
           $loader.html(jqXHR + " :: " + textStatus + " :: " + errorThrown);
       }
     });
     return false;
   }

   jQuery('#more_aggregator_posts').click( function() {
     jQuery("#more_aggregator_posts").attr("disabled",true);
     load_posts();
   })

//Mention you must be logged in
   $('#post-title').one("focus", function(){
     if ( !myAjax.loggedIn){
       $(this).before("<div>You must be <a href='" + myAjax.loginPage + "'>logged in</a> to post</div>");
     }
   })

//Script for replying to post
  $('#comment-text-area').one("focus", function(){
    if ( !myAjax.loggedIn){
      $(this).before("<div>You must be <a href='" + myAjax.loginPage + "'>logged in</a> to comment</div>");
    }
  })
  $('.reply-to-comment').one("click", function(){
    if ( !myAjax.loggedIn){
      $(this).before("<div>You must be <a href='" + myAjax.loginPage + "'>logged in</a> to comment</div>");
    }
  })
   var replyToPost = $("#reply-to-post");
   replyToPost.submit(function(e) {
    //  e.preventDefault();

       // Serialize the form data.
       var formData = $(replyToPost).serialize();

       // Submit the form using AJAX.
       $.ajax({
         type: 'POST',
         url: myAjax.ajaxurl,
         data: formData
       })
       .done(function(response) {
        window.location.reload(true);
});
     });

// script for replying to a comment
     $(".comment-node .reply-to-comment").on( "click", function() {
       $(this).next().toggle();
      });

      $('.submit-reply').on('click', function(e) {
           e.preventDefault();
          //  // information to be sent to the server
           var content = $(this).prev().val();
           var containingli = $(this).closest("li");
           var parentCommentID = containingli.attr('id');
           var nonce = containingli.attr('data-nonce');
           $.ajax({
        type: "POST",
        url: myAjax.ajaxurl,
        data: {replyContent: content, comment_parent: parentCommentID, nonce: nonce, action: "reply_to_comment" },
        success: function(response){
          if(response.redirect) {
            window.location.href = response.redirect;
          } else {
          window.location.reload(true);
        }
        }
         });
       });

 //Script for voting on a comment
    $(".vote_on_comment").on( "click", function(e) {
       e.preventDefault();
      var upvoter = $(this)
      var comment_id = $(this).closest("li").attr('id')
      var nonce = $(this).closest("li").attr('data-nonce')
      var upordown = $(this).attr("data-upordown")
       $.ajax({
          type : "post",
          dataType : "json",
          url : myAjax.ajaxurl,
          data : {
            action: "vote_on_comment",
            comment_id : comment_id,
            nonce: nonce,
            upordown: upordown
          },
          success: function(response) {
             if(response.redirect) {
               window.location.href = response.redirect;
             }
             if(response.type == "success") {
               if (response.upvoted == 0) {
               upvoter.html('++')
             } else {
               upvoter.html('unvote')
             }
           }
          }
       })

    });


//    $( ".aggregator-entry-link" ).each( function( ) {
//
//     var $quote = $(this);
//
//     var $numChars = $quote.text().length;
//     // $(this).html($numChars);
//
//     if (($numChars > 0) && ($numChars < 25)) {
//         $quote.css("font-size", "1.8em");
//     }
//     else if (($numChars >= 25) && ($numChars < 50)) {
//         $quote.css("font-size", "1.6em");
//     }
//     else if (($numChars >= 50) && ($numChars < 75)) {
//         $quote.css("font-size", "1.4em");
//     }
//     else if (($numChars >= 75 && ($numChars < 100))) {
//         $quote.css("font-size", "1.3em");
//     }   else {
//           $quote.css("font-size", "1.2em");
//       }
//
// });
//
// $('#aggregator-container').masonry({
//   // options
//   itemSelector: '.aggregator-entry-wrapper',
// });

});
