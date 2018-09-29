<?php 
        if (get_query_var('aggpost_tax') !== "") { 
          $agg_query_var = get_query_var('aggpost_tax');
          $agg_all_active = "";
        } else {
          $agg_query_var = "";
          $agg_all_active = "active";
        }
?>
<nav class="agg-nav">
  <ul class="agg-post-types">
    <li class="aggpost-type-nav-item <?php echo $agg_all_active ?>">
      <a href="/fin-forum">
        all
      </a>
    </li><?php
     $customterms =  get_terms( array(
                              'taxonomy' => 'aggpost-type',
                              'hide_empty' => false,
                                )
                              );
                              // var_dump($customterms);
      foreach ( $customterms as $term) {
          if ($term->{'slug'} === $agg_query_var) { 
            $activeClass = "active";
          } else { 
            $activeClass = "";
          }
         echo "<li class='aggpost-type-nav-item ".$activeClass."'><a href='". add_query_arg( 'aggpost_tax', $term->{'slug'}, home_url('fin-forum') ) ."'>" .$term->{'name'} . "</li>";
      }
  ?><li class="agg-submit-post">
      <a href="/new-post">Submit New Post</a>
    </li><?php
    if (is_user_logged_in()) {
      $current_user_id = get_current_user_id();
    ?>
 <?php } ?>
  </ul>
  <form role="search" method="get" class="agg-searchform" action="agg-search">
    <div class="agg-search-wrapper">
    <label class="screen-reader-text" for="s">Search for:</label>
    <input type="text" placeholder="search fin" name="agg-query" class="agg-query-input" />
    <input type="submit" class="agg-search-submit" value="Search" />
    </div>
  </form>


</nav>