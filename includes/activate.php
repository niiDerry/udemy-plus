<?php

function up_activate_plugin(){
  // 6.0 < 5.9
  if(version_compare(get_bloginfo('version'), '5.9', '<')){
    wp_die(
      __('This plugin requires WordPress version 5.9 or higher. Please update and try again.', 'udemy-plus')); 
      //__('Error', 'udemy-plus'), array('back_link' => true));
  }

  up_recipe_post_type(); // Register the custom post type
  flush_rewrite_rules(); // Flush rewrite rules to avoid 404 errors

  // Create custom database table for recipe ratings
  global $wpdb;
  $tableName = "{$wpdb->prefix}recipe_rating";
  $charsetCollate = $wpdb->get_charset_collate();

  // SQL to create the table if it doesn't exist
  $sql = "
  CREATE TABLE {$tableName} (
  ID bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  post_id bigint(20) unsigned NOT NULL,
  user_id bigint(20) unsigned NOT NULL,
  rating float(3,2) unsigned NOT NULL
) ENGINE='InnoDB' {$charsetCollate};";

// Use dbDelta to create or update the table
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);

  $options = get_option('up_options');

  if(!$options){
    add_option('up_options',[
      'og_title' => get_bloginfo('name'),
      'og_img' => '',
      'og_description' => get_bloginfo('description'),
      'enable_og' => 1
    ]);

  }
}