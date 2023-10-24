<?php

declare(strict_types=1);

namespace ExpertFramework\Database;

/**
 * class BaseModel
 *
 * @package ExpertFramework\Database
 * @author jonas-elias
 */
class BaseModel extends Database
{
    /**
     * @var string $table
     */
    protected static string $table = '';

    /**
     * @var array $columns
     */
    protected static array $columns = [];

    /**
     * @var array $conditionEqual
     */
    protected static array $conditionEqual = [];

    /**
     * Method to get all attributes table model
     *
     * @return array|null
     */
    public static function all(): array|null
    {
        $statement = static::table(static::$table);
        foreach (static::$conditionEqual as $column => $value) {
            $statement->where($column, '=', $value);
        }
        return $statement->select(static::$columns)->get();
    }

    /**
     * Method to insert data in database
     *
     * @param array $data
     * @return bool
     */
    public static function create(array $data): bool
    {
        return static::table(static::$table)->insert($data);
    }

    /**
     * Method to update data in database
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public static function update(array $data, int $id): bool
    {
        return static::table(static::$table)->where('id', '=', $id)->update($data);
    }

    /**
     * Method to find by id
     *
     * @param int $id
     * @return array
     */
    public static function find(int $id): array
    {
        $statement = static::table(static::$table);
        foreach (static::$conditionEqual as $column => $value) {
            $statement->where($column, '=', $value);
        }
        return $statement->where('id', '=', $id)->get();
    }
}
