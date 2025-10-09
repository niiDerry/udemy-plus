<?php
//Add endpoint for the rating
function up_rest_api_add_rating_handler($request){
  $response = ['status' => 1];
  $parameters = $request->get_json_params();

  //Check if parameters are set
  if(!isset($parameters['rating'], $parameters['postID']) ||
      empty($parameters['rating']) ||
      empty($parameters['postID'])
    ) {
      return $response;
  }

  $rating = round(floatval($parameters['rating']), 1); //Round to one decimal place
  $postID = absint($parameters['postID']); //Make sure it's a positive integer
  $userID = get_current_user_id(); //Get the current user ID

  global $wpdb; //Get access to the database
  $wpdb->get_results($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}recipe_rating
    WHERE post_id = %d AND user_id = %d",
    $postID, $userID
  )); //Check if the user has already rated this post

  //If the user has already rated this post, return status 1
  if($wpdb-> num_rows > 0){
    return $response;
  }

  //Insert the rating into the database
  $wpdb->insert(
    "{$wpdb->prefix}recipe_rating",
    [
      "post_id" => $postID, 
      "user_id" => $userID,
      "rating" => $rating,
    ],
    [ "%d", "%d", "%f" ]
  );

  //Get the new average rating
  $avgRating = round($wpdb->get_var(
    $wpdb->prepare(
      "SELECT AVG(`rating`) FROM {$wpdb->prefix}recipe_rating 
      WHERE post_id = %d", $postID
    )), 1);

  //Update the post meta with the new average rating
  update_post_meta($postID, 'recipe_rating', $avgRating); 

  do_action('recipe_rated', [
    'post_id' => $postID,
    'user_id' => $userID,
    'rating' => $rating,
  ]); //Trigger action for other plugins to hook into


  //If the insert was successful, return status 2
  $response['status'] = 2;
  $response['rating'] = $avgRating;
  return $response;
}