<?php

namespace xeki_db_sql;
class mysql
{
    private $con;

    public $host = '';
    public $user = '';
    public $pass = '';
    public $db = '';

    public function __construct($db = array())
    {
        $default = array(
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'db' => 'test'
        );
        $this->host = $db['host'];
        $this->user = $db['user'];
        $this->pass = $db['pass'];
        $this->db = $db['db'];
        $db = array_merge($default, $db);

        $this->con = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['db']) or die ('Error connecting to db');

    }

    public function __destruct()
    {
        mysqli_close($this->con);
    }

    public function error()
    {
        return mysqli_errno($this->con) . ": " . mysqli_error($this->con);
    }

    public function clean_text()
    {

    }

    public function query($s = '', $organize = false)
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

    public function execute($s = '')
    {
        if (mysqli_query($this->con, $s)) return true;
        return false;
    }

    public function select($options)
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
    public function row($options)
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

    public function get($table = null, $field = null, $conditions = '1')
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

    public function update($table = null, $array_of_values = array(), $conditions = 'FALSE')
    {
        if ($table === null || empty($array_of_values)) return false;
        $what_to_set = array();
        foreach ($array_of_values as $field => $value) {
            if (is_array($value) && !empty($value[0])) {
                $value[0] = $this->escape_string($value[0]);
                $what_to_set[] = "`$field`='{$value[0]}'";
            } else {
                $value = $this->escape_string($value);
                $what_to_set [] = "`$field`='" . mysqli_real_escape_string($this->con, $value) . "'";
            }
        }
        $what_to_set_string = implode(',', $what_to_set);
        $querystr = "UPDATE $table SET $what_to_set_string WHERE $conditions";
        return $this->execute($querystr);
    }

    public function insert($table = null, $array_of_values = array())
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
                $values[] = "'" . mysqli_real_escape_string($this->con, $value) . "'";
            }
        }
        $s = "INSERT INTO $table (" . implode(',', $fields) . ') VALUES (' . implode(',', $values) . ')';
        if (mysqli_query($this->con, $s)) return mysqli_insert_id($this->con);
        return false;
    }


    public function createTable($table = null, $array_of_values = array())
    {
        if ($table === null || empty($array_of_values) || !is_array($array_of_values)) return false;
        $fields = array();
        $values = array();
        foreach ($array_of_values as $id => $value) {
            $fields[] = $id;
            if (is_array($value) && !empty($value[0])) $values[] = $value[0];
            else $values[] = "'" . mysqli_real_escape_string($this->con, $value) . "'";
        }
        $s = "CREATE TABLE IF NOT EXISTS $table (" . implode(',', $fields) . ') VALUES (' . implode(',', $values) . ')';
        print $s;
        //        if (mysqli_query($s,$this->con)) return mysqli_insert_id($this->con);
        return false;
    }

    public function delete($table = null, $conditions = 'FALSE')
    {
        if ($table === null) return false;
        return $this->execute("DELETE FROM $table WHERE $conditions");
    }

    #adds

    public function getAllId($table = null, $id = null)
    {
        if ($table === null || $id === null) return false;
        $result = $this->row(array(
            'table' => $table,
            'condition' => 'id=' . $id
        ));
        if (empty($result)) return false;
        return $result;
    }

    public function getById($table = null, $id = null)
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
    public function getBySlug($table = null, $id = null)
    {
        if ($table === null || $id === null) return false;
        $result = $this->row(array(
            'table' => $table,
            'condition' => 'slug="' . $id . '"'
        ));
        if (empty($result)) return false;
        return $result;
    }

    public function getBy($table = null, $data = null, $id = null)
    {
        if ($table === null || $id === null || $data == null) return false;
        $result = $this->row(array(
            'table' => $table,
            'condition' => $data . '="' . $id . '"'
        ));
        if (empty($result)) return false;
        return $result;
    }

    public function getAllByRow($table = null, $row = null, $data = null)
    {
        if ($table === null || $row === null || $data === null) return false;
        $result = $this->row(array(
            'table' => $table,
            'condition' => $row . '=' . $data
        ));
        if (empty($result)) return false;
        return $result;
    }

    public function getDataLogin($login = null)
    {
        if ($login === null) return false;
        $result = $this->row(array(
            'table' => "usuario",
            'condition' => 'USUA_USUARIO="' . $login . '"'
        ));
        if (empty($result)) return false;
        return $result;
    }

    public function escape_string($str)
    {
        $chr_map = array(
            // html codes
            '¿' => "&iquest;",
            'á' => "&aacute;",
            'é' => "&eacute;",
            'í' => "&iacute;",
            'ó' => "&oacute;",
            'ú' => "&uacute;",
            'Á' => "&Aacute;",
            'É' => "&Eacute;",
            'Í' => "&Iacute;",
            'Ó' => "&Oacute;",
            'Ú' => "&Uacute;",
            'ñ' => "&ntilde;",
            'Ñ' => "&Ntilde;",

            "\xC3\x80"=>"&Agrave;",
            "\xC3\x81"=>"&Aacute;",
            "\xC3\x82"=>"&Acirc;",
            "\xC3\x83"=>"&Atilde;",
            "\xC3\x84"=>"&Auml;",
            "\xC3\x85"=>"&Aring;",
            "\xC3\x86"=>"&AElig;",
            "\xC3\x87"=>"&Ccedil;",
            "\xC3\x88"=>"&Egrave;",
            "\xC3\x89"=>"&Eacute;",
            "\xC3\x8A"=>"&Ecirc;",
            "\xC3\x8B"=>"&Euml;",
            "\xC3\x8C"=>"&Igrave;",
            "\xC3\x8D"=>"&Iacute;",
            "\xC3\x8E"=>"&Icirc;",
            "\xC3\x8F"=>"&Iuml;",
            "\xC3\x90"=>"&ETH;",
            "\xC3\x91"=>"&Ntilde;",
            "\xC3\x92"=>"&Ograve;",
            "\xC3\x93"=>"&Oacute;",
            "\xC3\x94"=>"&Ocirc;",
            "\xC3\x95"=>"&Otilde;",
            "\xC3\x96"=>"&Ouml;",
            "\xC3\x97"=>"&times;",
            "\xC3\x98"=>"&Oslash;",
            "\xC3\x99"=>"&Ugrave;",
            "\xC3\x9A"=>"&Uacute;",
            "\xC3\x9B"=>"&Ucirc;",
            "\xC3\x9C"=>"&Uuml;",
            "\xC3\x9D"=>"&Yacute;",
            "\xC3\x9E"=>"&THORN;",
            "\xC3\x9F"=>"&szlig;",
            "\xC3\xA0"=>"&agrave;",
            "\xC3\xA1"=>"&aacute;",
            "\xC3\xA2"=>"&acirc;",
            "\xC3\xA3"=>"&atilde;",
            "\xC3\xA4"=>"&auml;",
            "\xC3\xA5"=>"&aring;",
            "\xC3\xA6"=>"&aelig;",
            "\xC3\xA7"=>"&ccedil;",
            "\xC3\xA8"=>"&egrave;",
            "\xC3\xA9"=>"&eacute;",
            "\xC3\xAA"=>"&ecirc;",
            "\xC3\xAB"=>"&euml;",
            "\xC3\xAC"=>"&igrave;",
            "\xC3\xAD"=>"&iacute;",
            "\xC3\xAE"=>"&icirc;",
            "\xC3\xAF"=>"&iuml;",
            "\xC3\xB0"=>"&eth;",
            "\xC3\xB1"=>"&ntilde;",
            "\xC3\xB2"=>"&ograve;",
            "\xC3\xB3"=>"&oacute;",
            "\xC3\xB4"=>"&ocirc;",
            "\xC3\xB5"=>"&otilde;",
            "\xC3\xB6"=>"&ouml;",
            "\xC3\xB7"=>"&divide;",
            "\xC3\xB8"=>"&oslash;",
            "\xC3\xB9"=>"&ugrave;",
            "\xC3\xBA"=>"&uacute;",
            "\xC3\xBB"=>"&ucirc;",
            "\xC3\xBC"=>"&uuml;",
            "\xC3\xBD"=>"&yacute;",
            "\xC3\xBE"=>"&thorn;",
            "\xC3\xBF"=>"&yuml;",
//
            "\xC2\x89" => '',
            "\xC2\x9A" => '',


            "\xC5\xBE"=>'',
            "\xC5\xB8"=>'',
            "\xC2\xA0"=>'&nbsp;',
            "\xC2\xA1"=>'&iexcl;',
            "\xC2\xA2"=>'&cent;',
            "\xC2\xA3"=>'&pound;',
            "\xC2\xA4"=>'&curren;',
            "\xC2\xA5"=>'&yen;',
            "\xC2\xA6"=>'&brvbar;',
            "\xC2\xA7"=>'&sect;',
            "\xC2\xA8"=>'&uml;',
            "\xC2\xA9"=>'&copy;',
            "\xC2\xAA"=>'&ordf;',
            "\xC2\xAB"=>'&laquo;',
            "\xC2\xAC"=>'&not;',
            "\xC2\xAD"=>'&shy;',
            "\xC2\xAE"=>'&reg;',
            "\xC2\xAF"=>'&macr;',
            "\xC2\xB0"=>'&deg;',
            "\xC2\xB1"=>'&plusmn;',
            "\xC2\xB2"=>'&sup2;',
            "\xC2\xB3"=>'&sup3;',
            "\xC2\xB4"=>'&acute;',
            "\xC2\xB5"=>'&micro;',
            "\xC2\xB6"=>'&para;',
            "\xC2\xB7"=>'&middot;',
            "\xC2\xB8"=>'&cedil;',
            "\xC2\xB9"=>'&sup1;',
            "\xC2\xBA"=>'&ordm;',
            "\xC2\xBB"=>'&raquo;',
            "\xC2\xBC"=>'&frac14;',
            "\xC2\xBD"=>'&frac12;',
            "\xC2\xBE"=>'&frac34;',
            "\xC2\xBF"=>'&iquest;',

            // Windows codepage 1252
            "\xC2\x82" => "'", // U+0082⇒U+201A
            "\xC2\x84" => '"', // U+0084⇒U+201E


            "\xC2\x8B" => "'", // U+008B⇒U+2039
            "\xC2\x91" => "'", // U+0091⇒U+2018
            "\xC2\x92" => "'", // U+0092⇒U+2019
            "\xC2\x93" => '"', // U+0093⇒U+201C
            "\xC2\x94" => '"', // U+0094⇒U+201D
            "\xC2\x9B" => "'", // U+009B⇒U+203A

            // Regular Unicode     // U+0022 quotation mark (")
            // U+0027 apostrophe     (')
            "\xC2\x80" => '"', // U+00BB

            "\xE2\x80\x98" => "'", // U+2018
            "\xE2\x80\x99" => "'", // U+2019
            "\xE2\x80\x9A" => "'", // U+201A
            "\xE2\x80\x9B" => "'", // U+201B
            "\xE2\x80\x9C" => '"', // U+201C
            "\xE2\x80\x9D" => '"', // U+201D
            "\xE2\x80\x9E" => '"', // U+201E
            "\xE2\x80\x9F" => '"', // U+201F
            "\xE2\x80\xB9" => "'", // U+2039
            "\xE2\x80\xBA" => "'", // U+203A
            "\xE2\x9D\x9B" => "'", // U+203A
            "\xE2\x9D\x9C" => "'", // U+203A
            "\xE2\x9D\x9D" => '"', // U+203A
            "\xE2\x9D\x9E" => '"', // U+203A
        );
        $chr = array_keys($chr_map); // but: for efficiency you should
        $rpl = array_values($chr_map); // pre-calculate these two arrays
        $str = str_replace($chr, $rpl, html_entity_decode($str, ENT_QUOTES, "UTF-8"));
        return $str;
    }
}

?>