<?php
namespace ag_config;

class config
{
    public $popups=array();
    
    private $type="";
    
    private $config_params;


    private $sql;

    private $lang="main";
    // cdn
    
    /**
    * mail constructor.
    * @param $from
    * @param $path
    * @param $domain_mail_gun
    * @param $key_mailgun
    */
    public function __construct($config)
    {
        // load seccion xeki_popups
        $this->config_params = $config;

        $this->sql = \xeki\module_manager::import_module("xeki_db_sql");


        $this->type=$config['type'];

        // load of cookies 
        if(isset($_SESSION['AG_CONFIG_LANG'])){
            if($_SESSION['AG_CONFIG_LANG']!=''){
                $this->lang=$_SESSION['AG_CONFIG_LANG'];
            }
        }
        
        
    }

    function get_value_param($key){
        if(!isset($this->config_params[$key])){
            echo "ERROR value $key not found check config";
            die();
        }
        return $this->config_params[$key];
    }
    function get_data($lang=false){
       
        $lang_active = "";
        $info = array();
        $table =$this->get_value_param('table');
        if($lang!==false){
            $lang_active = $lang;
            
            
            $query = "SELECT * from {$table} where lang='{$lang_active}'";
            $info_sql=$this->sql->query($query);
            foreach ($info_sql as $key=>$item){
                $info[$item['code']]=$item['info'];
            }
        }
        else{
            // load and overflow
            $lang_active = $this->lang;
            // d($lang_active);
            $query = "SELECT * from {$table} where lang='{$lang_active}'";
            $info_sql = $this->sql->query($query);
            foreach ($info_sql as $key=>$item){
                if(isset($info[$item['code']])){
                    if($info[$item['code']]==''){
                        $info[$item['code']]=$item['info'];
                    }
                }
                else{
                    $info[$item['code']]=$item['info'];
                }
            }
        }
        
        $query = "SELECT * from {$table} where lang='main'";
        $info_sql = $this->sql->query($query);
        foreach ($info_sql as $key=>$item){
            if(isset($info[$item['code']])){
                if($info[$item['code']]==''){
                    $info[$item['code']]=$item['info'];
                }
            }
            else{
                $info[$item['code']]=$item['info'];
            }
        }


        // $info = clean_html($info);
        return $info;
    }
    function get_form($lang=false){
        $ag_admin_module = \xeki\module_manager::import_module("ag_admin");
        $variables = $this->get_value_param('box_items');
        $info = $this->get_data($lang);

        $field_controls="";
        foreach($variables as $item){
            $item['value'] = $info[$item['name']];
            $html_form = $ag_admin_module->form_generator($item);
            $field_controls .= $html_form;
        }
        return $field_controls;
    }
    function process_values($values,$lang=false){
        $ag_admin_module = \xeki\module_manager::import_module("ag_admin");
        $lang_active = "";
        if($lang!==false){
            $lang_active = $lang;
        }
        else{
            $lang_active = $this->lang;
        }

        // check db
        $table =$this->get_value_param('table');        
        $schema=$this->sql->db;
        $query ="SELECT * FROM information_schema.tables WHERE table_name = '{$table}' and TABLE_SCHEMA='{$schema}' LIMIT 1;";
        $item = $this->sql->query($query);
        if(count($item)==0){
            // check table
            $query = "CREATE TABLE `{$table}` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `code` VARCHAR(45) NULL,
            `lang` VARCHAR(45) NULL DEFAULT 'main',
            `info` VARCHAR(250) NULL,
            PRIMARY KEY (`id`));
          ";
            $this->sql->execute($query);

            $query = "CREATE TABLE `{$table}_languages` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(125) NULL,
                `active` VARCHAR(125) NULL,
                `datecreation` datetime NULL,
                PRIMARY KEY (`id`));
              ";
            $this->sql->execute($query);
            $query = "CREATE TABLE `{$table}_languages_objects` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `code` VARCHAR(125) NULL,
                `table` VARCHAR(125) NULL,
                `info` LONGTEXT NULL,
                `datecreation` datetime NULL,
                PRIMARY KEY (`id`));
              ";
            $this->sql->execute($query);
        }
        $variables = $this->get_value_param('box_items');
        $values = $ag_admin_module->process_data($variables,$values);
        foreach ($values as $key => $value) {

            if($value!=''){
                $data= array(
                    'lang'=>$lang_active,
                    'code'=>$key,
                    'info'=>$value,
                );
    
                // if not exit create else update
                $query = "SELECT * from {$table} where code='{$key}' and lang='{$lang_active}'";
                $info = $this->sql->query($query);
    
                if(count($info)>0){
                    $this->sql->update("{$table}",$data," code='{$key}' and lang='{$lang_active}' ");
                }else{
                    $this->sql->insert("{$table}",$data);
                }
            }            
        }

    }
    function get_active_lang(){
        return $_SESSION['AG_CONFIG_LANG'];
    }
    function set_active_lang($lang){
        // check is exist
        
        if($_SESSION['AG_CONFIG_LANG']==$lang){
            return true;
        }
        else{
            $lang_res = $this->get_lang($lang);
            if($lang===false ||$lang_res['main']=='on'){
                $_SESSION['AG_CONFIG_LANG']= $this->get_lang_main();
                $_SESSION['AG_CONFIG_LANG']="main";
                $this->lang="main";
            }
            else{
                $_SESSION['AG_CONFIG_LANG']=$lang;
                $this->lang=$lang;
            }
        }
    }
    function get_lang_list(){
        $table =$this->get_value_param('table');
        $query = $query = "SELECT * from {$table}_languages where active='on'";
        $info = $this->sql->query($query);
        // re order firts selected
        return $info;
    }
    function get_lang($code){
        $table =$this->get_value_param('table');
        $query = $query = "SELECT * from {$table}_languages where code_lang='{$code}' and active='on'";
        $info = $this->sql->query($query);
        if(count($info)>0){
            return $info[0];
        }
        else{
            return false;
        }
    }
    function get_lang_main(){
        $table =$this->get_value_param('table');
        $query = $query = "SELECT * from {$table}_languages where main='on' and active='on'";
        $info = $this->sql->query($query);
        if(count($info)>0){
            return $info[0]['code_lang'];
        }
        else{
            return false;
        }
    }
    function get_select_lang(){
        
        $active_lagns = $this->get_lang_list();
        $options = "";

        foreach ($list_langs as $key => $value) {
            $options .= "<option name='{$value['code_lang']}'>{$value['name']}</option>";
        }

        $html_languages=<<<HTML
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>
                        Choose language
                    </label>
                    <select class='form-control'>
                        {$options}
                    </select>
                </div>
            </div>
        </div>
HTML;
        return $html_languages;
    }
    

    function change_lang(){

    }

    function load_local_lang(){
        $ip = $_SERVER['REMOTE_ADDR'];
        $data = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip={$ip}"));
        $country_code = $data['geoplugin_countryName'];
        return $country_code;

    }

    function map_lang($code_country){
        //TODO
    }
    
    

    
    
    
}