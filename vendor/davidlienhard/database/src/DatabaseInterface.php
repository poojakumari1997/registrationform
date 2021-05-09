<?php
/**
 * contains a custom database interface class
 *
 * @package         davidlienhard/database
 * @author          David Lienhard <david@t-error.ch>
 * @version         1.0.4, 04.12.2020
 * @since           1.0.0, 11.11.2020, created
 * @copyright       tourasia
 */

declare(strict_types=1);

namespace DavidLienhard\Database;

use \DavidLienhard\Database\ParameterInterface;

/**
 * defines an interface to use for database connections
 *
 * @author          David Lienhard <david@t-error.ch>
 * @version         1.0.4, 04.12.2020
 * @since           1.0.0, 11.11.2020, created
 * @copyright       tourasia
 */
interface DatabaseInterface
{
    /**
     * connects to the database
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.4, 03.12.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @param           string          $host           the hostname to connect
     * @param           string          $user           the username
     * @param           string          $pass           the password
     * @param           string          $dbname         the database
     * @param           int|null        $port           port to use to connect
     * @param           string          $charset        charset to use for the database connection
     * @param           string          $collation      collation to use for the database connection
     * @return          bool
     */
    public function connect(
        string $host,
        string $user,
        string $pass,
        string $dbname,
        ?int $port = null,
        string $charset = "utf8mb4",
        string $collation = "utf8mb4_unicode_ci"
    ) : bool;


    /**
     * reconnects to the database server
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.4, 04.12.2020
     * @since           1.0.4, 04.12.2020, created
     * @copyright       t-error.ch
     * @return          bool
     */
    public function reconnect() : bool;


    /**
     * closes the database connection
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @return          bool
      */
    public function close() : bool;


    /**
     * changes the mode of autocommit
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @param           bool            $mode           the new mode to set
     * @return          bool
     */
    public function autocommit(bool $mode) : bool;


    /**
     * Starts a transaction
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @return          bool
     */
    public function begin_transaction() : bool;


    /**
     * Commits a transaction
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @return          bool
     */
    public function commit() : bool;


    /**
     * Rolls a transaction back
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @return          bool
     */
    public function rollback() : bool;


    /**
     * Executes a query
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @param           string              $q           the sql query
     * @param           \DavidLienhard\Database\ParameterInterface  $parameters  parameters to add to the query
     * @return          \mysqli_result|bool
      */
    public function query(string $q, ParameterInterface ...$parameters);


    /**
     * executes an already prepared statement
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @param           \DavidLienhard\Database\ParameterInterface  $parameters  parameters to add to the query
     * @return          \mysqli_result|bool
      */
    public function execute(ParameterInterface ...$parameters);


    /**
     * Counts the rows of a result resource
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @param           \mysqli_result      $result      the result resource
     * @return          int
     */
    public function num_rows($result) : int;


    /**
     * Gets a field out of a result resource
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @param           \mysqli_result      $result      the result resource
     * @param           int              $row         the row
     * @param           string           $field       the column
     * @return          string|int
     */
    public function result($result, int $row, string $field);


    /**
     * Frees the memory
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @param           \mysqli_result      $result      the result resource
     * @return          void
     */
    public function free_result($result) : void;


    /**
     * Creates an array out of a result resource
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @param           \mysqli_result      $result         the result resource
     * @param           int                 $type           the type of the result
     * @return          array|null
     */
    public function fetch_array($result, int $type = MYSQLI_BOTH);


    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @param           \mysqli_result      $result      the result resource
     * @return          array|null
     */
    public function fetch_assoc($result);


    /**
     * Creates an enumerated array out of a result resource
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @param           \mysqli_result      $result      the result resource
     * @return          array|null
     */
    public function fetch_row($result);


    /**
     * returns the id of the last inserted row
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @return          int
     */
    public function insert_id() : int;


    /**
     * returns the id of the last inserted row
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @param           \mysqli_result      $result      the result resource
     * @param           int                 $row         the row to jump
     * @return          bool
     */
    public function data_seek($result, int $row) : bool;


    /**
     * returns the number of affected rows
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @return          int
     */
    public function affected_rows() : int;


    /**
     * returns the latest error number
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @return          int
     */
    public function errno() : int;


    /**
     * returns the latest error string
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @return          string
     */
    public function errstr() : string;
}
