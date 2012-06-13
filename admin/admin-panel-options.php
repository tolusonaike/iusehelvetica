<?php

/* -------------------------------------------------------------- 
   
   Admin input fields
   
   Extended from Child Theme Options Framework 
   http://wptheming.com/2009/10/thematic-theme-options-panel/
   
   which:
   
   Theme options adapted from "A Theme Tip For WordPress Theme Authors"
   http://literalbarrage.org/blog/archives/2007/05/03/a-theme-tip-for-wordpress-theme-authors/
  
-------------------------------------------------------------- */



$my_options = array(
  array(
    'name' => 'Theme Style',
    'desc' => '',
    'id' => 'iusehelvetica_theme_style',
    'std' => 'Standard',
    'type' => 'radio',
    'options' => array('standard' => 'Standard', 'inverted' => 'Inverted')
  ),
  array(
    'name' => 'Sidebar Layouts',
    'desc' => '',
    'id' => 'iusehelvetica_theme_layout',
    'std' => 'Static',
    'type' => 'radio',
    'options' => array('static' => 'Static', 'fixed' => 'Fixed')
  ),
  array(
    'name' => 'Logo Image',
    'desc' => 'Upload a logo image to use.',
    'id' => 'iusehelvetica_logo',
    'std' => '',
    'type' => 'upload'
  ),
  array(
    'name' => 'Logo width',
    'desc' => 'Upload a logo width (default is 150px)',
    'id' => 'iusehelvetica_logo_width',
    'std' => '',
    'type' => 'text'
  ),
  array(
    'name' => 'Google Analytics Code',
    'desc' => 'Paste Your Google Analytics Code Here.',
    'id' => 'iusehelvetica_googleanalytics',
    'std' => '',
    'type' => 'textarea'
  )


);




?>
