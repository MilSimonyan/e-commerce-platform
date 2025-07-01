<?php

class Database
{
    /**
     * @var \PDO
     */
    private PDO $connection;

    /**
     * Connect Database.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";

        try {
            $this->connection = new PDO(
                $dsn,
                $config['username'],
                $config['password']
            );

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Database connection failed: '.$e->getMessage());
        }
    }

    /**
     * @return \PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
