<?php

namespace LojaVirtual\DataBase;

use PDO;
use Exception;

abstract class Entity
{
    protected $connection;
    protected $table;
    protected $timestamps = true;

    //Recebe a conexão do banco de dados por parânmetro
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    //Retorna uma lista de uma entidade
    public function findAll($fields = '*'): array
    {
        $findAll = 'SELECT ' . $fields . ' FROM ' . $this->table;

        $findResult = $this->connection->query($findAll);

        return $findResult->fetchAll(PDO::FETCH_ASSOC);
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

        if (!$objetc->rowCount()) {
            throw new Exception('Não obteve valor para consulta!');
        }

        return $objetc->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data): int
    {

        $binds = array_keys($data);

        $timestampsFields = $this->timestamps ? ', created_at, updated_at' : '';
        $timestampsValues = $this->timestamps ? ', NOW(), NOW()' : '';
        $sqlInsert = 'INSERT INTO ' . $this->table . '(' . implode(', ', $binds) . $timestampsFields .
            ') VALUES(:' . implode(', :', $binds) . $timestampsValues . ')';

        $insert = $this->bind($sqlInsert, $data);

        $insert->execute();

        return $this->connection->lastInsertId();
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

    public function delete($id): bool
    {
        if (is_array($id)) {
            $bind = $id;
            $field = array_keys($id)[0];
        } else {
            $bind = ['id' => $id];
            $field = 'id';
        }
        $sqlDelete = 'DELETE FROM ' . $this->table . ' WHERE ' . $field . ' = :' . $field;

        $delete = $this->bind($sqlDelete, $bind);

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
