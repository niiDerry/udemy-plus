<?php

function up_save_post_recipe($postID){

  if(defined('DOING_AUTOSAVE')&& DOING_AUTOSAVE) {
    return;
  }

  $rating = get_post_meta($postID, 'recipe_rating', true); //true = single value
  $rating = empty($rating) ? 0 : floatval($rating); //if empty, set to 0, else convert to float

  update_post_meta($postID, 'recipe_rating', $rating); //save the rating
}