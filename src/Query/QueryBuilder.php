<?php

declare(strict_types=1);

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
    public array $select = ['*'];

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
        $this->bindings = $this->compileBindings($fields);

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
        $this->bindings = $this->compileBindings($fields);

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

        $this->bindings = array_merge($this->bindings, [":{$column}" => $value]);

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
     * @return array
     */
    public function get(): array
    {
        $this->sql = $this->compileSelect($this);
        $this->sql .= $this->compileJoin($this);
        $this->sql .= $this->compileWheres($this);
        $this->compileWheres($this);

        return $this->execute();
    }

    /**
     * Method to execute query sql
     *
     * @return bool|array
     * @throws ExceptionExecuteQuery
     */
    private function execute(): bool|array
    {
        try {
            $statement = $this->getPdo()->prepare($this->sql);
            $response = $statement->execute($this->bindings);
 
            $sqlPrefix = strtoupper(substr($this->sql, 0, 6));

            $this->resetBuilder();

            switch ($sqlPrefix) {
                case 'INSERT':
                case 'UPDATE':
                case 'DELETE':
                    return $response;
                case 'SELECT':
                    return $statement->fetchAll();
                default:
                    throw new ExceptionExecuteQuery();
            }
        } catch (ExceptionExecuteQuery $e) {
            throw new ExceptionExecuteQuery($e->getMessage() . '. Sql Query: ' . $this->sql . '.');
        }
    }

    /**
     * Method to reset builder attributes class
     *
     * @return void
     */
    private function resetBuilder()
    {
        $this->sql = '';
        $this->table = '';
        $this->insert = [];
        $this->update = [];
        $this->select = ['*'];
        $this->joins = [];
        $this->wheres = [];
        $this->bindings = [];
    }
}
