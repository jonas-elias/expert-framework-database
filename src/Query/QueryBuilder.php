<?php

namespace ExpertFramework\Database\Query;

use ExpertFramework\Database\Exception\ExceptionExecuteQuery;
use ExpertFramework\Database\Query\Grammar\QueryGrammar;

/**
 * class QueryBuilder
 *
 * @package ExpertFramework\Database
 * @author jonas-elias
 */
class QueryBuilder extends QueryGrammar
{
    /**
     * @var string $sql
     */
    private string $sql = '';

    /**
     * @var string $table
     */
    public string $table = '';

    /**
     * @var array $insert
     */
    public array $insert = [];

    /**
     * @var array $update
     */
    public array $update = [];

    /**
     * @var array $select
     */
    public array $select = [];

    /**
     * @var array $joins
     */
    public array $joins = [];

    /**
     * @var array $wheres
     */
    public array $wheres = [];

    /**
     * @var array $bindings
     */
    public array $bindings = [];

    /**
     * Method constructor
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Method set table
     *
     * @param string $table
     * @return QueryBuilder
     */
    public function table(string $table): QueryBuilder
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Method to insert fields
     *
     * @param ?array $fiels
     * @return bool
     */
    public function insert(?array $fields = []): bool
    {
        foreach ($fields as $field => $value) {
            $this->insert[] = $field;
        }

        $this->sql = $this->compileInsert($this);
        $this->compileBindings($fields);

        return $this->execute();
    }

    /**
     * Method to update fields
     *
     * @param ?array $fields
     * @return bool
     */
    public function update(?array $fields = []): bool
    {
        foreach ($fields as $field => $value) {
            $this->update[] = $field;
        }

        $this->sql = $this->compileUpdate($this);
        $this->sql .= $this->compileWheres($this);
        $this->compileBindings($fields);

        return $this->execute();
    }

    /**
     * Method to set wheres
     *
     * @param string $column
     * @param string $operator
     * @param string $value
     * @param ?string $boolean = 'and'
     * @return QueryBuilder
     */
    public function where(string $column, string $operator, string $value, ?string $boolean = 'and'): QueryBuilder
    {
        if (!isset($this->wheres[0])) {
            $this->wheres[] = compact('column', 'operator', 'value');
        } else {
            $this->wheres[] = compact('column', 'value', 'boolean', 'operator');
        }

        $this->bindings[] = [":{$column}" => $value];

        return $this;
    }

    /**
     * Method to set select fields
     *
     * @param ?array $fields
     * @return QueryBuilder
     */
    public function select(?array $fields = []): QueryBuilder
    {
        $this->select = $fields;

        return $this;
    }

    /**
     * Method to set joins fields
     *
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @param ?string $type = 'JOIN'
     * @return QueryBuilder
     */
    public function join(
        string $table,
        string $first,
        string $operator,
        string $second,
        ?string $type = 'JOIN'
    ): QueryBuilder {
        $this->joins[] = compact('table', 'first', 'operator', 'second', 'type');

        return $this;
    }

    /**
     * Method to get select
     *
     * @return mixed
     */
    public function get(): mixed
    {
        $this->sql = $this->compileSelect($this);
        $this->sql .= $this->compileJoin($this);
        $this->sql .= $this->compileWheres($this);
        $this->compileWheres($this);

        return $this->execute();
    }

    public function execute(array $data = [])
    {
        try {
            $stmt = $this->getPdo();

            $stmt = $stmt->prepare($this->sql);

            return $stmt->execute($this->bindings);
        } catch (ExceptionExecuteQuery $e) {
            throw new ExceptionExecuteQuery($e->getMessage() . '. Sql Query: ' . $this->sql . '.');
        }
    }
}
