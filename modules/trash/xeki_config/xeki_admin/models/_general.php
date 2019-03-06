<?php

if ($module_action_code == "config" || $module_action_code == "list-languages") {
    $module['title'] = "Config";
    $module['elements'] = array();
    // check languages
    $ag_config = \xeki\module_manager::import_module("xeki_config");
    $languages = $ag_config->get_value_param("languages");
    if( $languages ){
        $element_buttons = array(
            "type" => "buttons",
            "class" => "col-md-12 square-btn",
            "buttons" => [
                array(
                    "type" => "url",
                    "icon" => "fa fa-sticky-note-o",
                    "class" => "square-btn",
                    "text" => "Languages",
                    "background" => "#66ccff",
                    "url" => "xeki_config/list-languages"
                ),
                array(
                    "type" => "url",
                    "icon" => "fa fa-sticky-note-o",
                    "class" => "square-btn",
                    "text" => "Config",
                    "background" => "#66ccff",
                    "url" => "xeki_config/config?lang=main"
                ),

            ],
        );

        array_push($module['elements'], $element_buttons);
    }

}