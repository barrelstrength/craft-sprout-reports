<?php

namespace barrelstrength\sproutreports\datasources;

use barrelstrength\sproutbasereports\base\DataSource;
use barrelstrength\sproutbasereports\elements\Report;
use Craft;

/**
 * Class SproutReportsQueryDataSource
 *
 * @package Craft
 */
class CustomQuery extends DataSource
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return Craft::t('sprout-reports', 'Custom Query');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return Craft::t('sprout-reports', 'Create reports using a custom database query');
    }

    /**
     * @inheritdoc
     */
    public function isAllowHtmlEditable()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getResults(Report $report, array $settings = [])
    {
        $query = $report->getSetting('query');

        $result = [];

        try {
            $result = Craft::$app->getDb()->createCommand($query)->queryAll();
        } catch (\Exception $e) {
            $report->setResultsError($e->getMessage());
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(array $settings = [])
    {
        $settingsErrors = $this->report->getErrors('settings');
        $settingsErrors = array_shift($settingsErrors);

        return Craft::$app->getView()->renderTemplate('sprout-base-reports/_components/datasources/CustomQuery/settings', [
            'settings' => count($settings) ? $settings : $this->report->getSettings(),
            'errors' => $settingsErrors
        ]);
    }

    /**
     * @inheritdoc
     */
    public function validateSettings(array $settings = [], array &$errors)
    {
        if (empty($settings['query'])) {
            $errors['query'][] = Craft::t('sprout-reports', 'Query cannot be blank.');

            return false;
        }

        return true;
    }
}
