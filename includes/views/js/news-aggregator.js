jQuery(document).ready( function() {

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
            if(response.type == "success") {
              if (response.upvoted == 0) {
              upvoter.html('unvote')
            } else {
              upvoter.html('++')
            }
            if (response.entry_karma == 1) {
              upvoter.prev().html(response.entry_karma + " point")
            } else {
            upvoter.prev().html(response.entry_karma + " points")
          }
          }
            else {
               alert(response.redirect)
            }
         }
      })

   });

   var aggregatorPPP = 9;
   var aggregatorPageNumber = 1;
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
           } else{
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

});
