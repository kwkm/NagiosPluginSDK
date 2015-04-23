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
    /**
     * Threshold upper limit
     * @var float|integer
     */
    private $upperLimit;

    /**
     * Threshold lower limit
     * @var float|integer
     */
    private $lowerLimit;

    /**
     * Be determined to the abnormal.
     *
     * true...If numeric value outside this limit range.
     * false..If numeric value this limit range.
     *
     * @var boolean
     */
    private $outsideRange;

    /**
     * Check the value
     *
     * @param $value float|integer
     * @return boolean
     */
    public function check($value)
    {
        if ($this->outsideRange) {
            return $this->checkRangeInside($value);
        } else {
            return $this->checkRangeOutside($value);
        }
    }

    /**
     * Check the value. within the range.
     *
     * @param $value
     * @return bool
     */
    private function checkRange($value)
    {
        if ((!is_null($this->lowerLimit)) and (!is_null($this->upperLimit))) {
            if (($this->lowerLimit < $value) and ($this->upperLimit > $value)) {
                return true;
            }
        } elseif (!is_null($this->lowerLimit)) {
            if ($this->lowerLimit < $value) {
                return true;
            }
        } elseif (!is_null($this->upperLimit)) {
            if ($this->upperLimit > $value) {
                return true;
            }
        } else {
            // upper/lower is null. No check.
            return true;
        }

        return false;
    }

    /**
     * Check the value. within the range.
     * @param $value
     * @return bool
     */
    private function checkRangeInside($value)
    {
        return $this->checkRange($value);
    }

    /**
     * Check the value. without the range.
     * @param $value
     * @return bool
     */
    private function checkRangeOutside($value)
    {
        return !$this->checkRange($value);
    }

    /**
     * @param $thresholdValue   string
     */
    private function setRangeType($thresholdValue)
    {
        if (substr($thresholdValue, 0, 1) === '@') {
            $this->outsideRange = false;
        } else {
            $this->outsideRange = true;
        }
    }

    /**
     * @param $thresholdValue
     */
    private function setSingleLimit($thresholdValue)
    {
        if (is_numeric($thresholdValue) === false) {
            throw new InvalidArgumentException('Invalid threshold.');
        }

        $this->upperLimit = $thresholdValue;
        $this->lowerLimit = 0;
    }

    /**
     * @param $thresholdValue
     */
    private function setPairLimit($thresholdValue)
    {
        list($lower, $upper) = explode(':', trim($thresholdValue, '@'), 2);

        $this->upperLimit = $this->parseLimitValue($upper);
        $this->lowerLimit = $this->parseLimitValue($lower);
    }

    /**
     * @param $value
     * @return null|integer|float
     */
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

    /**
     * @param $thresholdValue
     */
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
            $this->outsideRange = true;
        }
    }
}
