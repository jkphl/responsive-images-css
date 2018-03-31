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
 * Length normalizer tests
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests
 */
class LengthNormalizerTests extends AbstractTestBase
{
    /**
     * Length normalizer service
     *
     * @var LengthNormalizerService
     */
    protected $lengthNormalizerService;
    /**
     * EM to pixel ratio
     *
     * @var int
     */
    const EM_PIXEL = 16;

    /**
     * Setup the tests
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();
        $this->lengthNormalizerService = new LengthNormalizerService(self::EM_PIXEL);
    }

    /**
     * Test pixel normalization
     */
    public function testPixelNormalization()
    {
        $this->assertEquals(
            100,
            $this->lengthNormalizerService->normalize(
                100,
                UnitLengthInterface::UNIT_PIXEL
            )
        );
    }

    /**
     * Test em normalization
     */
    public function testEmNormalization()
    {
        $this->assertEquals(
            self::EM_PIXEL,
            $this->lengthNormalizerService->normalize(
                1,
                UnitLengthInterface::UNIT_EM
            )
        );
    }

    /**
     * Test rem normalization
     */
    public function testRemNormalization()
    {
        $this->assertEquals(
            self::EM_PIXEL,
            $this->lengthNormalizerService->normalize(
                1,
                UnitLengthInterface::UNIT_REM
            )
        );
    }

    /**
     * Test cm normalization
     */
    public function testCmNormalization()
    {
        $this->assertEquals(
            28,
            $this->lengthNormalizerService->normalize(
                1,
                UnitLengthInterface::UNIT_CM
            )
        );
    }

    /**
     * Test mm normalization
     */
    public function testMmNormalization()
    {
        $this->assertEquals(
            3,
            $this->lengthNormalizerService->normalize(
                1,
                UnitLengthInterface::UNIT_MM
            )
        );
    }

    /**
     * Test in normalization
     */
    public function testInNormalization()
    {
        $this->assertEquals(
            72,
            $this->lengthNormalizerService->normalize(
                1,
                UnitLengthInterface::UNIT_IN
            )
        );
    }

    /**
     * Test pt normalization
     */
    public function testPtNormalization()
    {
        $this->assertEquals(
            100,
            $this->lengthNormalizerService->normalize(
                75,
                UnitLengthInterface::UNIT_PT
            )
        );
    }

    /**
     * Test pc normalization
     */
    public function testPcNormalization()
    {
        $this->assertEquals(
            80,
            $this->lengthNormalizerService->normalize(
                5,
                UnitLengthInterface::UNIT_PC
            )
        );
    }

    /**
     * Test an invalid unit
     *
     * @expectedException \Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException
     * @expectedExceptionCode 1522493474
     */
    public function testInvalidUnit()
    {
        $this->lengthNormalizerService->normalize(1, 'xp');
    }
}