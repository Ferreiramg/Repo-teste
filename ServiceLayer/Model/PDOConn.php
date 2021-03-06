<?php

namespace Model;

use PDO;

/**
 * Description of PDOConn
 *
 * @author Luis Paulo
 */
class PDOConn extends PDO {

    public function __construct($dsn, $username = null, $password = null, $driver_options = array()) {
        parent::__construct($dsn, $username, $password, $driver_options);
        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('\Model\PDOStatementPrivate', array($this)));
    }

}

/**
 * For Debug SQL mode
 */
class PDOStatementPrivate extends \PDOStatement {

    const NO_MAX_LENGTH = -1;

    protected $connection;
    protected $bound_params = array();

    protected function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * Override PDOStatement
     * @param type $paramno
     * @param type $param
     * @param type $type
     * @param type $maxlen
     * @param type $driverdata
     */
    public function bindParam($paramno, &$param, $type = PDO::PARAM_STR, $maxlen = null, $driverdata = null) {
        $this->bound_params[$paramno] = array(
            'value' => &$param,
            'type' => $type,
            'maxlen' => (is_null($maxlen)) ? self::NO_MAX_LENGTH : $maxlen,
                // ignore driver data
        );

        parent::bindParam($paramno, $param, $type, $maxlen, $driverdata);
    }

    /**
     * Override PDOStatement
     * @param type $parameter
     * @param type $value
     * @param type $data_type
     */
    public function bindValue($parameter, $value, $data_type = PDO::PARAM_STR) {
        $this->bound_params[$parameter] = array(
            'value' => $value,
            'type' => $data_type,
            'maxlen' => self::NO_MAX_LENGTH
        );
        parent::bindValue($parameter, $value, $data_type);
    }

    /**
     * Representation of SQL and their values
     * @param type $values
     * @return string SQL Statement 
     */
    public function getSQL($values = array()) {
        $sql = $this->queryString;
        if (sizeof($values) > 0) {
            foreach ($values as $key => $value) {
                $sql = str_replace($key, $this->connection->quote($value), $sql);
            }
        }
        if (sizeof($this->bound_params)) {
            foreach ($this->bound_params as $key => $param) {
                $value = $param['value'];
                if (!is_null($param['type'])) {
                    $value = self::cast($value, $param['type']);
                }
                if ($param['maxlen'] && $param['maxlen'] != self::NO_MAX_LENGTH) {
                    $value = self::truncate($value, $param['maxlen']);
                }
                if (!is_null($value)) {
                    $sql = str_replace($key, $this->connection->quote($value), $sql);
                } else {
                    $sql = str_replace($key, 'NULL', $sql);
                }
            }
        }
        return $sql;
    }

    static protected function cast($value, $type) {
        switch ($type) {
            case PDO::PARAM_BOOL:
                return (bool) $value;
                break;
            case PDO::PARAM_NULL:
                return null;
                break;
            case PDO::PARAM_INT:
                return (int) $value;
            case PDO::PARAM_STR:
                return  (string) $value;
            default:
                return $value;
        }
    }

    static protected function truncate($value, $length) {
        return substr($value, 0, $length);
    }

}
