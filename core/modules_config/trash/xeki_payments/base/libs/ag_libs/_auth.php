<?php

/**
 * Class auth
 * version 0.000001
 */
class auth
{
    /**
     * @var string
     */
    protected $encryption_method = 'sha256';
    #db info
    /**
     * @var string
     */
    protected $table_user = 'customer';
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

    /**
     *
     */
    function __construct()
    {
        if (!$this->is_session_started()) {
            session_start();
        }
        $this->loadDefaultVars();

        if (!isset($_SESSION['id_user'])) {
            $_SESSION['logged'] = false;
            $_SESSION['id_user'] = -1;
            $_SESSION['created'] = time();
            $_SESSION['last_view'] = time();
            $_SESSION['user_info'] = array();
        }

        if ($_SESSION['logged']) {
            $this->logged = true;
            $this->user = $_SESSION['user_info'];
            $this->id = $_SESSION['id_user'];
            $_SESSION['last_view'] = time();
        }
    }

    /**
     * @return bool
     */
    function is_session_started()
    {
        if (php_sapi_name() !== 'cli') {
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }

    /**
     *
     */
    function loadDefaultVars()
    {

        global $_DEFAULT_AUTH_LOGIN_PAGE;
        if (!empty($_DEFAULT_AUTH_LOGIN_PAGE)) {
            $this->login_page = $_DEFAULT_AUTH_LOGIN_PAGE;
        }
    }

    /**
     * @return array
     */
    function getUserInfo()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    function getFieldUser()
    {
        return $this->field_user;
    }

    /**
     * @return string
     */
    function getTableUser()
    {
        return $this->table_user;
    }

    /**
     * @return array
     */
    function updateUserInfo()
    {
        global $sql;
        $info = $sql->query("SELECT * FROM {$this->table_user} WHERE {$this->field_id}='{$this->id}'");
        $this->user = $info[0];
        $_SESSION['user_info'] = $this->user;
        return $this->user;
    }

    /**
     * @param $user
     * @param $pass
     * @param bool|false $cleanPass
     * @return bool
     */
    function login($user, $pass, $cleanPass = false)
    {
        global $sql;
        global $_DEFAULT_AUTH_LOGGED_PAGE;
        if (!$cleanPass)
            $pass = hash($this->encryption_method, $pass);

        $query = "SELECT * FROM {$this->table_user} WHERE {$this->field_user}='{$user}' and {$this->field_password}='{$pass}' ";
        $info = $sql->query($query);
        if ($info) if (count($info) > 0) {
            $this->user = $info[0];
            $_SESSION['logged'] = true;
            $_SESSION['id_user'] = $this->user['id'];
            $_SESSION['last_view'] = time();
            $_SESSION['user_info'] = $this->user;
            ag_RedirectTo($_DEFAULT_AUTH_LOGGED_PAGE);
            return true;
        }
        return false;
    }

    /**
     * @param $user
     * @param $pass
     * @param array $extra_data
     * @return bool|int|string
     */
    function secure_register($user, $pass, $extra_data = array())
    {
        global $sql;
        $data = array(
            $this->field_user => $user,
            $this->field_password => hash($this->encryption_method, $pass),
        );
        $data = array_merge($data, $extra_data);
        $res = $sql->insert($this->table_user, $data);
        if ($res) {
            return $res;
        }
        return false;
    }

    /**
     * @param $user
     * @return string
     */
    public function setCodeRecover($user)
    {
        $_CODE = hash($this->encryption_method, time());
        global $sql;
        $data = array(
            $this->field_recover_code => $_CODE,
        );
        $res = $sql->update($this->table_user, $data, "$this->field_user='$user'");
        return $_CODE;
    }


    #return if islogged and redirect to $_DEFAULT_AUTH_LOGGED_PAGE if is logged

    /**
     * @param $user
     * @param $pass
     * @return bool
     */
    function secure_set_pass($user, $pass)
    {
        global $sql;
        $data = array(
            $this->field_user => $user,
            $this->field_password => hash($this->encryption_method, $pass),
        );
        $res = $sql->update($this->table_user, $data, "$this->field_user='$user'");
        return $res;
    }

    /**
     * @param int $level
     * @return bool
     */
    function checkLogin($level = 1)
    {
        global $_DEFAULT_AUTH_LOGGED_PAGE;
        if ($this->logged) return true;
        else ag_RedirectTo($this->login_page . '?from=' . $_DEFAULT_AUTH_LOGGED_PAGE);

    }

    /**
     * @param int $level
     * @return bool
     */
    function pageLoginCheck($level = 1)
    {
        global $_DEFAULT_AUTH_LOGGED_PAGE;
        if ($this->logged) ag_RedirectTo($_DEFAULT_AUTH_LOGGED_PAGE);
        return false;

    }

    /**
     * @return bool
     */
    public function isLogged()
    {
        return $this->logged;
    }

    /**
     * @param $var
     */
    function removeValue($var)
    {

    }

    /**
     *
     */
    function recoverSeccion()
    {

    }

    /**
     * @param $item
     * @param $value
     */
    public function setValue($item, $value)
    {
        $_SESSION[$item] = $value;
    }

    /**
     * @param $item
     * @return string
     */
    public function getValue($item)
    {
        return empty($_SESSION[$item]) ? '' : $_SESSION[$item];
    }

    /**
     *
     */
    public function destroy()
    {
        session_destroy();
    }

    /**
     * @param $table_user
     */
    public function change_table_user($table_user)
    {
        $this->table_user = $table_user;
    }

    /**
     * @param $field_id
     */
    public function change_field_id($field_id)
    {
        $this->field_id = $field_id;
    }

    /**
     * @param $field_user
     */
    public function change_field_user($field_user)
    {
        $this->field_user = $field_user;
    }
}