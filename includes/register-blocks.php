<?php
// Register blocks
function up_register_blocks(){
  // List of blocks to register
  $blocks = [
    ['name' => 'fancy-header'],
    
    ['name' => 'search-form', 'options'=> [
      'render_callback' => 'up_search_form_render_cb'
    ]], //for up_search_form_render_cb function in includes/blocks/search-form.php

    ['name' => 'page-header', 'options'=> [
      'render_callback' => 'up_page_header_render_cb' 
    ]], //for up_page_header_render_cb function in includes/blocks/page-header.php

    ['name' => 'header-tools', 'options' => [
      'render_callback' => 'up_header_tools_render_cb'
    ]], //for up_header_tools_render_cb function in includes/blocks/header-tools.php

    ['name' => 'auth-modal', 'options' => [
      'render_callback' => 'up_auth_modal_render_cb'
    ]], //for up_auth_modal_render_cb function in includes/blocks/auth-modal.php

    ['name' => 'recipe-summary', 'options' => [
      'render_callback' => 'up_recipe_summary_render_cb'
    ]], //for up_recipe_summary_render_cb function in includes/blocks/recipe-summary.php

    ['name' => 'team-member'], //no render callback needed as it's static content
    ['name' => 'team-members-group'], //no render callback needed as it's static content

    ['name' => 'popular-recipes', 'options' => [
      'render_callback' => 'up_popular_recipes_render_cb'
    ]],

    ['name' => 'daily-recipe', 'options' => [
      'render_callback' => 'up_daily_recipe_cb'
    ]]
  ];

  // Register each block
  foreach($blocks as $block){
    register_block_type(
      UP_PLUGIN_DIR . 'build/blocks/' . $block['name'],
      isset($block['options']) ? $block['options'] : []
    ); // Use options if provided, otherwise default to empty array
  }
}


