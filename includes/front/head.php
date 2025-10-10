<?php 

function up_wp_head() {
  $options = get_option('up_options');

  if($options['enable_og'] != 1) {
    return; // Exit if Open Graph meta tags are disabled
  }

  $title = $options['og_title'];
  $description = $options['og_description'];
  $image = $options['og_img'];
  $url = site_url( '/' );

  if(is_single( )){
    $postID = get_the_id();

    $newTitle = get_post_meta($postID, 'og_title', true); // Get the custom title from post meta
    $title = empty($newTitle) ? $title : $newTitle; // If a custom title is set in the post meta, use it instead

    $newDescription = get_post_meta($postID, 'og_description', true); // Get the custom Description from post meta
    $description = empty($newDescription) ? $description : $newDescription;  // If a custom description is set in the post meta, use it instead

    $overrideImage = get_post_meta($postID, 'og_override_image', true); // Get the custom image override from post meta
    $image = $overrideImage ? 
      get_post_meta( $postID, 'og_image', true ) : 
      get_the_post_thumbnail_url( 'opengraph' ); // If a custom image override is set in the post meta, use it instead

    $url = get_permalink($postID); // Get the URL of the current page
  }

  ?>
    <meta property = "og:title"
      content = "<?php echo esc_attr($title); ?>" /> 
    />
    <meta property = "og:description"
      content = "<?php echo esc_attr($description); ?>" /> 
    />
    <meta property = "og:image"
      content = "<?php echo esc_attr($image); ?>" /> 
    />

    <meta property = "og:type" content="website" />
    <meta property = "og:url" content="<?php echo esc_attr($url); ?>" />
  <?php
}