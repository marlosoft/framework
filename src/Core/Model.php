<?php

namespace Marlosoft\Framework\Core;

use Marlosoft\Framework\Database\PDO;
use Marlosoft\Framework\Validations\Validator;
use Marlosoft\Framework\Misc\Inflector;

/**
 * Class Model
 * @package Marlosoft\Framework\Core
 */
class Model
{
    const TABLE = '';

    /** @var int $id */
    public $id;

    /**
     * @return array
     */
    protected function getConstraints()
    {
        return [];
    }

    /**
     * @param array $rows
     * @return static[]
     */
    protected static function populate(array $rows)
    {
        $data = [];
        foreach ($rows as $row) {
            $data[] = new static($row);
        }

        return $data;
    }

    /**
     * Model constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        foreach ($data as $k => $v) {
            $property = Inflector::camelcase($k, true);
            if (property_exists($this, $property)) {
                $this->{$property} = $v;
            }
        }
    }

    /**
     * @param int $id
     * @return static|Model
     */
    public static function find($id)
    {
        return static::findBy(['id' => (int)$id]);
    }

    /**
     * @param array $where
     * @param array $options
     * @return static|Model
     */
    public static function findBy($where = [], $options = [])
    {
        $data = PDO::getConnection()->findBy(static::TABLE, $where, $options);
        return empty($data) ? null : new static($data);
    }

    /**
     * @param array $where
     * @param array $options
     * @return static[]|Model[]
     */
    public static function findAll($where = [], $options = [])
    {
        $data = PDO::getConnection()->findAllBy(static::TABLE, $where, $options);
        return empty($data) ? [] : static::populate($data);
    }

    /**
     * @return array
     */
    public function validate()
    {
        $validator = new Validator($this, $this->getConstraints());
        $validator->validate();

        return $validator->getErrors();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * Insert/update table row
     * @return void
     */
    public function save()
    {
        $properties = get_object_vars($this);
        unset($properties['id']);

        $data = [];
        foreach ($properties as $k => $v) {
            $data[Inflector::underscore($k)] = $v;
        }

        $pdo = PDO::getConnection();
        if (empty($this->id)) {
            $pdo->insert(static::TABLE, $data);
            $this->id = (int)$pdo->lastInsertId();
            return;
        }

        $pdo->update(static::TABLE, $data, ['id' => $this->id]);
    }
}
