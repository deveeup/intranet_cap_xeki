<?php
// home route
\xeki\routes::get('', 'login');

\xeki\routes::get('url', function($vars){
  $title = "title for seo";
  $description =  "description for seo";
  \xeki\html_manager::set_seo($title,$description,true);

  $items_to_print=array();
  \xeki\html_manager::render('name.html',$items_to_print);
});


// urls with slugs
  \xeki\routes::get('base/{slug}', 'render-view');


// Static pages
  \xeki\routes::any('trabaja-con-nosotros', function($vars){
  $title = "title for seo";
  $description =  "description for seo";
  \xeki\html_manager::set_seo($title,$description,true);
  $items_to_print = array();
  \xeki\html_manager::render('path/name.html', $items_to_print);
  });