<?php

namespace Nmea\Migration;

use Exception;
use Nmea\Database\Database;

class Migration
{
    /**
     * @throws Exception
     */
    public function run()
    {
        foreach (include(__DIR__ . '/../config/migration.php') as $key => $migration) {
            Database::getInstance()->execute('CREATE TABLE IF NOT EXISTS migrations (lfd serial, migration VARCHAR(255) NOT NULL, PRIMARY KEY (migration));');
            $result = Database::getInstance()->query(
                'select count(*) as sum from migrations where migration = "' . $migration['migration'] . '"'
            );
            if (isset($result) && $result[0]['sum'] === 1) {
                echo "skip old migration:  {$migration['migration']}\n";
                continue;
            }
            echo "run migration: {$migration['migration']}\n";
            $sql = file_get_contents($migration['file']);
            if (Database::getInstance()->execute($sql) !== false) {
                Database::getInstance()->execute('insert  into migrations (migration) values (\'' . $migration['migration'] . '\')');
            }
        }
    }
}