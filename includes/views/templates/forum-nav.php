<nav class="agg-nav">
  <ul class="agg-post-types"><?php 
     $customterms =  get_terms( array(
                              'taxonomy' => 'aggpost-type',
                              'hide_empty' => false,
                                )
                              );
                              // var_dump($customterms);
      foreach ( $customterms as $term) {
         echo "<li class='aggpost-type-nav-item'><a href='#'>" .$term->{'name'} . "</li>";
      };
  ?><li class="agg-submit-post">
      <a href="/new-post">Submit New Post</a>
    </li><?php
    if (is_user_logged_in()) {
      $current_user_id = get_current_user_id();
    ?>
 <?php } ?>
  </ul>
</nav>