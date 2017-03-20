<?php

class commentContainer
{
public static function render( $comments ) {
  if ( $comments ) {
  	foreach ( $comments as $comment ) {
      var_dump($comment);
  		echo '<p>' . $comment->comment_content . '</p>';
  	}
  } else {
  	echo 'No comments. Start the discussion!';
  }
}
}
?>
