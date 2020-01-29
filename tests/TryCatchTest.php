<?php
/**
 * This code is licensed under the MIT License. (MIT)
 *
 * Copyright (c) 2016 Alexey Kopytko <alexey@kopytko.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

declare(strict_types=1);

namespace Tests\TryCatch;

use TryCatch\TryCatch;

/**
 * @covers \TryCatch\TryCatch
 */
class TryCatchTest extends \PHPUnit\Framework\TestCase
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
            return 2 * $arg;
        });

        $this->assertEquals(4, $wrapped(2));
    }

    public function testUncaught()
    {
        $wrapped = TryCatch::wrap(function () {
            throw new \Exception('Unexpected');
        });

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unexpected');

        $wrapped();
    }

    public function testCaught()
    {
        $wrapped = TryCatch::wrap(function () {
            throw new \Exception('Unexpected');
        })->whenFailed(function (\Exception $e) {
            $this->assertEquals('Unexpected', $e->getMessage());
        });

        $wrapped();
    }

    public function testReturnIfCaught()
    {
        $numerator = 100;

        $fraction = function ($arg) use ($numerator) {
            if ($arg === 0) {
                throw new \Exception('Division by zero');
            }

            return $numerator / $arg;
        };

        $wrapped = TryCatch::wrap($fraction)->whenFailed(function (\Exception $e, $arg) {
            $this->assertEquals(0, $arg);

            return null;
        });

        $this->assertEquals(50, $wrapped(2));
        $this->assertNull($wrapped(0));
    }

    public function testFromReadme()
    {
        $wrapped = TryCatch::wrap(function ($divisor) {
            if ($divisor === 0) {
                throw new \Exception('Division by zero');
            }

            return M_PI / $divisor;
        })->whenFailed(function (\Exception $e, $divisor) {
            $this->assertEquals(0, $divisor);

            return null;
        });

        $this->assertEquals(M_PI / 2, $wrapped(2));
        $this->assertNull($wrapped(0));
    }
}
