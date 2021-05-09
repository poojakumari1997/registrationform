<?php
/**
 * contains a custom mysql class
 *
 * @package         Database
 * @author          David Lienhard <david@t-error.ch>
 * @version         1.0.6, 04.01.2021
 * @since           1.0.0, 11.11.2020, created
 * @copyright       t-error.ch
 */

declare(strict_types=1);

namespace DavidLienhard\Database;

use \DavidLienhard\Database\DatabaseInterface;
use \DavidLienhard\Database\ParameterInterface;
use \DavidLienhard\Database\Exception as DatabaseException;

/**
 * Methods for a comfortable use of the {@link http://www.mysql.com mySQL} database
 *
 * @category        Database
 * @author          David Lienhard <david@t-error.ch>
 * @version         1.0.6, 04.01.2021
 * @copyright       t-error.ch
 */
class Mysqli implements DatabaseInterface
{
    /**
     * defines whether connect() has been used yet
     * @var         bool
     */
    private $isConnected = false;

    /**
     * The Database connection resource
     * @var         \mysqli
     */
    private $mysqli;

    /**
     * The miliseconds used by the database
     * @var         float
     */
    public $dbTime = 0;

    /**
     * The number of queries
     * @var         int
     */
    public $totalQueries = 0;

    /**
     * contains infos about the client
     * @var         string
     */
    private $client_info = null;

    /**
     * contains infos about the host
     * @var         string
     */
    private $host_info = null;

    /**
     * contains infos about the protocol
     * @var         string
     */
    private $proto_info = null;

    /**
     * contains infos about the server
     * @var         string
     */
    private $server_info = null;

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
     * the last statement from the query
     * @var         \mysqli_stmt
     */
    private $stmt;

    /**
     * result data of the last query
     * @var         \mysqli_result
     */
    private $stmtResult;

    /**
     * the last query that was executed
     * @var         string
     */
    private $lastquery = "";


    /**
     * connects to the database
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.5, 14.12.2020
     * @copyright       t-error.ch
     * @param           string          $host           the hostname to connect
     * @param           string          $user           the username
     * @param           string          $pass           the password
     * @param           string          $dbname         the database
     * @param           int|null        $port           port to use to connect
     * @param           string          $charset        charset to use for the database connection
     * @param           string          $collation      collation to use for the database connection
     * @return          bool
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::$host
     * @uses            self::$user
     * @uses            self::$pass
     * @uses            self::$dbname
     * @uses            self::$port
     * @uses            self::$charset
     * @uses            self::$collation
     * @uses            self::$mysqli
     * @uses            self::$client_info
     * @uses            self::$host_info
     * @uses            self::$proto_info
     * @uses            self::$server_info
     * @uses            self::$isConnected
     */
    public function connect(
        string $host,
        string $user,
        string $pass,
        string $dbname,
        ?int $port = null,
        string $charset = "utf8mb4",
        string $collation = "utf8mb4_unicode_ci"
    ) : bool {
        try {
            \mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);     // set mysqli to throw exceptions

            $this->mysqli = new \mysqli(                                    // connect to database
                $host,
                $user,
                $pass,
                $dbname,
                (int) ($port ?? ini_get("mysqli.default_port") ?? 3306)
            );

            $this->isConnected = true;
            $this->mysqli->set_charset($charset);                           // set charset
            $this->query("SET NAMES '".$charset."' COLLATE '".$collation."'");                      // set charset / collation

            $this->host = $host;
            $this->user = $user;
            $this->pass = $pass;
            $this->dbname = $dbname;
            $this->port = $port;
            $this->charset = $charset;
            $this->collation = $collation;

            $this->client_info = $this->mysqli->get_client_info();
            $this->host_info = $this->mysqli->host_info;
            $this->proto_info = $this->mysqli->protocol_version;
            $this->server_info = $this->mysqli->server_info;

            return true;
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * reconnects to the database server
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.5, 14.12.2020
     * @since           1.0.0, 11.11.2020, created
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
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @return          bool
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::$client_info
     * @uses            self::$host_info
     * @uses            self::$proto_info
     * @uses            self::$server_info
     * @uses            self::$mysqli
     * @uses            self::checkConnected()
     */
    public function close() : bool
    {
        $this->checkConnected();

        try {
            $this->client_info = $this->host_info = $this->proto_info = $this->server_info = null;
            return $this->mysqli->close();
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * changes the mode of autocommit
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       t-error.ch
     * @param           bool            $mode           the new mode to set
     * @return          bool
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::$mysqli
     * @uses            self::$dbTime
     * @uses            self::checkConnected()
     */
    public function autocommit(bool $mode) : bool
    {
        $this->checkConnected();

        try {
            $dbStart = microtime(true);
            $result = $this->mysqli->autocommit($mode);
            $this->dbTime = $this->dbTime + (microtime(true) - $dbStart);

            return $result;
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * Starts a transaction
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @return          bool
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::$mysqli
     * @uses            self::$dbTime
     * @uses            self::checkConnected()
     */
    public function begin_transaction() : bool
    {
        $this->checkConnected();

        try {
            $dbStart = microtime(true);
            $result = $this->mysqli->begin_transaction();
            $this->dbTime = $this->dbTime + (microtime(true) - $dbStart);

            return $result;
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * Commits a transaction
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @return          bool
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::$mysqli
     * @uses            self::$dbTime
     * @uses            self::checkConnected()
     */
    public function commit() : bool
    {
        $this->checkConnected();

        try {
            $dbStart = microtime(true);
            $result = $this->mysqli->commit();
            $this->dbTime = $this->dbTime + (microtime(true) - $dbStart);

            return $result;
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * Rolls a transaction back
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @return          bool
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::$mysqli
     * @uses            self::$dbTime
     * @uses            self::checkConnected()
     */
    public function rollback() : bool
    {
        $this->checkConnected();

        try {
            $dbStart = microtime(true);
            $result = $this->mysqli->rollback();
            $this->dbTime = $this->dbTime + (microtime(true) - $dbStart);

            return $result;
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * Executes a query
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.6, 04.01.2021
     * @since           1.0.0, 11.11.2020, created
     * @copyright       t-error.ch
     * @param           string              $q           the sql query
     * @param           \DavidLienhard\Database\ParameterInterface  $parameters  parameters to add to the query
     * @return          \mysqli_result|bool
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::$lastquery
     * @uses            self::execute()
     * @uses            self::$mysqli
     * @uses            self::$stmtResult
     * @uses            self::$stmt
     * @uses            self::$dbTime
     * @uses            self::$totalQueries
     * @uses            self::checkConnected()
     */
    public function query(string $q, ParameterInterface ...$parameters)
    {
        $this->checkConnected();

        $dbStart = microtime(true);

        if ($q === $this->lastquery && count($parameters) !== 0) {
            return $this->execute(...$parameters);
        }

        try {
            if (count($parameters) === 0) {         // use non-prepared query if no values to bind for efficiency
                $this->stmt = $this->mysqli->prepare($q);
                $this->stmt->execute();
                $this->stmtResult = $result = $this->stmt->get_result();
                $this->lastquery = $q;
            } else {
                $types = "";
                $values = [ ];
                foreach ($parameters as $parameter) {
                    $types .= $parameter->getType();
                    $values[] = $parameter->getValue();
                }

                $this->stmt = $this->mysqli->prepare($q);
                $this->stmt->bind_param($types, ...$values);
                $this->stmt->execute();
                $this->stmtResult = $result = $this->stmt->get_result();
                $this->lastquery = $q;
            }

            $this->dbTime = $this->dbTime + (microtime(true) - $dbStart);
            $this->totalQueries++;

            return $result;
        } catch (\mysqli_sql_exception $e) {
            // create error message with given parameters
            $message = "error in mysql query: ".$e->getMessage();
            if (count($parameters) > 0) {
                $message .= "\n\tparameters given:\n\t";
                $message .= implode(
                    "\n\t",
                    array_map(
                        fn ($p) => " - ".$p->getType().": '".\substr(\str_replace("\r\n", " ", (string) $p->getValue()), 0, 100)."'",
                        $parameters
                    )
                );
                $message .= "\n\t";
            }

            throw new DatabaseException(
                $message,
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * executes an already prepared statement
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.6, 04.01.2021
     * @since           1.0.0, 11.11.2020, created
     * @copyright       t-error.ch
     * @param           \DavidLienhard\Database\ParameterInterface  $parameters  parameters to add to the query
     * @return          \mysqli_result|bool
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::$stmt
     * @uses            self::$stmtResult
     * @uses            self::checkConnected()
     */
    public function execute(ParameterInterface ...$parameters)
    {
        $this->checkConnected();

        try {
            $types = "";
            $values = [ ];
            foreach ($parameters as $parameter) {
                $types .= $parameter->getType();
                $values[] = $parameter->getValue();
            }

            $stmt = $this->stmt;
            if (strlen($types) > 0 && count($values) > 0) {
                $stmt->bind_param($types, ...$values);
            }
            $stmt->execute();
            $this->stmtResult = $result = $this->stmt->get_result();
            return $result;
        } catch (\mysqli_sql_exception $e) {
            // create error message with given parameters
            $message = "error in mysql query: ".$e->getMessage();
            if (count($parameters) > 0) {
                $message .= "\n\tparameters given:\n\t";
                $message .= implode(
                    "\n\t",
                    array_map(
                        fn ($p) => " - ".$p->getType().": '".\substr(\str_replace("\r\n", " ", (string) $p->getValue()), 0, 100)."'",
                        $parameters
                    )
                );
                $message .= "\n\t";
            }

            throw new DatabaseException(
                $message,
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * Counts the rows of a result resource
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @param           \mysqli_result  $result      the result resource
     * @return          int
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::checkConnected()
     */
    public function num_rows($result) : int
    {
        $this->checkConnected();

        try {
            return $result->num_rows;
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * Gets a field out of a result resource
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @param           \mysqli_result  $result      the result resource
     * @param           int             $row         the row
     * @param           string          $field       the column
     * @return          string|int
     * @throws          \Exception if the required field is does not exist
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::checkConnected()
     */
    public function result($result, int $row, string $field)
    {
        $this->checkConnected();

        try {
            $result->data_seek($row);
            $dataRow = $result->fetch_assoc();

            if ($dataRow === null) {
                throw new \Exception(
                    "unable to fetch assoc array"
                );
            }

            if (!array_key_exists($field, $dataRow)) {
                throw new \Exception(
                    "field '".$field."' does not exist"
                );
            }

            return $dataRow[$field];
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * check if the connection to the server is still open
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       t-error.ch
     * @return          bool
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::$mysqli
     * @uses            self::checkConnected()
     */
    public function ping() : bool
    {
        $this->checkConnected();

        try {
            return $this->mysqli->ping();
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * Frees the memory
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @param           \mysqli_result      $result      the result resource
     * @return          void
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::checkConnected()
     */
    public function free_result($result) : void
    {
        $this->checkConnected();

        try {
            $result->free();
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * Creates an array out of a result resource
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @param           \mysqli_result      $result         the result resource
     * @param           int                 $type           the type of the result
     * @return          array|null
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::checkConnected()
     */
    public function fetch_array($result, int $type = MYSQLI_BOTH)
    {
        $this->checkConnected();

        try {
            return $result->fetch_array($type);
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       t-error.ch
     * @param           \mysqli_result      $result      the result resource
     * @return          array|null
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::checkConnected()
     */
    public function fetch_assoc($result)
    {
        $this->checkConnected();

        try {
            return $result->fetch_assoc();
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * Creates an enumerated array out of a result resource
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       t-error.ch
     * @param           \mysqli_result      $result      the result resource
     * @return          array|null
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::checkConnected()
     */
    public function fetch_row($result)
    {
        $this->checkConnected();

        try {
            return $result->fetch_row();
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * creates an array containing all data of a result resource
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       t-error.ch
     * @param           \mysqli_result      $result         the result resource
     * @param           int                 $resulttype     type of array to return
     * @return          array|null
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::checkConnected()
     */
    public function fetch_all($result, int $resulttype = MYSQLI_NUM)
    {
        $this->checkConnected();

        try {
            return $result->fetch_all($resulttype);
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * returns the id of the last inserted row
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @return          int
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::$mysqli
     * @uses            self::checkConnected()
     */
    public function insert_id() : int
    {
        $this->checkConnected();

        try {
            return $this->mysqli->insert_id;
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * returns the id of the last inserted row
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @param           \mysqli_result  $result      the result resource
     * @param           int             $row         the row to jump
     * @return          bool
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::checkConnected()
     */
    public function data_seek($result, int $row) : bool
    {
        $this->checkConnected();

        try {
            return $result->data_seek($row);
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * returns the number of affected rows
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @return          int
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::$mysqli
     * @uses            self::checkConnected()
     */
    public function affected_rows() : int
    {
        $this->checkConnected();

        try {
            return $this->mysqli->affected_rows;
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * escapes a string
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @param           string      $str         the string to escape
     * @return          string
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::$mysqli
     * @uses            self::checkConnected()
     */
    public function esc($str) : string
    {
        $this->checkConnected();

        try {
            return $this->mysqli->real_escape_string((string) $str);
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * returns the client info
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @return          string
     * @uses            self::$client_info
     * @uses            self::checkConnected()
     */
    public function client_info() : string
    {
        $this->checkConnected();

        return $this->client_info;
    }


    /**
     * returns the host info
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @return          string
     * @uses            self::$host_info
     * @uses            self::checkConnected()
     */
    public function host_info() : string
    {
        $this->checkConnected();

        return $this->host_info;
    }


    /**
     * returns the proto info
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @return          string
     * @uses            self::$proto_info
     * @uses            self::checkConnected()
     */
    public function proto_info() : string
    {
        $this->checkConnected();

        return $this->proto_info;
    }


    /**
     * returns the server info
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @return          string
     * @uses            self::$server_info
     * @uses            self::checkConnected()
     */
    public function server_info() : string
    {
        $this->checkConnected();

        return $this->server_info;
    }


    /**
     * returns the size of the db
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @param           string      $dbname         optional mysqli connection
     * @return          int
     * @throws          \Exception if no database name is set
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::$dbname
     * @uses            self::checkConnected()
     */
    public function size($dbname = false) : int
    {
        $this->checkConnected();

        try {
            if ($dbname === false) {
                if (empty($this->dbname)) {
                    throw new \Exception("no database name ist set");
                }

                $dbname = $this->dbname;
            }

            $res = $this->query("SHOW TABLE STATUS FROM `".$dbname."`");

            $size = 0;
            while ($data = $this->fetch_assoc($res)) {
                $size += (int) $data['Data_length'] + (int) $data['Index_length'];
            }

            return $size;
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    /**
     * returns the latest error number
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @return          int
     * @uses            self::$mysqli
     * @uses            self::checkConnected()
     */
    public function errno() : int
    {
        $this->checkConnected();

        return $this->mysqli->errno;
    }


    /**
     * returns the latest error string
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @copyright       t-error.ch
     * @return          string
     * @uses            self::$mysqli
     * @uses            self::checkConnected()
     */
    public function errstr() : string
    {
        $this->checkConnected();

        return $this->mysqli->error;
    }


    /**
     * shortens the parameter value to be printed as exception
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.0, 11.11.2020
     * @copyright       t-error.ch
     * @param           mixed       $value          value to format as string
     * @return          string
     */
    private static function formatParamter($value) : string
    {
        return \trim(\substr(preg_replace("/\s\s+/", " ", (string) $value), 0, 100));
    }


    /**
     * throws an exception if connect() has not been used yet
     *
     * @author          David Lienhard <david@t-error.ch>
     * @version         1.0.1, 17.11.2020
     * @since           1.0.0, 16.11.2020, created
     * @copyright       t-error.ch
     * @return          void
     * @uses            self::$isConnected
     */
    private function checkConnected() : void
    {
        if (!$this->isConnected) {
            throw new \BadMethodCallException("this ".__CLASS__." object is no connected yet. use connect() first");
        }
    }
}
