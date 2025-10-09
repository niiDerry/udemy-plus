<?php 

function up_plugins_options_alt_page() {
  ?>
    <div class="wrap">
      <form method="POST" action="options.php">
        <?php 
          settings_fields('up_options_group');
          do_settings_sections('up_options_page');
          submit_button();
        ?>
      </form>
      
    </div>
  <?php 
}