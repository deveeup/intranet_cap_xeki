<?php

  \xeki\routes::any('', 'login'); 
  \xeki\routes::any('inicio', 'home');
  \xeki\routes::any('perfil', 'profile');
  \xeki\routes::any('edit_profile', 'edit_profile');
  \xeki\routes::any('procesos', 'process');
  \xeki\routes::any('archivos', 'files');
  \xeki\routes::any('panel', 'dash');
  // \xeki\routes::any('restaurar-clave-codigo', 'code_pw');

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

//logout 
  \xeki\routes::any('logout', function(){
    $auth = \xeki\module_manager::import_module('auth');
    $auth->logout();
    \xeki\core::redirect('');
  });

//forgot pw 
\xeki\routes::any('resaurar-clave', function(){
  $sql=\xeki\module_manager::import_module("db-sql");
  
  $title = "Restaurar contraseña";
  $description =  "description for seo";
  \xeki\html_manager::set_seo($title,$description,true);
  $items_to_print = array();
  \xeki\html_manager::render('login/forgot-pw.html', $items_to_print);
});

//request pw
\xeki\routes::any('restaurar-clave-codigo', function(){
  $sql=\xeki\module_manager::import_module("db-sql");
  
  $title = "Restaurar contraseña";
  $description =  "description for seo";
  \xeki\html_manager::set_seo($title,$description,true);
  $items_to_print = array();
  \xeki\html_manager::render('login/request-code.html', $items_to_print);

});

//new pw
\xeki\routes::any('nueva-clave', function(){
  $sql=\xeki\module_manager::import_module("db-sql");
  
  $title = "Restaurar contraseña";
  $description =  "description for seo";
  \xeki\html_manager::set_seo($title,$description,true);
  $items_to_print = array();

  $user_id = $_COOKIE["id_user_restorepw"];

  $items_to_print = array(
    'user_id' => $user_id
  );

  
  \xeki\html_manager::render('login/set-new-pw.html', $items_to_print);

});