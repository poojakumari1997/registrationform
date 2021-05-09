<?php
/**
 * contains a stub for database interface
 *
 * @package         tourBase
 * @author          David Lienhard <david.lienhard@tourasia.ch>
 * @version         1.0.5, 14.12.2020
 * @since           1.0.3, 17.11.2020, created
 * @copyright       tourasia
 */

declare(strict_types=1);

namespace DavidLienhard\Database;

use \DavidLienhard\Database\DatabaseInterface;
use \DavidLienhard\Database\ParameterInterface;

/**
 * stub for \DavidLienhard\Database\DatabaseInterface
 *
 * @category        Database
 * @author          David Lienhard <david.lienhard@tourasia.ch>
 * @version         1.0.5, 14.12.2020
 * @since           1.0.3, 17.11.2020, created
 * @copyright       tourasia
 */
class Stub implements DatabaseInterface
{
    /**
     * host to connect to
     * @var         string
     */
    private $host;

    /**
     * username to use to connect
     * @var         string
     */
    private $user;

    /**
     * password to use to connect
     * @var         string
     */
    private $pass;

    /**
     * the name of the selected database
     * @var         string
     */
    private $dbname;

    /**
     * port to connect to
     * @var         int|null
     */
    private $port;

    /**
     * charset to use to connect
     * @var         string
     */
    private $charset;

    /**
     * collation to use to connect
     * @var         string
     */
    private $collation;

    /**
     * the payload to use in the config
     * @var     array   $payload
     */
    private $payload = [ ];

    /**
     * connects to the database
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.5, 14.12.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @param           string          $host           the hostname to connect
     * @param           string          $user           the username
     * @param           string          $pass           the password
     * @param           string          $dbname         the database
     * @param           int|null        $port           port to use to connect
     * @param           string          $charset        charset to use for the database connection
     * @param           string          $collation      encoding to use for the database connection
     * @uses            self::$host
     * @uses            self::$user
     * @uses            self::$pass
     * @uses            self::$dbname
     * @uses            self::$port
     * @uses            self::$charset
     * @uses            self::$collation
     * @return          bool
     */
    public function connect(
        string $host,
        string $user,
        string $pass,
        string $dbname,
        ?int $port = null,
        string $charset = "utf8mb4_unicode_ci",
        string $collation = "utf8"
    ) : bool {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->dbname = $dbname;
        $this->port = $port;
        $this->charset = $charset;
        $this->collation = $collation;

        return true;
    }


    /**
     * reconnects to the database server
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.5, 14.12.2020
     * @since           1.0.4, 04.12.2020, created
     * @copyright       t-error.ch
     * @return          bool
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::connect()
     * @uses            self::$host
     * @uses            self::$user
     * @uses            self::$pass
     * @uses            self::$dbname
     * @uses            self::$port
     * @uses            self::$charset
     * @uses            self::$collation
     * @uses            self::checkConnected()
     */
    public function reconnect() : bool
    {
        $this->checkConnected();

        return $this->connect(
            $this->host,
            $this->user,
            $this->pass,
            $this->dbname,
            $this->port,
            $this->charset,
            $this->collation
        );
    }


    /**
     * closes the database connection
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @return          bool
     */
    public function close() : bool
    {
        return true;
    }


    /**
     * changes the mode of autocommit
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @param           bool            $mode           the new mode to set
     * @return          bool
     */
    public function autocommit(bool $mode) : bool
    {
        return true;
    }


    /**
     * Starts a transaction
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @return          bool
     */
    public function begin_transaction() : bool
    {
        return true;
    }


    /**
     * Commits a transaction
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @return          bool
     */
    public function commit() : bool
    {
        return true;
    }


    /**
     * Rolls a transaction back
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @return          bool
     */
    public function rollback() : bool
    {
        return true;
    }


    /**
     * Executes a query
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @param           string              $q           the sql query
     * @param           \DavidLienhard\Database\ParameterInterface  $parameters  parameters to add to the query
     * @return          \mysqli_result|bool
     */
    public function query(string $q, ParameterInterface ...$parameters)
    {
        return true;
    }


    /**
     * executes an already prepared statement
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @param           \DavidLienhard\Database\ParameterInterface  $parameters  parameters to add to the query
     * @return          \mysqli_result|bool
     */
    public function execute(ParameterInterface ...$parameters)
    {
        return true;
    }


    /**
     * Counts the rows of a result resource
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @param           \mysqli_result   $result      the result resource
     * @return          int
     */
    public function num_rows($result) : int
    {
        return count($this->payload);
    }


    /**
     * Gets a field out of a result resource
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @param           \mysqli_result   $result      the result resource
     * @param           int              $row         the row
     * @param           string           $field       the column
     * @return          string|int
     */
    public function result($result, int $row, string $field)
    {
        return "result";
    }


    /**
     * Frees the memory
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @param           \mysqli_result      $result      the result resource
     * @return          void
    */
    public function free_result($result) : void
    {
        return;
    }


    /**
     * Creates an array out of a result resource
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @param           \mysqli_result      $result         the result resource
     * @param           int                 $type           the type of the result
     * @return          array|null
     */
    public function fetch_array($result, int $type = MYSQLI_BOTH)
    {
        return $this->payload[0] ?? $this->payload;
    }


    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @param           \mysqli_result      $result      the result resource
     * @return          array|null
     */
    public function fetch_assoc($result)
    {
        return $this->payload[0] ?? $this->payload;
    }


    /**
     * Creates an enumerated array out of a result resource
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @param           \mysqli_result      $result      the result resource
     * @return          array|null
     */
    public function fetch_row($result)
    {
        return $this->payload[0] ?? $this->payload;
    }


    /**
     * returns the id of the last inserted row
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @return          int
     */
    public function insert_id() : int
    {
        return 1;
    }


    /**
     * returns the id of the last inserted row
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @param           \mysqli_result   $result      the result resource
     * @param           int              $row         the row to jump
     * @return          bool
     */
    public function data_seek($result, int $row) : bool
    {
        return true;
    }


    /**
     * returns the number of affected rows
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @return          int
     */
    public function affected_rows() : int
    {
        return 1;
    }


    /**
     * escapes a string
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @param           string      $str         the string to escape
     * @return          string
     */
    public function esc($str) : string
    {
        return $str;
    }


    /**
     * returns the client info
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @return          string
     */
    public function client_info() : string
    {
        return "client info";
    }


    /**
     * returns the host info
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @return          string
     */
    public function host_info() : string
    {
        return "host info";
    }


    /**
     * returns the proto info
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @return          string
     */
    public function proto_info() : string
    {
        return "proto info";
    }


    /**
     * returns the server info
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @return          string
     */
    public function server_info() : string
    {
        return "server info";
    }


    /**
     * returns the size of the db
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @param           string      $dbname         optional mysqli connection
     * @return          int
     */
    public function size($dbname = false) : int
    {
        return 1;
    }


    /**
     * returns the latest error number
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @return          int
     */
    public function errno() : int
    {
        return 1;
    }


    /**
     * returns the latest error string
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @return          string
     */
    public function errstr() : string
    {
        return "error";
    }

    /**
     * adds payload to the object
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.3, 17.11.2020
     * @since           1.0.3, 17.11.2020, created
     * @copyright       tourasia
     * @param           array           $payload        the payload to add
     * @return          void
     */
    public function addPayload(array $payload) : void
    {
        $this->payload = $payload;
    }
}
