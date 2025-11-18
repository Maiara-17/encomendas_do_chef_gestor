<?php

namespace Core;

class Database
{
    private static $instance = null;
    private $connection;

    // Construtor privado para singleton
    private function __construct()
    {
        $this->connection = new \mysqli('localhost', 'root', '', 'encomendas_chef_db');

        if ($this->connection->connect_error) {
            die('Erro de conexão: ' . $this->connection->connect_error);
        }

        // Definir charset UTF-8
        $this->connection->set_charset('utf8mb4');
    }

    // Retorna a instância única do Database
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Retorna a conexão mysqli
    public function getConnection()
    {
        return $this->connection;
    }
}
