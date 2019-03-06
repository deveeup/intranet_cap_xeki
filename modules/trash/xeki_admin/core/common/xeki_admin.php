<?php
namespace xeki_admin;
/**
* Class auth
* version 0.000001
*/
class xeki_admin
{
    /**
    * @var string
    */
    protected $encryption_method = 'sha256';
    /**
    * @var string
    */
    protected $table_user = 'user';
    /**
    * @var string
    */
    protected $field_id = 'id';
    /**
    * @var string
    */
    protected $field_user = 'email';
    /**
    * @var string
    */
    protected $field_password = 'password';
    /**
    * @var string
    */
    protected $field_recover_code = 'recover';

    #db info user_temp
    /**
    * @var string
    */
    protected $table_user_temp = 'customer_temp';
    /**
    * @var string
    */
    protected $field_id_temp = 'id';
    /**
    * @var string
    */
    protected $login_page = 'login';

    /**
    * @var string
    */
    protected $register_page = 'register';

    /**
    * @var string
    */
    protected $logged_page = 'dashboard';

    protected $folder_upload = 'static_files/xeki_admin/uploads/';


    /**
    * @var bool
    */
    protected $logged = false;

    /**
    * @var array
    */
    protected $user = array();
    /**
    * @var array|int
    */
    protected $id = array();
    private $sql = null;

    public $folder_pages = '';
    public $folder_base = '';
    public $default_pages = true;

    public $module_list;
    public $config_params=array();

    function get_folder()
    {
        if ($this->default_pages) return "";
        return $this->folder_pages;
    }

    function get_value_param($key){
        if(!isset($this->config_params[$key])){
            echo "ERROR value $key not found check config of user_zone";
            die();
        }
        return $this->config_params[$key];
    }

    /**
    *
    */
    function __construct($config, $sql)
    {
        $this->config_params=$config;
        $this->base_url=$config['base_url'];
        $this->module_list = $config['modules_to_load'];
        $this->sql = $sql;
    }

    function get_active_admin_module_name(){
        $params = \xeki\core::$URL_PARAMS;
        $last_param = \xeki\core::$URL_PARAMS_LAST;
        $base_admin = $this->base_url;
        if($base_admin == $last_param){
            return "";
        }
        else{
            $next = false;
            foreach ($params as $item){
                if($next) return $item;
                if($item == $base_admin)$next=true;
            }
        }
    }

    function get_controller_for_ws(){
        $name= $this->get_active_admin_module_name();
        if($name=="core")$info_file = \xeki\core::$DIR_PATH."core/modules_config/xeki_admin/core_admin/xeki_admin_ws.php";
        else $info_file = \xeki\core::$DIR_PATH."modules/$name/xeki_admin/xeki_admin_ws.php";


        return $info_file;
    }

    function get_controller_for_html(){
        $name= $this->get_active_admin_module_name();
        if($name=="core")$info_file = \xeki\core::$DIR_PATH."core/modules_config/xeki_admin/core_admin/xeki_admin_html.php";
        else $info_file = \xeki\core::$DIR_PATH."modules/$name/xeki_admin/xeki_admin_html.php";
        return $info_file;
    }

    function get_menu_list(){
        $menu_list=array();

        $info_file = \xeki\core::$DIR_PATH."core/modules_config/xeki_admin/core_admin/xeki_admin.json";
        if(file_exists($info_file)){
            $json_info = file_get_contents($info_file);
            $array_file = json_decode($json_info, true);
            $menu_list = array_merge($menu_list,$array_file ['items_menu']);
        }

        // get modules to read
        $module_list = $this->module_list;
        foreach ($module_list as $item){
            $info_file = \xeki\core::$DIR_PATH."modules/$item/xeki_admin/xeki_admin.json";
            if(file_exists($info_file)){
                $json_info = file_get_contents($info_file);
                $array_file = json_decode($json_info, true);
                $menu_list = array_merge($menu_list,$array_file ['items_menu']);
            }
        }

        // check admin for implementation


        // read .json config for
        return $menu_list;
    }
    function save_images($images_files,$files){
        // Do this more inteligent for parse input images with $images_files


        $this->folder_upload;
        $processed_images = array();



        foreach($files as $name=>$value){
            // d($files[$name]['name']);

            if (isset($files[$name]['name']) && is_array($files[$name]['name'])) {

                foreach ($files[$name]['name'] as $key=>$value_item){
                    $name_image = $files[$name]['name'][$key];
                    $size_image = $files[$name]['size'][$key];
                    $temp_file = $files[$name]['tmp_name'][$key];

                    $new_route=$this->folder_upload . '' . $name_image;
                    $file_route=$this->upload_image($name_image,$temp_file);
                    if ($file_route!==false) {
                        if(!is_array($processed_images[$name])){
                            $processed_images[$name]=array();
                        }
                        $processed_images[$name][$key]=$file_route;
                    }
                    // TODO check if have limit of size, i suppose 5 mb

                }
            }
            elseif (isset($files[$name]['name']) && $files[$name]['name'] != '' && $files[$name]['name'] != 'none') {
                $name_image = time() . $files[$name]['name'];
                $size_image = $files[$name]['size'];
                $temp_file = $files[$name]['tmp_name'];


                $file_route=$this->upload_image($name_image,$temp_file );
                if ($file_route!==false) {
                    $processed_images[$name]=$file_route;

                } else {
                    $error = 2;
                }
            }
        }
////        d($files);

        
        return $processed_images;
    }

    function upload_image($name_image,$route_files){
        // check aws data
        $ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $name_image);
        $name_image = \xeki\core::fix_to_slug($name_image);
        $name_image = time() . $name_image.".".$ext;
        if(!file_exists($route_files)){

            return false;
        }


        $set_aws = $this->get_value_param("aws_upload");

        if($set_aws){

            $set_aws_bucket = $this->get_value_param("aws_bucket");
            $set_aws_folder = $this->get_value_param("aws_folder");
            $set_aws_key_id = $this->get_value_param("aws_key_id");
            $set_aws_access_key = $this->get_value_param("aws_access_key");
            $set_aws_region = $this->get_value_param("aws_region");
            // load aws 
            $config = array(
                'credentials' => array(
                    'key'    => $set_aws_key_id,
                    'secret' => $set_aws_access_key,
                ),
                'region' => $set_aws_region,
                'version' => 'latest',
                // 'scheme'  => 'http',
                'http'    => [
                    'verify' => false
                ],
                'bucket'=>$set_aws_bucket,
            );
            require_once dirname(__FILE__) . "/mini_scripts/aws_upload.php";
            $name_image=$set_aws_folder.$name_image;
            $res = upload_aws($name_image,$route_files,$config);
            return $res;
        }
        else{
            $new_route=$this->folder_upload . '' . $name_image;
            if (move_uploaded_file($route_files,$new_route )) {
                return $new_route;

            } else {
                return false;
            }
        }

    }

    // Actual data is necesary for array N data
    function process_data($form_data,$values){
        // process and save images 
        $processed_images = $this->save_images(array(), $_FILES);
        // firts load data no files no images no videos
////        d($values);
////        d($_FILES);
        $data_to_load=array();
        $images_files=array();
        foreach($form_data as $item){
            
//            $item["name"];
            if(isset($values[$item["name"]])){
                if($item['type']=="image" || $item['type']=="video"){
                    $images_files[$item["name"]]="none";
                }

                else{
                    $data_to_load[$item["name"]]=$values[$item["name"]];
                }
            }
            elseif($item['type']=="array_json"){ ## for array json

                $name_item_array = $item['name'];
                $array_inner_items = array();

                $items_on_array = array();
                foreach ($item['array_json_data'] as $key=>$sub_item){
                    $items_on_array[$sub_item['value_name']]=$sub_item['type'];
                }

                // create new array items


                // old items
                // get items for this name
//                d($values);
                foreach ($values as $key=>$sub_item){
                    if(strpos($key,"{$name_item_array}_")!==false){
                        $save = false;
//                        d($sub_item);
                        foreach ($sub_item as $sub_key=>$value){
                            $sub_key=str_replace("_xeki_hidden_info","",$sub_key);
                            if($value!=''){
                                $save=true;
                            }

                            // valid if have image
                            if($_FILES[$key]['name'][$sub_key]!=''){
                                $save=true;
                            }
                        }
                        if($save){
                            $array_inner_items[$key]=$sub_item;
                        }

                    }
                }

//                d($array_inner_items);

////                d($_FILES);
                // clean
                
                // Json array items
                foreach ($array_inner_items as $key=>$sub_item){
                    // sub estructure

                    foreach ($sub_item as $sub_key=>$sub_estructure) {// sub estructure

                        $temp_sub_key = $sub_key;
                        $temp_sub_key = str_replace("_xeki_hidden_info","",$temp_sub_key);

                        if ($items_on_array[$temp_sub_key] == "image") {

                            $temp_image_files = array(
                                $key . '[' . $temp_sub_key . ']' => 'none',
                            );
//                            d("key");
//                            d($key);
//                            d("sub_key");
//                            d($temp_sub_key);
//                            d("images_list");
//                            d($processed_images);
//                            d("files");
//                            d($_FILES);
//                            d("images");
//                            d($processed_images[$key]);
                            if (isset($processed_images[$key][$temp_sub_key])) {
                                $array_inner_items[$key][$temp_sub_key] = $processed_images[$key][$temp_sub_key];
                                
                            }
                            else{
                                $array_inner_items[$key][$temp_sub_key]=$array_inner_items[$key][$temp_sub_key."_xeki_hidden_info"];
                            }

                        }
                    }
                    unset($processed_images[$key]);


                    // check is is image or video
                }   

//                d($array_inner_items);

                // json
                // add to main data
                // clean data
                foreach ($array_inner_items as $cc_key=>$cc_item){
                    foreach ($cc_item as $cc_2_key=>$cc_2_item) {
                        $array_inner_items[$cc_key][$cc_2_key] = \xeki\core::text_to_acutes($cc_2_item);
                    }
                }

                $json_array = json_encode ($array_inner_items);
                $data_to_load[$item["name"]]=$json_array ;


            }
            else{
                if($item['type']=="bool" ){
                    $data_to_load[$item["name"]]="off";
                }
            }
        }
        

        $array_json['processed_images']=$processed_images;
        $data=array_merge($data_to_load,$processed_images);


        // convert to json structures


        // get arrays

        return $data;
    }
    // For

    function form_generator($field){

        $html_return = "";
        $valid_data = false;
        // check if is array
        if(is_array($field))$valid_data=true;

        // check if is json and convert to array
        if(!$valid_data){
            // process json
        }
        // check if have all data

        if($valid_data){
            $errors = false;
            if(!isset($field['value']))$field['value']="";
            if(!isset($field['required']))$field['required']="";

            if($errors)$valid_data= false;
        }

        // process data list_images we need inline container forms
        if($valid_data){

            $type = $field['type'];
            $name = $field['name'];
            $editable = isset($field['editable'])?$field['editable']==false?"disabled":"":"";
            $name_id = $name; // name without []
            $name_id = str_replace("[","",$name_id);
            $name_id = str_replace("]","",$name_id);
            $title = $field['title'];
            $required = $field['required']?"required":"";
            $value = $field['value'];
            $help_text = $field['help_text'];
            $help_text = $field['description']!==""?"<small class='form-text text-muted'>{$help_text}</small>":"";
            $array_json_data = $field['array_json_data']!==array()?$field['array_json_data']:"";
            $class = $field['class'];
            $class_input = $field['class_input'];



//            <small class="form-text text-muted">Like this info for form in add data product</small>
            if($type == "separator"){
                $html_return.=<<<HTML
                <div class="{$class} form-group separator">
                    <h3>{$title}</h3>
                    <hr>
                </div>
HTML;
            }

            if($type == "sub-separator"){
                $html_return.=<<<HTML
                <div class="{$class} form-group sub-separator">
                    <h4>{$title}</h4>
                    <hr>
                </div>
HTML;
            }

            if($type == "sub-2-separator"){
                $html_return.=<<<HTML
                <div class="{$class} form-group sub-separator">
                    <h5>{$title}</h5>
                    <hr>
                </div>
HTML;
            }

            if($type == "array_json"){
                $array_json_data;

                $form_new_items="";
                foreach ($array_json_data as $item){
                    $item['name']="{$name}_N_[{$item['value_name']}]";
                    $form_new_items.= $this->form_generator($item);
                }


                $new_item="<div class=\"{$class} form-group\">
                    <label for=\"cmn-toggle-{$name}\">{$title}</label>
                    <hr>";


                $new_item .= "<a class='btn' data-toggle='modal' data-target='#modal_{$name}'>Add new item <i class='fa fa-plus' aria-hidden='true'></i> </a>";
                $new_item .= "
                <div id='modal_{$name}' class='modal fade' role='dialog'>
                      <div class='modal-dialog'>
                    
                        <!-- Modal content-->
                        <div class='modal-content'>
                          <div class='modal-header'>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                            <h4 class='modal-title'>New Item</h4>
                          </div>
                          <div class='modal-body'>
                                {$form_new_items}
                                <a class='btn btn-primary submit_top'>Submit</a>
                          </div>
                          
                        </div>
                    
                      </div>
                    </div>";


//                $separator = array(
//                    "type"=>"sub-2-separator",
//                    "title"=>"",
//                );
//                $new_item .= $this->form_generator($separator);



                // json items

                $json = <<<JSON
                [
                    {
                        "title" : "foodier 1",
                        "image" : "static_files/images/user1.png", 
                        "description" : "Vestibulum luctus pharetra consectetur. Etiam et cursus augue. Sed fringilla ultricies lectus eget pellentesque"                           
                    },
                    {
                        "title" : "foodier 2",
                        "image" : "static_files/images/user2.png", 
                        "description" : "Vestibulum luctus pharetra consectetur. Etiam et cursus augue. Sed fringilla ultricies lectus eget pellentesque"                           
                    },
                    {
                        "title" : "foodier 3",
                        "image" : "static_files/images/user3.png", 
                        "description" : "Vestibulum luctus pharetra consectetur. Etiam et cursus augue. Sed fringilla ultricies lectus eget pellentesque"                           
                    },
                    {
                        "title" : "foodier 4",
                        "image" : "static_files/images/user4.png", 
                        "description" : "Vestibulum luctus pharetra consectetur. Etiam et cursus augue. Sed fringilla ultricies lectus eget pellentesque"                           
                    }
                ]
JSON;
                $json = $value;
                $items = json_decode($json,true);


                $edit_form ="";
                $count = 0;

                // box ini

                $edit_form .= "<div class='list_box'>";
                $edit_form .= "    <div class='row'>";

                $count=0;

                foreach ($items as $value_items){

                    $item_separator = $count+1;
                    $separator = array(
                        "type"=>"sub-2-separator",
                        "title"=>"",
                    );

                    $edit_form .= "<div class='loaded_item_box col-md-4'>";
                    $edit_form .= $this->form_generator($separator);

                    $edit_form .= "<div class='item-box' id='{{$name}_{$count}}'>";

                    $form_edit_items="";
                    foreach ($array_json_data as $item){

                        $item['value'] = $value_items[$item['value_name']];
                        $item['name'] = "{$name}_{$count}_[{$item['value_name']}]";
                        $item['editable'] = false;
                        if($item['preview']==true){
                            $edit_form .= $this->form_generator($item);
                        }
                        $item['editable'] = true;
                        $form_edit_items.= $this->form_generator($item);
                    }


                    $edit_form .="
                                  <div class='inner_item_box_controlors'>
                                        <a data-toggle='modal' data-target='#modal_{$name}_{$count}' class='btn btn-warning pull-right'>Edit</a>
                                  </div>
                                ";


                    $new_item .= "
                    <div id='modal_{$name}_{$count}' class='modal fade' role='dialog'>
                      <div class='modal-dialog'>
                    
                        <!-- Modal content-->
                        <div class='modal-content'>
                          <div class='modal-header'>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                            <h4 class='modal-title'>Edit Item</h4>
                          </div>
                          <div class='modal-body'>
                                {$form_edit_items}
                                <a class='submit_top btn btn-primary'>Submit</a>
                          </div>
                          
                        </div>
                    
                      </div>
                    </div>";

                    $edit_form .="<div class='inner_item_box_controlors'>
                                    <a href='#' id_to_delete='modal_{$name}_{$count}' class='btn btn-danger items_array_delete pull-right'>Delete</a>
                                  </div>
                                    ";

                    $edit_form .= "    </div>";
                    $edit_form .= "</div>";

                    $count++;
                }
                $edit_form .= "    </div>";
                $edit_form .= "</div>";
                $edit_form .= "</div>";
////                d($edit_form);

//                die();


                // forms edit
                // new form

                // edit
                $html_return.=$new_item;
                $html_return.=$edit_form;
            }

            if($type == "text"){
                $html_return.=<<<HTML
                <div class="{$class} form-group">
                    <label for="id_{$name_id}">{$title}</label>
                    <input id="id_{$name_id}" value="{$value}" name="{$name}" type="text" class="{$class_input} form-control"  aria-describedby="input_text" placeholder="{$title}" {$required} {$editable}>
                    {$help_text}
                </div>
HTML;
            }

            if($type == "textarea"){
                $html_return.=<<<HTML
                <div class="{$class} form-group">
                    <label for="id_{$name_id}">{$title}</label>
                    <textarea name="{$name}" class="form-control" id="id_{$name_id}" rows="3" placeholder="{$title}" {$required} >{$value}</textarea>
                    {$help_text}
                </div>
HTML;
            }
            if($type == "admin_blog"){
                $html_return.=<<<HTML
                <div class="{$class} form-group">
                    <label for="id_{$name_id}">{$title}</label>
                    <textarea name="{$name}" class="form-control admin_blog" id="id_{$name_id}" rows="3" placeholder="{$title}" {$required} >{$value}</textarea>
                    {$help_text}
                </div>
HTML;
            }

            if($type == "number"){
                $html_return.=<<<HTML
                <div class="{$class} form-group">
                    <label for="id_{$name_id}">{$title}</label>
                    <input id="id_{$name_id}" value="{$value}" name="{$name}" type="text" class="form-control"  aria-describedby="input_text" placeholder="{$title}" {$required}>
                    {$help_text}
                </div>
HTML;
            }

            if($type == "bool"){
                $bi_active_html = $value == "on" ? "checked" : '';;
                $html_return.=<<<HTML
                 <div class="{$class} form-group">
                    <label for="cmn-toggle-{$name}">{$title}</label>
                    <div class="switch">
                        <input {$bi_active_html} name="{$name}" id="cmn-toggle-{$name}" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" >
                        <label for="cmn-toggle-{$name}"></label>
                    </div>
                    {$help_text}
                 </div>
HTML;
            }

            if($type == "image"){

//                foodies_array_N_[image]
                // fix image name


                $backup_name=$name;
                if(strpos($backup_name,"]")!==false){
                    $backup_name=str_replace("]","_xeki_hidden_info]",$name);
                }
                else{
                    $backup_name="{$name}_xeki_hidden_info";
                }
                if($editable=="disabled"){
                    $html_return.=<<<HTML
                        <div class="{$class} image-box form-group">
                            <label for="id_{$name_id}" class="label-image">{$title}</label><br>
                            <img src="{$value}" class="img-fluid" id="_preview_id_{$name_id}">
                            <input name="$backup_name" type="hidden" value="{$value}">
                            {$help_text}
                        </div>
HTML;
                }
                else{
                    $html_return.=<<<HTML
                        <div class="{$class} image-box form-group">
                            <label for="id_{$name_id}" class="label-image">{$title}</label><br>
                            <img src="{$value}" class="img-fluid" id="_preview_id_{$name_id}">
                            <label class="custom-file">
                              <input name="{$name}"  type="file" id="id_{$name_id}" class="custom-file-input image-input-preview" {$required}>
                              <input name="$backup_name"   type="hidden" value="{$value}"> 
                              <span class="custom-file-control"></span>
                            </label>
                            {$help_text}
                        </div>
HTML;
                }
            }

            if($type == "video"){
                $html_return.=<<<HTML
                <div class="{$class} form-group">
                    <label for="id_{$name_id}">{$title}</label><br>
                    <img src="{$value}" class="img-fluid">
                    <label class="custom-file">
                      <input name="{$name}"  type="file" id="id_{$name_id}" class="custom-file-input" {$required}> 
                      <span class="custom-file-control"></span>
                    </label>
                    {$help_text}
                </div>
HTML;
            }



            if($type == "select_table"){
                // query options // TODO move this to other class

                // check if table has active

                $sql = \xeki\module_manager::import_module("db-sql");
                $to_check_fields = $sql->query("SHOW COLUMNS FROM {$field['table']};");
//                d($to_check_fields);
                $has_active = false;
                foreach ($to_check_fields as $to_check_field){
                    if($to_check_field['Field']=="active"){
                        $has_active = true;
                    }
                }
                $options=array();
                if($has_active){
                    $options = $sql->query("SELECT * FROM {$field['table']} where active='on' ");
                }
                else{
                    $options = $sql->query("SELECT * FROM {$field['table']} ");
                }



                // options
                $select_options = $field['select_options'];
                $html_options="";

                foreach ($options as $key=>$value_local){
                    $select = $value_local['id']==$value?"selected":"";
                    $html_options.="<option value='{$value_local['id']}' $select>{$value_local[$field['table_title']]}</option>";
                }

                $html_return.=<<<HTML
                <div class="{$class} form-group">
                    <label for="id_input_code">{$title}</label>
                    <select name="{$name}" class="form-control">
                      {$html_options}
                    </select>
                    {$help_text}
                </div>
HTML;
            }

            if($type == "select"){
                // options
                $select_options = $field['select_options'];
                $html_options="";
                
                foreach ($select_options as $key=>$value_local){
                    $select = $key==$value?"selected":"";
                    $html_options.="<option value='{$key}' $select>{$value_local}</option>";
                }

                $html_return.=<<<HTML
                <div class="{$class} form-group">
                    <label for="id_input_code">{$title}</label>
                    <select name="{$name}" class="form-control">
                      {$html_options}
                    </select>
                    {$help_text}
                </div>
HTML;
            }

            if($type == "date"){
                // options

                $html_return.=<<<HTML
                <div class="{$class} form-group">
                    <label for="id_{$name_id}">{$title}</label>
                    <input id="id_{$name_id}" value="{$value}" name="{$name}" type="text" class="form-control datepicker"  aria-describedby="input_text" placeholder="{$title}" {$required}>
                    {$help_text}
                </div>
HTML;
            }

            if($type == "date_time"){
                // options

                $html_return.=<<<HTML
                <div class="{$class} form-group">
                    <label for="id_{$name_id}">{$title}</label>
                    <input id="id_{$name_id}" value="{$value}" name="{$name}" type="text" class="form-control timepicker"  aria-describedby="input_text" placeholder="{$title}" {$required}>
                    {$help_text}
                </div>
HTML;
            }


            return $html_return;
        }
        else{
            return false;
        }

    }
   
}