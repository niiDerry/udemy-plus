<?php 
/*
 * Plugin Name:       Udemy Plus
 * Plugin URI:        https://udemy.com
 * Description:       A plugin for adding blocks to a theme.
 * Version:           1.0.0
 * Requires at least: 5.9
 * Requires PHP:      8.2
 * Author:            Derrick Angelo Tagoe
 * Author URI:        https://udemy.com/
 * Text Domain:       udemy-plus
 * Domain Path:       /languages
 */

if (!function_exists('add_action')){
  echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
  exit;
}

// Setup
define('UP_PLUGIN_DIR', plugin_dir_path( __FILE__ )); // Use plugin_dir_path() to get the directory path to the plugin folder
define('UP_PLUGIN_FILE', __FILE__); // Use __FILE__ to get the full path to the plugin file

// Includes
//AFTER
$rootFiles = glob(UP_PLUGIN_DIR . 'includes/*.php');
$subdirectoryFiles = glob(UP_PLUGIN_DIR . 'includes/**/*.php');
$allFiles = array_merge($rootFiles, $subdirectoryFiles);

foreach($allFiles as $filename){
  include_once($filename); // Use include_once to prevent multiple inclusions
}

//BEFORE
// include(UP_PLUGIN_DIR . 'includes/register-blocks.php');
// include(UP_PLUGIN_DIR . 'includes/blocks/search-form.php');
// include(UP_PLUGIN_DIR . 'includes/blocks/page-header.php');

// Hooks
register_activation_hook( __FILE__, 'up_activate_plugin' ); 
//for up_activate_plugin function in includes/activate.php

//EXAMPLE* function($hook_name:string, $callback:callable, $priority:integer, $accepted_args:integer)
add_action('init', 'up_register_blocks');
//for up_register_blocks function in includes/register-blocks.php

add_action('rest_api_init', 'up_rest_api_init'); 
//for up_rest_api_init function in includes/rest-api.php

add_action('wp_enqueue_scripts', 'up_enqueue_scripts'); 
//for up_enqueue_scripts function in includes/enqueue-scripts.php

add_action('init', 'up_recipe_post_type'); 
//after this, create a recipe-post-type.php in the includes folder

add_action('cuisine_add_form_fields', 'up_cuisine_add_form_fields'); 
//after this, create a cuisine-taxonomy.php in the includes folder

add_action( 'create_cuisine', 'up_save_cuisine_meta' ); 
//after this, create a cuisine-taxonomy.php in the includes folder

add_action('cuisine_edit_form_fields', 'up_cuisine_edit_form_fields'); 
//after this, create a cuisine-taxonomy.php in the includes folder 

add_action('edited_cuisine', 'up_save_cuisine_meta'); 
//after this, create a cuisine-taxonomy.php in the includes folder

add_action('save_post_recipe', 'up_save_post_recipe'); 
//after this, create a save-recipe.php in the includes folder

add_action('after_setup_theme', 'up_setup_theme'); 
//for up_add_theme_support function in includes/setup.php

add_filter('image_size_names_choose', 'up_custom_image_sizes'); 
//for up_custom_image_sizes function in includes/setup.php

add_filter('rest_recipe_query', 'up_rest_recipe_query', 10, 2);
// for up_rest_recipe_query function in includes/rest-api.php

add_action('admin_menu', 'up_admin_menus');
// for up_admin_menus function in includes/admin/menus.php

add_action('admin_post_up_save_options', 'up_save_options');
// for up_save_options function in includes/admin/options.php

add_action('admin_enqueue_scripts', 'up_admin_enqueue'); 
// for up_admin_enqueue function in includes/admin/enqueue.php

add_action('init', 'up_register_assets'); 
//for up_register_assets function in includes/register-assets.php

add_action('admin_init', 'up_settings_api'); 
// for up_settings_api function in includes/admin/settings-api.php

add_action( 'enqueue_block_editor_assets', 'up_enqueue_block_editor_assets' ); 
// for up_enqueue_block_editor_assets function in includes/admin/editor-assets.php

add_action('wp_head', 'up_wp_head'); // for up_wp_head function in includes/front/head.php