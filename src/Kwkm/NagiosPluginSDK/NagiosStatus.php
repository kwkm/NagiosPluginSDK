<?php
/**
 * Nagios Plugin SDK - Nagios Status
 *
 * @package NagiosPluginSDK
 * @author Takehiro Kawakami <take@kwkm.org>
 * @license http://opensource.org/licenses/mit-license.php
 */
namespace Kwkm\NagiosPluginSDK;


class NagiosStatus
{
    /**
     * Service status OK when the plugin return code.
     */
    const OK = 0;

    /**
     * Service status WARNING when the plugin return code.
     */
    const WARNING = 1;

    /**
     * Service status CRITICAL when the plugin return code.
     */
    const CRITICAL = 2;

    /**
     * Service status UNKNOWN when the plugin return code.
     */
    const UNKNOWN = 3;
}