<?php
namespace Craft;

class SproutReportsPlugin extends BasePlugin
{
    function init()
    {
        require CRAFT_PLUGINS_PATH.'sproutreports/vendor/autoload.php';
    }

    function getName()
    {
        return Craft::t('Reports');
    }

    function getVersion()
    {
        return '0.3.1';
    }

    function getDeveloper()
    {
        return 'Barrel Strength Design';
    }

    function getDeveloperUrl()
    {
        return 'http://straightupcraft.com';
    }

    public function hasCpSection()
    {
        return true;
    }

    public function hookRegisterCpRoutes()
    {
        return array(
            'sproutreports/query' => 'sproutreports/reports/query',
        );
    }

}
