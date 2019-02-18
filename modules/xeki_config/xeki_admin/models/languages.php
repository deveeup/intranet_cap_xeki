<?php

// ##############################
// FEEDBACK module admin
// #############################
$xeki_admin_module = \xeki\module_manager::import_module("xeki_admin");
$title = "Languages";
$single_name = "Language";
$table = "xeki_config_languages"; # for db ( maybe multiple data bases for ref)
$code = "languages"; # for urls
$id_item=$_GET['id'];





$html_inners_edit = <<<HTML
    <div id_form="form_edit_languages" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</div>
    <div id_form="form_delete_languages" id_item="{$id_item}" class="admin-btn"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete </div>    

HTML;




$model_form = array(
    array(
        "type"=>"text",
        "name"=>"name", #name db field
        "title"=>"Name",
        "required"=>"true",
        "value"=>"",
        "description"=>"User name",
    ),
);

if ($module_action_code == "list-languages") {
    $element_table_languages = array(
        "type" => "table",
        "text" => "Languages",
        "class" => "col-md-12",
        "table" => array(
            "type" => "table",
            "items_query_code" => "languages", # code like ws_
            "background" => "#66ccff",
            "data_fields" => array(
                array(
                    "title" => "Name",
                ),
            ),
        ),
    );

    array_push($module['elements'], $element_table_languages);
}


if ($module_action_code == "ws_languages") {
//    d($_GET);
    $render_method = "json";
    $table = "{$table}";
    $primaryKey = 'id';
    $columns = array();
    array_push($columns, array("db" => "id", "dt" => count($columns)));
    array_push($columns, array("db" => "name", "dt" => count($columns)));

    array_push($columns, array("db" => "active", "dt" => count($columns)));

    $array_json = SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns);
}



if ($module_action_code == "form_new_languages") {

    $field_controls="";
    foreach($model_form as $item){
        $html_form = $xeki_admin_module->form_generator($item);
        $field_controls.=$html_form;
    }

    $render_method = "json";
    $html = <<< HTML
    
<form method="post" enctype="multipart/form-data">
    <h2>New Languages</h2>
    <hr>
    {$field_controls}
  
  <input name="xeki_admin_action" value="new_languages" type="hidden">
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
if ($module_action_code == "form_edit_languages") {
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
           <h2>Editar Languages</h2>
            <hr>
            {$field_controls}
            
          <input name="xeki_admin_action" value="edit_languages" type="hidden">
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

if ($module_action_code == "form_actions_languages") {
    $render_method = "json";
    $id_item = $_GET['id'];

    $html = <<< HTML
    <div class="row">
        <div class="col-md-2 left_buttons">
            {$html_inners_edit}
        </div>
        <div class="col-md-10">
            <h2>Actions Languages</h2>
            <form method="post">
              <input name="xeki_admin_action" value="send_change_pass_languages" type="hidden">
              <input name="id" value="{$id_item}" type="hidden">
              <button type="submit" class="btn btn-primary">Send Email Recover Pass</button>
            </form>
            <hr>
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
if ($module_action_code == "form_delete_languages") {
    $render_method = "json";
    $id_item = $_GET['id'];

    $html = <<< HTML
    <div class="row">
        <div class="col-md-2 left_buttons">
            {$html_inners_edit}
        </div>
        <div class="col-md-10">
            <form method="post">
               <h2>Delete Languages</h2>
                <hr>
              <input name="xeki_admin_action" value="delete_languages" type="hidden">
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



if($values["xeki_admin_action"]=="new_languages"){


    $data = $xeki_admin_module->process_data($model_form,$values);

    $array_json['data']=$data;
    $res = $ag_sql->insert($table,$data);

    if(!$res){
        $array_json['error']=$ag_sql->error();
    }
    else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("languages",{$res});
JS;


    }



}
if($values["xeki_admin_action"]=="edit_languages"){


    $data = $xeki_admin_module->process_data($model_form,$values);

    $array_json['data']=$data;

    $res = $ag_sql->update($table,$data," id = '{$values['id']}'");
    if(!$res){
        $array_json['error']=$ag_sql->error();
    }else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("languages",{$values['id']});
JS;
    }
}

if($values["xeki_admin_action"]=="delete_languages"){
    $render_method = "json";
    $res = $ag_sql->delete($table," id = {$values['id']}");
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