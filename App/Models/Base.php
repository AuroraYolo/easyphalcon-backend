<?php
namespace Backend\Models;

use Backend\Constant\Services;
use Phalcon\Mvc\Model;

class Base extends Model
{
    /**
     * @var \Phalcon\Db\Adapter\Pdo\Mysql
     */
    protected $db;

    public function initialize()
    {

    }

    /**
     * @return mixed|\Phalcon\Db\Adapter\Pdo\Mysql
     */
    public function db()
    {
        if (!is_object($this->db)) {
            $this->db = $this->getDI()->getShared(Services::DB);
        }
        return $this->db;
    }

    public function errorLog()
    {

    }

    /**
     * @return Model\Query\BuilderInterface
     */
    public function queryBuilder()
    {
        return $this->getModelsManager()->createBuilder();
    }

    /**
     * @param string $columns
     *
     * @return Model\Query\BuilderInterface
     */
    public function select($columns = '*')
    {
        return $this->queryBuilder()->columns($columns);
    }
}