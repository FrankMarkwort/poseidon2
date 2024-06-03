<?php

namespace Nmea\Migration;

use Nmea\Database\Database;

class Migration
{
    public function run()
    {
        foreach (include(__DIR__ . '/../config/migration.php') as $key => $migration) {
            echo "run migration {$key}\n";
            Database::getInstance()->execute('CREATE TABLE IF NOT EXISTS migrations (lfd serial, migration VARCHAR(255) NOT NULL, PRIMARY KEY (migration));');
            $result = Database::getInstance()->query(
                'select count(*) as sum from migrations where migration = "' . $migration['migration'] . '"'
            );
            if (isset($result) && $result[0]['sum'] === 1) {

                continue;
            };
            $sql = file_get_contents($migration['file']);
            if (Database::getInstance()->execute($sql) !== false) {
                Database::getInstance()->execute('insert  into migrations (migration) values (\'' . $migration['migration'] . '\')');
            }
        }
    }
}