<?php
// Enqueue scripts and styles for the front end
function up_enqueue_scripts(){
  $authURLs = json_encode([
    'signup' => esc_url_raw(rest_url('up/v1/signup')),
    'signin' => esc_url_raw(rest_url('up/v1/signin'))
  ]);

  // Enqueue the auth modal script
  wp_add_inline_script( 
    'udemy-plus-auth-modal-view-script',
    "const up_auth_rest = {$authURLs}",
    'before' // 'after
  );

  wp_enqueue_style(
    'up_editor'
  );
}