<?php

declare(strict_types=1);

namespace ExpertFramework\Database\Connector;

use PDO;

/**
 * class Connector
 *
 * @package ExpertFramework\Database
 * @author jonas-elias
 */
class Connector
{
    /**
     * @var PDO $pdo
     */
    private PDO|null $pdo = null;

    /**
     * Method to connect database
     *
     * @return PDO|null
     */
    private function connect(): PDO|null
    {
        $config = config('database');

        if (isset($config['connection'])) {
            $dsn = $this->getDsn($config);

            $this->pdo = new PDO(
                $dsn,
                $config['user'],
                $config['password'],
                $config['options'] ?? [],
            );
        }

        return null;
    }

    /**
     * Method to get data source name
     *
     * @param array $config
     * @return string
     */
    private function getDsn(array $config): string
    {
        return "{$config['connection']}:host={$config['host']};port={$config['port']};dbname={$config['dbname']};";
    }

    /**
     * Method to get pdo
     *
     * @return PDO
     */
    public function getPdo(): PDO
    {
        if (!$this->pdo) {
            $this->connect();
        }

        return $this->pdo;
    }
}
