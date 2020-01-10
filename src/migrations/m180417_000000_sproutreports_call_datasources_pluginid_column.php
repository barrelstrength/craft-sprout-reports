<?php

namespace barrelstrength\sproutreports\migrations;

use craft\db\Migration;
use barrelstrength\sproutbasereports\migrations\m180417_000000_sproutreports_datasources_pluginid_column as SproutReportsPluginId;
use yii\base\NotSupportedException;

/**
 * m180417_000000_sproutreports_call_datasources_pluginid_column migration.
 */
class m180417_000000_sproutreports_call_datasources_pluginid_column extends Migration
{
    /**
     * @inheritdoc
     *
     * @throws NotSupportedException
     */
    public function safeUp(): bool
    {
        $migration = new SproutReportsPluginId();

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
        echo "m180417_000000_sproutreports_call_datasources_pluginid_column cannot be reverted.\n";
        return false;
    }
}
