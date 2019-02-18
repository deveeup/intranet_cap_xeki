<?php
$xeki_admin_module = \xeki\module_manager::import_module("xeki_admin");
$ag_config = \xeki\module_manager::import_module("xeki_config");

$title = "Config";
$single_name = "Usuario";
$table = "user_admin"; # for db ( maybe multiple data bases for ref)
$code = "users"; # for urls
// ##############################
// FEEDBACK module admin
// #############################

$title = "Config";
$single_name = "Config";
$table = "config"; # for db ( maybe multiple data bases for ref)
$code = "config"; # for urls
$id_item=$_GET['id'];

//d($module_action_code );


if ($values["xeki_admin_action"] == "edit_ag_config") {

    $selected_lang = isset($_GET['lang'])?$_GET['lang']:false;
    
    $ag_config->process_values($values,$selected_lang);
    header("Refresh:0");
    die();
}


if ($module_action_code == "config") {
    // get forms

//    $query = "SELECT * FROM {$table} where id='{$id_item}'";
//    $info = $sql->query($query);
//    $info = $info[0];
//    d($info);

//    d($variables);
    $variables = $ag_config->get_value_param('box_items');
    $selected_lang = isset($_GET['lang'])?$_GET['lang']:false;

    
    $field_controls ="";

    $selected_begin =  $info['position']=="begin_body"?"selected":"";

    $bi_active_html = $info['bi_active'] == "on" ? "checked" : '';


    $html_languages = "";    
    if($ag_config->get_value_param('languages')){
        $list_langs = $ag_config->get_lang_list();

        // items options
        $options = "";

        foreach ($list_langs as $key => $value) {
            if($value['main']=="on"){
                $value['code_lang']='main';
            }
            $selected = $selected_lang==$value['code_lang']?"selected":"";
            $options .= "<option value='{$value['code_lang']}' {$selected}>{$value['name']}</option>";
        }

        $html_languages=<<<HTML
        <div class="row">
            <div class="col-md-4">
                <div class="form-group ">
                    <label>
                        Choose language
                    </label>
                    <select class='form-control change_get_value' change-value="lang" >
                        {$options}
                    </select>
                </div>
            </div>
        </div>
HTML;
    }

    $field_controls = $ag_config->get_form($selected_lang);
    
    $html = <<< HTML
        <div class="row">            
            <div class="col-md-10">
                <h1>
                    Config
                </h1>
                $html_languages
                <form method="post" enctype="multipart/form-data">
                    <hr>
                    <div class="row">
                        {$field_controls}
                    </div>                    
                    <input name="xeki_admin_action" value="edit_ag_config" type="hidden">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
HTML;

    $js = <<< HTML
        $(function(){
            xeki_admin.prototype.run_third_scripts();
        });
HTML;
    $form_update_list = array(
        "type" => "html",
        "html" => "{$html}",
        "js" => $js

    );
    array_push($module['elements'], $form_update_list);
}