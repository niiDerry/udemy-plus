<?php

function up_setup_theme(){
  add_image_size('teamMember', 56, 56, true); //true for hard crop
  add_image_size('opengraph', 1200, 630, true);
}