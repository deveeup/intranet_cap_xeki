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
  \xeki\routes::any('panel/usuarios/{username}', 'users_edit');
  \xeki\routes::any('panel/procesos', 'process_dash');
  \xeki\routes::any('panel/procesos/subprocesos/{process}-{item}', 'subprocess_search');
  \xeki\routes::any('panel/noticias/{notice}', 'edit_notice');
  // \xeki\routes::any('restaurar-clave-codigo', 'code_pw');

  #logout 
    \xeki\routes::any('logout', function(){
      $auth = \xeki\module_manager::import_module('auth');
      $auth->logout();
      \xeki\core::redirect('');
    });

  #forgot pw 
  \xeki\routes::any('resaurar-clave', function(){
    $sql=\xeki\module_manager::import_module("db-sql");
    
    $title = "Restaurar contraseña";
    $description =  "description for seo";
    \xeki\html_manager::set_seo($title,$description,true);
    $items_to_print = array();
    \xeki\html_manager::render('login/forgot-pw.html', $items_to_print);
  });

  #request pw
  \xeki\routes::any('restaurar-clave-codigo', function(){
    $sql=\xeki\module_manager::import_module("db-sql");
    
    $title = "Restaurar contraseña";
    $description =  "description for seo";
    \xeki\html_manager::set_seo($title,$description,true);
    $items_to_print = array();
    \xeki\html_manager::render('login/request-code.html', $items_to_print);

  });

  #new pw
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

  #new user
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

  #new group
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

  #cities
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

  #notices dash 
  \xeki\routes::any('panel/noticias', function(){
    #import modules
    $auth = \xeki\module_manager::import_module('auth');
    $sql=\xeki\module_manager::import_module("db-sql");

    if(!$auth->is_logged()){
      \xeki\core::redirect('');
    } else {
      #user admin ? 
      $user = $auth->get_user(); 
      $user_admin = $user->get("is_superuser");
      if($user_admin == 'yes'){
        #info seo
        $title = "Intranet";
        $description = "Lorem...";
        $keyworkds= "Funeraria, Coorserpark, Capillas de La Fe, capillas la fe, obituarios, sedes";
        \xeki\html_manager::set_seo($title,$description,false);
    
        #search notices
        $queryOne = "SELECT * FROM notices ORDER BY id ASC";
        $notices = $sql->query($queryOne);
        $notices;

        $info_notice = array();
        foreach ($notices as $notice){  
          $notice[info_group] = array();
          $notice[user] = array();
          #search group by notice
          $queryTwo = "SELECT * FROM auth_group WHERE auth_group.id = '$notice[group_ref]' ";
          $group_notice = $sql->query($queryTwo);
          if($group_notice[0][id] == $notice[group_ref]){
            array_push($notice[info_group], $group_notice[0]);
          }

          $queryThree = "SELECT * FROM auth_user WHERE auth_user.id = '$notice[created_by]' ";
          $created_notice = $sql->query($queryThree);

          if($created_notice[0][id] == $notice[created_by]){
            array_push($notice[user], $created_notice[0]);
          }
          array_push($info_notice, $notice);
        }
        
        #sending data to view
        $items_to_print = array();
        $items_to_print['notices'] = $info_notice;
        \xeki\html_manager::render('dashboard/notices.html',$items_to_print);
      }else{
        \xeki\core::redirect('');
      }
    }
  });

  #new notice 
  \xeki\routes::any('crear-noticia', function(){
    #import modules
    $sql=\xeki\module_manager::import_module("db-sql");

    $title = "Crear noticia";
    $description =  "description for seo";
    \xeki\html_manager::set_seo($title,$description,true);

    #queries
    $queryOne = "SELECT * FROM auth_group";
    $groups = $sql->query($queryOne);
    $queryTwo = "SELECT * FROM company";
    $company = $sql->query($queryTwo);
    #send info > view
    $items_to_print = array(
      'groups' => $groups,
      'business' => $company

    );
    \xeki\html_manager::render('dashboard/new_notice.html', $items_to_print);
  });

  #agreements dash 
  \xeki\routes::any('panel/convenios', function(){
    #import modules
    $auth = \xeki\module_manager::import_module('auth');
    $sql=\xeki\module_manager::import_module("db-sql");

    if(!$auth->is_logged()){
      \xeki\core::redirect('');
    } else {
      #user admin ? 
      $user = $auth->get_user(); 
      $user_admin = $user->get("is_superuser");
      if($user_admin == 'yes'){
        #info seo
        $title = "Intranet";
        $description = "Lorem...";
        $keyworkds= "Funeraria, Coorserpark, Capillas de La Fe, capillas la fe, obituarios, sedes";
        \xeki\html_manager::set_seo($title,$description,false);
    
        #search notices
        $queryOne = "SELECT * FROM agreements ORDER BY id ASC";
        $agreements = $sql->query($queryOne);

        #sending data to view
        $items_to_print = array();
        $items_to_print['agreements'] = $agreements;
        \xeki\html_manager::render('dashboard/agreements.html',$items_to_print);
      }else{
        \xeki\core::redirect('');
      }
    }
  });

  #induction dash 
  \xeki\routes::any('panel/induccion', function(){
    #import modules
    $auth = \xeki\module_manager::import_module('auth');
    $sql=\xeki\module_manager::import_module("db-sql");

    if(!$auth->is_logged()){
      \xeki\core::redirect('');
    } else {
      #user admin ? 
      $user = $auth->get_user(); 
      $user_admin = $user->get("is_superuser");
      if($user_admin == 'yes'){
        #info seo
        $title = "Intranet";
        $description = "Lorem...";
        $keyworkds= "Funeraria, Coorserpark, Capillas de La Fe, capillas la fe, obituarios, sedes";
        \xeki\html_manager::set_seo($title,$description,false);
    
        #search notices
        $queryOne = "SELECT * FROM induction ORDER BY id ASC";
        $induction = $sql->query($queryOne);

        #sending data to view
        $items_to_print = array();
        $items_to_print['inductions'] = $induction;
        \xeki\html_manager::render('dashboard/induction.html',$items_to_print);
      }else{
        \xeki\core::redirect('');
      }
    }
  });