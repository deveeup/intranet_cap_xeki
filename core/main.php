<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

\xeki\html_manager::add_extra_data("cap","https://capillasdelafe.com/funeraria");
\xeki\html_manager::add_extra_data("coor","https://coorserpark.com/");
\xeki\html_manager::add_extra_data("alt","Intranet Funeraria Capillas de La Fe -");