<?php

namespace GGP\DataBase;

use PDO;
use Exception;
use PDOStatement;

abstract class Entity
{
    /**
     * Nome tabela
     *
     * @var string
     */
    protected $table;

    /**
     * Conexão banco
     *
     * @var PDO
     */
    private $connection;

    /**
     * Recebe a string de conexão com o banco
     *
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Realiza busca por todas as despesas
     *
     * @param string $fields
     * @return array
     */
    public function findAll(string $fields = '*'): array
    {
        $sqlFindAll = 'SELECT ' . $fields . ' FROM ' . $this->table;
        $queryResponse = $this->connection->query($sqlFindAll);
        return $queryResponse->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna uma busca pelo id
     *
     * @param integer $id
     * @param string $fields
     * @return array
     */
    public function findById(int $id, string $fields = '*'): array
    {
        return current($this->filterWithConditions(['id' => $id], '', $fields));
    }

    /**
     * Realiza busca por filtragem
     *
     * @param array $conditions
     * @param string $operator
     * @param string $fields
     * @return array
     */
    public function filterWithConditions(
        array $conditions,
        string $operator = ' AND ',
        string $fields = '*'
    ): array {
        $sqlFilter = 'SELECT ' . $fields . ' FROM ' . $this->table . ' WHERE ';
        $binds = array_keys($conditions);
        $where = null;

        foreach ($binds as $bind) {
            is_null($where) ? $where .= $bind . ' = :' . $bind :
                $where .= $operator . $bind . ' = :' . $bind;
        }
        $sqlFilter .= $where;
        $object = $this->bind($sqlFilter, $conditions);
        $object->execute();
        return $object->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Realiza a inserção no banco
     *
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        $binds = array_keys($data);
        $sqlInsert = 'INSERT INTO ' . $this->table . '(' . implode(', ', $binds) .
            ', created_at, updated_at) VALUES(:' . implode(', :', $binds) . ', NOW(), NOW())';
        $insert = $this->bind($sqlInsert, $data);
        return $insert->execute();
    }

    /**
     * Realiza a atualização de dados
     *
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
        if (!array_key_exists('id', $data)) {
            throw new Exception("É preciso informar um ID válido para realização do update.");
        }
        $sqlUpdate = 'UPDATE ' . $this->table . ' SET ';
        $setValue = null;
        $binds = array_keys($data);

        foreach ($binds as $value) {
            if ($value !== 'id') {
                $setValue .= is_null($setValue) ? $value . ' = :' . $value :
                 ', ' . $value . ' = :' . $value;
            }
        }
        $sqlUpdate .= $setValue . ', updated_at = NOW() WHERE id = :id';
        $update = $this->bind($sqlUpdate, $data);
        return $update->execute();
    }

    /**
     * Realiza a deleção de um dado pelo id
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $sqlDelete = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $delete = $this->bind($sqlDelete, ['id' => $id]);
        return $delete->execute();
    }

    /**
     * para preparar querys para consulta
     *
     * @param string $sqlInsert
     * @param array $data
     * @return PDOStatement
     */
    private function bind(string $sqlInsert, array $data): PDOStatement
    {
        $bind = $this->connection->prepare($sqlInsert);
        foreach ($data as $key => $value) {
            gettype($value) == 'int' ? $bind->bindValue(':' . $key, $value, PDO::PARAM_INT)
                : $bind->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }
        return $bind;
    }
}
