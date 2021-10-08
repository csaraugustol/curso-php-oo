<?php

namespace GGP\DataBase;

use Exception;
use PDO;

abstract class Entity
{
    private $connection;
    protected $table;

    //Recebe a conexão do banco de dados por parânmetro
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    //Retorna uma lista de uma entidade
    public function findAll($fields = '*'): array
    {
        $findAll = 'SELECT ' . $fields . ' FROM ' . $this->table;

        $products = $this->connection->query($findAll);

        return $products->fetchAll(PDO::FETCH_ASSOC);
    }

    //Retorna uma busca pelo id
    public function findById(int $id, $fields = '*'): array
    {
        return current($this->filterWithConditions(['id' => $id], '', $fields));
    }

    //Método para busca filtrada de uma entidade
    public function filterWithConditions(array $conditions, $operator = ' AND ', $fields = '*'): array
    {
        $sqlFilter = 'SELECT ' . $fields . ' FROM ' . $this->table . ' WHERE ';

        $binds = array_keys($conditions);

        $where = null;

        foreach ($binds as $bind) {
            if (is_null($where)) {
                $where .= $bind . ' = :' . $bind;
            } else {
                $where .= $operator . $bind . ' = :' . $bind;
            }
        }

        $sqlFilter .= $where;

        $objetc = $this->bind($sqlFilter, $conditions);
        $objetc->execute();
        return $objetc->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data): bool
    {
        $binds = array_keys($data);

        $sqlInsert = 'INSERT INTO ' . $this->table . '(' . implode(', ', $binds) .
            ', created_at, updated_at) VALUES(:' . implode(', :', $binds) . ', NOW(), NOW())';

        $insert = $this->bind($sqlInsert, $data);

        return $insert->execute();
    }

    public function update($data): bool
    {
        if (!array_key_exists('id', $data)) {
            throw new Exception("É preciso informar um ID válido para realização do update.");
        }

        $sqlUpdate = 'UPDATE ' . $this->table . ' SET ';

        $setValue = null;
        $binds = array_keys($data);

        foreach ($binds as $value) {
            if ($value !== 'id') {
                $setValue .= is_null($setValue) ? $value . ' = :' . $value : ', ' . $value . ' = :' . $value;
            }
        }

        $sqlUpdate .= $setValue . ', updated_at = NOW() WHERE id = :id';

        $update = $this->bind($sqlUpdate, $data);

        return $update->execute();
    }

    public function delete(int $id): bool
    {
        $sqlDelete = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $delete = $this->bind($sqlDelete, ['id' => $id]);

        return $delete->execute();
    }

    private function bind($sqlInsert, $data)
    {
        $bind = $this->connection->prepare($sqlInsert);

        foreach ($data as $key => $value) {
            gettype($value) == 'int' ? $bind->bindValue(':' . $key, $value, PDO::PARAM_INT)
                : $bind->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }

        return $bind;
    }
}
