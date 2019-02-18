<?php
$xeki_admin_module = \xeki\module_manager::import_module("xeki_admin");
$title = "Usuarios";
$single_name = "Usuario";
$table = "user_admin"; # for db ( maybe multiple data bases for ref)
$code = "users"; # for urls
if ($module_action_code == "config" || $module_action_code == "clean_cache") {
    $module['title'] = "Config";
    $module['elements'] = array();

    $element_buttons = array(
        "type" => "buttons",
        "class" => "col-md-12 square-btn",
        "buttons" => [
            array(
                "type" => "url",
                "icon" => "fa fa-sticky-note-o",
                "class" => "square-btn",
                "text" => "Clean Cache",
                "background" => "#66ccff",
                "url" => "xeki_admin/clean_cache"
            ),

        ],
    );

    array_push($module['elements'], $element_buttons);
}

if ($module_action_code == "clean_cache") {
    $html = "Cleaning server<br>";
    $dir = \xeki\core::$SYSTEM_PATH_BASE.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."";
    $html.=$dir."<br>";
    function rrmdir($dir) {

        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir")
                        rrmdir($dir."/".$object);
                    else unlink   ($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
    rrmdir($dir);


    $html.= "Cleaning cloudflare<br>";

    // get credentials
    $cloudflare_email = $xeki_admin_module->get_value_param("cloudflare_email");
    $cloudflare_api = $xeki_admin_module->get_value_param("cloudflare_api_key");

    $cloudflare_domain = $xeki_admin_module->get_value_param("cloudflare_domain");

//    d($cloudflare_email);
//    d($cloudflare_api);
//    d($cloudflare_domain);
    $html.= "Cloudflare domain: $cloudflare_domain <br>";
    // check if is emply
    if($cloudflare_api!="" && $cloudflare_email!=""){
        require_once(\xeki\core::$SYSTEM_PATH_BASE.'/modules/xeki_admin/core/libs/vendor/autoload.php');

        $key = new \Cloudflare\API\Auth\APIKey($cloudflare_email, $cloudflare_api);
        $adapter = new Cloudflare\API\Adapter\Guzzle($key);

        $zones = new \Cloudflare\API\Endpoints\Zones($adapter);
        foreach ($zones->listZones()->result as $zone) {


            if($zone->name==$cloudflare_domain || $cloudflare_domain==""){
//                d($zone);
                $html.= "Cloudflare clean id: $zone->id <br>";
                $html.= "Cloudflare clean name: $zone->name <br>";

                $result = $zones->cachePurgeEverything($zone->id) == true ? "successful" : "failed";
                $html.= "Cloudflare clean result: {$result} <br>";
            }

//            d($zone->name.' ('.$zone->plan->name.')'.PHP_EOL);
        }
    }
    else{
        d("cloudflare not set");
        $html.="cloudflare not set";
    }

    $module['title'] = "Config";
    $module['elements'] = array();

    $element_buttons = array(
        "type" => "html",
        "html" => "
            $html
        ",
    );

    array_push($module['elements'], $element_buttons);
}



if ($module_action_code == "form_new_user_admin") {

    $field_controls="";
    foreach($model_form as $item){
        $html_form = $xeki_admin_module->form_generator($item);
        $field_controls.=$html_form;
    }

    $render_method = "json";
    $html = <<< HTML
    
<form method="post" enctype="multipart/form-data">
    <h2>New Usuario</h2>
    <hr>
    {$field_controls}
  
  <input name="xeki_admin_action" value="new_company" type="hidden">
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
HTML;

    $array_json = array(
        "type" => "form",
        "html" => $html,
        "" => "",
        "" => "",
    );

}
if ($module_action_code == "form_edit_user_admin") {
    $render_method = "json";
    $id_item = $_GET['id'];

    $query = "SELECT * FROM user where id='{$id_item}'";
    $info = $sql->query($query);
    $info = $info[0];
//    d($info);

    $field_controls="";
    foreach($model_form as $item){
        $item['value']=$info[$item['name']];
        $html_form = $xeki_admin_module->form_generator($item);
        $field_controls.=$html_form;
    }

    $selected_begin =  $info['position']=="begin_body"?"selected":"";

    $bi_active_html = $info['bi_active'] == "on" ? "checked" : '';
    $html = <<< HTML
<div class="row">
    <div class="col-md-2 left_buttons">
        <div id_form="form_edit_user_admin" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Info</div>
        <div id_form="form_permissions_user_admin" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Permissions </div>
        <div id_form="form_delete_user_admin" id_item="{$id_item}" class="admin-btn"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete </div>
    </div>
    <div class="col-md-10">
        <form method="post">
           <h2>Editar Usuario</h2>
            <hr>
            {$field_controls}
            
          <input name="xeki_admin_action" value="edit_user_admin" type="hidden">
          <input name="id" value="{$id_item}" type="hidden">
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
HTML;

    $array_json = array(
        "type" => "form",
        "html" => $html,
        "" => "",
        "" => "",
    );

}

if($module_action_code == "form_permissions_user_admin"){
    $render_method = "json";
    $id_item = $_GET['id'];

    $html = <<< HTML
<div class="row">
    <div class="col-md-2 left_buttons">
        <div id_form="form_edit_user_admin" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Info</div>
        <div id_form="form_permissions_user_admin" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Permissions </div>
        <div id_form="form_delete_user_admin" id_item="{$id_item}" class="admin-btn"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete </div>
    </div>
    <div class="col-md-10">
        <form method="post">
           <h2>Permisos Usuario</h2>
            <hr>
            <h3>Usuarios</h3>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" value="">
                Ver Modulo Usuarios
              </label>
            </div>
            <div class="ml-5">
                <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" value="">
                        Crear usuario
                </label>
                </div>
                <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" value="">
                        Editar usuario
                </label>
                </div>
                <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" value="">
                        Editar permisos
                </label>
                </div>
                <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" value="">
                        Eliminar usuario
                </label>
                </div>
            </div>
            <h3>Creditos</h3>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" value="">
                Ver Modulo Credito
              </label>
            </div>
            <div class="ml-5">
                <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" value="">
                        Crear credito
                </label>
                </div>
                <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" value="">
                        Editar credito
                </label>
                </div>
                <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" value="">
                        Opciones avanzadas
                </label>
                </div>
                <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" value="">
                        Eliminar credito
                </label>
                </div>
            </div>
            <h3>Clientes</h3>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" value="">
                Ver Modulo clientes
              </label>
            </div>
            <div class="ml-5">
                <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" value="">
                        Crear clientes
                </label>
                </div>
                <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" value="">
                        Editar clientes
                </label>
                </div>
                <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" value="">
                        Opciones avanzadas
                </label>
                </div>
                <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" value="">
                        Eliminar clientes
                </label>
                </div>
            </div>
            
            
          <input name="xeki_admin_action" value="edit_user_admin" type="hidden">
          <input name="id" value="{$id_item}" type="hidden">
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
HTML;
    $array_json = array(
        "type" => "form",
        "html" => $html,
        "" => "",
        "" => "",
    );
}


if ($module_action_code == "form_delete_user_admin") {
    $render_method = "json";
    $id_item = $_GET['id'];

    $html = <<< HTML
    <div class="row">
        <div class="col-md-2 left_buttons">
            <div id_form="form_edit_user_admin" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Info</div>
            <div id_form="form_delete_user_admin" id_item="{$id_item}" class="admin-btn"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete </div>
        </div>
        <div class="col-md-10">
            <form method="post">
               <h2>Delete Web String</h2>
                <hr>
              <input name="xeki_admin_action" value="delete_user_admin" type="hidden">
              <input name="id" value="{$id_item}" type="hidden">
              <button type="submit" class="btn btn-primary">DELETE</button>
            </form>
        </div>
    </div>
HTML;

    $array_json = array(
        "type" => "form",
        "html" => $html,
        "" => "",
        "" => "",
    );
}



if($values["xeki_admin_action"]=="new_user_admin"){


    $data=array(
        "title"=>$values["title"],
        "nit"=>$values["nit"],
        "address"=>$values["address"],
        "phone_contact"=>$values["phone_contact"],
        "mail_contact"=>$values["mail_contact"],
    );

    // add images
    $array_json['data']=$data;
    $res = $ag_sql->insert("user_admin",$data);

    if(!$res){
        $array_json['error']=$ag_sql->error();
    }
    else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("user_admin",{$res});
JS;


    }



}


if($values["xeki_admin_action"]=="edit_user_admin"){


    $data=array(
        "name"=>$values["name"],
        "lastName"=>$values["lastName"],
        "email"=>$values["email"],
        "xeki_super_admin"=>$values["xeki_super_admin"],
    );

    $res = $ag_sql->update("user",$data," id = '{$values['id']}'");
    if(!$res){
        $array_json['error']=$ag_sql->error();
    }else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("user_admin",{$values['id']});
JS;
    }
}

if($values["xeki_admin_action"]=="edit_user_admin"){


    $data=array(
        "name"=>$values["name"],
        "lastName"=>$values["lastName"],
        "email"=>$values["email"],
    );

    $res = $ag_sql->update("user",$data," id = '{$values['id']}'");
    if(!$res){
        $array_json['error']=$ag_sql->error();
    }else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("user_admin",{$values['id']});
JS;
    }
}


if($values["xeki_admin_action"]=="delete_user_admin"){
    $render_method = "json";
    $res = $ag_sql->delete("user"," id = {$values['id']}");
    if(!$res){
        $array_json['error']=$ag_sql->error();
    }
    else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.close();
JS;
    }
}