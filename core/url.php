<?php

  \xeki\routes::any('', 'login'); 
  \xeki\routes::any('inicio', 'home');
  \xeki\routes::any('perfil', 'profile');
  \xeki\routes::any('panel/grupos/{group_id}', 'group_view');
  \xeki\routes::any('edit_profile', 'edit_profile');
  \xeki\routes::any('procesos', 'process');
  \xeki\routes::any('archivos', 'files');
  \xeki\routes::any('panel', 'dash');
  \xeki\routes::any('panel/usuarios', 'users');
  \xeki\routes::any('panel/grupos', 'groups');
  \xeki\routes::any('usuarios/{username}', 'view_user');
  // \xeki\routes::any('restaurar-clave-codigo', 'code_pw');




  // get user edit
  \xeki\routes::any('panel/usuarios/{username}', 'users_edit');

  \xeki\routes::get('url', function($vars){
    $title = "title for seo";
    $description =  "description for seo";
    \xeki\html_manager::set_seo($title,$description,true);

    $items_to_print=array();
    \xeki\html_manager::render('name.html',$items_to_print);
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

  //new user
  \xeki\routes::any('crear-usuario', function(){
    #import modules
    $sql=\xeki\module_manager::import_module("db-sql");

    $title = "Crear usuario";
    $description =  "description for seo";
    \xeki\html_manager::set_seo($title,$description,true);

    #queries
    $queryOne = "SELECT * FROM cities";
    $cities = $sql->query($queryOne);
    $queryTwo = "SELECT * FROM company";
    $company = $sql->query($queryTwo);

    #send info > view
    $items_to_print = array(
      'cities' => $cities,
      'business' => $company
    );
    \xeki\html_manager::render('dashboard/new_user.html', $items_to_print);
  });

//new group
  \xeki\routes::any('crear-grupo', function(){
    #import modules
    $auth = \xeki\module_manager::import_module('auth');
    $user = $auth->get_user();
    $title = "Crear grupo";
    $description =  "description for seo";
    \xeki\html_manager::set_seo($title,$description,true);

    #send info > view
    $items_to_print = array(
     'user' => $user->get_info()
    );
    \xeki\html_manager::render('dashboard/new_group.html', $items_to_print);
  });

//cities
\xeki\routes::any('panel/ciudades', function(){
  #import modules
  $auth = \xeki\module_manager::import_module('auth');
  $sql=\xeki\module_manager::import_module("db-sql");


  $user = $auth->get_user();
  $title = "Crear grupo";
  $description =  "description for seo";
  \xeki\html_manager::set_seo($title,$description,true);

  $queryOne = "SELECT * FROM cities order by id desc";
  $cities = $sql->query($queryOne);

  #send info > view
  $items_to_print = array(
    'cities' => $cities
  );
  \xeki\html_manager::render('dashboard/cities.html', $items_to_print);
});