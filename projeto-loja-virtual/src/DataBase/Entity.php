<?php

namespace LojaVirtual\DataBase;

use PDO;
use Exception;
use PDOStatement;

abstract class Entity
{
    /**
     * Conexão com o banco
     *
     * @var PDO
     */
    protected $connection;

    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table;

    /**
     * Recebe a conexão do banco de dados por parânmetro
     *
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Retorna todos os dados de uma entidade
     *
     * @param string $fields
     * @return array
     */
    public function findAll(string $fields = '*'): array
    {
        $findAll = 'SELECT ' . $fields . ' FROM ' . $this->table;
        $findResult = $this->connection->query($findAll);

        return $findResult->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna uma busca pelo id
     *
     * @param int $id
     * @param string $fields
     * @return array
     */
    public function findById(int $id, string $fields = '*'): array
    {
        return $this->filterWithConditions(['id' => $id], '', $fields);
    }

    /**
     * Realiza busca filtrada de uma entidade
     *
     * @param array $conditions
     * @param string $operator
     * @param string $fields
     * @return array
     */
    public function filterWithConditions(
        array $conditions,
        string $operator = 'AND',
        string $fields = '*'
    ): array {
        $sqlFilter = 'SELECT ' . $fields . ' FROM ' . $this->table . ' WHERE ';
        $binds = array_keys($conditions);
        $where = null;

        foreach ($binds as $bind) {
            is_null($where) ? $where .= $bind . ' = :' . $bind :
                $where .= ' ' . $operator . ' ' . $bind . ' = :' . $bind;
        }

        $sqlFilter .= $where;

        $object = $this->bind($sqlFilter, $conditions);
        $object->execute();

        if (!$object->rowCount()) {
            throw new Exception('Não obteve valor para consulta!');
        }

        if ($object->rowCount() === 1) {
            return $object->fetch(PDO::FETCH_ASSOC);
        }

        return $object->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inserção no banco de dados
     *
     * @param array $data
     * @return array
     */
    public function insert(array $data): array
    {
        $binds = array_keys($data);

        $sqlInsert = 'INSERT INTO ' . $this->table . '(' . implode(', ', $binds) .
            ', created_at, updated_at) VALUES(:' . implode(', :', $binds) . ', NOW(), NOW())';

        $insert = $this->bind($sqlInsert, $data);
        $insert->execute();

        return $this->findById($this->connection->lastInsertId());
    }

    /**
     * Insere as categorias do produto no banco de dados
     *
     * @param array $data
     * @return void
     */
    public function insertCategories(array $data): void
    {
        $sqlInsert = 'INSERT INTO products_categories(product_id, category_id)
                      VALUES(:product_id, :category_id)';
        $insert = $this->bind($sqlInsert, $data);
        $insert->execute();
    }

    /**
     * Atualiza dados no banco
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
                $setValue .= is_null($setValue) ? $value . ' = :' . $value : ', ' . $value . ' = :' . $value;
            }
        }

        $sqlUpdate .= $setValue . ', updated_at = NOW() WHERE id = :id';

        $update = $this->bind($sqlUpdate, $data);

        return $update->execute();
    }

    /**
     * Remove um dado do banco
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $bind = ['id' => $id];
        $field = 'id';

        $sqlDelete = 'DELETE FROM ' . $this->table . ' WHERE ' . $field . ' = :' . $field;
        $delete = $this->bind($sqlDelete, $bind);

        return $delete->execute();
    }

    /**
     * Remove da tabela 'products_categories' todas
     * as categorias relacionadas ao produto
     * para fazer nova inserção no banco
     *
     * @param array $id
     * @return bool
     */
    public function deleteCategoriesOfProduct(array $id): bool
    {
        $bind = $id;
        $field = array_keys($id)[0];
        $sqlDelete = 'DELETE FROM ' . $this->table . ' WHERE ' . $field . ' = :' . $field;

        $delete = $this->bind($sqlDelete, $bind);

        return $delete->execute();
    }

    /**
     * Retorna query
     *
     * @param string $sqlInsert
     * @param array $data
     * @return PDOStatement
     */
    private function bind(string $sqlInsert, array $data): PDOStatement
    {
        $bind = $this->connection->prepare($sqlInsert);

        foreach ($data as $key => $value) {
            gettype($value) === 'int' ? $bind->bindValue(':' . $key, $value, PDO::PARAM_INT)
                : $bind->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }

        return $bind;
    }
}
