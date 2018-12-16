<?php
class mysql implements idatabase
{
    public $connection;
    protected $lquery, $lquery_type;

    public function connect()
    {
        require 'config/mysql.php';

        $this->connection = mysqli_connect($db['host'], $db['user'], $db['pass']);

        mysqli_select_db($this->connection, $db['dbname']);

        return $this;
    }

    public function query($str)
    {
        if (!isset($this->connection)) {
            $this->connect();
        }

        $t = explode(' ', $str);

        $this->lquery_type = strtolower($t[0]);

        $this->lquery = @mysqli_query($this->connection, $str);

        if (!$this->lquery) {
            throw new AppException(mysqli_error($this->connection) . $str);
        }

        return $this;
    }

    public function get_connection()
    {
        return $this->connection;
    }

    public function get_num_rows($query = null)
    {
        if ($query != null) {
            $this->query($query);
        }

        return mysqli_num_rows($this->connection, $this->lquery);
    }

    public function get_last_query()
    {
        return $this->lquery;
    }

    public function insert($into, $values)
    {
        $qFields = '';
        $qValues = '';
        $first = true;

        foreach ($values as $key => $value) {
            if ($first) {
                $first = false;
            } else {
                $qFields .= ',';
                $qValues .= ',';
            }

            $qFields .= "`$key`";
            $qValues .= "'$value'";
        }

        return $this->query("INSERT INTO $into($qFields) VALUES($qValues)");
    }

    public function get_last_insert_id()
    {
        return mysql_insert_id();
    }

    public function results($query = null, $force_multiple = false)
    {
        if ($query) {
            $this->query($query);
        }

        $results = array();

        while ($ar = @mysqli_fetch_assoc($this->lquery)) {
            $results[] = $ar;
        }

        if (count($results) == 1 && $force_multiple == false) {
            $results = $results[0];
        }

        return $results;
    }

    public function success($query = null)
    {
        if ($query) {
            $this->query($query);
        }

        switch ($this->lquery_type) {
            //INSERT, UPDATE, REPLACE or DELETE
            case 'insert':
            case 'update':
            case 'replace':
            case 'delete':
                return (mysqli_affected_rows($this->connection) > 0);
                break;

            case 'select':
            case 'show':
                if (!is_resource($this->lquery)) {
                    return false;
                }

                return (mysqli_num_rows($this->connection, $this->lquery) > 0);
                break;

            default:
                return (mysqli_num_rows($this->connection, $this->lquery) > 0 || mysql_affected_rows() > 0);
                break;
        }
    }

    public function update($table, $value, $where = '1=1', $limit = 1)
    {
        if (is_array($value)) {
            $value = $this->specialglue(',', $value);
        }

        if (is_array($where)) {
            $where = implode(' AND ', $where);
        }

        return $this->query("UPDATE $table SET $value WHERE $where LIMIT $limit;");
    }

    public function clear_table($table)
    {
        return $this->query('TRUNCATE TABLE `' . $table . '`');
    }

    private function specialglue($glue, $pieces)
    {
        $first = true;
        $result = null;

        foreach ($pieces as $key => $value) {
            if ($first) {
                $first = false;
            } else {
                $result .= $glue;
            }

            $result .= "$key = '$value'";
        }

        return $result;
    }

    public function clean_string($string)
    {
        return mysqli_real_escape_string($this->connection, $string);
    }

}
