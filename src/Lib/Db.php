<?php

namespace BeeJeeMVC\Lib;

use PDO;

class Db
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @return PDO
     */
    public function getPdo(): PDO
    {
        $this->connect();
//        $this->createTables();

        return $this->pdo;
    }

    public function connect(): void
    {
        $this->pdo = new PDO('sqlite:'.dirname(__DIR__).'/..'.$_ENV['DB_FOLDER_NAME'].$_ENV['DB_NAME']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function createTables(): void
    {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS task (id INTEGER PRIMARY KEY, user_name TEXT, email TEXT, text TEXT, status TEXT)');
        $this->pdo->exec('CREATE UNIQUE INDEX idx_task_user_name_email_text ON task (user_name, email, text);');
    }
}
