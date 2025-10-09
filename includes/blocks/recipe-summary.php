<?php

function up_recipe_summary_render_cb($attributes, $content, $block){

    $prepTime = isset($attributes['prepTime']) ? esc_html($attributes['prepTime']) : ''; // Sanitize prepTime attribute
    $cookTime = isset($attributes['cookTime']) ? esc_html($attributes['cookTime']) : ''; // Sanitize cookTime attribute
    $course = isset($attributes['course']) ? esc_html($attributes['course']) : ''; // Sanitize course attribute

    $postID = $block->context['postId']; // Get the current post ID from the block context
    $postTerms = get_the_terms($postID, 'cuisine'); //
    $postTerms = is_array($postTerms) ? $postTerms : []; // Ensure it's an array
    $cuisines = ''; // Initialize cuisine variable
    $lastKey = array_key_last($postTerms); // Get the last key of the array for comma placement

    // Loop through terms to create links
    foreach($postTerms as $key => $term) {
        $url = esc_url(get_term_meta($term->term_id, 'more_info_url', true)); // Get the URL from term meta and sanitize it
        $comma = $key === $lastKey ? '' : ', '; 

        $cuisines .= "<a href='{$url}' target='_blank'>{$term->name}</a>{$comma}";
    } 

    $rating = get_post_meta($postID, 'recipe_rating', true); // Get the recipe rating from post meta
    if ($rating === '' || $rating === false) {
        $rating = 0;
    } // Default to 0 if no rating found

    global $wpdb; // Access the database
    $userID = get_current_user_id(); // Get the current user ID

    // Check the number of ratings for this post
    $ratingCount = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->prefix}recipe_rating 
        WHERE post_id = %d", $postID
    )); //AND user_id = %d , $userID


    ob_start(); // Start output buffering
    ?>
    <div class="wp-block-udemy-plus-recipe-summary">
        <i class="bi bi-alarm"></i>
        <div class="recipe-columns-2">
            <div class="recipe-metadata">
            <div class="recipe-title">
                <?php _e('Prep Time', 'udemy-plus'); ?> 
            </div>
            <div class="recipe-data recipe-prep-time">
                <?php echo $prepTime; ?>
            </div>
            </div>
            <div class="recipe-metadata">
            <div class="recipe-title">
                <?php _e('Cook Time', 'udemy-plus'); ?>
            </div>
            <div class="recipe-data recipe-cook-time">
                <?php echo $cookTime; ?>
            </div>
            </div>
        </div>
        <div class="recipe-columns-2-alt">
            <div class="recipe-columns-2">
            <div class="recipe-metadata">
                <div class="recipe-title">
                <?php _e('Course', 'udemy-plus'); ?>
                </div>
                <div class="recipe-data recipe-course">
                <?php echo $course; ?>
                </div>
            </div>
            <div class="recipe-metadata">
                <div class="recipe-title">
                <?php _e('Cuisine', 'udemy-plus'); ?>
                </div>
                <div class="recipe-data recipe-cuisine">
                <?php echo $cuisines; ?>
                </div>
            </div>
            <i class="bi bi-egg-fried"></i>
            </div>
            <div class="recipe-metadata">
            <div class="recipe-title">
                <?php _e('Rating', 'udemy-plus'); ?>
            </div>
            <div class="recipe_data" 
                id="recipe-rating"
                data-post-id="<?php echo $postID; ?>"
                data-avg-rating="<?php echo $rating ?>"
                data-logged-in="<?php echo is_user_logged_in(); ?>"
                data-rating-count="<?php echo $ratingCount; ?>"
            ></div>
            <i class="bi bi-hand-thumbs-up"></i>
            </div>
        </div>
    </div>
    <?php // End of HTML output

    $output = ob_get_contents(); // Get the contents of the output buffer
    ob_end_clean(); // End output buffering and clean up

    return $output; // Return the generated HTML
}