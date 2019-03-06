<?php

// ##############################
// Funerarias module admin
// #############################
$xeki_admin_module = \xeki\module_manager::import_module("xeki_admin");
$title = "Funerarias";
$single_name = "Funeraria";
$table = "sedes"; # for db ( maybe multiple data bases for ref)
$code = "funerarias"; # for urls
$id_item=$_GET['id'];

$inner_code_mp3 = "sedes_inner_images"; # for urls

$html_inners_edit = <<<HTML
    <div id_form="form_edit_funerarias" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</div>
    <div id_form="form_edit_list_{$inner_code_mp3}" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Imagenes</div>
    <!--<div id_form="form_actions_funerarias" id_item="{$id_item}" class="admin-btn"><i class="fa fa-cogs" aria-hidden="true"></i> Acciones</div>-->
    <!--<div id_form="form_edit_funerarias" id_item="{$id_item}" class="admin-btn"><i class="fa fa-money" aria-hidden="true"></i> Payments</div>-->
    <div id_form="form_delete_funerarias" id_item="{$id_item}" class="admin-btn"><i class="fa fa-trash-o" aria-hidden="true"></i> Eliminar </div>    

HTML;


$model_form = array(
    array(
        "type"=>"text",
        "name"=>"titulo",
        "title"=>"titulo",
        "required"=>false,
        "value"=>"",
        ),
        
    array(
        "type"=>"text",
        "name"=>"direccion",
        "title"=>"direccion",
        "required"=>false,
        "value"=>"",
    ),        
    array(
        "type"=>"text",
        "name"=>"telefono",
        "title"=>"telefono",
        "required"=>false,
        "value"=>"",
    ),
        
    array(
        "type"=>"text",
        "name"=>"telefono_back",
        "title"=>"telefono_back",
        "required"=>false,
        "value"=>"",
    ),
        
    array(
        "type"=>"text",
        "name"=>"horario",
        "title"=>"horario",
        "required"=>false,
        "value"=>"",
    ),
        
    array(
        "type"=>"text",
        "name"=>"imagen",
        "title"=>"imagen",
        "required"=>false,
        "value"=>"",
    ),
        
    array(
        "type"=>"text",
        "name"=>"lat",
        "title"=>"lat",
        "required"=>false,
        "value"=>"",
    ),
        
    array(
        "type"=>"text",
        "name"=>"lon",
        "title"=>"lon",
        "required"=>false,
        "value"=>"",
    ),
        
    array(
        "type"=>"text",
        "name"=>"slug",
        "title"=>"slug",
        "required"=>false,
        "value"=>"",
    ),
        
    array(
        "type"=>"text",
        "name"=>"keyWords",
        "title"=>"keyWords",
        "required"=>false,
        "value"=>"",
    ),
        
    array(
        "type"=>"text",
        "name"=>"metaDescripcion",
        "title"=>"metaDescripcion",
        "required"=>false,
        "value"=>"",
    ),
        
    array(
        "type"=>"text",
        "name"=>"opcion",
        "title"=>"opcion",
        "required"=>false,
        "value"=>"",
    ),
        
    array(
        "type"=>"text",
        "name"=>"presidencial",
        "title"=>"presidencial",
        "required"=>false,
        "value"=>"",
    ),
        
    array(
        "type"=>"text",
        "name"=>"order_list",
        "title"=>"order_list",
        "required"=>false,
        "value"=>"",
    ),

);

$model_form_images = array(
    array(
        "type"=>"array_json",
        "array_json_data"=>array(
            // array(
            //     "type"=>"text",
            //     "title"=>"Title",
            //     "value_name"=>"title",
            //     "preview"=>true,
            // ),
            array(
                "type"=>"image",
                "title"=>"Image",
                "value_name"=>"image",
                "preview"=>true,
            ),
            // array(
            //     "type"=>"admin_blog",
            //     "title"=>"Description",
            //     "value_name"=>"description",
            //     "preview"=>false,
            // ),

        ),
        "name"=>"images_array", #name db field
        "title"=>"images",
        "required"=>"",
        "value"=>"",
        "description"=>"",
    ),
);
if ($module_action_code == "list-funerarias") {
    $element_table_funerarias = array(
        "type" => "table",
        "text" => "{$title}",
        "class" => "col-md-12",
        "table" => array(
            "type" => "table",
            "items_query_code" => "funerarias", # code like ws_
            "background" => "#66ccff",
            "data_fields" => array(
                array(
                    "title" => "Nombre",
                ),
                array(
                    "title" => "Dirección",
                ),
                // array(
                //     "title" => "Activo",
                // ),
            ),
        ),
    );

    array_push($module['elements'], $element_table_funerarias);
}

if ($module_action_code == "ws_funerarias") {
//    d($_GET);
    $render_method = "json";
    $table = "{$table}";
    $primaryKey = 'id';
    $columns = array();
    array_push($columns, array("db" => "id", "dt" => count($columns)));
    array_push($columns, array("db" => "titulo", "dt" => count($columns)));
    array_push($columns, array("db" => "direccion", "dt" => count($columns)));

    // array_push($columns, array("db" => "active", "dt" => count($columns)));

    $array_json = SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns);
}



if ($module_action_code == "form_new_funerarias") {

    $field_controls="";
    foreach($model_form as $item){
        $html_form = $xeki_admin_module->form_generator($item);
        $field_controls.=$html_form;
    }

    $render_method = "json";
    $html = <<< HTML
    
<form method="post" enctype="multipart/form-data">
    <h2>Nueva {$single_name}</h2>
    <hr>
    {$field_controls}
  
  <input name="xeki_admin_action" value="new_funerarias" type="hidden">
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
if ($module_action_code == "form_edit_funerarias") {
    $render_method = "json";

    $id_item = $_GET['id'];
    $query = "SELECT * FROM {$table} where id='{$id_item}'";
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
        {$html_inners_edit}
    </div>
    <div class="col-md-10">
        <form method="post">
           <h2>Editar</h2>
            <hr>
            {$field_controls}
            
          <input name="xeki_admin_action" value="edit_funerarias" type="hidden">
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

if ($module_action_code == "form_edit_funerarias_images") {
    $render_method = "json";

    $id_item = $_GET['id'];
    $query = "SELECT * FROM {$table} where id='{$id_item}'";
    $info = $sql->query($query);
    $info = $info[0];

//    d($info);

    $field_controls="";
    foreach($model_form_images as $item){
        $item['value']=$info[$item['name']];
        $html_form = $xeki_admin_module->form_generator($item);
        $field_controls.=$html_form;
    }

    $selected_begin =  $info['position']=="begin_body"?"selected":"";

    $bi_active_html = $info['bi_active'] == "on" ? "checked" : '';
    $html = <<< HTML
<div class="row">
    <div class="col-md-2 left_buttons">
        {$html_inners_edit}
    </div>
    <div class="col-md-10">
        <form method="post">
           <h2>Editar Images</h2>
            <hr>
            {$field_controls}
            
          <input name="xeki_admin_action" value="edit_funerarias_images" type="hidden">
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

if($values["xeki_admin_action"]=="new_funerarias"){


    $data = $xeki_admin_module->process_data($model_form,$values);

    $array_json['data']=$data;
    $res = $ag_sql->insert("{$table}",$data);

    if(!$res){
        $array_json['error']=$ag_sql->error();
    }
    else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("funerarias",{$res});
JS;


    }

}
if($values["xeki_admin_action"]=="edit_funerarias_images"){

    $data = $xeki_admin_module->process_data($model_form_images,$values);

    $array_json['data']=$data;

    $res = $ag_sql->update("{$table}",$data," id = '{$values['id']}'");
    if(!$res){
        $array_json['error']=$ag_sql->error();
    }else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("funerarias_images",{$values['id']});
JS;
    }
}

if($values["xeki_admin_action"]=="edit_funerarias"){

    $data = $xeki_admin_module->process_data($model_form,$values);

    $array_json['data']=$data;

    $res = $ag_sql->update("{$table}",$data," id = '{$values['id']}'");
    if(!$res){
        $array_json['error']=$ag_sql->error();
    }else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("funerarias",{$values['id']});
JS;
    }
}



if($values["xeki_admin_action"]=="delete_funerarias"){
    $render_method = "json";
    $res = $ag_sql->delete("{$table}"," id = {$values['id']}");
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