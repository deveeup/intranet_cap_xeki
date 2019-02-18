<?php
namespace xeki_popup;

class popup
{
    public $popups=array();
    
    private $type="";
    
    private $config_array;
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
        $this->config_array=$config;
        
        $this->type=$config['type'];

        // load of cookies 
        if(count($_SESSION["xeki_popup_ARRAY"])>0)
            $this->popups = $_SESSION["xeki_popup_ARRAY"] ;
        
    }
    
    
    public function add_msg($title,$message=false)
    {
        if(!$message){
            $message = $title;
        }
        $item = array('title'=>$title,'message'=>$message);
        array_push($this->popups,$item);
        
        $_SESSION["xeki_popup_ARRAY"] = $this->popups;
    }
    
    public function run_popups(){
        $_SESSION["xeki_popup_ARRAY"]= array();
        if ($this->type=="messiJs") {
            $this->messiJS();
        }
        
        if ($this->type=="boostrap") {
            $this->boostrap();
        }
        
        // clean seccion xeki_popups
        
    }
    public function boostrap(){
        if(count($this->popups)==0)return;
        // run libs TODO export to method
        
        $popups = $this->popups;
        $count = 0;
        foreach ($popups as $key=>$item  ){
            $count++;
            echo <<<HTML
            <div id="alert_ag_{$count}" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xs" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <p>{$item['message']}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Continuar</button>
                        </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            
HTML;


            echo <<<HTML
            <script>
            $( document ).ready(function() {
                $('#alert_ag_{$count}').modal('show')
            });
            </script>
HTML;
        }
        
    }
    public function messiJS(){
        if(count($this->popups)==0)return;
        // run libs TODO export to method
        $js_libs = $this->config_array['messi_js_urls'];
        foreach ($js_libs as $key=>$item  ){
            echo <<<HTML
            <script>
            if (typeof($key) == 'undefined') {
                document.write("<scr" + "ipt type='text/javascript' src='{$item}'></scr" + "ipt>");
            }
            </script>
HTML;
        }
        
        // run css TODO export to method
        
        $css_libs = $this->config_array['messi_css_urls'];
        foreach ($css_libs as $key=>$item  ){
            echo <<<HTML
            <link rel='stylesheet' href='{$item}'>
HTML;
        }
        
        // run popups
        $popups = $this->popups;
        foreach ($popups as $key=>$item  ){
            echo <<<HTML
            <script>
            $( document ).ready(function() {
                new Messi(
                "{$item['message']}",
                {modal: true, animate:{ open: 'fadeIn',close: 'fadeOut' }}
                );
            });
            </script>
HTML;
        }
        
    }
    
    
    
}