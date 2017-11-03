<?php
/*
 * Copyright (c) 2016 Alexey Kopytko
 * Released under the MIT license.
 */

class TryCatchTest extends PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $wrapped = TryCatch::wrap(function () {
            return 2;
        });

        $this->assertEquals(2, $wrapped());
    }

    public function testWithAnArg()
    {
        $wrapped = TryCatch::wrap(function ($arg) {
            return 2*$arg;
        });

        $this->assertEquals(4, $wrapped(2));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Unexpected
     */
    public function testUncaught()
    {
        $wrapped = TryCatch::wrap(function () {
            throw new Exception("Unexpected");
        });

        $wrapped();
    }

    public function testCaught()
    {
        $wrapped = TryCatch::wrap(function () {
            throw new Exception("Unexpected");
        })->whenFailed(function (Exception $e) {
            $this->assertEquals('Unexpected', $e->getMessage());
        });

        $wrapped();
    }

    public function testReturnIfCaught()
    {
        $numerator = 100;

        $fraction = function ($arg) use ($numerator) {
            if ($arg == 0) {
                throw new Exception("Division by zero");
            }
            return $numerator/$arg;
        };

        $wrapped = TryCatch::wrap($fraction)->whenFailed(function (Exception $e, $arg) {
            $this->assertEquals(0, $arg);
            return null;
        });

        $this->assertEquals(50, $wrapped(2));
        $this->assertNull($wrapped(0));
    }

    public function testFromReadme()
    {
        $wrapped = TryCatch::wrap(function ($divisor) {
            if ($divisor == 0) {
                throw new Exception("Division by zero");
            }
            return M_PI/$divisor;
        })->whenFailed(function (Exception $e, $divisor) {
            $this->assertEquals(0, $divisor);
            return null;
        });

        $this->assertEquals(M_PI/2, $wrapped(2));
        $this->assertNull($wrapped(0));
    }
}

