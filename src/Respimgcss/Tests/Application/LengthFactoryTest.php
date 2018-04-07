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

use Jkphl\Respimgcss\Application\Contract\CalculatorServiceFactoryInterface;
use Jkphl\Respimgcss\Application\Factory\LengthFactory;
use Jkphl\Respimgcss\Application\Model\AbsoluteLength;
use Jkphl\Respimgcss\Application\Model\AbstractRelativeLength;
use Jkphl\Respimgcss\Application\Model\ViewportLength;
use Jkphl\Respimgcss\Infrastructure\ViewportCalculatorServiceFactory;
use Jkphl\Respimgcss\Tests\AbstractTestBase;

/**
 * AbstractLength factory tests
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests
 */
class LengthFactoryTest extends AbstractTestBase
{
    /**
     * Length factory
     *
     * @var LengthFactory
     */
    protected $lengthFactory;

    /**
     * Test setup
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();
        $this->lengthFactory = new LengthFactory(new ViewportCalculatorServiceFactory(), 16);
    }

    /**
     * Test the length factory
     */
    public function testLengthFactory()
    {
        $this->assertInstanceOf(LengthFactory::class, $this->lengthFactory);
        $this->assertInstanceOf(
            CalculatorServiceFactoryInterface::class,
            $this->lengthFactory->getCalculatorServiceFactory()
        );
    }

    /**
     * Test an invalid length string
     *
     * @depends               testLengthFactory
     * @expectedException \Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException
     * @expectedExceptionCode 1522492102
     */
    public function testInvalidLengthString()
    {
        $this->lengthFactory->createLengthFromString('123abc');
    }

    /**
     * Test the creation of an absolute length
     *
     * @depends testLengthFactory
     */
    public function testAbsoluteLengthCreation()
    {
        $length = $this->lengthFactory->createLengthFromString('1px');
        $this->assertInstanceOf(AbsoluteLength::class, $length);
        $this->assertInstanceOf(AbsoluteLength::class, $this->lengthFactory->createAbsoluteLength(1));
    }

    /**
     * Test the creation of a viewport length
     *
     * @depends testLengthFactory
     */
    public function testViewportLengthCreation()
    {
        $length = $this->lengthFactory->createLengthFromString('100vw');
        $this->assertInstanceOf(ViewportLength::class, $length);
    }

    /**
     * Test the creation of a percentage length
     *
     * @depends testLengthFactory
     */
    public function testPercentageLengthCreation()
    {
        $length = $this->lengthFactory->createLengthFromString('100%');
        $this->assertInstanceOf(AbstractRelativeLength::class, $length);
    }

    /**
     * Test an invalid length unit
     *
     * @depends               testLengthFactory
     * @expectedException \Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException
     * @expectedExceptionCode 1522493474
     */
    public function testInvalidLengthUnit()
    {
        $this->lengthFactory->createLengthFromString('123xp');
    }
}