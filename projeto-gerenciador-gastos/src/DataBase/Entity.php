<?php

namespace GGP\DataBase;

use PDO;
use Exception;

abstract class Entity
{
    protected $table;
    private $connection;

    //Recebe a conexão do banco de dados por parânmetro
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    //Retorna uma lista de uma entidade
    public function findAll($fields = '*'): array
    {
        $sqlFindAll = 'SELECT ' . $fields . ' FROM ' . $this->table;

        $queryResponse = $this->connection->query($sqlFindAll);

        return $queryResponse->fetchAll(PDO::FETCH_ASSOC);
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

    //Método para inserção do banco de dados
    public function insert($data): bool
    {
        $binds = array_keys($data);

        $sqlInsert = 'INSERT INTO ' . $this->table . '(' . implode(', ', $binds) .
            ', created_at, updated_at) VALUES(:' . implode(', :', $binds) . ', NOW(), NOW())';

        $insert = $this->bind($sqlInsert, $data);

        return $insert->execute();
    }

    //Método para atualizar banco de dados
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

    //Método para deletar um elemento no banco de dados
    public function delete(int $id): bool
    {
        $sqlDelete = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $delete = $this->bind($sqlDelete, ['id' => $id]);

        return $delete->execute();
    }

    //Método para preparar querys para consulta
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
