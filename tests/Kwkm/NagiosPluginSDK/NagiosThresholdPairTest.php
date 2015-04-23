<?php

namespace Kwkm\NagiosPluginSDK\Tests;

use Kwkm\NagiosPluginSDK;
use Kwkm\NagiosPluginSDK\Exceptions\InvalidArgumentException;


require_once __DIR__ . '/../../bootstrap.php';

class NagiosThresholdPairTest extends \PHPUnit_Framework_TestCase
{
    public function testNoThreshold()
    {
        $mock = \TestMock::on(
            new \Kwkm\NagiosPluginSDK\NagiosThresholdPair('')
        );

        $this->assertNull($mock->upperLimit);
        $this->assertNull($mock->lowerLimit);

        $this->assertTrue($mock->check(1));
        $this->assertTrue($mock->check(0));
        $this->assertTrue($mock->check(-1));
    }

    public function testSingleThreshold()
    {
        $mock = \TestMock::on(
            new \Kwkm\NagiosPluginSDK\NagiosThresholdPair('50')
        );

        $this->assertEquals(50, $mock->upperLimit);
        $this->assertEquals(0, $mock->lowerLimit);

        $this->assertFalse($mock->check(51));
        $this->assertFalse($mock->check(50));
        $this->assertTrue($mock->check(49));
        $this->assertTrue($mock->check(1));
        $this->assertFalse($mock->check(0));
        $this->assertFalse($mock->check(-1));
    }

    public function testMultiThreshold()
    {
        $mock = \TestMock::on(
            new \Kwkm\NagiosPluginSDK\NagiosThresholdPair('10:20')
        );

        $this->assertEquals(20, $mock->upperLimit);
        $this->assertEquals(10, $mock->lowerLimit);

        $this->assertFalse($mock->check(21));
        $this->assertFalse($mock->check(20));
        $this->assertTrue($mock->check(19));
        $this->assertTrue($mock->check(11));
        $this->assertFalse($mock->check(10));
        $this->assertFalse($mock->check(9));
    }

    public function testMultiThresholdNoUpper()
    {
        $mock = \TestMock::on(
            new \Kwkm\NagiosPluginSDK\NagiosThresholdPair('10:')
        );

        $this->assertNull($mock->upperLimit);
        $this->assertEquals(10, $mock->lowerLimit);

        $this->assertTrue($mock->check(21));
        $this->assertTrue($mock->check(20));
        $this->assertTrue($mock->check(19));
        $this->assertTrue($mock->check(11));
        $this->assertFalse($mock->check(10));
        $this->assertFalse($mock->check(9));
    }

    public function testMultiThresholdNoLower()
    {
        $mock = \TestMock::on(
            new \Kwkm\NagiosPluginSDK\NagiosThresholdPair('~:10')
        );

        $this->assertEquals(10, $mock->upperLimit);
        $this->assertNull($mock->lowerLimit);

        $this->assertFalse($mock->check(21));
        $this->assertFalse($mock->check(20));
        $this->assertFalse($mock->check(19));
        $this->assertFalse($mock->check(11));
        $this->assertFalse($mock->check(10));
        $this->assertTrue($mock->check(9));
        $this->assertTrue($mock->check(-1));
    }

    public function testMultiThresholdOutside()
    {
        $mock = \TestMock::on(
            new \Kwkm\NagiosPluginSDK\NagiosThresholdPair('@10:20')
        );

        $this->assertEquals(20, $mock->upperLimit);
        $this->assertEquals(10, $mock->lowerLimit);

        $this->assertTrue($mock->check(21));
        $this->assertTrue($mock->check(20));
        $this->assertFalse($mock->check(19));
        $this->assertFalse($mock->check(11));
        $this->assertTrue($mock->check(10));
        $this->assertTrue($mock->check(9));
    }

    public function testExceptionSingleThresholdString()
    {
        try {
            $mock = \TestMock::on(
                new \Kwkm\NagiosPluginSDK\NagiosThresholdPair('foo')
            );
        } catch (InvalidArgumentException $ex) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testExceptionMultiThresholdString()
    {
        try {
            $mock = \TestMock::on(
                new \Kwkm\NagiosPluginSDK\NagiosThresholdPair('foo:bar')
            );
        } catch (InvalidArgumentException $ex) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }
}

