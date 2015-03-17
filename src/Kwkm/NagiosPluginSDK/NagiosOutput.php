<?php
/**
 * Nagios Plugin SDK - Nagios Output
 *
 * @package NagiosPluginSDK
 * @author Takehiro Kawakami <take@kwkm.org>
 * @license http://opensource.org/licenses/mit-license.php
 */
namespace Kwkm\NagiosPluginSDK;


class NagiosOutput
{
    private static function getStatusLabel($status)
    {
        switch ($status) {
            case NagiosStatus::OK:
                return 'OK';
            case NagiosStatus::WARNING:
                return 'WARNING';
            case NagiosStatus::CRITICAL:
                return 'CRITICAL';
            case NagiosStatus::UNKNOWN:
                return 'UNKNOWN';
        }
    }

    public static function display($status, $text, $perfData = null)
    {
        if (!is_null($perfData)) {
            echo sprintf('%s - %s | %s', self::getStatusLabel($status), $text, $perfData);
        } else {
            echo sprintf('%s - %s', self::getStatusLabel($status), $text);
        }

        echo PHP_EOL;
        exit($status);
    }
}
