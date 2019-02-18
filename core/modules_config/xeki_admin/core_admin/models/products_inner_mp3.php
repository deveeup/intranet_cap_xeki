<?php
$xeki_admin_module = \xeki\module_manager::import_module("xeki_admin");
$title = "Products mp3";
$single_name = "Products mp3";
$table_up = "productos"; # for db ( maybe multiple data bases for ref)
$table = "productos_mp3"; # for db ( maybe multiple data bases for ref)
$table_ref = "productos_Ref";

$code_up = "products"; # for urls
$code = "products_inner_mp3"; # for urls



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
        "name"=>"title", #name db field
        "title"=>"Title",
        "required"=>"true",
        "value"=>"",
        "description"=>"Name of company",
    ),
    array(
        "type"=>"image",
        "name"=>"file", #name db field
        "title"=>"Audio",
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

if ($module_action_code == "form_new_{$code}") {
    $id_item = $_GET['id'];
    $field_controls="";
    foreach($model_form as $item){
        $html_form = $xeki_admin_module->form_generator($item);
        $field_controls.=$html_form;
    }

    $render_method = "json";
    $html = <<< HTML
 <div class="row">
    <div class="col-md-2 left_buttons">
        $html_inners_edit
    </div>
    <div class="col-md-10">   
    <form method="post" enctype="multipart/form-data">
        <h2>New</h2>
        <hr>
        {$field_controls}
      <input name="id_up" value="{$id_item}" type="hidden">
      <input name="xeki_admin_action" value="new_{$code}" type="hidden">
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
HTML;

    $array_json = array(
        "type" => "form",
        "html" => $html,
        "" => "",
        "" => "",
    );

}


if ($module_action_code == "form_edit_list_{$code}") {
    $render_method = "json";
    $id_item = $_GET['id'];
    $query = "select * from {$table} where {$table_ref}='{$id_item}'";
    $res = $sql->query($query );

    $text = "
    ";
    foreach ($res as $inner_item){
        $text.="
         <tr>
              <th scope='row'>{$inner_item['id']}</th>
              <td><img src='{$inner_item['image']}'/></td>
              <td>{$inner_item['alt']}</td>
              <td>
                   <a id_form='form_edit_{$code}' id_item='{$inner_item['id']}' class='admin-btn btn btn-warning btn-sm'>Edit</a>
                   <form method='post'>  
                      <input name='xeki_admin_action' value='delete_{$code}' type='hidden'>
                      <input name='id' value='{$inner_item['id']}' type='hidden'>
                      <input name='id_up' value='{$id_item}' type='hidden'>
                      <button type='submit' class='btn btn-danger btn-sm'>Eliminar</button>
                  </form> 
              </td>
         </tr>
        ";

    }
//    d($res);;
    // get items
    $list_items = <<<HTML
    <a id_form='form_new_{$code}' id_item='{$id_item}' class='admin-btn btn btn-primary'>+ Nuevo</a>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Image</th>
          <th scope="col">Alt</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        {$text}
        
      </tbody>
    </table>
HTML;

    $html = <<< HTML
<div class="row">
    <div class="col-md-2 left_buttons">
        $html_inners_edit
    </div>
    <div class="col-md-10">
        
           <h2>Items</h2>
            <hr>
            {$list_items}
        
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

if ($module_action_code == "form_edit_{$code}") {
    $render_method = "json";
    $id_item_inner = $_GET['id'];

    $query = "SELECT * FROM {$table} where id='{$id_item}'";
    $info = $sql->query($query);
    $info = $info[0];
    $id_item = $info[$table_ref];
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
          <input name="id" value="{$id_item_inner}" type="hidden">
          <input name="id_up" value="{$id_item}" type="hidden">
          
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




if($values["xeki_admin_action"]=="new_{$code}"){


    $data = $xeki_admin_module->process_data($model_form,$values);

    $data["{$table_ref}"]=$values['id_up'];
    $array_json['data']=$data;
    $res = $ag_sql->insert("{$table}",$data);

    if(!$res){
        $array_json['error']=$ag_sql->error();
    }
    else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("list_{$code}",{$values['id_up']});
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
        js_admin.edit_item("list_{$code}",{$values['id_up']});
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
         js_admin.edit_item("list_{$code}",{$values['id_up']});
JS;
    }
}