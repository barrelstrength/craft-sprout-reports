<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   https://craftcms.github.io/license
 */

namespace barrelstrength\sproutreports\widgets;

use barrelstrength\sproutbasereports\elements\Report;
use barrelstrength\sproutbasereports\SproutBaseReports;
use Craft;
use craft\base\Widget;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\Exception;

/**
 *
 * @property mixed  $bodyHtml
 * @property mixed  $settingsHtml
 * @property string $title
 */
class Number extends Widget
{
    /**
     * @var string
     */
    public $heading;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $number;

    /**
     * @var string
     */
    public $resultPrefix;

    /**
     * @var int
     */
    public $reportId;

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('sprout-reports', 'Number');
    }

    /**
     * @inheritdoc
     */
    public static function icon()
    {
        return Craft::getAlias('@barrelstrength/sproutreports/icon-mask.svg');
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): string
    {
        return $this->heading;
    }

    /**
     * @inheritdoc
     *
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     * @throws Exception
     */
    public function getSettingsHtml(): string
    {
        $reportOptions = SproutBaseReports::$app->reports->getReportsAsSelectFieldOptions();

        return Craft::$app->getView()->renderTemplate('sprout-base-reports/_components/widgets/Number/settings', [
                'widget' => $this,
                'reportOptions' => $reportOptions
            ]
        );
    }

    /**
     * @inheritdoc
     *
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     * @throws Exception
     * @throws Exception
     */
    public function getBodyHtml(): string
    {
        $report = Craft::$app->elements->getElementById($this->reportId, Report::class);

        if ($report) {
            $dataSource = SproutBaseReports::$app->dataSources->getDataSourceById($report->dataSourceId);

            if ($dataSource) {
                $result = $dataSource->getResults($report);

                return Craft::$app->getView()->renderTemplate('sprout-base-reports/_components/widgets/Number/body',
                    [
                        'widget' => $this,
                        'result' => $this->getScalarValue($result)
                    ]
                );
            }
        }

        return Craft::$app->getView()->renderTemplate('sprout-base-reports/_components/widgets/Number/body',
            [
                'widget' => $this,
                'result' => Craft::t('sprout-reports', 'NaN')
            ]);
    }

    /**
     * @param $result
     *
     * @return int|mixed|null
     */
    protected function getScalarValue($result)
    {
        $value = null;

        if (is_array($result)) {

            if (count($result) == 1 && isset($result[0]) && count($result[0]) == 1) {
                $value = array_shift($result[0]);
            } else {
                $value = count($result);
            }
        } else {
            $value = $result;
        }

        return $value;
    }
}
