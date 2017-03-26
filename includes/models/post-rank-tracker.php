<?php

    class postRankTracker
    {
        // Information needed for creating the plugin's pages
        public function update_karma()
        {
            if (!wp_verify_nonce($_REQUEST['nonce'], "aggregator_karma_nonce")) {
                exit("No naughty business please");
            }
            global $wpdb;
            $userid = get_current_user_id();
            $post_id = $_REQUEST["post_id"];
            $upvoted = $wpdb->get_var($wpdb->prepare(
              "
                SELECT count(1)
                FROM $wpdb->postmeta
                WHERE post_id=%d
                AND meta_key='user_upvote_id'
                AND meta_value=%d
              ",
              $post_id,
              $userid
            ));
            if ($upvoted >= 1) {
                $vote = $wpdb->delete($wpdb->postmeta, array("post_id" => $post_id, "meta_key" => "user_upvote_id", "meta_value" => $userid), array("%d", "%s", "%d"));
            } else {
                $vote = $wpdb->insert($wpdb->postmeta, array("post_id" => $post_id, "meta_key" => "user_upvote_id", "meta_value" => $userid), array("%d", "%s", "%d"));
            }

            $entry_karma = $wpdb->get_var($wpdb->prepare(
              "
                SELECT count(*)
                FROM $wpdb->postmeta
                WHERE post_id=%d
                AND meta_key='user_upvote_id'
              ",
              $post_id
            ));

            if ($vote === false) {
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

        public static function update_post_heat() {
          global $wpdb;
          $entry_karma = $wpdb->get_var($wpdb->prepare(
            "
              SELECT count(*)
              FROM $wpdb->postmeta
              WHERE post_id=%d
              AND meta_key='user_upvote_id'
            ",
            $post_id
          ));
        }
    }
