<?php

class mysql
{
    var $con;

    function __construct($db = array())
    {
        $default = array(
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'db' => 'test'
        );
        $db = array_merge($default, $db);
//        print_r($db);
        $this->con = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['db']) or die ('Error connecting to MySQL');
    }

    function __destruct()
    {
        mysqli_close($this->con);
    }

    function error()
    {
        echo mysqli_errno($this->con) . ": " . mysqli_error($this->con) . "\n";
    }

    function query($s = '', $organize = false)
    {
        if (!$q = mysqli_query($this->con, $s)) return false;
        if (is_numeric($q)) if ($q == 1) return true;
        $fields = mysqli_fetch_fields($q);
        $rez = array();
        $count = 0;
        if ($organize) {##divide for tables
            while ($line = mysqli_fetch_array($q, MYSQLI_NUM)) {
                foreach ($line as $field_id => $value) {
                    $rsfInfo = $fields[$field_id];
                    $table = $rsfInfo->table;
                    if ($table === '') $table = 0;
                    $rez[$count][$table][$rsfInfo->name] = $value;
                }
                ++$count;
            }
        } else {
            while ($line = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
                $rez[$count] = $line;
                ++$count;
            }
        }
        return $rez;
    }

    function execute($s = '')
    {
        if (mysqli_query($this->con, $s)) return true;
        return false;
    }

    function select($options)
    {
        $default = array(
            'table' => '',
            'fields' => '*',
            'condition' => '1',
            'order' => '1',
            'limit' => 50
        );
        $options = array_merge($default, $options);
        $sql = "SELECT {$options['fields']} FROM {$options['table']} WHERE {$options['condition']} ORDER BY {$options['order']} LIMIT {$options['limit']}";
        return $this->query($sql);
    }

    /**
     * @param $options
     * @return bool
     */
    function row($options)
    {
        $default = array(
            'table' => '',
            'fields' => '*',
            'condition' => '1',
            'order' => '1',
            'limit' => 1
        );
        $options = array_merge($default, $options);
        $sql = "SELECT {$options['fields']} FROM {$options['table']} WHERE {$options['condition']} ORDER BY {$options['order']}";
        $result = $this->query($sql);
        if (empty($result[0])) return false;
        return $result[0];
    }

    function get($table = null, $field = null, $conditions = '1')
    {
        if ($table === null || $field === null) return false;
        $result = $this->row(array(
            'table' => $table,
            'condition' => $conditions,
            'fields' => $field
        ));
        if (empty($result[$field])) return false;
        return $result[$field];
    }

    function update($table = null, $array_of_values = array(), $conditions = 'FALSE')
    {
        if ($table === null || empty($array_of_values)) return false;
        $what_to_set = array();
        foreach ($array_of_values as $field => $value) {
            if (is_array($value) && !empty($value[0])) $what_to_set[] = "`$field`='{$value[0]}'";
            else $what_to_set [] = "`$field`='" . mysqli_real_escape_string($this->con,$value) . "'";
        }
        $what_to_set_string = implode(',', $what_to_set);
        $querystr="UPDATE $table SET $what_to_set_string WHERE $conditions";
        return $this->execute($querystr);
    }

    function insert($table = null, $array_of_values = array())
    {
        if ($table === null || empty($array_of_values) || !is_array($array_of_values)) return false;
        $fields = array();
        $values = array();
        foreach ($array_of_values as $id => $value) {
            $fields[] = $id;
            if (is_array($value) && !empty($value[0])) {
                $values[] = $value[0];
            } else {
                $value = $this->escape_string($value);

                $values[] = "'" . mysqli_real_escape_string($this->con,$value) . "'";
            }
        }
        $s = "INSERT INTO $table (" . implode(',', $fields) . ') VALUES (' . implode(',', $values) . ')';
        if (mysqli_query($this->con, $s)) return mysqli_insert_id($this->con);
        return false;
    }


    function createTable($table = null, $array_of_values = array())
    {
        if ($table === null || empty($array_of_values) || !is_array($array_of_values)) return false;
        $fields = array();
        $values = array();
        foreach ($array_of_values as $id => $value) {
            $fields[] = $id;
            if (is_array($value) && !empty($value[0])) $values[] = $value[0];
            else $values[] = "'" . mysqli_real_escape_string($this->con,$value) . "'";
        }
        $s = "CREATE TABLE IF NOT EXISTS $table (" . implode(',', $fields) . ') VALUES (' . implode(',', $values) . ')';
        print $s;
//        if (mysqli_query($s,$this->con)) return mysqli_insert_id($this->con);
        return false;
    }

    function delete($table = null, $conditions = 'FALSE')
    {
        if ($table === null) return false;
        return $this->execute("DELETE FROM $table WHERE $conditions");
    }

    #adds

    function getAllId($table = null, $id = null)
    {
        if ($table === null || $id === null) return false;
        $result = $this->row(array(
            'table' => $table,
            'condition' => 'id=' . $id
        ));
        if (empty($result)) return false;
        return $result;
    }

    function getById($table = null, $id = null)
    {
        if ($table === null || $id === null) return false;
        $result = $this->row(array(
            'table' => $table,
            'condition' => 'id=' . $id
        ));
        if (empty($result)) return false;
        return $result;
    }

    #adds
    function getBySlug($table = null, $id = null)
    {
        if ($table === null || $id === null) return false;
        $result = $this->row(array(
            'table' => $table,
            'condition' => 'slug="' . $id . '"'
        ));
        if (empty($result)) return false;
        return $result;
    }

    function getBy($table = null, $data = null, $id = null)
    {
        if ($table === null || $id === null || $data == null) return false;
        $result = $this->row(array(
            'table' => $table,
            'condition' => $data . '="' . $id . '"'
        ));
        if (empty($result)) return false;
        return $result;
    }

    function getAllByRow($table = null, $row = null, $data = null)
    {
        if ($table === null || $row === null || $data === null) return false;
        $result = $this->row(array(
            'table' => $table,
            'condition' => $row . '=' . $data
        ));
        if (empty($result)) return false;
        return $result;
    }

    function getDataLogin($login = null)
    {
        if ($login === null) return false;
        $result = $this->row(array(
            'table' => "usuario",
            'condition' => 'USUA_USUARIO="' . $login . '"'
        ));
        if (empty($result)) return false;
        return $result;
    }

    function escape_string($cadena)
    {
        $cadena = str_replace('á', "&aacute;", $cadena);
        $cadena = str_replace('é', "&eacute;", $cadena);
        $cadena = str_replace('í', "&iacute;", $cadena);
        $cadena = str_replace('ó', "&oacute;", $cadena);
        $cadena = str_replace('ú', "&uacute;", $cadena);
        $cadena = str_replace('Á', "&Aacute;", $cadena);
        $cadena = str_replace('É', "&Eacute;", $cadena);
        $cadena = str_replace('Í', "&Iacute;", $cadena);
        $cadena = str_replace('Ó', "&Oacute;", $cadena);
        $cadena = str_replace('Ú', "&Uacute;", $cadena);
        $cadena = str_replace('ñ', "&ntilde;", $cadena);
        $cadena = str_replace('Ñ', "&Ntilde;", $cadena);


        $cadena = str_replace('í“', "&Oacute;", $cadena);
        $cadena = str_replace('ñ', "&ntilde;", $cadena);
        $cadena = str_replace('í±', "&ntilde;", $cadena);
        $cadena = str_replace('Ñ', "&Ntilde;", $cadena);
        $cadena = str_replace('í‘', "&Ntilde;", $cadena);


        $cadena = str_replace('á', "&aacute;", $cadena);
        $cadena = str_replace('é', "&eacute;", $cadena);
        $cadena = str_replace('í', "&iacute;", $cadena);
        $cadena = str_replace('ó', "&oacute;", $cadena);
        $cadena = str_replace('ú', "&uacute;", $cadena);

        $cadena = str_replace('Ã³', "&oacute;", $cadena);
        $cadena = str_replace('Ã¡', "&aacute;", $cadena);
        $cadena = str_replace('Ã©', "&aacute;", $cadena);
        $cadena = str_replace('Ã*', "&iacute;", $cadena);
        $cadena = str_replace('Ãº', "&uacute;", $cadena);
        $cadena = str_replace('Ã', "&iacute;", $cadena);


        $cadena = str_replace('Á?', "&Aacute;", $cadena);
        $cadena = str_replace('É', "&Eacute;", $cadena);
        $cadena = str_replace('Í?', "&Iacute;", $cadena);
        $cadena = str_replace('Ó', "&Oacute;", $cadena);
        $cadena = str_replace('Ú', "&Uacute;", $cadena);
        return $cadena;
    }
}

?>