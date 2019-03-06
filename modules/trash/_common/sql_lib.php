<?php

function createSqlxeki_v1($sql_array, $sql)
{
    $pk = 'id';
    $query = "CREATE TABLE IF NOT EXISTS {$sql_array['table']}
        ( $pk int(11) NOT NULL AUTO_INCREMENT , PRIMARY KEY ( $pk ))
        ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;";
    if (!$sql->execute($query)) {
        print($query);
        print($sql->error());
    }
    foreach ($sql_array['elements'] as $key => $value) {
        $sqlTemp = "ALTER TABLE `{$sql_array['table']}` ADD COLUMN `$key`  ";
        $opcion = explode(':', $value);
        ##type
        if ($opcion[0] == "int") $sqlTemp .= 'int(11)';
        else if ($opcion[0] == "number") $sqlTemp .= 'int(11)';
        else if ($opcion[0] == "float") $sqlTemp .= 'float(11)';
        else if ($opcion[0] == "text") $sqlTemp .= 'varchar(500)';
        else if ($opcion[0] == "tags") $sqlTemp .= 'varchar(500)';
        else if ($opcion[0] == "textArea") $sqlTemp .= 'longtext';
        else if ($opcion[0] == "textAreaBlog") $sqlTemp .= 'longtext';
        else if ($opcion[0] == "textAreaSimple") $sqlTemp .= 'longtext';
        else if ($opcion[0] == "date") $sqlTemp .= 'date';
        else if ($opcion[0] == "hour") $sqlTemp .= 'varchar(200)';
        else if ($opcion[0] == "select") $sqlTemp .= 'varchar(100)';
        else if ($opcion[0] == "selectSub") $sqlTemp .= 'int(10)';
        else if ($opcion[0] == "image") $sqlTemp .= 'varchar(500)';
        else if ($opcion[0] == "password") $sqlTemp .= 'varchar(500)';
        else if ($opcion[0] == "separator") $sqlTemp = 'PASS';

        else if ($opcion[0] == "video") {
            $sqlTemp .= '
            varchar(100) NOT NULL ,
            codigo varchar(100) NOT NULL ,
            linkVideo varchar(100) NOT NULL ,
            embedVideo varchar(100) NOT NULL ,
            linkImagen varchar(100)
        ';
        } else {
            echo " ERROR DB CONFIG ";
            print_r($opcion);
            print_r($sql_array);
            die();
        }
        if ($sqlTemp != 'PASS') {
            $sqlTemp .= " ";
            if ($opcion[1] == "NN") $sqlTemp .= "NOT NULL";
            $sqlTemp .= " ";
            if ($opcion[2] == "AI") $sqlTemp .= "AUTO_INCREMENT";
            $sqlTemp .= " ";
            $sqlTemp .= "";
            $sqlTemp .= ';' . "\n";
//            $sql .= $sqlTemp;
            print_r($sqlTemp);
            if (!$sql->execute($sqlTemp)) {
                print($sqlTemp);
                print($sql->error());
            }
        }
    }
    return $sql;
}