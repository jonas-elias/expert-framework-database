<?php

namespace ExpertFramework\Database\Query\Grammar;

use ExpertFramework\Database\Connector\Connector;
use ExpertFramework\Database\Query\QueryBuilder;

/**
 * class QueryGrammar
 *
 * @package ExpertFramework\Database
 * @author jonas-elias
 */
class QueryGrammar extends Connector
{
    /**
     * Method to compile insert
     *
     * @param QueryBuilder $builder
     * @return string
     */
    protected function compileInsert(QueryBuilder $builder): string
    {
        $table = $builder->table;
        $fields = implode(', ', $builder->insert);
        $fieldsValues = implode(', ', array_map(function ($item) {
            return ':' . $item;
        }, $builder->insert));

        return "INSERT INTO {$table} ({$fields}) VALUES ({$fieldsValues})";
    }

    /**
     * Method to compile update
     *
     * @param QueryBuilder $builder
     * @return string
     */
    protected function compileUpdate(QueryBuilder $builder): string
    {
        $sql = "UPDATE {$builder->table} SET";

        foreach ($builder->update as $field) {
            $sql .= " {$field} = :{$field}";
        }

        foreach ($builder->wheres as $where) {
            $boolean = $where['boolean'] ?? 'WHERE';
            $sql .= " {$boolean} {$where['column']} {$where['operator']} :{$where['column']}";
        }

        return $sql;
    }

    /**
     * Method to compile select
     *
     * @param QueryBuilder $builder
     * @return string
     */
    protected function compileSelect(QueryBuilder $builder): string
    {
        $fields = implode(', ', $builder->select);

        return "SELECT {$fields} FROM {$builder->table}";
    }

    /**
     * Method to compile join
     *
     * @param QueryBuilder $builder
     * @return string
     */
    protected function compileJoin(QueryBuilder $builder): string
    {
        $sql = '';
        foreach ($builder->joins as $join) {
            $sql .= " {$join['type']} {$join['table']} ON {$join['first']} {$join['operator']} {$join['second']}";
        }

        return $sql;
    }

    /**
     * Method to compile wheres
     *
     * @param QueryBuilder $builder
     * @return string
     */
    protected function compileWheres(QueryBuilder $builder): string
    {
        $sql = '';
        foreach ($builder->wheres as $where) {
            $boolean = $where['boolean'] ?? 'WHERE';
            $sql .= " {$boolean} {$where['column']} {$where['operator']} :{$where['column']}";
        }

        return $sql;
    }

    /**
     * Method to compile bindings
     *
     * @param ?array $fields
     * @return array
     */
    protected function compileBindings(?array $fields = []): array
    {
        return array_combine(array_map(function ($key) {
            return ':' . $key;
        }, array_keys($fields)), $fields);
    }
}
