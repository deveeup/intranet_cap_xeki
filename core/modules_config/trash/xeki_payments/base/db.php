<?php
        require_once('libs/mysql.php');
        $dbhost = '127.0.0.1'; # for production
        $dbhost = '107.180.1.32'; # for local  
        $dbuser = 'capilla_page';
        $dbpass = 'BSDrs1nkbkgW';
        $db = 'capilla_page';
        $connection_information = array(
            'host' => $dbhost,
            'user' => $dbuser,
            'pass' => $dbpass,
            'db' => $db
        );
        $sql = new mysql($connection_information);
    