<?php

    class newsAggregatorComments
    {
        // Information needed for creating the plugin's pages
        public function query_comments()
        {
            global $wpdb;
            $querystr = "
          SELECT
            $wpdb->comments.*,
            ROUND(POW((TIMESTAMPDIFF( MINUTE, $wpdb->comments.comment_date_gmt, UTC_TIMESTAMP())/60), 1.8), 2) as karma_divisor,
            (SELECT count(*) FROM wp_commentmeta WHERE comment_id=$wpdb->comments.comment_ID AND meta_key='user_upvote_id') as karma
           FROM $wpdb->comments
            WHERE $wpdb->comments.comment_post_ID=".get_query_var('agg_post_id')."
            ORDER BY  (
                    karma/karma_divisor
                   ) DESC";

            $rankedcomments = $wpdb->get_results($querystr, OBJECT);
            return $rankedcomments;
        }

        public function add_comment_to_post($postID)
        {
        //   if ( !wp_verify_nonce( $_REQUEST['nonce'], "reply_to_post_nonce")) {
        //   exit("No naughty business please");
        // }
          $comment = wp_insert_comment(
            array(
              'comment_parent' => 0,
              'user_id' => get_current_user_id(),
              'comment_parent' => 0,
              'comment_content' => $_REQUEST['reply'],
              'comment_post_ID' => $_REQUEST['post_id'],
              'comment_author' => wp_get_current_user()->display_name,
              'comment_author_email' => wp_get_current_user()->user_email
            )
          );
          global $wpdb;
            $firstvote = $wpdb->insert($wpdb->commentmeta, array("comment_id" => $comment, "meta_key" => "user_upvote_id", "meta_value" => get_current_user_id()),
            array("%d", "%s", "%d"));
          die();
        }

  public function update_comment_karma() {
          if ( !wp_verify_nonce( $_REQUEST['nonce'], "comment_nonce")) {
          exit("No naughty business please");
        }
        global $wpdb;
        $userid = get_current_user_id();
        $comment_id = $_REQUEST["comment_id"];
        $voted = $wpdb->get_var($wpdb->prepare(
          "
            SELECT count(1)
            FROM $wpdb->commentmeta
            WHERE comment_ID=%d
            AND meta_key='user_upvote_id'
            AND meta_value=%d
          ",
          $comment_id,
          $userid
        ));
        if ($voted >= 1 ) {
            $vote = $wpdb->delete($wpdb->commentmeta, array("comment_id" => $comment_id, "meta_key" => "user_upvote_id", "meta_value" => $userid), array("%d", "%s", "%d"));
        } else {
            $vote = $wpdb->insert($wpdb->postmeta, array("comment_id" => $comment_id, "meta_key" => "user_upvote_id", "meta_value" => $userid), array("%d", "%s", "%d"));
        }

        $entry_karma = $wpdb->get_var($wpdb->prepare(
          "
            SELECT count(*)
            FROM $wpdb->commentmeta
            WHERE comment_id=%d
            AND meta_key='user_upvote_id'
          ",
          $post_id
        ));

        if ($voted === false) {
            $result['type'] = "error";
            $result['entry_karma'] = $entry_karma;
            $result['redirect'] = 'wat';
        } else {
            $result['upvoted'] = $upvoted;
            $result['type'] = "success";
            $result['entry_karma'] = $entry_karma;
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $result = json_encode($result);
            echo $result;
        } else {
            header("Location: ".$_SERVER["HTTP_REFERER"]);
        }

        die();
        }

        public function comment_on_comment() {
            if ( !wp_verify_nonce( $_REQUEST['nonce'], "comment_nonce")) {
            exit("No naughty business please");
          }

          $comment = wp_insert_comment(
            array(
              'comment_parent' => 0,
              'user_id' => get_current_user_id(),
              'comment_parent' => $_REQUEST['comment_parent'],
              'comment_content' => $_REQUEST['replyContent'],
              'comment_post_ID' => get_comment( $_REQUEST['comment_parent'] )->comment_post_ID,
              'comment_author' => wp_get_current_user()->display_name,
              'comment_author_email' => wp_get_current_user()->user_email
            )
          );
          global $wpdb;
            $firstvote = $wpdb->insert($wpdb->commentmeta, array("comment_id" => $comment, "meta_key" => "user_upvote_id", "meta_value" => get_current_user_id()),
            array("%d", "%s", "%d"));
          die();
        }
    }
