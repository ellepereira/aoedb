<?php
/**
 * Interface que todas os drivers de base de dados devem adotar
 * Interface for all database drivers
 * @author lspereira
 */
interface idatabase
{
    /**
     * Connect to a database
     * Conecta a uma base de dados
     * @param $host
     * @param $dbname
     * @param $user
     * @param $pass
     * @return unknown_type
     */
    public function connect();

    /**
     * Run an SQL query on the database
     * @param $str
     * @return unknown_type
     */
    function query($str);

    function results($query = null, $force_multiple = false);

    function success($query = null);

    function get_last_query();

    function get_num_rows($query = null);

    function get_connection();

    function insert($into, $array);

    function update($table, $value, $where = '1=1', $limit = null);

    public function clear_table($table);

    function get_last_insert_id();

    function clean_string($string);
}
