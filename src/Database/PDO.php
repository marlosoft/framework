<?php

namespace Marlosoft\Framework\Database;

use Marlosoft\Framework\Components\Logger;
use Marlosoft\Framework\Core\Config;
use Marlosoft\Framework\Exceptions\PDOException;
use Marlosoft\Framework\Misc\Inflector;
use Marlosoft\Framework\Misc\ObjectManager;

/**
 * Class Connection
 * @package Marlosoft\Framework\Database
 */
class PDO extends \PDO
{
    /** @var Logger $logger */
    protected $logger;

    /**
     * @param string $table
     * @param array $where
     * @return array
     */
    protected function createFindQuery($table, $where = [])
    {
        $query = sprintf(
            'SELECT *' . ' FROM `%s`',
            $table
        );

        if (empty($where) === false) {
            $placeholders = implode(' AND ', array_map(function ($v) {
                return sprintf('`%s` = ?', Inflector::underscore($v));
            }, array_keys($where)));

            $query = sprintf('%s WHERE %s', $query, $placeholders);
        }

        return [$query, array_values($where)];
    }

    /**
     * @param PDOStatement $statement
     * @param string $query
     * @param array $data
     */
    protected function onQueryEnd(PDOStatement $statement, $query, $data = [])
    {
        $this->logger->debug('SQL query executed', [
            'timestamp' => $statement->getTimestamp(),
            'query' => $query,
            'data' => $data,
        ]);
    }

    /**
     * PDO constructor.
     */
    public function __construct()
    {
        $this->logger = ObjectManager::factory(
            'core.class.logger',
            Logger::class
        );

        try {
            parent::__construct(
                (string)Config::get('database.dsn'),
                (string)Config::get('database.username'),
                (string)Config::get('database.password'),
                (array)Config::get('database.options')
            );

            $this->setAttribute(self::ATTR_EMULATE_PREPARES, false);
            $this->setAttribute(self::ATTR_ERRMODE, self::ERRMODE_EXCEPTION);
            $this->setAttribute(self::ATTR_STATEMENT_CLASS, [PDOStatement::class]);
        } catch (\PDOException $exception) {
            throw new PDOException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception->getPrevious()
            );
        }
    }

    /**
     * @return PDO
     */
    public static function getConnection()
    {
        static $instance;
        if (empty($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @param string $sql
     * @param array $data
     * @return bool|PDOStatement
     */
    public function execute($sql, $data = [])
    {
        try {
            /** @var PDOStatement $statement */
            $statement = $this->prepare($sql);
            $statement->execute($data);
            $this->onQueryEnd($statement, $sql, $data);
        } catch (\PDOException $exception) {
            $this->logger->error('SQL Query Error', [$sql, $data]);
            throw new PDOException($exception->getMessage());
        }

        return $statement;
    }

    /**
     * @param string $table
     * @param array $data
     */
    public function insert($table, $data = [])
    {
        $columns = implode('`, `', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $query = 'INSERT'
            . ' INTO `%s`(`%s`)'
            . ' VALUES (%s)';

        $this->execute(
            sprintf($query, $table, $columns, $placeholders),
            array_values($data)
        );
    }

    /**
     * @param string $table
     * @param array $data
     * @param array $where
     */
    public function update($table, $data = [], $where = [])
    {
        $callback = function ($v) {
            return sprintf('`%s` = ?', $v);
        };

        $query = 'UPDATE `%s`'
            . ' SET %s'
            . ' WHERE %s';

        $updates = implode(', ', array_map($callback, array_keys($data)));
        $conditions = implode(' AND ', array_map($callback, array_keys($where)));

        $this->execute(
            sprintf($query, $table, $updates, $conditions),
            array_merge(array_values($data), array_values($where))
        );
    }

    /**
     * @param string $table
     * @param array $where
     * @return array
     */
    public function findBy($table, $where = [])
    {
        list($query, $data) = $this->createFindQuery($table, $where);
        $statement = $this->execute($query, $data);

        return $statement->fetch(self::FETCH_ASSOC);
    }

    /**
     * @param string $table
     * @param array $where
     * @return array
     */
    public function findAllBy($table, $where = [])
    {
        list($query, $data) = $this->createFindQuery($table, $where);
        $statement = $this->execute($query, $data);

        return $statement->fetchAll(self::FETCH_ASSOC);
    }
}
