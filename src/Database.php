<?php

namespace ExpertFramework\Database;

use ExpertFramework\Database\Query\QueryBuilder;

/**
 * class Database
 *
 * @package ExpertFramework\Database
 * @author jonas-elias
 */

/**
 * @method static void table(string $table)
 */
class Database
{
    /**
     * @var QueryBuilder $queryBuilder
     */
    protected static QueryBuilder|null $queryBuilder = null;

    /**
     * Call static by database class
     *
     * @param string $name
     * @param array $arguments
     */
    public static function __callStatic(string $name, array $arguments): QueryBuilder
    {
        $queryBuilder = self::getQueryBuilder();
        return call_user_func_array([$queryBuilder, $name], $arguments);
    }

    /**
     * Method to get instance query builder
     *
     * @return QueryBuilder
     */
    public static function getQueryBuilder(): QueryBuilder
    {
        if (self::$queryBuilder === null) {
            self::$queryBuilder = new QueryBuilder();
        }

        return self::$queryBuilder;
    }
}
