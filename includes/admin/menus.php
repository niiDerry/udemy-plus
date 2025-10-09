<?php

function up_admin_menus() {
  add_menu_page(
    __('Udemy Plus', 'udemy-plus'),
    __('Udemy Plus', 'udemy-plus'),
    'edit_theme_options',
    'up-plugin-options',
    'up_plugins_options_page',
    plugins_url('letter-u.svg', UP_PLUGIN_FILE)
  ); // Add the main menu page

  add_submenu_page(
    'up-plugin-options',
    __('Alt Udemy Plus', 'udemy-plus'),
    __('Alt Udemy Plus', 'udemy-plus'),
    'edit_theme_options',
    'up-plugin-options-alt',
    'up_plugins_options_alt_page'
  ); // Add the submenu page

}