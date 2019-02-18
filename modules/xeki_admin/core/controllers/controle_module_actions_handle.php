<?php
require_once dirname(__FILE__) . "/../common/main_for_controllers.php";
require_once dirname(__FILE__) . "/../common/data_table/ssp.class.php";
$ag_sql = \xeki\module_manager::import_module('xeki_db_sql');

$xeki_admin = \xeki\module_manager::import_module('xeki_admin');

$values=$_POST;
$render_method = "json";
$array_json = array();
$array_json["xeki_admin_action"]=$values["xeki_admin_action"];
$array_json["values"]=$_POST;
$array_json["files"]=$_FILES;

if($values["xeki_admin_action"]=="new_article"){

    $images_files=array(
        'image_main' => 'none',
    );
    $processed_images = $xeki_admin->save_images($images_files,$_FILES);

    // check if have images
    // save image
    $data=array(
        "title"=>$values["title"],
        "description"=>$values["description"],

        "slug"=>$values["slug"],
        "date_release"=>$date_release,

        "seo_title"=>$values["seo_title"],
        "seo_description"=>$values["seo_description"],
        "seo_keywords"=>$values["seo_keywords"],

        "author_ref_id"=>$values['author_ref_id'],
        // basic info
        "bi_active"=>$values["bi_active"],

    );

    // add images
    $data=array_merge($data,$processed_images);
    $array_json['data']=$data;
    $res = $ag_sql->insert("blog_articles",$data);

    if(!$res){
        $array_json['error']=$ag_sql->error();
    }
    else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("articles",{$res});
JS;


    }



}
if($values["xeki_admin_action"]=="edit_article"){

    $images_files=array(
        'image_main' => 'none',
    );
    $processed_images = $xeki_admin->save_images($images_files,$_FILES);

    $data=array(
        "title"=>$values["title"],
        "description"=>$values["description"],

        "slug"=>$values["slug"],
        "date_release"=>$values["date_release"],

        "seo_title"=>$values["seo_title"],
        "seo_description"=>$values["seo_description"],
        "seo_keywords"=>$values["seo_keywords"],


        "author_ref_id"=>$values['author_ref_id'],
        // basic info
        "bi_active"=>$values["bi_active"],

    );
    $res = $ag_sql->update("blog_articles",$data," id = '{$values['id']}'");
    if(!$res){
        $array_json['error']=$ag_sql->error();
    }else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("articles",{$values['id']});
JS;
    }
}


if($values["xeki_admin_action"]=="new_author"){

    $images_files=array(
        'image_main' => 'none',
    );
    $processed_images = $xeki_admin->save_images($images_files,$_FILES);

    $data=array(
        "title"=>$values["title"],
        "description"=>$values["description"],

        "slug"=>$values["slug"],
        "seo_title"=>$values["seo_title"],
        "seo_description"=>$values["seo_description"],
        "seo_keywords"=>$values["seo_keywords"],
        // basic info
        "bi_active"=>$values["bi_active"],

    );
    $data=array_merge($data,$processed_images);
    $res = $ag_sql->insert("blog_authors",$data);
    if(!$res){
        $array_json['error']=$ag_sql->error();
    }
    else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("authors",{$res});
JS;
    }

}
if($values["xeki_admin_action"]=="edit_author"){
    $images_files=array(
        'image_main' => 'none',
    );
    $processed_images = $xeki_admin->save_images($images_files,$_FILES);
    $data=array(
        "title"=>$values["title"],
        "description"=>$values["description"],
        "slug"=>$values["slug"],

        "seo_title"=>$values["seo_title"],
        "seo_description"=>$values["seo_description"],
        "seo_keywords"=>$values["seo_keywords"],

        // basic info
        "bi_active"=>$values["bi_active"],

    );
    $data=array_merge($data,$processed_images);
    $res = $ag_sql->update("blog_authors",$data," id = '{$values['id']}'");
    if(!$res){
        $array_json['error']=$ag_sql->error();
    }
    else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("authors",{$values['id']});
JS;
    }
}


if($values["xeki_admin_action"]=="new_category"){

    $data=array(
        "code"=>$values["code"],

        "title"=>$values["title"],
        "description"=>$values["description"],
        "slug"=>$values["slug"],
        "seo_title"=>$values["seo_title"],
        "seo_description"=>$values["seo_description"],
        "seo_keywords"=>$values["seo_keywords"],
        // basic info
        "bi_active"=>$values["bi_active"],

    );

    $res = $ag_sql->insert("blog_categories",$data);
    if(!$res){
        $array_json['error']=$ag_sql->error();
    }
    else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.edit_item("categories",{$res});
JS;
    }
}
if($values["xeki_admin_action"]=="edit_category"){
    $data=array(
        "code"=>$values["code"],

        "title"=>$values["title"],
        "description"=>$values["description"],
        "tree_dad"=>$values["tree_dad"],
        "slug"=>$values["slug"],
        "order_list"=>$values["order_list"],

        "seo_title"=>$values["seo_title"],
        "seo_description"=>$values["seo_description"],
        "seo_keywords"=>$values["seo_keywords"],

        // basic info
        "bi_active"=>$values["bi_active"],

    );
    $res = $ag_sql->update("blog_categories",$data," id = '{$values['id']}'");
}


if($values["xeki_admin_action"]=="add_category"){
    $render_method = "json";
    $data=array(
        "id_article"=>$values["id_item"],
        "id_category"=>$values["id_ref"],

    );
    $res = $ag_sql->insert("blog_articles_categories_ref",$data);
    if(!$res){
        $array_json['error']=$ag_sql->error();
    }
    else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.launch_control("form_control_articles_categories",{$values['id_item']});
JS;
    }
}

if($values["xeki_admin_action"]=="remove_category"){
    $render_method = "json";
    $res = $ag_sql->delete("blog_articles_categories_ref"," id = {$values['id']}");
    if(!$res){
        $array_json['error']=$ag_sql->error();
    }
    else{
        $array_json['id_item']=$res;
        $array_json['callback']= <<<JS
        js_admin.launch_control("form_control_articles_categories",{$values['id_item']});
JS;
    }
}






if ($render_method == "json") {

    $array_json = utf8ize($array_json);
    $array_json = html_entity_decode($array_json);
    $array_json = json_encode($array_json);

    echo $array_json;
    error_reporting(0);
    \xeki\html_manager::$done_render = true;
}
