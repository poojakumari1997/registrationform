<?php

declare(strict_types=1);

namespace DavidLienhard;

require_once dirname(__DIR__) . "/src/Parameter.php";
require_once dirname(__DIR__) . "/src/ParameterInterface.php";

use \PHPUnit\Framework\TestCase;
use \DavidLienhard\Database\Parameter;
use \DavidLienhard\Database\ParameterInterface;

class DatabaseParameterTest extends TestCase
{
    /**
     * @covers \DavidLienhard\Database\Parameter
     * @test
    */
    public function testCanBeCreated(): void
    {
        $param = new Parameter("i", 1);

        $this->assertInstanceOf(
            Parameter::class,
            $param
        );

        $this->assertInstanceOf(
            ParameterInterface::class,
            $param
        );
    }

    /**
     * @covers \DavidLienhard\Database\Parameter
     * @test
    */
    public function testTypeValueGetsTested(): void
    {
        $this->assertInstanceOf(
            Parameter::class,
            new Parameter("i", 1)
        );

        $this->assertInstanceOf(
            Parameter::class,
            new Parameter("s", 1)
        );

        $this->assertInstanceOf(
            Parameter::class,
            new Parameter("d", 1)
        );

        $this->assertInstanceOf(
            Parameter::class,
            new Parameter("b", 1)
        );

        $this->expectException(\InvalidArgumentException::class);
        new Parameter("a", 1);
    }

    /**
     * @covers \DavidLienhard\Database\Parameter
     * @test
    */
    public function testCetGetType(): void
    {
        $type = "i";
        $value = 1;
        $param = new Parameter($type, $value);

        $this->assertEquals($type, $param->getType());
    }

    /**
     * @covers \DavidLienhard\Database\Parameter
     * @test
    */
    public function testCetGetValue(): void
    {
        $type = "i";
        $value = 1;
        $param = new Parameter($type, $value);

        $this->assertEquals($value, $param->getValue());
    }
}
