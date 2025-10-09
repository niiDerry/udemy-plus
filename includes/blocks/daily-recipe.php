<?php

function up_daily_recipe_cb($attributes){
    $title = esc_html($attributes['title']);
    $id = get_transient('up_daily_recipe_id');

    if(!$id) {
        $id = up_generate_daily_recipe();
    }

    ob_start();
    ?>
    <div class="wp-block-udemy-plus-daily-recipe">
        <h6><?php echo $title ?></h6>
        <a href= <?php the_permalink($id ) ?>>
            <img src=<?php echo get_the_post_thumbnail( $id, 'full' ) ?> >
            <h3><?php get_the_title( $id )?></h3>
        </a>
    </div>
  
    <?php
  
    $output = ob_get_contents();
    ob_end_clean();
  
    return $output;
}