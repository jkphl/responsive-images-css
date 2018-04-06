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

use Jkphl\Respimgcss\Application\Factory\CalcLengthFactory;
use Jkphl\Respimgcss\Application\Model\AbsoluteLength;
use Jkphl\Respimgcss\Application\Model\ViewportLength;
use Jkphl\Respimgcss\Infrastructure\ViewportCalculatorServiceFactory;
use Jkphl\Respimgcss\Tests\AbstractTestBase;

/**
 * Calculation length factory tests
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Application
 */
class CalcLengthFactoryTest extends AbstractTestBase
{
    /**
     * Calculation length factory
     *
     * @var CalcLengthFactory
     */
    protected $calcLengthFactory;

    /**
     * Test setup
     */
    protected function setUp()
    {
        parent::setUp();
        $this->calcLengthFactory = new CalcLengthFactory(new ViewportCalculatorServiceFactory(), 16);
    }

    /**
     * Test the calculation length factory with an absolute value
     */
    public function testCalcLengthFactoryAbsolute()
    {
        /** @var AbsoluteLength $calcLength */
        $calcLength = $this->calcLengthFactory->createLengthFromString('calc(1em + 10px)');
        $this->assertInstanceOf(AbsoluteLength::class, $calcLength);
        $this->assertEquals(26, $calcLength->getValue());
    }

    /**
     * Test the calculation length factory with a viewport value
     */
    public function testCalcLengthFactoryViewport()
    {
        /** @var ViewportLength $calcLength */
        $viewportWidth  = rand(1000, getrandmax());
        $viewportLength = $this->calcLengthFactory->createAbsoluteLength($viewportWidth);
        $calcLength     = $this->calcLengthFactory->createLengthFromString('calc(100vw + 100px)');
        $this->assertInstanceOf(ViewportLength::class, $calcLength);
        $this->assertEquals($viewportWidth + 100, $calcLength->getValue($viewportLength));
    }

    /**
     * Test the calculation length factory with an invalid calc() string
     *
     * @expectedException \Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException
     * @expectedException 1522687100
     */
    public function testCalcLengthFactoryInvalid()
    {
        $this->calcLengthFactory->createLengthFromString('invalid');
    }
}