<?php
$xeki_admin_module = \xeki\module_manager::import_module("xeki_admin");
$title = "Ventas";
$single_name = "Ventas";
$table = "customer_buy_info"; # for db ( maybe multiple data bases for ref)
$code = "buys"; # for urls


$html_inners_edit = <<<HTML
    <div id_form="" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</div>
    <!--<div id_form="form_reserves_slide_desktop" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Reservas</div>-->
     <div id_form="" id_item="{$id_item}" class="admin-btn"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete </div> 
HTML;


$model_form = array(
    array(
        "type"=>"text",
        "name"=>"code", #name db field
        "title"=>"Code",
        "required"=>"true",
        "value"=>"",
        "description"=>"Name of company",
    ),
    array(
        "type"=>"text",
        "name"=>"state", #name db field
        "title"=>"Stado",
        "required"=>"true",
        "value"=>"",
        "description"=>"Name of company",
    ),
    array(
        "type"=>"text",
        "name"=>"product", #name db field
        "title"=>"Stado",
        "required"=>"true",
        "value"=>"",
        "description"=>"Name of company",
    ),
    array(
        "type"=>"text",
        "name"=>"price", #name db field
        "title"=>"Precio",
        "required"=>"true",
        "value"=>"",
        "description"=>"Name of company",
    ),
    array(
        "type"=>"text",
        "name"=>"name", #name db field
        "title"=>"Name",
        "required"=>"true",
        "value"=>"",
        "description"=>"Name of company",
    ),
    array(
        "type"=>"text",
        "name"=>"plan", #name db field
        "title"=>"Plan",
        "required"=>"true",
        "value"=>"",
        "description"=>"Name of company",
    ),
    array(
        "type"=>"text",
        "name"=>"date_creation", #name db field
        "title"=>"Fecha",
        "required"=>"true",
        "value"=>"",
        "description"=>"Name of company",
    ),
    array(
        "type"=>"text",
        "name"=>"buyerPhone", #name db field
        "title"=>"Fecha",
        "required"=>"true",
        "value"=>"",
        "description"=>"Name of company",
    ),

);

if ($module_action_code == "list-{$code}") {
    $element_table_slide_desktop = array(
        "type" => "table",
        "text" => "{$title}",
        "class" => "col-md-12",
        "table" => array(
            "type" => "table",
            "items_query_code" => "{$code}", # code like ws_
            "background" => "#66ccff",
            "data_fields" => array(
                "code"=>array(
                    "title" => "Código",
                ),
                "name"=>array(
                    "title" => "Nombre",
                ),
                "product"=>array(
                    "title" => "Producto",
                ),
                "price"=>array(
                    "title" => "Precio",
                ),
                "date_creation"=>array(
                    "title" => "Fecha",
                ),
                "state"=>array(
                    "title" => "Estado",
                ),
                "buyerEmail"=>array(
                    "title" => "Email",
                ),
                "buyerPhone"=>array(
                    "title" => "Teléfono",
                ),

            ),
        ),
    );

    array_push($module['elements'], $element_table_slide_desktop);
}

if ($module_action_code == "ws_{$code}") {
//    d($_GET);
    $render_method = "json";
    $table = "{$table}";
    $primaryKey = 'id';
    $columns = array();
    array_push($columns, array("db" => "id", "dt" => count($columns)));
    array_push($columns, array("db" => "code", "dt" => count($columns)));
    array_push($columns, array("db" => "name", "dt" => count($columns)));
    array_push($columns, array("db" => "plan", "dt" => count($columns)));
    array_push($columns, array("db" => "price", "dt" => count($columns)));
    array_push($columns, array("db" => "date_creation", "dt" => count($columns)));
    array_push($columns, array("db" => "state", "dt" => count($columns)));
    array_push($columns, array("db" => "buyerEmail", "dt" => count($columns)));
    array_push($columns, array("db" => "buyerPhone", "dt" => count($columns)));
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
    <h2>Not available</h2>
    <hr>

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


if ($module_action_code == "form_delete_slide_desktop") {
    $render_method = "json";
    $id_item = $_GET['id'];

    $html = <<< HTML
    <div class="row">
        <div class="col-md-2 left_buttons">
            <div id_form="form_edit_slide_desktop" id_item="{$id_item}" class="admin-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Info</div>
            <div id_form="form_delete_slide_desktop" id_item="{$id_item}" class="admin-btn"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete </div>
        </div>
        <div class="col-md-10">
            <form method="post">
               <h2>Delete Client</h2>
                <hr>
              <input name="xeki_admin_action" value="delete_slide_desktop" type="hidden">
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



if($values["xeki_admin_action"]=="new_slide_desktop"){


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
if($values["xeki_admin_action"]=="edit_slide_desktop"){


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


if($values["xeki_admin_action"]=="delete_slide_desktop"){
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