jQuery( function($) {

   $("#agg-container").on( "click", ".upvote_entry", function() {
      voter = $(this)
      post_id = $(this).attr("data-post_id")
      nonce = $(this).attr("data-nonce")

      // if (voter.children('div.agg-vote').hasClass('upvoted')) {
      //   console.log('upvoted');
      //   voter.children('div.agg-vote').removeClass('upvoted')
      // } else {
      //   voter.children('div.agg-vote').addClass('upvoted')
      // }

      $.ajax({
         type : "post",
         dataType : "json",
         url : myAjax.ajaxurl,
         data : {action: "add_entry_karma", post_id : post_id, nonce: nonce},
         success: function(response) {
              voter.bind('click')
            if(response.redirect) {
              // window.alert("you need to login <a href='" + response.redirect + "'>here</a> to comment ")
              // console.log(response.redirect)
              window.location.href = response.redirect;
            }
            if(response.type == "success") {
              console.log('success')
            if (response.upvoted == 1) {
               voter.children('div.agg-vote').removeClass('upvoted')
            } else {
              voter.children('div.agg-vote').addClass('upvoted')
              }
            if (response.entry_karma == 1) {
              voter.next().html(response.entry_karma++)
            } else {
            voter.next().html(response.entry_karma--)
          }
          } else {
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
     $.ajax({
       type: "POST",
       dataType: "html",
       url: myAjax.ajaxurl,
       data: {action: "more_aggregator_posts", ppp: aggregatorPPP, pageNumber: aggregatorPageNumber},
       success: function(data){
         var $data = $(data);
           if($data.length){
               $("#agg-container").append($data);
               $("#more_aggregator_posts").attr("disabled",false);
               $("#more_aggregator_posts").html("Load More Posts")
           } else {
               $("#more_aggregator_posts").html('No More Posts');
               $("#more_aggregator_posts").attr("disabled",true);
           }
       },
       error : function(jqXHR, textStatus, errorThrown) {
           $loader.html(jqXHR + " :: " + textStatus + " :: " + errorThrown);
       }
     });
     return false;
   }

   $('#more_aggregator_posts').click( function() {
     $(this).attr("disabled",true);
     $(this).html("<img src='http://fin.covertnine.com/wp-content/uploads/2018/05/Ellipsis-2s-200px.gif'>")
     load_posts();
   })

//Mention you must be logged in
   $('#post-title').one("focus", function(){
     if ( !myAjax.loggedIn){
       $(this).before("<div>You must be <a href='" + myAjax.loginPage + "' class='must-be-logged-in'>logged in</a> to post</div>");
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
      var voter = $(this)
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
                voter.children('div.agg-vote').removeClass('upvoted')
             } else {
                voter.children('div.agg-vote').addClass('upvoted')
             }
           }
          }
       })

    });

////////////////////////////////*  Code for turning Checkbox into Toggle #  */////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$("#new-post-url").change(function() {
        if (!/^https?:\/\//.test(this.value)) {
            this.value = "https://" + this.value;
        }
    });

////////////////////////////////*  Code for turning Checkbox into Toggle #  */////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $('#link-toggle').lc_switch('Text', 'URL');

         // triggered each time a field is checked
      $('body').delegate('.lcs_check', 'lcs-on', function() {
          $('.new-post-url').hide();
          $('.new-post-textarea').show();
          $('#new-post-url').removeAttr('required');
          $('#new-post-textarea').attr('required', true);
        });
  

    // triggered each time a field is unchecked
    $('body').delegate('.lcs_check', 'lcs-off', function() {
      $('.new-post-url').show();
      $('.new-post-textarea').hide();
      $('#new-post-textarea').removeAttr('required');
      $('#new-post-url').attr('required', true);
  });

});
