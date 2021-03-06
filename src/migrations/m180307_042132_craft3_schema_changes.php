<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   https://craftcms.github.io/license
 */

namespace barrelstrength\sproutreports\migrations;

use barrelstrength\sproutbasereports\migrations\m180307_042132_craft3_schema_changes as SproutReportsCraft2toCraft3Migration;
use craft\db\Migration;
use yii\base\NotSupportedException;

/**
 * m180307_042132_craft3_schema_changes migration.
 */
class m180307_042132_craft3_schema_changes extends Migration
{
    /**
     * @inheritdoc
     *
     * @throws NotSupportedException
     */
    public function safeUp(): bool
    {
        $migration = new SproutReportsCraft2toCraft3Migration();

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
        echo "m180307_042132_craft3_schema_changes cannot be reverted.\n";

        return false;
    }
}
