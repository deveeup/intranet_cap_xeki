<?php
$xeki_admin_module = \xeki\module_manager::import_module("xeki_admin");
$title = "Slide Mobile";
$single_name = "Slide Mobile";
$table = "slider_mobile"; # for db ( maybe multiple data bases for ref)
$code = "slide_mobile"; # for urls


$html_inners_edit = <<<HTML
    <div id_form="form_edit_{$code}" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</div>
    <!--<div id_form="form_reserves_{$code}" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Reservas</div>-->
     <div id_form="form_delete_{$code}" id_item="{$id_item}" class="admin-btn"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete </div> 
HTML;


$model_form = array(
    array(
        "type"=>"text",
        "name"=>"text", #name db field
        "title"=>"Texto",
        "required"=>"true",
        "value"=>"",
        "description"=>"Name of company",
    ),
    array(
        "type"=>"image",
        "name"=>"image", #name db field
        "title"=>"Imagen",
        "required"=>"true",
        "value"=>"",
        "description"=>"Name of company",
    ),
    array(
        "type"=>"text",
        "name"=>"url", #name db field
        "title"=>"Url",
        "required"=>"true",
        "value"=>"",
        "description"=>"Name of company",
    ),
    array(
        "type"=>"bool",
        "name"=>"active", #name db field
        "title"=>"Active",
        "required"=>"false",
        "value"=>"",
        "description"=>"Name of company",
    )
);

if ($module_action_code == "list-{$code}") {
    $element_table_{$code} = array(
        "type" => "table",
        "text" => "{$title}",
        "class" => "col-md-12",
        "table" => array(
            "type" => "table",
            "items_query_code" => "{$code}", # code like ws_
            "background" => "#66ccff",
            "data_fields" => array(
                "id"=>array(
                    "title" => "Código",
                ),
                "text"=>array(
                    "title" => "Texto",
                ),
                "url"=>array(
                    "title" => "Redirección",
                ),

            ),
        ),
    );

    array_push($module['elements'], $element_table_{$code});
}

if ($module_action_code == "ws_{$code}") {
//    d($_GET);
    $render_method = "json";
    $table = "{$table}";
    $primaryKey = 'id';
    $columns = array();
    array_push($columns, array("db" => "id", "dt" => count($columns)));
    array_push($columns, array("db" => "id", "dt" => count($columns)));
    array_push($columns, array("db" => "text", "dt" => count($columns)));
    array_push($columns, array("db" => "url", "dt" => count($columns)));

    $array_json = SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns);
}



if ($module_action_code == "form_new_{$code}") {

    $field_controls="";
    foreach($model_form as $item){
        $html_form = $xeki_admin_module->form_generator($item);
        $field_controls.=$html_form;
    }

    $render_method = "json";
    $html = <<< HTML
    
<form method="post" enctype="multipart/form-data">
    <h2>New</h2>
    <hr>
    {$field_controls}
  
  <input name="xeki_admin_action" value="new_{$code}" type="hidden">
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


if ($module_action_code == "form_edit_{$code}") {
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
        $html_inners_edit
    </div>
    <div class="col-md-10">
        <form method="post">
           <h2>Edit</h2>
            <hr>
            {$field_controls}
            
          <input name="xeki_admin_action" value="edit_{$code}" type="hidden">
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


if ($module_action_code == "form_delete_{$code}") {
    $render_method = "json";
    $id_item = $_GET['id'];

    $html = <<< HTML
    <div class="row">
        <div class="col-md-2 left_buttons">
            <div id_form="form_edit_{$code}" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Info</div>
            <div id_form="form_delete_{$code}" id_item="{$id_item}" class="admin-btn"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete </div>
        </div>
        <div class="col-md-10">
            <form method="post">
               <h2>Delete Client</h2>
                <hr>
              <input name="xeki_admin_action" value="delete_{$code}" type="hidden">
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



if($values["xeki_admin_action"]=="new_{$code}"){


    $data = $xeki_admin_module->process_data($model_form,$values);

    $array_json['data']=$data;
    $res = $ag_sql->insert("{$table}",$data);

    if(!$res){
        $array_json['error']=$ag_sql->error();
    }
    else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("{$code}",{$res});
JS;


    }



}
if($values["xeki_admin_action"]=="edit_{$code}"){


    $data = $xeki_admin_module->process_data($model_form,$values);

    $array_json['data']=$data;

    $res = $ag_sql->update("{$table}",$data," id = '{$values['id']}'");
    if(!$res){
        $array_json['error']=$ag_sql->error();
    }else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("{$code}",{$values['id']});
JS;
    }
}


if($values["xeki_admin_action"]=="delete_{$code}"){
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