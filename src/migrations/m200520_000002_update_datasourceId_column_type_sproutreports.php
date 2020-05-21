<?php

namespace barrelstrength\sproutreports\migrations;

use barrelstrength\sproutbasereports\migrations\m200520_000002_update_datasourceId_column_type;
use craft\db\Migration;

class m200520_000002_update_datasourceId_column_type_sproutreports extends Migration
{
    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $migration = new m200520_000002_update_datasourceId_column_type();

        ob_start();
        $migration->safeUp();
        ob_end_clean();

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m200520_000002_update_datasourceId_column_type_sproutreports cannot be reverted.\n";

        return false;
    }
}
