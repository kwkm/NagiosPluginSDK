<?php
/**
 * Nagios Plugin SDK - Nagios Threshold Pair
 *
 * @package NagiosPluginSDK
 * @author Takehiro Kawakami <take@kwkm.org>
 * @license http://opensource.org/licenses/mit-license.php
 */
namespace Kwkm\NagiosPluginSDK;

use Kwkm\NagiosPluginSDK\Exceptions\InvalidArgumentException;

class NagiosThresholdPair
{

    private $upperLimit;
    private $lowerLimit;

    /**
     * Be determined to the abnormal.
     *
     * true...If numeric value outside this limit range.
     * false..If numeric value this limit range.
     *
     * @var boolean
     */
    private $abnormalOutsideLimitRange;

    private function setRangeType($thresholdValue)
    {
        if (substr($thresholdValue, 0, 1) === '@') {
            $this->abnormalOutsideLimitRange = false;
        } else {
            $this->abnormalOutsideLimitRange = true;
        }
    }

    private function setSingleLimit($thresholdValue)
    {
        if (is_numeric($thresholdValue) === false) {
            throw new InvalidArgumentException('Invalid threshold.');
        }

        $this->upperLimit = $thresholdValue;
        $this->lowerLimit = 0;
    }

    private function setPairLimit($thresholdValue)
    {
        list($lower, $upper) = explode(':', trim($thresholdValue, '@'), 2);

        $this->upperLimit = $this->parseLimitValue($upper);
        $this->lowerLimit = $this->parseLimitValue($lower);
    }

    private function parseLimitValue($value)
    {
        switch ($value) {
            case '':
            case '~':
                return null;
                break;
        }

        if (is_numeric($value)) {
            return $value;
        } else {
            throw new InvalidArgumentException('Invalid threshold.');
        }
    }

    private function parseThreshold($thresholdValue)
    {
        $this->setRangeType($thresholdValue);

        if (strpos($thresholdValue, ':') === false) {
            $this->setSingleLimit($thresholdValue);
        } else {
            $this->setPairLimit($thresholdValue);
        }
    }

    public function __construct($thresholdValue)
    {
        if (strlen($thresholdValue) !== 0) {
            $this->parseThreshold($thresholdValue);
        } else {
            $this->lowerLimit = null;
            $this->upperLimit = null;
            $this->abnormalOutsideLimitRange = true;
        }
    }
}