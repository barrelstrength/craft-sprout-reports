<?php /**
 * @link https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license https://craftcms.github.io/license
 */ /** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutreports\migrations;

use barrelstrength\sproutbasereports\migrations\m190628_000000_fix_data_sources;
use craft\db\Migration;
use Throwable;

/**
 * m190628_000000_fix_data_sources_sproutreports migration.
 */
class m190628_000000_fix_data_sources_sproutreports extends Migration
{
    /**
     * @return bool
     * @throws Throwable
     */
    public function safeUp(): bool
    {
        $migration = new m190628_000000_fix_data_sources();

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
        echo "m190628_000000_fix_data_sources_sproutreports cannot be reverted.\n";

        return false;
    }
}
