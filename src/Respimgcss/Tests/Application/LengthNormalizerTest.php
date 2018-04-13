<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Application
 * @author     Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @copyright  Copyright © 2018 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2018 Joschi Kuphal <joschi@kuphal.net> / @jkphl
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  the Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 *  the Software, and to permit persons to whom the Software is furnished to do so,
 *  subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ***********************************************************************************/

namespace Jkphl\Respimgcss\Tests\Application;

use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Application\Service\LengthNormalizerService;
use Jkphl\Respimgcss\Tests\AbstractTestBase;

/**
 * AbstractLength normalizer tests
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests
 */
class LengthNormalizerTest extends AbstractTestBase
{
    /**
     * EM to pixel ratio
     *
     * @var int
     */
    const EM_PIXEL = 16;

    /**
     * Test the normalization
     *
     * @param float $expected
     * @param float $value
     * @param string $unit
     *
     * @dataProvider normalizationProvider
     */
    public function testNormalization(float $expected, float $value, string $unit): void
    {
        $lengthNormalizerService = new LengthNormalizerService(self::EM_PIXEL);
        $this->assertEquals($expected, $lengthNormalizerService->normalize($value, $unit));
    }

    /**
     * Data provider for normalization tests
     *
     * @return array Test data
     */
    public function normalizationProvider()
    {
        return [
            [1, 1, UnitLengthInterface::UNIT_PIXEL],
            [self::EM_PIXEL, 1, UnitLengthInterface::UNIT_EM],
            [self::EM_PIXEL, 1, UnitLengthInterface::UNIT_REM],
            [28, 1, UnitLengthInterface::UNIT_CM],
            [3, 1, UnitLengthInterface::UNIT_MM],
            [72, 1, UnitLengthInterface::UNIT_IN],
            [100, 75, UnitLengthInterface::UNIT_PT],
            [80, 5, UnitLengthInterface::UNIT_PC],
        ];
    }

    /**
     * Test an invalid unit
     *
     * @expectedException \Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException
     * @expectedExceptionCode 1522493474
     */
    public function testInvalidUnit()
    {
        $lengthNormalizerService = new LengthNormalizerService(self::EM_PIXEL);
        $lengthNormalizerService->normalize(1, 'xp');
    }
}
