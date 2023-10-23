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
     * Method to get all attributes table model
     *
     * @return array|null
     */
    public static function all(): array|null
    {
        return static::table(static::$table)->select(static::$columns)->get();
    }

    /**
     * Method to insert data in database
     *
     * @return bool
     */
    public static function create(array $data): bool
    {
        return static::table(static::$table)->insert($data);
    }
}
