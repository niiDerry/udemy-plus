<?php

function up_rest_recipe_query($arguments, $request){
  $orderBy = $request-> get_param('orderByRating');

  if(isset($orderBy)){
    $arguments['orderby'] = 'meta_value_num';
    $arguments['meta_key'] = 'recipe_rating';
  }

  return $arguments;
}