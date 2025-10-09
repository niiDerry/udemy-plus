<?php

function up_popular_recipes_render_cb($attributes){
    $title = esc_html($attributes['title']);
    $cuisineIDs = array_map(function($term){
        return $term['id'];

    }, $attributes['cuisines']);

    $arguments = [
        'post_type' => 'recipe',
        'post_per_page' => $attributes['count'],
        'orderby' => 'meta_value_num',
        'meta_key' => 'recipe_rating',
        'order' => 'DESC'
    ];

    if(!empty($cuisineIDs)){
        $arguments['tax_query'] = [
            [
                'taxonomy' => 'cuisine',
                'field' => 'term_id',
                'terms' => $cuisineIDs
            ]
        ];
    }

    $query = new WP_Query($arguments);


    ob_start();
    ?>
        <div class="wp-block-udemy-plus-popular-recipes">
            <h6><?php echo $title ?></h6>
            <?php
            if($query->have_posts()){
                while($query->have_posts()){
                    $query->the_post();
                    ?>
                    <div class="single-post">
                        <a href="<?php the_permalink(); ?>" class="single-post-image">
                            <?php the_post_thumbnail('thumbnail'); ?>
                        </a>
                        <div class="single-post-detail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                            <span>
                                By
                                <a href="<?php the_permalink(); ?>"> 
                                    <?php the_author(); ?>
                                </a>
                            </span>
                        </div>
                    </div>
                    <?php
                }
            }   
            ?>
        </div>
    <?php

    wp_reset_postdata();
  
    $output = ob_get_contents();
    ob_end_clean();
  
    return $output;
}