<?php

add_action('admin_enqueue_scripts', 'up_admin_enqueue');

function up_admin_enqueue($hook_suffix) {
  if( 
      $hook_suffix === "toplevel_page_up-plugin-options"
      || $hook_suffix === "udemy-plus_page_up_plugin-options-alt"
    ) {
    wp_enqueue_media();
    wp_enqueue_style('up_admin');
    wp_enqueue_script('up_admin');
  }
}