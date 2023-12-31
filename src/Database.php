<?php

declare(strict_types=1);

namespace ExpertFramework\Database;

use ExpertFramework\Database\Query\QueryBuilder;

/**
 * class Database.
 *
 * @author jonas-elias
 */

/**
 * @method static Database table(string $table)
 * @method static void     begin()
 * @method static void     commit()
 * @method static void     rollback()
 * @method        bool     insert(?array $fields = [])
 * @method        int      insertGetId(?array $fields = [])
 * @method        bool     update(?array $fields = [])
 * @method        Database select(?array $fields = [])
 * @method        Database where(string $column, string $operator, string|float|int $value, ?string $boolean = 'and')
 * @method        Database join(string $table, string $first, string $operator, string $second, ?string $type = 'JOIN')
 * @method        bool     delete()
 * @method        array    get()
 */
class Database
{
    /**
     * @var QueryBuilder
     */
    private static QueryBuilder|null $queryBuilder = null;

    /**
     * Call static by database class.
     *
     * @param string $name
     * @param array  $arguments
     */
    public static function __callStatic(string $name, array $arguments): QueryBuilder
    {
        $queryBuilder = self::getQueryBuilder();

        return call_user_func_array([$queryBuilder, $name], $arguments);
    }

    /**
     * Method to get instance query builder.
     *
     * @return QueryBuilder
     */
    private static function getQueryBuilder(): QueryBuilder
    {
        if (self::$queryBuilder === null) {
            self::$queryBuilder = new QueryBuilder();
        }

        return self::$queryBuilder;
    }
}
