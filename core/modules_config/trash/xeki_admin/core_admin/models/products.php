<?php

$xeki_admin_module = \xeki\module_manager::import_module("xeki_admin");
$title = "Productos";
$single_name = "Producto";
$table = "productos"; 
$code = "products"; # for urls
$id_item=$_GET['id'];

$inner_code_images = "products_inner_images"; # for urls
$inner_code_pricing = "products_inner_pricing"; # for urls
$inner_code_mp3 = "products_inner_mp3"; # for urls




$html_inners_edit = <<<HTML
    <div id_form="form_edit_products" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</div>
    <div id_form="form_edit_list_{$inner_code_images}" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Imagenes</div>
    <div id_form="form_edit_list_{$inner_code_pricing}" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Precios</div>
    <div id_form="form_edit_list_{$inner_code_mp3}" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Audios</div>
    <!--<div id_form="form_actions_products" id_item="{$id_item}" class="admin-btn"><i class="fa fa-cogs" aria-hidden="true"></i> Acciones</div>-->
    <!--<div id_form="form_edit_products" id_item="{$id_item}" class="admin-btn"><i class="fa fa-money" aria-hidden="true"></i> Payments</div>-->
    <div id_form="form_delete_products" id_item="{$id_item}" class="admin-btn"><i class="fa fa-trash-o" aria-hidden="true"></i> Eliminar </div>    

HTML;


$model_form = array(
    array(
        "type"=>"text",
        "name"=>"name",
        "title"=>"name",
        "required"=>false,
        "value"=>"",
    ),
    array(
        "type"=>"bool",
        "name"=>"active",
        "title"=>"active",
        "required"=>false,
        "value"=>"",
    ),
    array(
        "type"=>"image",
        "name"=>"image",
        "title"=>"image",
        "required"=>false,
        "value"=>"",
    ),
    array(
        "type"=>"admin_blog",
        "name"=>"caracteristicas",
        "title"=>"caracteristicas",
        "required"=>false,
        "value"=>"",
    ),
    array(
        "type"=>"admin_blog",
        "name"=>"info_precio",
        "title"=>"info_precio",
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
        "name"=>"keyWords",
        "title"=>"keyWords",
        "required"=>false,
        "value"=>"",
    ),
    

);

$model_form_images = array(
    array(
        "type"=>"array_json",
        "array_json_data"=>array(            
            array(
                "type"=>"text",
                "title"=>"Precio",
                "value_name"=>"image",
                "preview"=>true,
            ),

        ),
        "name"=>"images_array", #name db field
        "title"=>"images",
        "required"=>"",
        "value"=>"",
        "description"=>"",
    ),
);

$model_form_prices = array(
    array(
        "type"=>"array_json",
        "array_json_data"=>array(
            array(
                "type"=>"text",
                "title"=>"Titulo",
                "value_name"=>"title",
                "preview"=>true,
            ),
            array(
                "type"=>"text",
                "title"=>"Precio",
                "value_name"=>"price",
                "preview"=>true,
            ),
        ),
        "name"=>"prices_array", #name db field
        "title"=>"Prices",
        "required"=>"",
        "value"=>"",
        "description"=>"",
    ),
);

$model_form_mp3 = array(
    array(
        "type"=>"array_json",
        "array_json_data"=>array(
            array(
                "type"=>"text",
                "title"=>"Titulo",
                "value_name"=>"title",
                "preview"=>true,
            ),
            array(
                "type"=>"image",
                "title"=>"Audio",
                "value_name"=>"File",
                "preview"=>true,
            ),
        ),
        "name"=>"prices_array", #name db field
        "title"=>"Prices",
        "required"=>"",
        "value"=>"",
        "description"=>"",
    ),
);

if ($module_action_code == "list-productos") {
    $element_table_products = array(
        "type" => "table",
        "text" => "{$title}",
        "class" => "col-md-12",
        "table" => array(
            "type" => "table",
            "items_query_code" => "products", # code like ws_
            "background" => "#66ccff",
            "data_fields" => array(
                array(
                    "title" => "Código",
                ),
                array(
                    "title" => "Nombre",
                ),
                
                // array(
                //     "title" => "Activo",
                // ),
            ),
        ),
    );

    array_push($module['elements'], $element_table_products);
}

if ($module_action_code == "ws_products") {
//    d($_GET);
    $render_method = "json";
    $table = "{$table}";
    $primaryKey = 'id';
    $columns = array();
    array_push($columns, array("db" => "id", "dt" => count($columns)));
    array_push($columns, array("db" => "id", "dt" => count($columns)));
    array_push($columns, array("db" => "name", "dt" => count($columns)));
    // array_push($columns, array("db" => "active", "dt" => count($columns)));

    $array_json = SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns);
}



if ($module_action_code == "form_new_products") {

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
  
  <input name="xeki_admin_action" value="new_products" type="hidden">
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
if ($module_action_code == "form_edit_products") {
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
           <h2>Editar Productos</h2>
            <hr>
            {$field_controls}   
          <input name="xeki_admin_action" value="edit_products" type="hidden">
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

if ($module_action_code == "form_edit_products_images") {
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
            
          <input name="xeki_admin_action" value="edit_products_images" type="hidden">
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

if ($module_action_code == "form_edit_products_prices") {
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
            
          <input name="xeki_admin_action" value="edit_products_images" type="hidden">
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

if($values["xeki_admin_action"]=="new_products"){


    $data = $xeki_admin_module->process_data($model_form,$values);

    $array_json['data']=$data;
    $res = $ag_sql->insert("{$table}",$data);

    if(!$res){
        $array_json['error']=$ag_sql->error();
    }
    else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("products",{$res});
JS;


    }

}
if($values["xeki_admin_action"]=="edit_products_images"){

    $data = $xeki_admin_module->process_data($model_form_images,$values);

    $array_json['data']=$data;

    $res = $ag_sql->update("{$table}",$data," id = '{$values['id']}'");
    if(!$res){
        $array_json['error']=$ag_sql->error();
    }else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("products_images",{$values['id']});
JS;
    }
}

if($values["xeki_admin_action"]=="edit_products"){

    $data = $xeki_admin_module->process_data($model_form,$values);

    $array_json['data']=$data;

    $res = $ag_sql->update("{$table}",$data," id = '{$values['id']}'");
    if(!$res){
        $array_json['error']=$ag_sql->error();
    }else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("products",{$values['id']});
JS;
    }
}



if($values["xeki_admin_action"]=="delete_products"){
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