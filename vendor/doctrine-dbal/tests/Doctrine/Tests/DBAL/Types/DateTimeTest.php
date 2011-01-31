<?php

namespace Doctrine\Tests\DBAL\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\Tests\DBAL\Mocks;

require_once __DIR__ . '/../../TestInit.php';
 
class DateTimeTest extends \Doctrine\Tests\DbalTestCase
{
    protected
        $_platform,
        $_type;

    protected function setUp()
    {
        $this->_platform = new \Doctrine\Tests\DBAL\Mocks\MockPlatform();
        $this->_type = Type::getType('datetime');
    }

    public function testDateTimeConvertsToDatabaseValue()
    {
        $date = new \DateTime('1985-09-01 10:10:10');

        $expected = $date->format($this->_platform->getDateTimeTzFormatString());
        $actual = is_string($this->_type->convertToDatabaseValue($date, $this->_platform));

        $this->assertEquals($expected, $actual);
    }

    public function testDateTimeConvertsToPHPValue()
    {
        // Birthday of jwage and also birthday of Doctrine. Send him a present ;)
        $date = $this->_type->convertToPHPValue('1985-09-01 00:00:00', $this->_platform);
        $this->assertType('DateTime', $date);
        $this->assertEquals('1985-09-01 00:00:00', $date->format('Y-m-d H:i:s'));
    }

    public function testInvalidDateTimeFormatConversion()
    {
        $this->setExpectedException('Doctrine\DBAL\Types\ConversionException');
        $this->_type->convertToPHPValue('abcdefg', $this->_platform);
    }

    public function testNullConversion()
    {
        $this->assertNull($this->_type->convertToPHPValue(null, $this->_platform));
    }
}